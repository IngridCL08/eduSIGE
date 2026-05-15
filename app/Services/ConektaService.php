<?php

namespace App\Services;

use App\Models\FichaPago;
use Conekta\Api\OrdersApi;
use Conekta\Configuration;
use Conekta\Model\OrderRequest;
use Conekta\Model\Product;
use Conekta\Model\CustomerInfo;
use Exception;
use Illuminate\Support\Facades\Log;

class ConektaService
{
    private Configuration $config;

    public function __construct()
    {
        $this->config = Configuration::getDefaultConfiguration()
            ->setAccessToken(config('conekta.private_key'));
    }

    /**
     * Crea una orden de cobro en Conekta.
     * Retorna: ['order_id' => '...', 'redirect_url' => '...']
     */
    public function crearOrden(FichaPago $ficha): array
    {
        try {
            $aspirante = $ficha->aspirante;

            $lineItem = new Product([
                'name'      => $ficha->concepto,
                'quantity'  => 1,
                'unit_price' => (int) ($ficha->monto * 100), // centavos
            ]);

            $customerInfo = new CustomerInfo([
                'name'  => $aspirante->nombre_completo,
                'email' => $aspirante->email,
                'phone' => $aspirante->telefono ?? '+5200000000000',
            ]);

            $orderRequest = new OrderRequest([
                'currency'      => 'MXN',
                'customer_info' => $customerInfo,
                'line_items'    => [$lineItem],
                'metadata'      => [
                    'folio_ficha'   => $ficha->folio_ficha,
                    'aspirante_id'  => $aspirante->id,
                ],
                'checkout' => [
                    'type'           => 'Integration',
                    'allowed_payment_methods' => ['card', 'cash', 'bank_transfer'],
                    'success_url'    => route('financiero.pago.exitoso') . '?ficha=' . $ficha->id,
                    'failure_url'    => route('financiero.pago.cancelado') . '?ficha=' . $ficha->id,
                    'expires_at'     => $ficha->fecha_vencimiento->endOfDay()->timestamp,
                ],
            ]);

            $apiInstance = new OrdersApi(null, $this->config);
            $order = $apiInstance->createOrder($orderRequest);

            return [
                'order_id'     => $order->getId(),
                'redirect_url' => $order->getCheckout()?->getUrl() ?? '',
            ];
        } catch (Exception $e) {
            Log::error('Conekta crearOrden error: ' . $e->getMessage(), [
                'ficha_id' => $ficha->id,
            ]);
            throw $e;
        }
    }

    /**
     * Verifica la firma HMAC del webhook de Conekta.
     */
    public function verificarFirmaWebhook(string $payload, string $firma): bool
    {
        $secreto = config('conekta.webhook_secret');
        $firmaCalculada = hash_hmac('sha256', $payload, $secreto);
        return hash_equals($firmaCalculada, $firma);
    }
}
