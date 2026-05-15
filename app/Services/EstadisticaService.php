<?php

namespace App\Services;

use App\Models\Aspirante;
use App\Models\Alumno;
use App\Models\FichaPago;
use App\Models\Carrera;
use App\Models\Periodo;
use Illuminate\Support\Facades\DB;

class EstadisticaService
{
    // ─── Estadísticas generales del dashboard financiero ──────

    public function resumenFinanciero(?int $periodoId = null): array
    {
        $query = FichaPago::query();

        if ($periodoId) {
            $query->whereHas('aspirante', fn ($q) => $q->where('periodo_id', $periodoId));
        }

        $fichas = $query->selectRaw("
            COUNT(*) as total,
            SUM(CASE WHEN status = 'pagado'    THEN 1 ELSE 0 END) as pagadas,
            SUM(CASE WHEN status = 'pendiente' THEN 1 ELSE 0 END) as pendientes,
            SUM(CASE WHEN status = 'vencido'   THEN 1 ELSE 0 END) as vencidas,
            SUM(CASE WHEN status = 'cancelado' THEN 1 ELSE 0 END) as canceladas,
            SUM(CASE WHEN status = 'pagado' THEN monto ELSE 0 END) as ingresos_total
        ")->first();

        return [
            'total_fichas'    => (int) $fichas->total,
            'pagadas'         => (int) $fichas->pagadas,
            'pendientes'      => (int) $fichas->pendientes,
            'vencidas'        => (int) $fichas->vencidas,
            'canceladas'      => (int) $fichas->canceladas,
            'ingresos_total'  => (float) $fichas->ingresos_total,
        ];
    }

    public function ingresosPorMes(int $anio): array
    {
        $rows = FichaPago::where('status', 'pagado')
            ->whereYear('fecha_pago', $anio)
            ->selectRaw('MONTH(fecha_pago) as mes, SUM(monto) as total')
            ->groupBy('mes')
            ->orderBy('mes')
            ->pluck('total', 'mes')
            ->toArray();

        $meses = ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'];
        $totales = [];
        foreach (range(1, 12) as $m) {
            $totales[] = (float) ($rows[$m] ?? 0);
        }

        return ['meses' => $meses, 'totales' => $totales];
    }

    public function fichasPorMetodoPago(?int $periodoId = null): array
    {
        $query = FichaPago::where('status', 'pagado');

        if ($periodoId) {
            $query->whereHas('aspirante', fn ($q) => $q->where('periodo_id', $periodoId));
        }

        return $query
            ->selectRaw('metodo_pago, COUNT(*) as total')
            ->groupBy('metodo_pago')
            ->pluck('total', 'metodo_pago')
            ->toArray();
    }

    // ─── Estadísticas escolares ───────────────────────────────

    public function resumenEscolar(?int $periodoId = null): array
    {
        $aspQ = Aspirante::query();
        $aluQ = Alumno::query();

        if ($periodoId) {
            $aspQ->where('periodo_id', $periodoId);
        }

        return [
            'total_aspirantes'  => $aspQ->count(),
            'admitidos'         => $aspQ->clone()->where('status', 'admitido')->count(),
            'en_proceso'        => $aspQ->clone()->whereNotIn('status', ['admitido', 'no_admitido', 'cancelado'])->count(),
            'no_admitidos'      => $aspQ->clone()->where('status', 'no_admitido')->count(),
            'total_alumnos'     => $aluQ->count(),
            'alumnos_activos'   => $aluQ->clone()->where('status', 'activo')->count(),
            'egresados'         => $aluQ->clone()->where('status', 'egresado')->count(),
            'titulados'         => $aluQ->clone()->where('status', 'titulado')->count(),
        ];
    }

    public function aspirantesPorCarrera(?int $periodoId = null): array
    {
        $query = Aspirante::join('carreras', 'aspirantes.carrera_id', '=', 'carreras.id')
            ->selectRaw('carreras.nombre as carrera, COUNT(aspirantes.id) as total')
            ->groupBy('carreras.id', 'carreras.nombre')
            ->orderByDesc('total');

        if ($periodoId) {
            $query->where('aspirantes.periodo_id', $periodoId);
        }

        $rows = $query->get();

        return [
            'carreras' => $rows->pluck('carrera')->toArray(),
            'totales'  => $rows->pluck('total')->map(fn ($v) => (int) $v)->toArray(),
        ];
    }

    public function aspirantesPorSexo(?int $periodoId = null): array
    {
        $query = Aspirante::selectRaw("sexo, COUNT(*) as total")->groupBy('sexo');

        if ($periodoId) {
            $query->where('periodo_id', $periodoId);
        }

        $rows = $query->pluck('total', 'sexo');

        return [
            'M' => (int) ($rows['M'] ?? 0),
            'F' => (int) ($rows['F'] ?? 0),
            'O' => (int) ($rows['O'] ?? 0),
        ];
    }

    public function aspirantesPorStatus(?int $periodoId = null): array
    {
        $query = Aspirante::selectRaw("status, COUNT(*) as total")->groupBy('status');

        if ($periodoId) {
            $query->where('periodo_id', $periodoId);
        }

        return $query->pluck('total', 'status')->toArray();
    }

    public function alumnosPorCarrera(): array
    {
        $rows = Alumno::join('carreras', 'alumnos.carrera_id', '=', 'carreras.id')
            ->where('alumnos.status', 'activo')
            ->selectRaw('carreras.nombre as carrera, COUNT(alumnos.id) as total')
            ->groupBy('carreras.id', 'carreras.nombre')
            ->orderByDesc('total')
            ->get();

        return [
            'carreras' => $rows->pluck('carrera')->toArray(),
            'totales'  => $rows->pluck('total')->map(fn ($v) => (int) $v)->toArray(),
        ];
    }
}
