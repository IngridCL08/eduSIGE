@extends('layouts.escolar')

@section('title', 'Períodos Escolares')

@section('breadcrumb')
    <span class="breadcrumb-separator">/</span>
    <span class="text-carbon-700 font-medium">Períodos Escolares</span>
@endsection

@section('header-actions')
    <a href="{{ route('escolar.periodos.create') }}" class="btn-primary btn-sm">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        Nuevo Período
    </a>
@endsection

@section('content')

<div class="grid grid-cols-1 gap-6">

    {{-- Período activo --}}
    @php $activo = $periodos->firstWhere('activo', true); @endphp
    @if($activo)
    <div class="card border-l-4 border-green-500">
        <div class="flex items-start justify-between">
            <div>
                <p class="text-xs font-semibold text-green-600 uppercase tracking-wider mb-1">Período actual en curso</p>
                <h2 class="text-xl font-bold text-carbon-950">{{ $activo->nombre }}</h2>
                <p class="text-sm text-carbon-500 mt-1">
                    {{ $activo->fecha_inicio->format('d/m/Y') }} — {{ $activo->fecha_fin->format('d/m/Y') }}
                    &nbsp;·&nbsp; Semana {{ $activo->semana_actual ?? '—' }} de {{ $activo->num_semanas }}
                </p>
            </div>
            <div class="flex items-center gap-3">
                {{-- Toggle calificaciones --}}
                <form method="POST" action="{{ route('escolar.periodos.calificaciones', $activo) }}">
                    @csrf @method('PATCH')
                    <button type="submit"
                            class="{{ $activo->abierto_calificaciones ? 'btn-danger' : 'btn-success' }} btn-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="{{ $activo->abierto_calificaciones
                                       ? 'M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z'
                                       : 'M8 11V7a4 4 0 118 0m-4 8v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2z' }}"/>
                        </svg>
                        {{ $activo->abierto_calificaciones ? 'Cerrar calificaciones' : 'Abrir calificaciones' }}
                    </button>
                </form>
                {{-- Avanzar semana --}}
                <form method="POST" action="{{ route('escolar.periodos.semana', $activo) }}">
                    @csrf @method('PATCH')
                    <button type="submit" class="btn-secondary btn-sm">
                        Avanzar semana →
                    </button>
                </form>
            </div>
        </div>

        {{-- Barra de progreso --}}
        <div class="mt-4">
            <div class="flex justify-between text-xs text-carbon-500 mb-1">
                <span>Avance del semestre</span>
                <span>{{ $activo->porcentaje_avance }}%</span>
            </div>
            <div class="w-full bg-carbon-100 rounded-full h-2">
                <div class="bg-green-500 h-2 rounded-full transition-all" style="width: {{ $activo->porcentaje_avance }}%"></div>
            </div>
        </div>

        @if($activo->abierto_calificaciones)
        <div class="mt-3 flex items-center gap-2 text-green-700 text-xs font-medium">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            Sistema de calificaciones ABIERTO desde {{ $activo->fecha_apertura_calificaciones?->format('d/m/Y') }}
        </div>
        @else
        <div class="mt-3 flex items-center gap-2 text-carbon-500 text-xs">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
            </svg>
            Sistema de calificaciones CERRADO
            @if($activo->fecha_cierre_calificaciones)
                — cerrado el {{ $activo->fecha_cierre_calificaciones->format('d/m/Y') }}
            @endif
        </div>
        @endif
    </div>
    @endif

    {{-- Tabla de períodos --}}
    <div class="card p-0">
        <div class="card-header px-6 pt-5 pb-4">
            <h3 class="card-title">Todos los Períodos</h3>
        </div>
        <div class="table-wrapper rounded-t-none border-t-0">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Tipo</th>
                        <th>Fechas</th>
                        <th>Semanas</th>
                        <th>Estado</th>
                        <th>Calificaciones</th>
                        <th class="text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($periodos as $periodo)
                    <tr>
                        <td>
                            <p class="font-medium text-carbon-950">{{ $periodo->nombre }}</p>
                            <p class="text-xs text-carbon-400">{{ $periodo->anio }} — Ciclo {{ $periodo->ciclo }}</p>
                        </td>
                        <td><span class="badge-info badge">{{ $periodo->tipo_nombre }}</span></td>
                        <td class="text-xs">
                            {{ $periodo->fecha_inicio->format('d/m/Y') }}<br>
                            {{ $periodo->fecha_fin->format('d/m/Y') }}
                        </td>
                        <td>
                            <span class="text-carbon-950 font-medium">{{ $periodo->semana_actual ?? '—' }}</span>
                            <span class="text-carbon-400">/ {{ $periodo->num_semanas }}</span>
                        </td>
                        <td><span class="{{ $periodo->estado_color }}">{{ ucfirst($periodo->estado ?? 'planeacion') }}</span></td>
                        <td>
                            @if($periodo->abierto_calificaciones)
                                <span class="badge-success">Abierto</span>
                            @else
                                <span class="badge-neutral">Cerrado</span>
                            @endif
                        </td>
                        <td class="text-right">
                            <div class="flex items-center justify-end gap-1">
                                <a href="{{ route('escolar.periodos.show', $periodo) }}" class="btn-secondary btn-sm">Ver</a>
                                <a href="{{ route('escolar.periodos.edit', $periodo) }}" class="btn-secondary btn-sm">Editar</a>
                                @if($periodo->estado !== 'cerrado' && !$periodo->activo)
                                <form method="POST" action="{{ route('escolar.periodos.activar', $periodo) }}">
                                    @csrf @method('PATCH')
                                    <button type="submit" class="btn-success btn-sm"
                                            onclick="return confirm('¿Activar este período como el actual?')">
                                        Activar
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="text-center py-8 text-carbon-400">No hay períodos registrados.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4">{{ $periodos->links() }}</div>
    </div>
</div>

@endsection
