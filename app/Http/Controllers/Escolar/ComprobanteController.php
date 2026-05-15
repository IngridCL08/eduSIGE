<?php

namespace App\Http\Controllers\Escolar;

use App\Http\Controllers\Controller;
use App\Models\Aspirante;
use App\Models\Bitacora;
use App\Models\ComprobanteTransferencia;
use App\Models\FichaPago;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ComprobanteController extends Controller
{
    public function index(Request $request): View
    {
        $query = ComprobanteTransferencia::with([
            'fichaPago.aspirante.carrera',
            'revisadoPor',
        ])->orderByRaw("FIELD(status, 'pendiente', 'rechazado', 'aprobado')")
          ->orderByDesc('created_at');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $comprobantes = $query->paginate(20)->withQueryString();

        return view('escolar.comprobantes.index', compact('comprobantes'));
    }

    public function aprobar(Request $request, ComprobanteTransferencia $comprobante): RedirectResponse
    {
        $comprobante->update([
            'status'      => ComprobanteTransferencia::STATUS_APROBADO,
            'revisado_por' => auth()->id(),
            'revisado_at' => now(),
            'observaciones' => $request->observaciones,
        ]);

        // Marcar ficha como pagada
        $ficha = $comprobante->fichaPago;
        $ficha->update([
            'status'          => 'pagado',
            'fecha_pago'      => now(),
            'metodo_pago'     => 'transferencia',
            'referencia_pago' => $comprobante->id,
        ]);

        // Actualizar status del aspirante
        $ficha->aspirante->update(['status' => Aspirante::STATUS_FICHA_PAGADA]);

        Bitacora::registrar(
            'comprobante_aprobado',
            "Comprobante #{$comprobante->id} aprobado. Ficha {$ficha->folio_ficha} marcada como pagada.",
            FichaPago::class,
            $ficha->id,
        );

        return back()->with('success', 'Comprobante aprobado y ficha marcada como pagada.');
    }

    public function rechazar(Request $request, ComprobanteTransferencia $comprobante): RedirectResponse
    {
        $request->validate([
            'observaciones' => ['required', 'string', 'max:500'],
        ]);

        $comprobante->update([
            'status'       => ComprobanteTransferencia::STATUS_RECHAZADO,
            'revisado_por' => auth()->id(),
            'revisado_at'  => now(),
            'observaciones' => $request->observaciones,
        ]);

        Bitacora::registrar(
            'comprobante_rechazado',
            "Comprobante #{$comprobante->id} rechazado. Motivo: {$request->observaciones}",
            ComprobanteTransferencia::class,
            $comprobante->id,
        );

        return back()->with('success', 'Comprobante rechazado. El aspirante será notificado.');
    }
}
