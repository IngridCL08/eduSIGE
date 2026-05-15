<?php

namespace App\Http\Controllers\Financiero;

use App\Http\Controllers\Controller;
use App\Services\EstadisticaService;
use App\Models\Periodo;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __construct(private EstadisticaService $estadisticas) {}

    public function index(Request $request)
    {
        $periodoActual = Periodo::actual();
        $anio          = $request->integer('anio', now()->year);
        $periodoId     = $request->integer('periodo_id', $periodoActual?->id);

        $resumen       = $this->estadisticas->resumenFinanciero($periodoId);
        $ingresosMes   = $this->estadisticas->ingresosPorMes($anio);
        $metodoPago    = $this->estadisticas->fichasPorMetodoPago($periodoId);
        $periodos      = Periodo::orderByDesc('anio')->get();

        return view('financiero.dashboard', compact(
            'resumen', 'ingresosMes', 'metodoPago', 'periodos',
            'periodoActual', 'anio', 'periodoId'
        ));
    }
}
