@extends('layouts.escolar')

@section('title', $materia->clave . ' — ' . $materia->nombre)

@section('breadcrumb')
    <span class="breadcrumb-separator">/</span>
    <a href="{{ route('escolar.materias.index') }}">Materias</a>
    <span class="breadcrumb-separator">/</span>
    <span class="text-carbon-700 font-medium">{{ $materia->clave }}</span>
@endsection

@section('header-actions')
    <a href="{{ route('escolar.materias.edit', $materia) }}" class="btn-secondary btn-sm">Editar</a>
@endsection

@section('content')
<div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

    {{-- Datos de la materia --}}
    <div class="xl:col-span-2 space-y-5">
        <div class="card">
            <div class="card-header">
                <div>
                    <p class="font-mono text-sm font-semibold text-navy-700 mb-0.5">{{ $materia->clave }}</p>
                    <h2 class="text-xl font-bold text-carbon-950">{{ $materia->nombre }}</h2>
                </div>
                @if($materia->activa)
                    <span class="badge-success">Activa</span>
                @else
                    <span class="badge-neutral">Inactiva</span>
                @endif
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                <div class="bg-carbon-50 rounded-lg p-4 text-center">
                    <p class="text-2xl font-bold text-navy-800">{{ $materia->creditos }}</p>
                    <p class="text-xs text-carbon-500 mt-1">Créditos</p>
                </div>
                <div class="bg-carbon-50 rounded-lg p-4 text-center">
                    <p class="text-2xl font-bold text-carbon-950">{{ $materia->horas_teoria }}h</p>
                    <p class="text-xs text-carbon-500 mt-1">Teoría</p>
                </div>
                <div class="bg-carbon-50 rounded-lg p-4 text-center">
                    <p class="text-2xl font-bold text-carbon-950">{{ $materia->horas_practica }}h</p>
                    <p class="text-xs text-carbon-500 mt-1">Práctica</p>
                </div>
                <div class="bg-carbon-50 rounded-lg p-4 text-center">
                    <p class="text-2xl font-bold text-carbon-950">{{ $materia->horas_teoria + $materia->horas_practica }}h</p>
                    <p class="text-xs text-carbon-500 mt-1">Total semanal</p>
                </div>
            </div>

            @if($materia->semestre_sugerido)
            <div class="mt-4 flex items-center gap-2 text-sm text-carbon-600">
                <svg class="w-4 h-4 text-navy-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                Semestre sugerido: <span class="badge-info ml-1">{{ $materia->semestre_sugerido }}°</span>
            </div>
            @endif
        </div>

        {{-- Plan de estudios por carrera --}}
        <div class="card p-0">
            <div class="card-header px-6 pt-5 pb-4">
                <h3 class="card-title">Plan de Estudios — Carreras que la incluyen</h3>
            </div>

            @if($materia->planEstudios->isEmpty())
            <div class="px-6 pb-6 text-sm text-carbon-400 text-center py-8">
                Esta materia no está asignada a ningún plan de estudios.
            </div>
            @else
            <div class="table-wrapper rounded-t-none border-t-0">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Carrera</th>
                            <th class="text-center">Semestre</th>
                            <th class="text-center">Tipo</th>
                            <th class="text-center">Prerrequisitos</th>
                            <th class="text-right">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($materia->planEstudios as $plan)
                        <tr>
                            <td>
                                <p class="font-medium text-carbon-950">{{ $plan->carrera->nombre }}</p>
                                <p class="text-xs text-carbon-400">{{ $plan->carrera->clave }}</p>
                            </td>
                            <td class="text-center"><span class="badge-info">{{ $plan->semestre }}°</span></td>
                            <td class="text-center">
                                @if($plan->obligatoria)
                                    <span class="badge-neutral">Obligatoria</span>
                                @else
                                    <span class="badge-warning">Optativa</span>
                                @endif
                            </td>
                            <td class="text-center text-sm text-carbon-500">
                                {{ $plan->prerequisitos ? count($plan->prerequisitos) . ' requisito(s)' : '—' }}
                            </td>
                            <td class="text-right">
                                <form method="POST"
                                      action="{{ route('escolar.materias.plan.destroy', $plan) }}"
                                      onsubmit="return confirm('¿Quitar esta materia del plan de estudios?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn-danger btn-sm">Quitar</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif
        </div>
    </div>

    {{-- Panel lateral --}}
    <div class="space-y-4">
        {{-- Grupos activos --}}
        <div class="card">
            <h3 class="card-title mb-4">Grupos activos</h3>
            @php $grupos = $materia->grupos->where('activo', true); @endphp
            @if($grupos->isEmpty())
                <p class="text-sm text-carbon-400">Sin grupos en el período actual.</p>
            @else
                <div class="space-y-2">
                    @foreach($grupos as $grupo)
                    <div class="flex items-center justify-between text-sm">
                        <div>
                            <p class="font-medium text-carbon-950">{{ $grupo->nombre }}</p>
                            <p class="text-xs text-carbon-400">{{ $grupo->turno }} · {{ $grupo->cupo_actual }}/{{ $grupo->cupo_maximo }}</p>
                        </div>
                        <div class="w-16 bg-carbon-100 rounded-full h-1.5">
                            <div class="bg-navy-600 h-1.5 rounded-full"
                                 style="width: {{ $grupo->porcentaje_llenado }}%"></div>
                        </div>
                    </div>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- Asignar a carrera --}}
        <div class="card">
            <h3 class="card-title mb-4">Agregar a plan de estudios</h3>
            <form method="POST" action="{{ route('escolar.materias.plan.store', $materia) }}" class="space-y-3">
                @csrf
                <div>
                    <label class="form-label">Carrera</label>
                    <select name="carrera_id" class="form-select" required>
                        <option value="">— Seleccionar —</option>
                        @foreach($carreras as $carrera)
                        <option value="{{ $carrera->id }}">{{ $carrera->clave }} — {{ $carrera->nombre }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="grid grid-cols-2 gap-2">
                    <div>
                        <label class="form-label">Semestre</label>
                        <select name="semestre" class="form-select" required>
                            @for($i = 1; $i <= 9; $i++)
                            <option value="{{ $i }}" {{ $materia->semestre_sugerido == $i ? 'selected' : '' }}>{{ $i }}°</option>
                            @endfor
                        </select>
                    </div>
                    <div class="flex items-end pb-1">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="obligatoria" value="1" class="form-checkbox" checked>
                            <span class="text-sm text-carbon-700">Obligatoria</span>
                        </label>
                    </div>
                </div>
                <button type="submit" class="btn-primary btn-sm w-full">Agregar</button>
            </form>
        </div>
    </div>

</div>
@endsection
