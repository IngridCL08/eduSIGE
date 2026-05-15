@extends('layouts.escolar')
@section('title', 'Editar Examen')

@section('breadcrumb')
    <span class="breadcrumb-separator">/</span>
    <a href="{{ route('escolar.examenes.index') }}" class="text-carbon-500 hover:text-carbon-700">Exámenes</a>
    <span class="breadcrumb-separator">/</span>
    <span class="text-carbon-700 font-medium">Editar</span>
@endsection

@section('content')

<div class="card max-w-lg">
    <h2 class="text-base font-semibold text-carbon-900 mb-1">Editar examen de admisión</h2>
    <p class="text-sm text-carbon-500 mb-6">
        Aspirante: <span class="font-semibold text-carbon-800">{{ $examen->aspirante?->nombre_completo }}</span>
        — <span class="font-mono text-xs">{{ $examen->aspirante?->folio }}</span>
    </p>

    <form method="POST" action="{{ route('escolar.examenes.update', $examen) }}" class="space-y-5">
        @csrf @method('PUT')

        <div>
            <label class="form-label">Fecha del examen <span class="text-red-500">*</span></label>
            <input type="date" name="fecha_examen"
                   value="{{ old('fecha_examen', $examen->fecha_examen->format('Y-m-d')) }}"
                   class="form-input" required>
        </div>

        <div>
            <label class="form-label">Calificación (0-100)</label>
            <input type="number" name="calificacion"
                   value="{{ old('calificacion', $examen->calificacion) }}"
                   min="0" max="100" step="0.01" class="form-input">
        </div>

        <div>
            <label class="form-label">Resultado</label>
            <select name="resultado" class="form-select">
                <option value="">Sin resultado aún</option>
                <option value="aprobado"     {{ old('resultado', $examen->resultado) === 'aprobado'     ? 'selected' : '' }}>Aprobado</option>
                <option value="reprobado"    {{ old('resultado', $examen->resultado) === 'reprobado'    ? 'selected' : '' }}>Reprobado</option>
                <option value="lista_espera" {{ old('resultado', $examen->resultado) === 'lista_espera' ? 'selected' : '' }}>Lista de espera</option>
            </select>
        </div>

        <div>
            <label class="form-label">Observaciones</label>
            <textarea name="observaciones" rows="3" class="form-input">{{ old('observaciones', $examen->observaciones) }}</textarea>
        </div>

        <div class="flex gap-3 pt-2">
            <button type="submit" class="btn-primary">Guardar cambios</button>
            <a href="{{ route('escolar.examenes.index') }}" class="btn-secondary">Cancelar</a>
        </div>
    </form>
</div>

@endsection
