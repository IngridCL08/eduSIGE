<?php

namespace App\Http\Controllers\Financiero;

use App\Http\Controllers\Controller;
use App\Models\FichaPago;
use App\Models\Periodo;
use App\Services\ReporteService;
use App\Services\EstadisticaService;
use Illuminate\Http\Request;

class ReporteController extends Controller
{
    public function __construct(
        private ReporteService    $reporteService,
        private EstadisticaService $estadisticas,
    ) {}

    public function ingresos(Request $request)
    {
        $periodos = Periodo::orderByDesc('anio')->get();

        $query = FichaPago::with(['aspirante.carrera', 'aspirante.periodo'])
            ->where('status', 'pagado')
            ->orderByDesc('fecha_pago');

        if ($request->filled('periodo_id')) {
            $query->whereHas('aspirante', fn ($q) => $q->where('periodo_id', $request->periodo_id));
        }
        if ($request->filled('metodo_pago')) {
            $query->where('metodo_pago', $request->metodo_pago);
        }
        if ($request->filled('desde')) {
            $query->whereDate('fecha_pago', '>=', $request->desde);
        }
        if ($request->filled('hasta')) {
            $query->whereDate('fecha_pago', '<=', $request->hasta);
        }

        $fichas = $query->paginate(25)->withQueryString();

        // KPIs del reporte
        $allQuery   = clone $query->toBase();
        $totalMonto = FichaPago::where('status', 'pagado')->sum('monto');
        $totalFichas = FichaPago::where('status', 'pagado')->count();

        // Ingresos por mes (últimos 12 meses)
        $ingresosMes = $this->estadisticas->ingresosPorMes(now()->year);

        // Mes de mayor ingreso
        $maxIdx   = array_search(max($ingresosMes['totales']), $ingresosMes['totales']);
        $mesMayor = $ingresosMes['totales'][$maxIdx] > 0 ? $ingresosMes['meses'][$maxIdx] : null;

        $totales = [
            'total_ingresos' => $totalMonto,
            'total_fichas'   => $totalFichas,
            'promedio_monto' => $totalFichas > 0 ? $totalMonto / $totalFichas : 0,
            'mes_mayor'      => $mesMayor,
        ];

        return view('financiero.reportes.ingresos', compact(
            'fichas', 'totales', 'ingresosMes', 'periodos'
        ));
    }

    public function fichas(Request $request)
    {
        $periodos = Periodo::orderByDesc('anio')->get();

        $query = FichaPago::with(['aspirante.carrera', 'aspirante.periodo'])
            ->latest('created_at');

        if ($request->filled('buscar')) {
            $query->where(function ($q) use ($request) {
                $q->where('folio_ficha', 'like', "%{$request->buscar}%")
                  ->orWhereHas('aspirante', fn ($aq) =>
                      $aq->where('nombre', 'like', "%{$request->buscar}%")
                         ->orWhere('apellido_paterno', 'like', "%{$request->buscar}%")
                  );
            });
        }
        if ($request->filled('periodo_id')) {
            $query->whereHas('aspirante', fn ($q) => $q->where('periodo_id', $request->periodo_id));
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('metodo_pago')) {
            $query->where('metodo_pago', $request->metodo_pago);
        }

        $fichas = $query->paginate(25)->withQueryString();

        $resumen = [
            'total'      => FichaPago::count(),
            'pagadas'    => FichaPago::where('status', 'pagado')->count(),
            'pendientes' => FichaPago::where('status', 'pendiente')->count(),
            'vencidas'   => FichaPago::where('status', 'vencido')->count(),
        ];

        return view('financiero.reportes.fichas', compact('fichas', 'resumen', 'periodos'));
    }

    public function exportar(Request $request)
    {
        $tipo    = $request->get('tipo', 'fichas');
        $filtros = $request->only(['status', 'metodo_pago', 'desde', 'hasta', 'periodo_id']);

        return match ($tipo) {
            'fichas'     => $this->reporteService->excelFichas($filtros),
            'aspirantes' => $this->reporteService->excelAspirantes($filtros),
            default      => back()->with('error', 'Tipo de exportación no válido.'),
        };
    }

    public function descargarPdf(Request $request, string $tipo)
    {
        $filtros = $request->only(['desde', 'hasta', 'periodo_id', 'metodo_pago']);

        return match ($tipo) {
            'ingresos' => $this->reporteService->pdfIngresos($filtros),
            default    => abort(404),
        };
    }
}
