<?php

namespace App\Http\Controllers\Escolar;

use App\Http\Controllers\Controller;
use App\Models\Alumno;
use App\Models\Periodo;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ConstanciaController extends Controller
{
    public function index(Request $request)
    {
        $query = Alumno::with(['aspirante', 'carrera', 'periodoIngreso'])
            ->activos()
            ->orderBy('matricula');

        if ($request->filled('buscar')) {
            $t = $request->buscar;
            $query->whereHas('aspirante', fn ($q) =>
                $q->where('nombre', 'like', "%{$t}%")
                  ->orWhere('apellido_paterno', 'like', "%{$t}%")
                  ->orWhere('apellido_materno', 'like', "%{$t}%")
            )->orWhere('matricula', 'like', "%{$t}%");
        }

        $alumnos = $query->paginate(20)->withQueryString();

        return view('escolar.constancias.index', compact('alumnos'));
    }

    public function estudios(Alumno $alumno): Response
    {
        $alumno->load(['aspirante', 'carrera', 'periodoIngreso']);

        $periodoActual = Periodo::enCurso();
        $adeudosPendientes = $alumno->adeudos()->where('status', 'pendiente')->exists();

        $pdf = Pdf::loadView('escolar.constancias.pdf.estudios', compact(
            'alumno', 'periodoActual', 'adeudosPendientes'
        ))->setPaper('letter', 'portrait');

        $filename = "constancia_estudios_{$alumno->matricula}.pdf";

        return $pdf->download($filename);
    }

    public function kardex(Alumno $alumno): Response
    {
        $alumno->load(['aspirante', 'carrera', 'periodoIngreso']);

        $historial = $alumno->historialAcademico()
            ->with('periodo')
            ->orderBy('periodo_id')
            ->orderBy('materia')
            ->get()
            ->groupBy('periodo_id');

        $periodos = Periodo::whereIn('id', $historial->keys())->get()->keyBy('id');

        $creditosAprobados = $alumno->historialAcademico()
            ->where('status', 'acreditada')
            ->sum('creditos');

        $pdf = Pdf::loadView('escolar.constancias.pdf.kardex', compact(
            'alumno', 'historial', 'periodos', 'creditosAprobados'
        ))->setPaper('letter', 'portrait');

        $filename = "kardex_{$alumno->matricula}.pdf";

        return $pdf->download($filename);
    }

    public function boleta(Alumno $alumno, Periodo $periodo): Response
    {
        $alumno->load(['aspirante', 'carrera']);

        $materias = $alumno->historialAcademico()
            ->with('periodo')
            ->where('periodo_id', $periodo->id)
            ->orderBy('materia')
            ->get();

        $promedioPeriodo = $materias->where('status', 'acreditada')->avg('calificacion') ?? 0;

        $pdf = Pdf::loadView('escolar.constancias.pdf.boleta', compact(
            'alumno', 'periodo', 'materias', 'promedioPeriodo'
        ))->setPaper('letter', 'portrait');

        $filename = "boleta_{$alumno->matricula}_{$periodo->anio}{$periodo->ciclo}.pdf";

        return $pdf->download($filename);
    }
}
