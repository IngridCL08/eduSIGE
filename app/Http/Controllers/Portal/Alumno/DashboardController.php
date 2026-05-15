<?php

namespace App\Http\Controllers\Portal\Alumno;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $alumno = auth('alumno')->user();
        $alumno->load([
            'carrera', 'periodoIngreso', 'aspirante',
            'historial' => fn ($q) => $q->with('periodo')->latest()->take(5),
            'adeudos'   => fn ($q) => $q->where('status', 'pendiente'),
        ]);

        $totalCreditos    = $alumno->carrera?->creditos_totales ?? 0;
        $avanceCreditos   = $totalCreditos > 0
            ? round(($alumno->creditos_acumulados / $totalCreditos) * 100)
            : 0;

        return view('portal.alumno.dashboard', compact('alumno', 'avanceCreditos'));
    }
}
