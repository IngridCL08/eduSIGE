<?php

namespace App\Http\Controllers\Portal\Alumno;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class AdeudoController extends Controller
{
    public function index(): View
    {
        $alumno = auth('alumno')->user();

        $adeudos = $alumno->adeudos()
            ->with('periodo')
            ->orderByRaw("FIELD(status, 'pendiente', 'vencido', 'pagado')")
            ->orderBy('fecha_vencimiento')
            ->get();

        $totalPendiente = $adeudos->where('status', 'pendiente')->sum('monto');
        $totalVencido   = $adeudos->where('status', 'vencido')->sum('monto');

        return view('portal.alumno.adeudos', compact('alumno', 'adeudos', 'totalPendiente', 'totalVencido'));
    }
}
