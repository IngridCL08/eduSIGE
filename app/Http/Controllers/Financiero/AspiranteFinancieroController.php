<?php

namespace App\Http\Controllers\Financiero;

use App\Http\Controllers\Controller;
use App\Models\Aspirante;
use App\Models\Carrera;
use App\Models\Periodo;
use App\Services\PagoService;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;

class AspiranteFinancieroController extends Controller
{
    public function __construct(private PagoService $pagoService) {}

    public function index(Request $request)
    {
        $query = Aspirante::with(['carrera', 'periodo', 'fichaPago'])
            ->orderByDesc('created_at');

        if ($request->filled('periodo_id')) {
            $query->where('periodo_id', $request->periodo_id);
        }
        if ($request->filled('carrera_id')) {
            $query->where('carrera_id', $request->carrera_id);
        }
        if ($request->filled('ficha_status')) {
            if ($request->ficha_status === 'sin_ficha') {
                $query->doesntHave('fichasPago');
            } else {
                $query->whereHas('fichasPago', fn ($q) => $q->where('status', $request->ficha_status));
            }
        }
        if ($request->filled('buscar')) {
            $query->buscar($request->buscar);
        }

        $aspirantes = $query->paginate(20)->withQueryString();
        $carreras   = Carrera::activas()->orderBy('nombre')->get();
        $periodos   = Periodo::orderByDesc('anio')->get();

        return view('financiero.aspirantes.index', compact('aspirantes', 'carreras', 'periodos'));
    }

    public function show(Aspirante $aspirante)
    {
        $aspirante->load(['carrera', 'periodo', 'fichasPago.transacciones']);
        return view('financiero.aspirantes.show', compact('aspirante'));
    }

    public function generarFicha(Aspirante $aspirante): RedirectResponse
    {
        if ($aspirante->tieneFichaPagada()) {
            return back()->with('error', 'El aspirante ya cuenta con una ficha pagada.');
        }

        $ficha = $this->pagoService->generarFicha($aspirante, auth()->id());

        return back()->with('success', "Ficha {$ficha->folio_ficha} generada correctamente.");
    }
}
