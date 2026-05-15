<?php

namespace App\Http\Controllers\Portal\Aspirante;

use App\Http\Controllers\Controller;
use App\Models\ComprobanteTransferencia;
use App\Models\FichaPago;
use App\Services\PagoService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FichaController extends Controller
{
    public function __construct(private PagoService $pagoService) {}

    public function show(): View
    {
        $aspirante = auth('aspirante')->user();
        $aspirante->load(['fichasPago.comprobantes', 'carrera']);

        $ficha = $aspirante->fichaPago;

        return view('portal.aspirante.ficha', compact('aspirante', 'ficha'));
    }

    public function iniciarPago(Request $request): RedirectResponse
    {
        $request->validate([
            'gateway' => ['required', 'in:conekta,paypal,transferencia'],
        ]);

        $aspirante = auth('aspirante')->user();
        $ficha     = $aspirante->fichaPago;

        if (! $ficha || $ficha->status === 'pagado') {
            return back()->with('error', 'No tienes una ficha de pago pendiente.');
        }

        if ($ficha->status === 'vencido') {
            return back()->with('error', 'Tu ficha ha vencido. Contacta a la institución para generar una nueva.');
        }

        if ($request->gateway === 'transferencia') {
            return back()->with('info', 'Realiza tu transferencia a la cuenta indicada y sube tu comprobante.');
        }

        try {
            $resultado = $this->pagoService->iniciarPagoEnLinea($ficha, $request->gateway);
            return redirect()->away($resultado['redirect_url']);
        } catch (\Exception $e) {
            return back()->with('error', 'No fue posible iniciar el pago. Intenta más tarde.');
        }
    }

    public function exitoso(Request $request): View
    {
        $aspirante = auth('aspirante')->user();
        $ficha     = FichaPago::find($request->query('ficha'));

        return view('portal.aspirante.pago-exitoso', compact('aspirante', 'ficha'));
    }

    public function cancelado(): View
    {
        $aspirante = auth('aspirante')->user();
        return view('portal.aspirante.pago-cancelado', compact('aspirante'));
    }

    public function subirComprobante(Request $request): RedirectResponse
    {
        $request->validate([
            'comprobante' => ['required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'],
        ], [
            'comprobante.required' => 'Selecciona un archivo.',
            'comprobante.mimes'    => 'Solo se permiten imágenes JPG/PNG o PDF.',
            'comprobante.max'      => 'El archivo no debe superar 5 MB.',
        ]);

        $aspirante = auth('aspirante')->user();
        $ficha     = $aspirante->fichaPago;

        if (! $ficha || $ficha->status === 'pagado') {
            return back()->with('error', 'No tienes una ficha de pago pendiente.');
        }

        $archivo   = $request->file('comprobante');
        $path      = $archivo->store("comprobantes/{$aspirante->folio}", 'public');

        ComprobanteTransferencia::create([
            'ficha_pago_id'  => $ficha->id,
            'archivo_path'   => $path,
            'nombre_original' => $archivo->getClientOriginalName(),
            'status'         => 'pendiente',
        ]);

        return back()->with('success', 'Comprobante subido correctamente. Será revisado por el área financiera.');
    }
}
