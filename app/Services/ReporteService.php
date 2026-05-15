<?php

namespace App\Services;

use App\Models\FichaPago;
use App\Models\Aspirante;
use App\Models\Alumno;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\AspirantesExport;
use App\Exports\AlumnosExport;
use App\Exports\FichasExport;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Illuminate\Http\Response;

class ReporteService
{
    /**
     * Genera el PDF de una ficha de pago individual.
     */
    public function pdfFicha(FichaPago $ficha): Response
    {
        $ficha->load(['aspirante.carrera', 'aspirante.periodo', 'generadoPor']);

        $pdf = Pdf::loadView('financiero.fichas.pdf', compact('ficha'))
            ->setPaper('letter', 'portrait');

        return $pdf->download("ficha-{$ficha->folio_ficha}.pdf");
    }

    /**
     * Genera PDF del reporte de ingresos.
     */
    public function pdfIngresos(array $filtros = []): Response
    {
        $query = FichaPago::with(['aspirante.carrera', 'aspirante.periodo'])
            ->where('status', 'pagado')
            ->orderByDesc('fecha_pago');

        if (! empty($filtros['desde'])) {
            $query->whereDate('fecha_pago', '>=', $filtros['desde']);
        }
        if (! empty($filtros['hasta'])) {
            $query->whereDate('fecha_pago', '<=', $filtros['hasta']);
        }
        if (! empty($filtros['periodo_id'])) {
            $query->whereHas('aspirante', fn ($q) => $q->where('periodo_id', $filtros['periodo_id']));
        }
        if (! empty($filtros['metodo_pago'])) {
            $query->where('metodo_pago', $filtros['metodo_pago']);
        }

        $fichas = $query->get();
        $totalMonto  = $fichas->sum('monto');
        $totalFichas = $fichas->count();

        $totales = [
            'total_ingresos' => $totalMonto,
            'total_fichas'   => $totalFichas,
            'promedio_monto' => $totalFichas > 0 ? $totalMonto / $totalFichas : 0,
            'mes_mayor'      => null,
        ];

        $pdf = Pdf::loadView('financiero.reportes.pdf-ingresos', compact('fichas', 'totales', 'filtros'))
            ->setPaper('letter', 'landscape');

        return $pdf->download('reporte-ingresos-' . now()->format('Y-m-d') . '.pdf');
    }

    /**
     * Exporta aspirantes a Excel.
     */
    public function excelAspirantes(array $filtros = []): BinaryFileResponse
    {
        return Excel::download(
            new AspirantesExport($filtros),
            'aspirantes-' . now()->format('Y-m-d') . '.xlsx'
        );
    }

    /**
     * Exporta alumnos a Excel.
     */
    public function excelAlumnos(array $filtros = []): BinaryFileResponse
    {
        return Excel::download(
            new AlumnosExport($filtros),
            'alumnos-' . now()->format('Y-m-d') . '.xlsx'
        );
    }

    /**
     * Exporta fichas de pago a Excel.
     */
    public function excelFichas(array $filtros = []): BinaryFileResponse
    {
        return Excel::download(
            new FichasExport($filtros),
            'fichas-pago-' . now()->format('Y-m-d') . '.xlsx'
        );
    }
}
