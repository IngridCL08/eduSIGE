@extends('layouts.escolar')

@section('title', $periodo->nombre)

@section('breadcrumb')
    <span class="breadcrumb-separator">/</span>
    <a href="{{ route('escolar.periodos.index') }}">Períodos</a>
    <span class="breadcrumb-separator">/</span>
    <span class="text-carbon-700 font-medium">{{ $periodo->nombre }}</span>
@endsection

@section('header-actions')
    <a href="{{ route('escolar.periodos.edit', $periodo) }}" class="btn-secondary btn-sm">Editar</a>
@endsection

@section('content')
<div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

    {{-- Info principal --}}
    <div class="xl:col-span-2 space-y-5">
        <div class="card">
            <div class="card-header">
                <div>
                    <h2 class="text-xl font-bold text-carbon-950">{{ $periodo->nombre }}</h2>
                    <p class="text-sm text-carbon-500">{{ $periodo->tipo_nombre }} · {{ $periodo->anio }}</p>
                </div>
                <span class="{{ $periodo->estado_color }}">{{ ucfirst($periodo->estado ?? 'planeacion') }}</span>
            </div>

            <div class="grid grid-cols-2 gap-4 text-sm">
                <div>
                    <p class="text-carbon-500 text-xs uppercase tracking-wider mb-1">Inicio</p>
                    <p class="font-medium">{{ $periodo->fecha_inicio->format('d \d\e F \d\e Y') }}</p>
                </div>
                <div>
                    <p class="text-carbon-500 text-xs uppercase tracking-wider mb-1">Fin</p>
                    <p class="font-medium">{{ $periodo->fecha_fin->format('d \d\e F \d\e Y') }}</p>
                </div>
                <div>
                    <p class="text-carbon-500 text-xs uppercase tracking-wider mb-1">Semana actual</p>
                    <p class="font-medium">{{ $periodo->semana_actual ?? '—' }} / {{ $periodo->num_semanas }}</p>
                </div>
                <div>
                    <p class="text-carbon-500 text-xs uppercase tracking-wider mb-1">Avance</p>
                    <div class="flex items-center gap-2">
                        <div class="flex-1 bg-carbon-100 rounded-full h-2">
                            <div class="bg-navy-600 h-2 rounded-full" style="width: {{ $periodo->porcentaje_avance }}%"></div>
                        </div>
                        <span class="font-medium">{{ $periodo->porcentaje_avance }}%</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Estado calificaciones --}}
        <div class="card">
            <h3 class="card-title mb-4">Sistema de Calificaciones</h3>
            <div class="flex items-center justify-between">
                <div>
                    @if($periodo->abierto_calificaciones)
                        <span class="badge-success text-sm px-3 py-1">Abierto</span>
                        <p class="text-xs text-carbon-500 mt-1">
                            Abierto desde {{ $periodo->fecha_apertura_calificaciones?->format('d/m/Y') }}
                        </p>
                    @else
                        <span class="badge-neutral text-sm px-3 py-1">Cerrado</span>
                        @if($periodo->fecha_cierre_calificaciones)
                        <p class="text-xs text-carbon-500 mt-1">
                            Cerrado el {{ $periodo->fecha_cierre_calificaciones->format('d/m/Y') }}
                        </p>
                        @endif
                    @endif
                </div>
                @if($periodo->estado === 'en_curso')
                <form method="POST" action="{{ route('escolar.periodos.calificaciones', $periodo) }}">
                    @csrf @method('PATCH')
                    <button class="{{ $periodo->abierto_calificaciones ? 'btn-danger' : 'btn-success' }} btn-sm">
                        {{ $periodo->abierto_calificaciones ? 'Cerrar sistema' : 'Abrir sistema' }}
                    </button>
                </form>
                @endif
            </div>
        </div>
    </div>

    {{-- Estadísticas rápidas --}}
    <div class="space-y-4">
        <div class="stat-card">
            <div class="stat-card-icon bg-navy-100">
                <svg class="w-6 h-6 text-navy-800" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
            </div>
            <div>
                <p class="stat-card-value">{{ $periodo->aspirantes_count }}</p>
                <p class="stat-card-label">Aspirantes</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-card-icon bg-green-100">
                <svg class="w-6 h-6 text-green-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 14l9-5-9-5-9 5 9 5zm0 7l9-5-9-5-9 5 9 5z"/>
                </svg>
            </div>
            <div>
                <p class="stat-card-value">{{ $periodo->alumnos_count }}</p>
                <p class="stat-card-label">Alumnos inscritos</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-card-icon bg-amber-100">
                <svg class="w-6 h-6 text-amber-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                </svg>
            </div>
            <div>
                <p class="stat-card-value">{{ $periodo->reinscripciones_count }}</p>
                <p class="stat-card-label">Reinscripciones</p>
            </div>
        </div>

        <a href="{{ route('escolar.calendario.index', ['periodo_id' => $periodo->id]) }}"
           class="btn-outline w-full justify-center">
            Ver calendario de reinscripción
        </a>
    </div>
</div>
@endsection
