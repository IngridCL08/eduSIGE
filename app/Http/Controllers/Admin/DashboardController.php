<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\EstadisticaService;
use App\Models\User;
use App\Models\Aspirante;
use App\Models\Alumno;
use App\Models\FichaPago;
use App\Models\Bitacora;

class DashboardController extends Controller
{
    public function __construct(private EstadisticaService $estadisticas) {}

    public function index()
    {
        $resumenFinanciero = $this->estadisticas->resumenFinanciero();
        $resumenEscolar    = $this->estadisticas->resumenEscolar();

        $stats = [
            'total_usuarios'    => User::count(),
            'usuarios_activos'  => User::where('activo', true)->count(),
            'total_aspirantes'  => Aspirante::count(),
            'total_alumnos'     => Alumno::count(),
            'fichas_hoy'        => FichaPago::where('status', 'pagado')
                                      ->whereDate('fecha_pago', today())->count(),
            'ingresos_mes'      => FichaPago::where('status', 'pagado')
                                      ->whereMonth('fecha_pago', now()->month)
                                      ->sum('monto'),
        ];

        $ultimaActividad = Bitacora::with('user')
            ->orderByDesc('created_at')
            ->limit(10)
            ->get();

        return view('admin.dashboard', compact('stats', 'resumenFinanciero', 'resumenEscolar', 'ultimaActividad'));
    }
}
