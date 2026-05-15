<?php

namespace App\Http\Controllers\Escolar;

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
        $periodoId     = $request->integer('periodo_id', $periodoActual?->id);

        $resumen          = $this->estadisticas->resumenEscolar($periodoId);
        $porCarrera       = $this->estadisticas->aspirantesPorCarrera($periodoId);
        $porSexo          = $this->estadisticas->aspirantesPorSexo($periodoId);
        $porStatus        = $this->estadisticas->aspirantesPorStatus($periodoId);
        $alumnosPorCarrera = $this->estadisticas->alumnosPorCarrera();
        $periodos         = Periodo::orderByDesc('anio')->get();

        return view('escolar.dashboard', compact(
            'resumen', 'porCarrera', 'porSexo', 'porStatus',
            'alumnosPorCarrera', 'periodos', 'periodoActual', 'periodoId'
        ));
    }
}
