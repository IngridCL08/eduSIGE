@extends('layouts.escolar')

@section('title', 'Dashboard Escolar')

@section('breadcrumb')
    <span class="breadcrumb-separator">·</span>
    <span class="text-carbon-700 font-medium">Dashboard Escolar</span>
@endsection

@section('content')

    {{-- Filtro período --}}
    <form method="GET" class="flex items-center gap-3 mb-6">
        <select name="periodo_id" class="form-select w-52" onchange="this.form.submit()">
            <option value="">— Todos los períodos —</option>
            @foreach($periodos as $p)
                <option value="{{ $p->id }}" {{ $periodoId == $p->id ? 'selected' : '' }}>{{ $p->nombre }}</option>
            @endforeach
        </select>
    </form>

    {{-- KPI Cards --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">

        <div class="stat-card">
            <div class="stat-card-icon bg-navy-100">
                <svg class="w-6 h-6 text-navy-800" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
            </div>
            <div>
                <p class="stat-card-value">{{ number_format($resumen['total_aspirantes']) }}</p>
                <p class="stat-card-label">Aspirantes</p>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-card-icon bg-green-100">
                <svg class="w-6 h-6 text-green-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                </svg>
            </div>
            <div>
                <p class="stat-card-value text-green-700">{{ number_format($resumen['admitidos']) }}</p>
                <p class="stat-card-label">Admitidos</p>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-card-icon bg-blue-100">
                <svg class="w-6 h-6 text-blue-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/>
                </svg>
            </div>
            <div>
                <p class="stat-card-value text-blue-700">{{ number_format($resumen['total_alumnos']) }}</p>
                <p class="stat-card-label">Alumnos Total</p>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-card-icon bg-emerald-100">
                <svg class="w-6 h-6 text-emerald-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
            </div>
            <div>
                <p class="stat-card-value text-emerald-700">{{ number_format($resumen['alumnos_activos']) }}</p>
                <p class="stat-card-label">Alumnos Activos</p>
            </div>
        </div>
    </div>

    {{-- Gráficas --}}
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

        {{-- Aspirantes por carrera --}}
        <div class="card xl:col-span-2">
            <div class="card-header">
                <h3 class="card-title">Aspirantes por Carrera</h3>
                <a href="{{ route('escolar.estadisticas.aspirantes', ['periodo_id' => $periodoId]) }}"
                   class="text-xs text-navy-500 hover:text-navy-700">Ver más →</a>
            </div>
            <div id="chart-carrera"></div>
        </div>

        {{-- Por sexo --}}
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Distribución por Género</h3>
            </div>
            <div id="chart-sexo"></div>
        </div>

        {{-- Accesos rápidos --}}
        <div class="card xl:col-span-3">
            <div class="card-header">
                <h3 class="card-title">Acciones Rápidas</h3>
            </div>
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                <a href="{{ route('escolar.aspirantes.create') }}"
                   class="flex flex-col items-center gap-2 p-4 rounded-xl border-2 border-navy-200 bg-navy-50
                          hover:border-navy-400 transition-all text-center">
                    <div class="w-10 h-10 bg-navy-200 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-navy-800" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                    </div>
                    <span class="text-xs font-medium text-navy-800">Nuevo Aspirante</span>
                </a>
                <a href="{{ route('escolar.aspirantes.exportar', ['periodo_id' => $periodoId]) }}"
                   class="flex flex-col items-center gap-2 p-4 rounded-xl border-2 border-green-200 bg-green-50
                          hover:border-green-400 transition-all text-center">
                    <div class="w-10 h-10 bg-green-200 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-green-800" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <span class="text-xs font-medium text-green-800">Exportar Aspirantes</span>
                </a>
                <a href="{{ route('escolar.alumnos.index') }}"
                   class="flex flex-col items-center gap-2 p-4 rounded-xl border-2 border-amber-200 bg-amber-50
                          hover:border-amber-400 transition-all text-center">
                    <div class="w-10 h-10 bg-amber-200 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-amber-800" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"/>
                        </svg>
                    </div>
                    <span class="text-xs font-medium text-amber-800">Ver Alumnos</span>
                </a>
                <a href="{{ route('escolar.estadisticas.aspirantes') }}"
                   class="flex flex-col items-center gap-2 p-4 rounded-xl border-2 border-carbon-200 bg-carbon-50
                          hover:border-carbon-400 transition-all text-center">
                    <div class="w-10 h-10 bg-carbon-200 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-carbon-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                    </div>
                    <span class="text-xs font-medium text-carbon-700">Estadísticas</span>
                </a>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const carreraData = @json($porCarrera);
    const sexoData    = @json($porSexo);

    new ApexCharts(document.getElementById('chart-carrera'), {
        chart: { type: 'bar', height: 260, toolbar: { show: false }, fontFamily: 'Inter, sans-serif' },
        series: [{ name: 'Aspirantes', data: carreraData.totales }],
        xaxis: { categories: carreraData.carreras },
        colors: ['#1E3A5F'],
        plotOptions: { bar: { borderRadius: 5, columnWidth: '55%' } },
        dataLabels: { enabled: false },
        grid: { borderColor: '#e2e8f0', strokeDashArray: 4 },
    }).render();

    new ApexCharts(document.getElementById('chart-sexo'), {
        chart: { type: 'donut', height: 260, fontFamily: 'Inter, sans-serif' },
        series: [sexoData.M, sexoData.F, sexoData.O],
        labels: ['Masculino', 'Femenino', 'Otro'],
        colors: ['#1E3A5F', '#3B82F6', '#64748b'],
        legend: { position: 'bottom', fontFamily: 'Inter, sans-serif', fontSize: '12px' },
        dataLabels: { formatter: (v) => v.toFixed(1) + '%' },
        plotOptions: { pie: { donut: { size: '60%' } } },
    }).render();
});
</script>
@endpush
