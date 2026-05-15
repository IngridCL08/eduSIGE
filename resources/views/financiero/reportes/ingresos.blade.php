@extends('layouts.financiero')
@section('title','Reporte de Ingresos')

@section('breadcrumb')
    <span class="breadcrumb-separator">/</span>
    <span class="text-carbon-700 font-medium">Reporte de Ingresos</span>
@endsection

@section('header-actions')
    <a href="{{ route('financiero.reportes.pdf', array_merge(request()->all(), ['tipo'=>'ingresos'])) }}"
       target="_blank" class="btn-secondary btn-sm">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
        </svg>
        Exportar PDF
    </a>
@endsection

@section('content')

{{-- Filtros --}}
<form method="GET" class="card mb-5">
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
        <div>
            <label class="form-label">Período</label>
            <select name="periodo_id" class="form-select">
                <option value="">Todos</option>
                @foreach($periodos as $p)
                <option value="{{ $p->id }}" {{ request('periodo_id') == $p->id ? 'selected' : '' }}>{{ $p->nombre }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="form-label">Método de pago</label>
            <select name="metodo_pago" class="form-select">
                <option value="">Todos</option>
                @foreach(['conekta'=>'Conekta','paypal'=>'PayPal','transferencia'=>'Transferencia','efectivo'=>'Efectivo','otro'=>'Otro'] as $k=>$v)
                <option value="{{ $k }}" {{ request('metodo_pago') === $k ? 'selected' : '' }}>{{ $v }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="form-label">Desde</label>
            <input type="date" name="desde" value="{{ request('desde') }}" class="form-input">
        </div>
        <div>
            <label class="form-label">Hasta</label>
            <input type="date" name="hasta" value="{{ request('hasta') }}" class="form-input">
        </div>
        <div class="flex items-end gap-2">
            <button type="submit" class="btn-primary btn-sm flex-1 justify-center">Filtrar</button>
            <a href="{{ route('financiero.reportes.ingresos') }}" class="btn-secondary btn-sm">Limpiar</a>
        </div>
    </div>
</form>

{{-- KPIs del reporte --}}
<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
    <div class="stat-card">
        <div>
            <p class="stat-card-value">${{ number_format($totales['total_ingresos'], 2) }}</p>
            <p class="stat-card-label">Total Ingresos</p>
        </div>
    </div>
    <div class="stat-card">
        <div>
            <p class="stat-card-value">{{ number_format($totales['total_fichas']) }}</p>
            <p class="stat-card-label">Fichas Pagadas</p>
        </div>
    </div>
    <div class="stat-card">
        <div>
            <p class="stat-card-value">${{ number_format($totales['promedio_monto'], 2) }}</p>
            <p class="stat-card-label">Monto Promedio</p>
        </div>
    </div>
    <div class="stat-card">
        <div>
            <p class="stat-card-value">{{ $totales['mes_mayor'] ?? '—' }}</p>
            <p class="stat-card-label">Mes de Mayor Ingreso</p>
        </div>
    </div>
</div>

{{-- Gráfica ingresos por mes --}}
<div class="card mb-5">
    <div class="card-header">
        <h3 class="card-title">Ingresos por Mes</h3>
    </div>
    <div id="chart-ingresos-reporte"></div>
</div>

{{-- Tabla de fichas pagadas --}}
<div class="card p-0 overflow-hidden">
    <div class="px-4 py-3 border-b border-carbon-100 flex items-center justify-between">
        <h3 class="font-semibold text-carbon-900">Fichas Pagadas</h3>
        <a href="{{ route('financiero.reportes.exportar', array_merge(request()->all(), ['tipo'=>'fichas'])) }}"
           class="btn-secondary btn-sm">Exportar Excel</a>
    </div>
    <div class="table-wrapper">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Folio</th>
                    <th>Aspirante</th>
                    <th>Carrera</th>
                    <th>Período</th>
                    <th>Monto</th>
                    <th>Método</th>
                    <th>Fecha Pago</th>
                </tr>
            </thead>
            <tbody>
                @forelse($fichas as $ficha)
                <tr>
                    <td class="font-mono text-xs">{{ $ficha->folio_ficha }}</td>
                    <td>
                        <div class="font-medium text-sm">{{ $ficha->aspirante->nombre_completo }}</div>
                        <div class="text-xs text-carbon-400 font-mono">{{ $ficha->aspirante->folio }}</div>
                    </td>
                    <td class="text-sm">{{ $ficha->aspirante->carrera?->clave ?? '—' }}</td>
                    <td class="text-sm">{{ $ficha->aspirante->periodo?->nombre ?? '—' }}</td>
                    <td class="font-semibold">{{ $ficha->monto_formateado }}</td>
                    <td class="text-sm">{{ $ficha->metodo_pago ? ucfirst($ficha->metodo_pago) : '—' }}</td>
                    <td class="text-xs text-carbon-500 whitespace-nowrap">{{ $ficha->fecha_pago?->format('d/m/Y H:i') ?? '—' }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center py-10 text-carbon-400">
                        No hay fichas pagadas con los filtros seleccionados.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($fichas->hasPages())
    <div class="px-4 py-3 border-t border-carbon-100">{{ $fichas->links() }}</div>
    @endif
</div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const data = @json($ingresosMes);
    new ApexCharts(document.getElementById('chart-ingresos-reporte'), {
        chart: { type: 'bar', height: 260, toolbar: { show: false }, fontFamily: 'Inter, sans-serif' },
        series: [{ name: 'Ingresos MXN', data: data.totales }],
        xaxis: { categories: data.meses },
        colors: ['#1E3A5F'],
        plotOptions: { bar: { borderRadius: 4, columnWidth: '55%' } },
        dataLabels: { enabled: false },
        grid: { borderColor: '#e2e8f0', strokeDashArray: 4 },
        yaxis: { labels: { formatter: (v) => '$' + v.toLocaleString('es-MX') } },
        tooltip: { y: { formatter: (v) => '$' + v.toLocaleString('es-MX', { minimumFractionDigits: 2 }) } },
    }).render();
});
</script>
@endpush
