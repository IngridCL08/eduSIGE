<?php

namespace App\Services;

use App\Models\FichaPago;
use Srmklive\PayPal\Services\PayPal as PayPalClient;
use Exception;
use Illuminate\Support\Facades\Log;

class PayPalService
{
    private ?PayPalClient $provider = null;

    private function provider(): PayPalClient
    {
        if ($this->provider === null) {
            $this->provider = new PayPalClient;
            $this->provider->setApiCredentials(config('paypal'));
            $this->provider->getAccessToken();
        }
        return $this->provider;
    }

    /**
     * Crea una orden de pago en PayPal.
     * Retorna: ['order_id' => '...', 'redirect_url' => '...']
     */
    public function crearOrden(FichaPago $ficha): array
    {
        try {
            $response = $this->provider()->createOrder([
                'intent'         => 'CAPTURE',
                'purchase_units' => [
                    [
                        'reference_id' => $ficha->folio_ficha,
                        'description'  => $ficha->concepto,
                        'amount'       => [
                            'currency_code' => 'MXN',
                            'value'         => number_format($ficha->monto, 2, '.', ''),
                        ],
                    ],
                ],
                'application_context' => [
                    'return_url' => route('financiero.pago.exitoso') . '?ficha=' . $ficha->id,
                    'cancel_url' => route('financiero.pago.cancelado') . '?ficha=' . $ficha->id,
                ],
            ]);

            $redirectUrl = collect($response['links'])
                ->firstWhere('rel', 'approve')['href'] ?? '';

            return [
                'order_id'     => $response['id'],
                'redirect_url' => $redirectUrl,
            ];
        } catch (Exception $e) {
            Log::error('PayPal crearOrden error: ' . $e->getMessage(), [
                'ficha_id' => $ficha->id,
            ]);
            throw $e;
        }
    }

    /**
     * Captura el pago una vez que el usuario aprueba en PayPal.
     */
    public function capturarPago(string $orderId): array
    {
        return $this->provider()->capturePaymentOrder($orderId);
    }
}
