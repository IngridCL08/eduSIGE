@extends('layouts.escolar')

@section('title', 'Editar Período')

@section('breadcrumb')
    <span class="breadcrumb-separator">/</span>
    <a href="{{ route('escolar.periodos.index') }}">Períodos</a>
    <span class="breadcrumb-separator">/</span>
    <span class="text-carbon-700 font-medium">{{ $periodo->nombre }}</span>
@endsection

@section('content')
<div class="max-w-2xl space-y-6">

    {{-- Editar datos --}}
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Editar Período</h3>
            <span class="{{ $periodo->estado_color }}">{{ ucfirst($periodo->estado ?? 'planeacion') }}</span>
        </div>

        <form method="POST" action="{{ route('escolar.periodos.update', $periodo) }}" class="space-y-5">
            @csrf @method('PUT')

            <div class="grid grid-cols-2 gap-4">
                <div class="col-span-2">
                    <label class="form-label">Nombre del período</label>
                    <input type="text" name="nombre" value="{{ old('nombre', $periodo->nombre) }}"
                           class="form-input" required>
                    @error('nombre')<p class="form-error">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="form-label">Fecha de inicio</label>
                    <input type="date" name="fecha_inicio" value="{{ old('fecha_inicio', $periodo->fecha_inicio->format('Y-m-d')) }}"
                           class="form-input" required>
                </div>
                <div>
                    <label class="form-label">Fecha de fin</label>
                    <input type="date" name="fecha_fin" value="{{ old('fecha_fin', $periodo->fecha_fin->format('Y-m-d')) }}"
                           class="form-input" required>
                </div>
                <div>
                    <label class="form-label">Total de semanas</label>
                    <input type="number" name="num_semanas" value="{{ old('num_semanas', $periodo->num_semanas) }}"
                           class="form-input" min="1" max="26" required>
                </div>
                <div>
                    <label class="form-label">Semana actual</label>
                    <input type="number" name="semana_actual" value="{{ old('semana_actual', $periodo->semana_actual) }}"
                           class="form-input" min="1" max="{{ $periodo->num_semanas }}">
                    <p class="form-help">Puede ajustar manualmente la semana.</p>
                </div>
            </div>

            <div class="flex gap-3">
                <button type="submit" class="btn-primary">Guardar cambios</button>
                <a href="{{ route('escolar.periodos.index') }}" class="btn-secondary">Cancelar</a>
            </div>
        </form>
    </div>

    {{-- Cerrar período --}}
    @if($periodo->estado !== 'cerrado')
    <div class="card border border-red-200 bg-red-50">
        <h3 class="text-base font-semibold text-red-800 mb-3">Cerrar período definitivamente</h3>
        <p class="text-sm text-red-600 mb-4">
            Una vez cerrado, el período no podrá reactivarse. Asegúrese de que todas las calificaciones estén capturadas.
        </p>
        <form method="POST" action="{{ route('escolar.periodos.cerrar', $periodo) }}"
              onsubmit="return confirm('¿Confirmar cierre definitivo del período?')">
            @csrf @method('PATCH')
            <div class="mb-3">
                <label class="form-label text-red-700">Motivo del cierre</label>
                <input type="text" name="motivo_cierre" class="form-input"
                       placeholder="Ej. Semestre concluido, calificaciones capturadas" required>
                @error('motivo_cierre')<p class="form-error">{{ $message }}</p>@enderror
            </div>
            <button type="submit" class="btn-danger btn-sm">Cerrar período</button>
        </form>
    </div>
    @endif

</div>
@endsection
