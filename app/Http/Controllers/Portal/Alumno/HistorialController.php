<?php

namespace App\Http\Controllers\Portal\Alumno;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class HistorialController extends Controller
{
    public function index(): View
    {
        $alumno = auth('alumno')->user();
        $alumno->load(['carrera']);

        $historial = $alumno->historial()
            ->with('periodo')
            ->orderBy('periodo_id', 'desc')
            ->orderBy('materia')
            ->get()
            ->groupBy(fn ($h) => $h->periodo?->nombre ?? 'Sin período');

        $promedioGeneral = $alumno->historial()
            ->where('status', 'acreditada')
            ->avg('calificacion');

        return view('portal.alumno.historial', compact('alumno', 'historial', 'promedioGeneral'));
    }
}
