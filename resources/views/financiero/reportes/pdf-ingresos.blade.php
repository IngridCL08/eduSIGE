<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<style>
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body { font-family: 'DejaVu Sans', sans-serif; font-size: 10px; color: #1a1a1a; background: #fff; }

    .header { background: #0F172A; color: #fff; padding: 20px 24px; display: flex; justify-content: space-between; align-items: center; }
    .header-logo { font-size: 22px; font-weight: 900; letter-spacing: -0.5px; }
    .header-sub { font-size: 9px; color: #94a3b8; margin-top: 2px; }
    .header-right { text-align: right; }
    .header-right p { font-size: 9px; color: #94a3b8; }
    .header-right .report-title { font-size: 14px; font-weight: 700; color: #fff; }

    .meta-bar { background: #1E3A5F; color: #cbd5e1; padding: 8px 24px; font-size: 9px; display: flex; justify-content: space-between; }

    .content { padding: 20px 24px; }

    .section-title { font-size: 11px; font-weight: 700; color: #0F172A; margin-bottom: 10px; padding-bottom: 4px; border-bottom: 2px solid #1E3A5F; }

    .kpi-grid { display: flex; gap: 12px; margin-bottom: 20px; }
    .kpi-box { flex: 1; border: 1px solid #e2e8f0; border-radius: 6px; padding: 10px 12px; }
    .kpi-label { font-size: 8px; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em; }
    .kpi-value { font-size: 16px; font-weight: 900; color: #0F172A; margin-top: 2px; }
    .kpi-value.green { color: #16a34a; }

    table { width: 100%; border-collapse: collapse; font-size: 9px; }
    thead tr { background: #1E3A5F; color: #fff; }
    thead th { padding: 7px 8px; text-align: left; font-weight: 600; font-size: 8.5px; }
    tbody tr { border-bottom: 1px solid #f1f5f9; }
    tbody tr:nth-child(even) { background: #f8fafc; }
    tbody td { padding: 6px 8px; color: #374151; }
    .mono { font-family: 'DejaVu Sans Mono', monospace; font-size: 8px; }
    .badge { display: inline-block; padding: 1px 6px; border-radius: 999px; font-size: 7.5px; font-weight: 600; }
    .badge-pagado { background: #dcfce7; color: #166534; }
    .amount { font-weight: 700; color: #0F172A; }

    .totals-row { background: #0F172A !important; color: #fff; }
    .totals-row td { color: #fff; font-weight: 700; padding: 8px; }

    .footer { margin-top: 24px; padding: 12px 24px; border-top: 1px solid #e2e8f0; display: flex; justify-content: space-between; font-size: 8px; color: #94a3b8; }
</style>
</head>
<body>

<div class="header">
    <div>
        <div class="header-logo">eduSIGE</div>
        <div class="header-sub">{{ config('app.edusige.institucion') }}</div>
    </div>
    <div class="header-right">
        <p class="report-title">Reporte de Ingresos</p>
        <p>Generado: {{ now()->format('d/m/Y H:i') }}</p>
        <p>Por: {{ auth()->user()->name }}</p>
    </div>
</div>

<div class="meta-bar">
    <span>
        @if(!empty($filtros['periodo'])) Período: {{ $filtros['periodo'] }} @endif
        @if(!empty($filtros['metodo_pago'])) · Método: {{ ucfirst($filtros['metodo_pago']) }} @endif
        @if(!empty($filtros['desde'])) · Desde: {{ $filtros['desde'] }} @endif
        @if(!empty($filtros['hasta'])) · Hasta: {{ $filtros['hasta'] }} @endif
        @if(empty(array_filter($filtros ?? []))) Todos los registros @endif
    </span>
    <span>Total fichas: {{ number_format($totales['total_fichas']) }}</span>
</div>

<div class="content">

    {{-- KPIs --}}
    <div class="kpi-grid">
        <div class="kpi-box">
            <div class="kpi-label">Total Ingresos</div>
            <div class="kpi-value green">${{ number_format($totales['total_ingresos'], 2) }}</div>
        </div>
        <div class="kpi-box">
            <div class="kpi-label">Fichas Pagadas</div>
            <div class="kpi-value">{{ number_format($totales['total_fichas']) }}</div>
        </div>
        <div class="kpi-box">
            <div class="kpi-label">Monto Promedio</div>
            <div class="kpi-value">${{ number_format($totales['promedio_monto'], 2) }}</div>
        </div>
        <div class="kpi-box">
            <div class="kpi-label">Mes Mayor Ingreso</div>
            <div class="kpi-value" style="font-size:13px;">{{ $totales['mes_mayor'] ?? '—' }}</div>
        </div>
    </div>

    {{-- Tabla --}}
    <p class="section-title">Detalle de Fichas Pagadas</p>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Folio Ficha</th>
                <th>Aspirante</th>
                <th>Folio Aspirante</th>
                <th>Carrera</th>
                <th>Período</th>
                <th>Método</th>
                <th>Fecha Pago</th>
                <th style="text-align:right;">Monto</th>
            </tr>
        </thead>
        <tbody>
            @php $total = 0; $i = 1; @endphp
            @forelse($fichas as $ficha)
            @php $total += $ficha->monto; @endphp
            <tr>
                <td>{{ $i++ }}</td>
                <td class="mono">{{ $ficha->folio_ficha }}</td>
                <td>{{ $ficha->aspirante->nombre_completo }}</td>
                <td class="mono">{{ $ficha->aspirante->folio }}</td>
                <td>{{ $ficha->aspirante->carrera?->clave ?? '—' }}</td>
                <td>{{ $ficha->aspirante->periodo?->nombre ?? '—' }}</td>
                <td>{{ $ficha->metodo_pago ? ucfirst($ficha->metodo_pago) : '—' }}</td>
                <td>{{ $ficha->fecha_pago?->format('d/m/Y') ?? '—' }}</td>
                <td style="text-align:right;" class="amount">${{ number_format($ficha->monto, 2) }}</td>
            </tr>
            @empty
            <tr><td colspan="9" style="text-align:center;padding:16px;color:#94a3b8;">Sin registros</td></tr>
            @endforelse
            @if($fichas->count() > 0)
            <tr class="totals-row">
                <td colspan="8" style="text-align:right;">TOTAL</td>
                <td style="text-align:right;">${{ number_format($total, 2) }}</td>
            </tr>
            @endif
        </tbody>
    </table>

</div>

<div class="footer">
    <span>eduSIGE — Sistema Integral de Gestión Educativa</span>
    <span>Documento generado electrónicamente — {{ now()->format('d/m/Y H:i:s') }}</span>
</div>

</body>
</html>
