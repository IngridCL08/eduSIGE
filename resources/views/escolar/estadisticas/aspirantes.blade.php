@extends('layouts.escolar')
@section('title','Estadísticas de Aspirantes')

@section('breadcrumb')
    <span class="breadcrumb-separator">/</span>
    <span class="text-carbon-700 font-medium">Estadísticas — Aspirantes</span>
@endsection

@section('content')

{{-- KPIs --}}
<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
    @foreach([
        ['Total registrados', $resumen['total_aspirantes'],       'text-carbon-900',  'bg-carbon-100'],
        ['Ficha pagada',      $resumen['con_ficha_pagada'],       'text-green-700',   'bg-green-100'],
        ['Admitidos',         $resumen['admitidos'],              'text-navy-800',    'bg-navy-100'],
        ['No admitidos',      $resumen['no_admitidos'],           'text-red-700',     'bg-red-100'],
    ] as [$label, $count, $textCls, $bgCls])
    <div class="rounded-xl {{ $bgCls }} px-4 py-4">
        <p class="text-3xl font-black {{ $textCls }}">{{ number_format($count) }}</p>
        <p class="text-xs text-carbon-500 mt-1">{{ $label }}</p>
    </div>
    @endforeach
</div>

{{-- Gráficas --}}
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">

    {{-- Por carrera --}}
    <div class="card">
        <div class="card-header"><h3 class="card-title">Aspirantes por Carrera</h3></div>
        @if(count($porCarrera) > 0)
            <div id="chart-carrera"></div>
        @else
            <p class="text-center py-8 text-sm text-carbon-400">Sin datos.</p>
        @endif
    </div>

    {{-- Por estatus --}}
    <div class="card">
        <div class="card-header"><h3 class="card-title">Distribución por Estatus</h3></div>
        @if(count($porEstatus) > 0)
            <div id="chart-estatus"></div>
        @else
            <p class="text-center py-8 text-sm text-carbon-400">Sin datos.</p>
        @endif
    </div>

    {{-- Por sexo --}}
    <div class="card">
        <div class="card-header"><h3 class="card-title">Distribución por Sexo</h3></div>
        @if(array_sum(array_values($porSexo ?? [])) > 0)
            <div id="chart-sexo"></div>
        @else
            <p class="text-center py-8 text-sm text-carbon-400">Sin datos.</p>
        @endif
    </div>

    {{-- Por período --}}
    <div class="card">
        <div class="card-header"><h3 class="card-title">Aspirantes por Período</h3></div>
        @if(count($porPeriodo ?? []) > 0)
            <div id="chart-periodo"></div>
        @else
            <p class="text-center py-8 text-sm text-carbon-400">Sin datos.</p>
        @endif
    </div>

</div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const colors = ['#1E3A5F','#3B82F6','#16a34a','#d97706','#64748b','#7c3aed','#dc2626'];

    // Por carrera
    const carrera = @json($porCarrera);
    if (Object.keys(carrera).length) {
        new ApexCharts(document.getElementById('chart-carrera'), {
            chart: { type: 'bar', height: 240, toolbar: { show: false }, fontFamily: 'Inter, sans-serif' },
            series: [{ name: 'Aspirantes', data: Object.values(carrera) }],
            xaxis: { categories: Object.keys(carrera) },
            colors: ['#1E3A5F'],
            plotOptions: { bar: { borderRadius: 4, columnWidth: '60%' } },
            dataLabels: { enabled: true, style: { fontSize: '11px' } },
            grid: { borderColor: '#e2e8f0', strokeDashArray: 4 },
        }).render();
    }

    // Por estatus
    const estatus = @json($porEstatus);
    if (Object.keys(estatus).length) {
        new ApexCharts(document.getElementById('chart-estatus'), {
            chart: { type: 'donut', height: 240, fontFamily: 'Inter, sans-serif' },
            series: Object.values(estatus),
            labels: Object.keys(estatus).map(k => k.replace(/_/g,' ').replace(/\b\w/g,c=>c.toUpperCase())),
            colors: colors,
            legend: { position: 'bottom', fontSize: '11px' },
            dataLabels: { formatter: (v) => v.toFixed(1) + '%' },
            plotOptions: { pie: { donut: { size: '60%' } } },
        }).render();
    }

    // Por sexo
    const sexo = @json($porSexo ?? []);
    if (Object.values(sexo).reduce((a,b)=>a+b,0) > 0) {
        const sexoLabels = { M: 'Masculino', F: 'Femenino', O: 'Otro' };
        new ApexCharts(document.getElementById('chart-sexo'), {
            chart: { type: 'pie', height: 240, fontFamily: 'Inter, sans-serif' },
            series: Object.values(sexo),
            labels: Object.keys(sexo).map(k => sexoLabels[k] ?? k),
            colors: ['#1E3A5F','#3B82F6','#64748b'],
            legend: { position: 'bottom', fontSize: '11px' },
            dataLabels: { formatter: (v) => v.toFixed(1) + '%' },
        }).render();
    }

    // Por período
    const periodo = @json($porPeriodo ?? []);
    if (Object.keys(periodo).length) {
        new ApexCharts(document.getElementById('chart-periodo'), {
            chart: { type: 'bar', height: 240, toolbar: { show: false }, fontFamily: 'Inter, sans-serif' },
            series: [{ name: 'Aspirantes', data: Object.values(periodo) }],
            xaxis: { categories: Object.keys(periodo) },
            colors: ['#3B82F6'],
            plotOptions: { bar: { borderRadius: 4, columnWidth: '60%' } },
            dataLabels: { enabled: true, style: { fontSize: '11px' } },
            grid: { borderColor: '#e2e8f0', strokeDashArray: 4 },
        }).render();
    }
});
</script>
@endpush
