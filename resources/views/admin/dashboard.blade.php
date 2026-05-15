@extends('layouts.admin')

@section('title', 'Dashboard Global')

@section('breadcrumb')
    <span class="breadcrumb-separator">·</span>
    <span class="text-carbon-700 font-medium">Dashboard Global</span>
@endsection

@section('content')

    {{-- KPIs globales --}}
    <div class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-6 gap-4 mb-6">
        @php
        $kpis = [
            ['label' => 'Usuarios',        'value' => $stats['total_usuarios'],   'color' => 'bg-navy-100 text-navy-800'],
            ['label' => 'Usuarios Activos','value' => $stats['usuarios_activos'],  'color' => 'bg-green-100 text-green-700'],
            ['label' => 'Aspirantes',      'value' => $stats['total_aspirantes'],  'color' => 'bg-blue-100 text-blue-700'],
            ['label' => 'Alumnos',         'value' => $stats['total_alumnos'],     'color' => 'bg-amber-100 text-amber-700'],
            ['label' => 'Fichas Hoy',      'value' => $stats['fichas_hoy'],        'color' => 'bg-purple-100 text-purple-700'],
            ['label' => 'Ingresos Mes',    'value' => '$' . number_format($stats['ingresos_mes'], 0), 'color' => 'bg-emerald-100 text-emerald-700'],
        ];
        @endphp

        @foreach($kpis as $kpi)
            <div class="card text-center py-4">
                <p class="text-2xl font-bold {{ $kpi['color'] }} rounded-lg py-1 px-2 inline-block mb-1">
                    {{ $kpi['value'] }}
                </p>
                <p class="text-xs text-carbon-500 mt-1">{{ $kpi['label'] }}</p>
            </div>
        @endforeach
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        {{-- Resumen Financiero --}}
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Resumen Financiero</h3>
                <a href="{{ route('financiero.dashboard') }}" class="text-xs text-navy-500 hover:text-navy-700">
                    Ir al módulo →
                </a>
            </div>
            <div class="space-y-3">
                @php
                $fiFilas = [
                    ['Fichas pagadas',   $resumenFinanciero['pagadas'],    'badge-success'],
                    ['Fichas pendientes',$resumenFinanciero['pendientes'],  'badge-warning'],
                    ['Fichas vencidas',  $resumenFinanciero['vencidas'],    'badge-danger'],
                    ['Total ingresos',   '$' . number_format($resumenFinanciero['ingresos_total'], 2), 'badge-navy'],
                ];
                @endphp
                @foreach($fiFilas as [$label, $valor, $badge])
                    <div class="flex items-center justify-between py-2 border-b border-carbon-100 last:border-0">
                        <span class="text-sm text-carbon-600">{{ $label }}</span>
                        <span class="{{ $badge }}">{{ $valor }}</span>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Resumen Escolar --}}
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Resumen Escolar</h3>
                <a href="{{ route('escolar.dashboard') }}" class="text-xs text-navy-500 hover:text-navy-700">
                    Ir al módulo →
                </a>
            </div>
            <div class="space-y-3">
                @php
                $esFilas = [
                    ['Total aspirantes',  $resumenEscolar['total_aspirantes'],  'badge-navy'],
                    ['Admitidos',         $resumenEscolar['admitidos'],          'badge-success'],
                    ['En proceso',        $resumenEscolar['en_proceso'],         'badge-warning'],
                    ['Alumnos activos',   $resumenEscolar['alumnos_activos'],    'badge-info'],
                    ['Egresados',         $resumenEscolar['egresados'],          'badge-neutral'],
                    ['Titulados',         $resumenEscolar['titulados'],          'badge-navy'],
                ];
                @endphp
                @foreach($esFilas as [$label, $valor, $badge])
                    <div class="flex items-center justify-between py-2 border-b border-carbon-100 last:border-0">
                        <span class="text-sm text-carbon-600">{{ $label }}</span>
                        <span class="{{ $badge }}">{{ $valor }}</span>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Última actividad --}}
        <div class="card lg:col-span-2">
            <div class="card-header">
                <h3 class="card-title">Actividad Reciente del Sistema</h3>
                <a href="{{ route('admin.bitacora') }}" class="text-xs text-navy-500 hover:text-navy-700">
                    Ver bitácora completa →
                </a>
            </div>
            <div class="table-wrapper">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Fecha</th>
                            <th>Usuario</th>
                            <th>Acción</th>
                            <th>IP</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($ultimaActividad as $reg)
                            <tr>
                                <td class="text-xs text-carbon-500">{{ $reg->created_at->format('d/m H:i') }}</td>
                                <td>{{ $reg->user?->name ?? 'Sistema' }}</td>
                                <td><code class="text-xs bg-carbon-100 px-1.5 py-0.5 rounded">{{ $reg->accion }}</code></td>
                                <td class="text-xs text-carbon-400">{{ $reg->ip ?? '—' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-carbon-400 py-4">Sin actividad registrada.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

@endsection
