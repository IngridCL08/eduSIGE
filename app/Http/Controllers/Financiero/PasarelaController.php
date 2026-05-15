<?php

namespace App\Http\Controllers\Financiero;

use App\Http\Controllers\Controller;
use App\Models\FichaPago;
use App\Services\PagoService;
use App\Services\ConektaService;
use App\Services\PayPalService;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;

class PasarelaController extends Controller
{
    public function __construct(
        private PagoService    $pagoService,
        private ConektaService $conekta,
        private PayPalService  $paypal,
    ) {}

    public function iniciarPago(Request $request, FichaPago $ficha): RedirectResponse
    {
        $request->validate([
            'gateway' => ['required', 'in:conekta,paypal'],
        ]);

        if (! $ficha->isVigente()) {
            return back()->with('error', 'La ficha de pago no está disponible para pago en línea.');
        }

        try {
            $resultado = $this->pagoService->iniciarPagoEnLinea($ficha, $request->gateway);
            return redirect($resultado['redirect_url']);
        } catch (\Exception $e) {
            Log::error('Error al iniciar pago en línea', ['error' => $e->getMessage()]);
            return back()->with('error', 'Ocurrió un error al conectar con la pasarela de pago. Intenta más tarde.');
        }
    }

    public function pagoExitoso(Request $request)
    {
        $ficha = FichaPago::find($request->ficha);

        // Si viene de PayPal, capturar el pago
        if ($request->filled('token')) {
            try {
                $this->paypal->capturarPago($request->token);
            } catch (\Exception $e) {
                Log::error('Error capturando pago PayPal: ' . $e->getMessage());
            }
        }

        return view('financiero.pago.exitoso', compact('ficha'));
    }

    public function pagoCancelado(Request $request)
    {
        $ficha = FichaPago::find($request->ficha);
        return view('financiero.pago.cancelado', compact('ficha'));
    }

    // ─── Webhooks (sin middleware CSRF) ──────────────────────

    public function webhookConekta(Request $request)
    {
        $firma   = $request->header('Digest', '');
        $payload = $request->getContent();

        if (! $this->conekta->verificarFirmaWebhook($payload, $firma)) {
            Log::warning('Webhook Conekta: firma inválida');
            return response()->json(['error' => 'Firma inválida'], 401);
        }

        try {
            $this->pagoService->procesarWebhookConekta($request->all());
            return response()->json(['ok' => true]);
        } catch (\Exception $e) {
            Log::error('Webhook Conekta error: ' . $e->getMessage());
            return response()->json(['error' => 'Error interno'], 500);
        }
    }

    public function webhookPaypal(Request $request)
    {
        try {
            $this->pagoService->procesarWebhookPaypal($request->all());
            return response()->json(['ok' => true]);
        } catch (\Exception $e) {
            Log::error('Webhook PayPal error: ' . $e->getMessage());
            return response()->json(['error' => 'Error interno'], 500);
        }
    }
}
