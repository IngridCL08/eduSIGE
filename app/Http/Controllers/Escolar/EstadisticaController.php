<?php

namespace App\Http\Controllers\Escolar;

use App\Http\Controllers\Controller;
use App\Models\Alumno;
use App\Models\Aspirante;
use App\Models\HistorialAcademico;
use App\Models\Periodo;
use App\Models\Carrera;
use App\Services\EstadisticaService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EstadisticaController extends Controller
{
    public function __construct(private EstadisticaService $estadisticas) {}

    public function index()
    {
        return redirect()->route('escolar.estadisticas.aspirantes');
    }

    public function aspirantes(Request $request)
    {
        $periodos  = Periodo::orderByDesc('anio')->get();
        $periodoId = $request->filled('periodo_id') ? (int) $request->periodo_id : null;

        $resumenEscolar = $this->estadisticas->resumenEscolar($periodoId);

        $resumen = [
            'total_aspirantes' => $resumenEscolar['total_aspirantes'],
            'con_ficha_pagada' => Aspirante::when($periodoId, fn ($q) => $q->where('periodo_id', $periodoId))
                                    ->whereIn('status', ['ficha_pagada', 'documentos_entregados', 'admitido'])
                                    ->count(),
            'admitidos'        => $resumenEscolar['admitidos'],
            'no_admitidos'     => $resumenEscolar['no_admitidos'],
        ];

        // Por carrera: ['ISIA' => 12, 'IIND' => 8, ...]
        $porCarreraRaw = $this->estadisticas->aspirantesPorCarrera($periodoId);
        $porCarrera = array_combine($porCarreraRaw['carreras'], $porCarreraRaw['totales']);

        // Por estatus
        $porEstatus = $this->estadisticas->aspirantesPorStatus($periodoId);

        // Por sexo
        $porSexo = $this->estadisticas->aspirantesPorSexo($periodoId);

        // Por período (últimos 5)
        $porPeriodo = Aspirante::join('periodos', 'aspirantes.periodo_id', '=', 'periodos.id')
            ->selectRaw('periodos.nombre, COUNT(aspirantes.id) as total')
            ->groupBy('periodos.id', 'periodos.nombre')
            ->orderByDesc('periodos.anio')
            ->limit(5)
            ->pluck('total', 'periodos.nombre')
            ->toArray();

        return view('escolar.estadisticas.aspirantes', compact(
            'resumen', 'porCarrera', 'porEstatus', 'porSexo', 'porPeriodo', 'periodos', 'periodoId'
        ));
    }

    public function alumnos(Request $request)
    {
        $total    = Alumno::count();
        $activos  = Alumno::where('status', 'activo')->count();
        $bajas    = Alumno::whereIn('status', ['baja_temporal', 'baja_definitiva'])->count();
        $egresados = Alumno::whereIn('status', ['egresado', 'titulado'])->count();

        $promedioGeneral  = Alumno::avg('promedio_general');
        $creditosPromedio = Alumno::avg('creditos_acumulados');

        $totalMaterias    = HistorialAcademico::count();
        $acreditadas      = HistorialAcademico::where('status', 'acreditada')->count();
        $tasaAcreditacion = $totalMaterias > 0 ? round($acreditadas / $totalMaterias * 100, 1) : null;

        $resumen = [
            'total_alumnos'    => $total,
            'activos'          => $activos,
            'bajas'            => $bajas,
            'egresados'        => $egresados,
            'promedio_general' => $promedioGeneral,
            'creditos_promedio'=> $creditosPromedio,
            'tasa_acreditacion'=> $tasaAcreditacion,
        ];

        // Por carrera
        $porCarreraRaw = $this->estadisticas->alumnosPorCarrera();
        $porCarrera    = array_combine($porCarreraRaw['carreras'], $porCarreraRaw['totales']);

        // Por estatus
        $porEstatus = Alumno::selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status')
            ->toArray();

        // Promedio por carrera
        $promedioPorCarrera = Alumno::join('carreras', 'alumnos.carrera_id', '=', 'carreras.id')
            ->where('alumnos.status', 'activo')
            ->whereNotNull('alumnos.promedio_general')
            ->selectRaw('carreras.clave, AVG(alumnos.promedio_general) as promedio')
            ->groupBy('carreras.id', 'carreras.clave')
            ->pluck('promedio', 'carreras.clave')
            ->toArray();

        return view('escolar.estadisticas.alumnos', compact(
            'resumen', 'porCarrera', 'porEstatus', 'promedioPorCarrera'
        ));
    }

    public function porCarrera(Request $request)
    {
        $periodoId = $request->filled('periodo_id') ? (int) $request->periodo_id : null;
        $periodos  = Periodo::orderByDesc('anio')->get();
        $datos     = $this->estadisticas->aspirantesPorCarrera($periodoId);

        return view('escolar.estadisticas.por-carrera', compact('datos', 'periodos', 'periodoId'));
    }

    public function json(Request $request)
    {
        $tipo      = $request->get('tipo', 'aspirantes_carrera');
        $periodoId = $request->filled('periodo_id') ? (int) $request->periodo_id : null;

        $datos = match ($tipo) {
            'aspirantes_carrera' => $this->estadisticas->aspirantesPorCarrera($periodoId),
            'aspirantes_sexo'    => $this->estadisticas->aspirantesPorSexo($periodoId),
            'aspirantes_status'  => $this->estadisticas->aspirantesPorStatus($periodoId),
            'alumnos_carrera'    => $this->estadisticas->alumnosPorCarrera(),
            default              => [],
        };

        return response()->json($datos);
    }
}
