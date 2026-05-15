@extends('layouts.financiero')

@section('title', 'Dashboard Financiero')

@section('breadcrumb')
    <span class="breadcrumb-separator">·</span>
    <span class="text-carbon-700 font-medium">Dashboard Financiero</span>
@endsection

@section('content')

    {{-- Filtro de período --}}
    <form method="GET" action="{{ route('financiero.dashboard') }}" class="flex items-center gap-3 mb-6">
        <select name="periodo_id" class="form-select w-52" onchange="this.form.submit()">
            <option value="">— Todos los períodos —</option>
            @foreach($periodos as $p)
                <option value="{{ $p->id }}" {{ $periodoId == $p->id ? 'selected' : '' }}>
                    {{ $p->nombre }}
                </option>
            @endforeach
        </select>
        <select name="anio" class="form-select w-32" onchange="this.form.submit()">
            @foreach(range(now()->year, now()->year - 4) as $y)
                <option value="{{ $y }}" {{ $anio == $y ? 'selected' : '' }}>{{ $y }}</option>
            @endforeach
        </select>
    </form>

    {{-- ─── KPI Cards ─────────────────────────────────────────── --}}
    <div class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-5 gap-4 mb-6">

        <div class="stat-card">
            <div class="stat-card-icon bg-navy-100">
                <svg class="w-6 h-6 text-navy-800" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
            </div>
            <div>
                <p class="stat-card-value">{{ number_format($resumen['total_fichas']) }}</p>
                <p class="stat-card-label">Total Fichas</p>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-card-icon bg-green-100">
                <svg class="w-6 h-6 text-green-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div>
                <p class="stat-card-value text-green-700">{{ number_format($resumen['pagadas']) }}</p>
                <p class="stat-card-label">Pagadas</p>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-card-icon bg-amber-100">
                <svg class="w-6 h-6 text-amber-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div>
                <p class="stat-card-value text-amber-700">{{ number_format($resumen['pendientes']) }}</p>
                <p class="stat-card-label">Pendientes</p>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-card-icon bg-red-100">
                <svg class="w-6 h-6 text-red-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div>
                <p class="stat-card-value text-red-700">{{ number_format($resumen['vencidas']) }}</p>
                <p class="stat-card-label">Vencidas</p>
            </div>
        </div>

        <div class="stat-card col-span-2 md:col-span-1">
            <div class="stat-card-icon bg-navy-100">
                <svg class="w-6 h-6 text-navy-800" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div>
                <p class="stat-card-value text-navy-800">${{ number_format($resumen['ingresos_total'], 2) }}</p>
                <p class="stat-card-label">Ingresos Totales</p>
            </div>
        </div>
    </div>

    {{-- ─── Gráficas ────────────────────────────────────────────── --}}
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

        {{-- Ingresos por mes --}}
        <div class="card xl:col-span-2">
            <div class="card-header">
                <h3 class="card-title">Ingresos por Mes — {{ $anio }}</h3>
            </div>
            <div id="chart-ingresos"></div>
        </div>

        {{-- Método de pago --}}
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Método de Pago</h3>
            </div>
            @if(array_sum($metodoPago) > 0)
                <div id="chart-metodo"></div>
            @else
                <div class="flex flex-col items-center justify-center py-10 text-carbon-400">
                    <svg class="w-10 h-10 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                    <p class="text-sm">Sin pagos registrados</p>
                </div>
            @endif
        </div>

        {{-- Accesos rápidos --}}
        <div class="card xl:col-span-3">
            <div class="card-header">
                <h3 class="card-title">Acciones Rápidas</h3>
            </div>
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                <a href="{{ route('financiero.fichas.index', ['status' => 'pendiente']) }}"
                   class="flex flex-col items-center gap-2 p-4 rounded-xl border-2 border-amber-200 bg-amber-50
                          hover:border-amber-400 hover:bg-amber-100 transition-all text-center group">
                    <div class="w-10 h-10 bg-amber-200 rounded-lg flex items-center justify-center group-hover:bg-amber-300 transition-colors">
                        <svg class="w-5 h-5 text-amber-800" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <span class="text-xs font-medium text-amber-800">Ver Pendientes</span>
                </a>
                <a href="{{ route('financiero.fichas.index', ['status' => 'pagado']) }}"
                   class="flex flex-col items-center gap-2 p-4 rounded-xl border-2 border-green-200 bg-green-50
                          hover:border-green-400 transition-all text-center group">
                    <div class="w-10 h-10 bg-green-200 rounded-lg flex items-center justify-center group-hover:bg-green-300 transition-colors">
                        <svg class="w-5 h-5 text-green-800" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <span class="text-xs font-medium text-green-800">Ver Pagadas</span>
                </a>
                <a href="{{ route('financiero.reportes.ingresos') }}"
                   class="flex flex-col items-center gap-2 p-4 rounded-xl border-2 border-navy-200 bg-navy-50
                          hover:border-navy-400 transition-all text-center group">
                    <div class="w-10 h-10 bg-navy-200 rounded-lg flex items-center justify-center group-hover:bg-navy-300 transition-colors">
                        <svg class="w-5 h-5 text-navy-800" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <span class="text-xs font-medium text-navy-800">Reporte Ingresos</span>
                </a>
                <a href="{{ route('financiero.reportes.exportar', ['tipo' => 'fichas']) }}"
                   class="flex flex-col items-center gap-2 p-4 rounded-xl border-2 border-carbon-200 bg-carbon-50
                          hover:border-carbon-400 transition-all text-center group">
                    <div class="w-10 h-10 bg-carbon-200 rounded-lg flex items-center justify-center group-hover:bg-carbon-300 transition-colors">
                        <svg class="w-5 h-5 text-carbon-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <span class="text-xs font-medium text-carbon-700">Exportar Excel</span>
                </a>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    // Gráfica de ingresos por mes
    const ingresosData = @json($ingresosMes);
    new ApexCharts(document.getElementById('chart-ingresos'), {
        chart: { type: 'area', height: 250, toolbar: { show: false }, fontFamily: 'Inter, sans-serif' },
        series: [{ name: 'Ingresos MXN', data: ingresosData.totales }],
        xaxis: { categories: ingresosData.meses },
        colors: ['#1E3A5F'],
        stroke: { curve: 'smooth', width: 2 },
        fill: { type: 'gradient', gradient: { shadeIntensity: 1, opacityFrom: 0.3, opacityTo: 0.05 } },
        dataLabels: { enabled: false },
        grid: { borderColor: '#e2e8f0', strokeDashArray: 4 },
        yaxis: { labels: { formatter: (v) => '$' + v.toLocaleString('es-MX') } },
        tooltip: { y: { formatter: (v) => '$' + v.toLocaleString('es-MX', { minimumFractionDigits: 2 }) } },
    }).render();

    // Gráfica de método de pago (solo si hay datos)
    const metodoData = @json($metodoPago);
    const metodoLabels = Object.keys(metodoData).map(k => k.charAt(0).toUpperCase() + k.slice(1));
    const metodoValues = Object.values(metodoData);

    if (metodoValues.reduce((a, b) => a + b, 0) > 0) {
        new ApexCharts(document.getElementById('chart-metodo'), {
            chart: { type: 'donut', height: 250, fontFamily: 'Inter, sans-serif' },
            series: metodoValues,
            labels: metodoLabels,
            colors: ['#1E3A5F', '#3B82F6', '#16a34a', '#d97706', '#64748b'],
            legend: { position: 'bottom', fontFamily: 'Inter, sans-serif', fontSize: '12px' },
            dataLabels: { formatter: (v) => v.toFixed(1) + '%' },
            plotOptions: { pie: { donut: { size: '60%' } } },
        }).render();
    }
});
</script>
@endpush
