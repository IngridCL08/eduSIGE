<?php

namespace App\Http\Controllers\Portal\Alumno;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class PagosController extends Controller
{
    public function index(): View
    {
        $alumno = auth('alumno')->user();

        $pagos = $alumno->adeudos()
            ->with(['periodo', 'validadoPor'])
            ->orderByRaw("FIELD(status, 'pendiente', 'vencido', 'pagado')")
            ->orderByDesc('created_at')
            ->get();

        $totalPagado    = $pagos->where('status', 'pagado')->sum('monto');
        $totalPendiente = $pagos->where('status', 'pendiente')->sum('monto');
        $countPagados   = $pagos->where('status', 'pagado')->count();
        $countPendientes = $pagos->where('status', 'pendiente')->count();

        $tipos   = \App\Http\Controllers\Escolar\AdeudoController::$tipos;
        $metodos = \App\Http\Controllers\Financiero\PagoAlumnoController::$metodos;

        return view('portal.alumno.pagos', compact(
            'pagos', 'totalPagado', 'totalPendiente',
            'countPagados', 'countPendientes', 'tipos', 'metodos'
        ));
    }
}
