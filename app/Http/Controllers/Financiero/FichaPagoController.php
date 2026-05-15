<?php

namespace App\Http\Controllers\Financiero;

use App\Http\Controllers\Controller;
use App\Models\FichaPago;
use App\Models\Aspirante;
use App\Models\Carrera;
use App\Models\Periodo;
use App\Services\PagoService;
use App\Services\ReporteService;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;

class FichaPagoController extends Controller
{
    public function __construct(
        private PagoService    $pagoService,
        private ReporteService $reporteService,
    ) {}

    public function index(Request $request)
    {
        $query = FichaPago::with(['aspirante.carrera', 'generadoPor'])
            ->orderByDesc('created_at');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('metodo_pago')) {
            $query->where('metodo_pago', $request->metodo_pago);
        }
        if ($request->filled('carrera_id')) {
            $query->whereHas('aspirante', fn ($q) => $q->where('carrera_id', $request->carrera_id));
        }
        if ($request->filled('buscar')) {
            $termino = $request->buscar;
            $query->where(function ($q) use ($termino) {
                $q->where('folio_ficha', 'like', "%{$termino}%")
                  ->orWhereHas('aspirante', fn ($aq) =>
                      $aq->where('folio', 'like', "%{$termino}%")
                         ->orWhere('nombre', 'like', "%{$termino}%")
                         ->orWhere('apellido_paterno', 'like', "%{$termino}%")
                  );
            });
        }

        $fichas   = $query->paginate(20)->withQueryString();
        $carreras = Carrera::activas()->orderBy('nombre')->get();

        return view('financiero.fichas.index', compact('fichas', 'carreras'));
    }

    public function show(FichaPago $ficha)
    {
        $ficha->load(['aspirante.carrera', 'aspirante.periodo', 'transacciones', 'generadoPor']);
        return view('financiero.fichas.show', compact('ficha'));
    }

    public function cancelar(FichaPago $ficha): RedirectResponse
    {
        if (! in_array($ficha->status, ['pendiente', 'vencido'])) {
            return back()->with('error', 'Solo se pueden cancelar fichas pendientes o vencidas.');
        }

        $ficha->update(['status' => 'cancelado']);

        return back()->with('success', "Ficha {$ficha->folio_ficha} cancelada correctamente.");
    }

    public function registrarPagoManual(Request $request, FichaPago $ficha): RedirectResponse
    {
        $request->validate([
            'metodo_pago' => ['required', 'in:transferencia,efectivo,otro'],
            'referencia'  => ['nullable', 'string', 'max:100'],
        ]);

        if ($ficha->status !== 'pendiente') {
            return back()->with('error', 'Solo se puede registrar pago en fichas pendientes.');
        }

        $this->pagoService->registrarPagoManual(
            $ficha,
            $request->metodo_pago,
            $request->referencia,
        );

        return back()->with('success', "Pago registrado correctamente para la ficha {$ficha->folio_ficha}.");
    }

    public function descargarPdf(FichaPago $ficha)
    {
        return $this->reporteService->pdfFicha($ficha);
    }

    // Obligatorio por resource pero no se usa
    public function create() { abort(404); }
    public function store()  { abort(404); }
}
