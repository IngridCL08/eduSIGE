<?php

namespace App\Services;

use App\Models\Aspirante;
use App\Models\FichaPago;
use App\Models\Transaccion;
use App\Models\Bitacora;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Exception;

class PagoService
{
    public function __construct(
        private ConektaService $conekta,
        private PayPalService  $paypal,
    ) {}

    /**
     * Genera una nueva ficha de pago para el aspirante.
     * Si ya tiene una pendiente vigente, la retorna.
     */
    public function generarFicha(Aspirante $aspirante, int $generadoPor): FichaPago
    {
        // Verificar si ya existe una ficha vigente
        $fichaExistente = $aspirante->fichasPago()
            ->where('status', 'pendiente')
            ->where('fecha_vencimiento', '>=', today())
            ->first();

        if ($fichaExistente) {
            return $fichaExistente;
        }

        $anio = now()->year;

        $ficha = FichaPago::create([
            'aspirante_id'      => $aspirante->id,
            'folio_ficha'       => FichaPago::generarFolio($anio),
            'monto'             => config('app.edusige.monto_ficha', 500.00),
            'concepto'          => 'Ficha de inscripción ' . $anio,
            'fecha_emision'     => today(),
            'fecha_vencimiento' => today()->addDays(config('app.edusige.dias_vigencia_ficha', 5)),
            'status'            => FichaPago::STATUS_PENDIENTE,
            'generado_por'      => $generadoPor,
        ]);

        // Actualizar estatus del aspirante
        $aspirante->update(['status' => Aspirante::STATUS_FICHA_GENERADA]);

        Bitacora::registrar(
            'generar_ficha',
            "Ficha {$ficha->folio_ficha} generada para aspirante {$aspirante->folio}",
            FichaPago::class,
            $ficha->id,
        );

        return $ficha;
    }

    /**
     * Inicia un pago en línea con el gateway seleccionado.
     * Retorna la URL de redirección al portal de pago.
     */
    public function iniciarPagoEnLinea(FichaPago $ficha, string $gateway): array
    {
        $resultado = match ($gateway) {
            'conekta' => $this->conekta->crearOrden($ficha),
            'paypal'  => $this->paypal->crearOrden($ficha),
            default   => throw new Exception("Gateway '{$gateway}' no soportado."),
        };

        // Guardar referencia del gateway en la ficha
        $ficha->update(['gateway_order_id' => $resultado['order_id']]);

        // Crear registro de transacción pendiente
        Transaccion::create([
            'ficha_pago_id'      => $ficha->id,
            'gateway'            => $gateway,
            'referencia_externa' => $resultado['order_id'],
            'monto'              => $ficha->monto,
            'status'             => 'pendiente',
            'ip_cliente'         => request()->ip(),
        ]);

        return $resultado;
    }

    /**
     * Procesa un webhook de Conekta y actualiza la ficha correspondiente.
     */
    public function procesarWebhookConekta(array $payload): void
    {
        $tipo    = $payload['type'] ?? '';
        $chargeId = $payload['data']['object']['id'] ?? null;

        if (! $chargeId) {
            Log::warning('Webhook Conekta sin charge ID', $payload);
            return;
        }

        $transaccion = Transaccion::where('referencia_externa', $chargeId)->first();

        if (! $transaccion) {
            Log::warning("Webhook Conekta: transacción no encontrada para {$chargeId}");
            return;
        }

        DB::transaction(function () use ($transaccion, $tipo, $payload) {
            $statusMap = [
                'order.paid'    => ['exitosa', 'pagado'],
                'order.expired' => ['fallida',  'vencido'],
                'order.voided'  => ['fallida',  'cancelado'],
            ];

            [$statusTxn, $statusFicha] = $statusMap[$tipo] ?? ['pendiente', null];

            $transaccion->update([
                'status'        => $statusTxn,
                'respuesta_raw' => $payload,
            ]);

            if ($statusFicha) {
                $ficha = $transaccion->fichaPago;
                $ficha->update([
                    'status'           => $statusFicha,
                    'fecha_pago'       => $statusFicha === 'pagado' ? now() : null,
                    'metodo_pago'      => 'conekta',
                    'referencia_pago'  => $transaccion->referencia_externa,
                    'gateway_response' => $payload,
                ]);

                if ($statusFicha === 'pagado') {
                    $ficha->aspirante->update(['status' => Aspirante::STATUS_FICHA_PAGADA]);
                }

                Bitacora::registrar(
                    "webhook_conekta_{$statusFicha}",
                    "Ficha {$ficha->folio_ficha} actualizada a '{$statusFicha}' vía webhook Conekta",
                    FichaPago::class,
                    $ficha->id,
                );
            }
        });
    }

    /**
     * Procesa un webhook/callback de PayPal.
     */
    public function procesarWebhookPaypal(array $payload): void
    {
        $orderId = $payload['resource']['id'] ?? null;
        $status  = $payload['resource']['status'] ?? null;

        if (! $orderId) {
            Log::warning('Webhook PayPal sin order ID', $payload);
            return;
        }

        $transaccion = Transaccion::where('referencia_externa', $orderId)->first();

        if (! $transaccion) {
            return;
        }

        DB::transaction(function () use ($transaccion, $status, $payload) {
            $esExitosa = in_array($status, ['COMPLETED', 'APPROVED']);

            $transaccion->update([
                'status'        => $esExitosa ? 'exitosa' : 'fallida',
                'respuesta_raw' => $payload,
            ]);

            if ($esExitosa) {
                $ficha = $transaccion->fichaPago;
                $ficha->update([
                    'status'           => 'pagado',
                    'fecha_pago'       => now(),
                    'metodo_pago'      => 'paypal',
                    'referencia_pago'  => $orderId,
                    'gateway_response' => $payload,
                ]);

                $ficha->aspirante->update(['status' => Aspirante::STATUS_FICHA_PAGADA]);

                Bitacora::registrar(
                    'webhook_paypal_pagado',
                    "Ficha {$ficha->folio_ficha} pagada vía PayPal",
                    FichaPago::class,
                    $ficha->id,
                );
            }
        });
    }

    /**
     * Registra un pago manual (efectivo, transferencia).
     */
    public function registrarPagoManual(FichaPago $ficha, string $metodo, ?string $referencia = null): void
    {
        DB::transaction(function () use ($ficha, $metodo, $referencia) {
            $ficha->update([
                'status'          => 'pagado',
                'fecha_pago'      => now(),
                'metodo_pago'     => $metodo,
                'referencia_pago' => $referencia,
            ]);

            $ficha->aspirante->update(['status' => Aspirante::STATUS_FICHA_PAGADA]);

            Bitacora::registrar(
                'pago_manual',
                "Pago manual registrado para ficha {$ficha->folio_ficha}. Método: {$metodo}",
                FichaPago::class,
                $ficha->id,
            );
        });
    }

    /**
     * Marca como vencidas las fichas pendientes cuya fecha límite expiró.
     * Ejecutado por el scheduler diario.
     */
    public function marcarVencidas(): int
    {
        return FichaPago::where('status', 'pendiente')
            ->where('fecha_vencimiento', '<', today())
            ->update(['status' => 'vencido']);
    }
}
