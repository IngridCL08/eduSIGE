@extends('layouts.escolar')

@section('title', 'Editar Materia')

@section('breadcrumb')
    <span class="breadcrumb-separator">/</span>
    <a href="{{ route('escolar.materias.index') }}">Materias</a>
    <span class="breadcrumb-separator">/</span>
    <a href="{{ route('escolar.materias.show', $materia) }}">{{ $materia->clave }}</a>
    <span class="breadcrumb-separator">/</span>
    <span class="text-carbon-700 font-medium">Editar</span>
@endsection

@section('header-actions')
    <a href="{{ route('escolar.materias.show', $materia) }}" class="btn-secondary btn-sm">Ver detalle</a>
@endsection

@section('content')
<div class="max-w-2xl space-y-6">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Editar Materia</h3>
            @if($materia->activa)
                <span class="badge-success">Activa</span>
            @else
                <span class="badge-neutral">Inactiva</span>
            @endif
        </div>

        <form method="POST" action="{{ route('escolar.materias.update', $materia) }}" class="space-y-5">
            @csrf @method('PUT')

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="form-label">Clave <span class="text-danger">*</span></label>
                    <input type="text" name="clave" value="{{ old('clave', $materia->clave) }}"
                           class="form-input font-mono uppercase" required maxlength="20">
                    @error('clave')<p class="form-error">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="form-label">Semestre sugerido</label>
                    <select name="semestre_sugerido" class="form-select">
                        <option value="">— Sin asignar —</option>
                        @for($i = 1; $i <= 9; $i++)
                        <option value="{{ $i }}" {{ old('semestre_sugerido', $materia->semestre_sugerido) == $i ? 'selected' : '' }}>
                            Semestre {{ $i }}
                        </option>
                        @endfor
                    </select>
                    @error('semestre_sugerido')<p class="form-error">{{ $message }}</p>@enderror
                </div>
            </div>

            <div>
                <label class="form-label">Nombre completo <span class="text-danger">*</span></label>
                <input type="text" name="nombre" value="{{ old('nombre', $materia->nombre) }}"
                       class="form-input" required maxlength="150">
                @error('nombre')<p class="form-error">{{ $message }}</p>@enderror
            </div>

            <div class="grid grid-cols-3 gap-4">
                <div>
                    <label class="form-label">Créditos <span class="text-danger">*</span></label>
                    <input type="number" name="creditos" value="{{ old('creditos', $materia->creditos) }}"
                           class="form-input" min="1" max="20" required>
                    @error('creditos')<p class="form-error">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="form-label">Horas teoría <span class="text-danger">*</span></label>
                    <input type="number" name="horas_teoria" value="{{ old('horas_teoria', $materia->horas_teoria) }}"
                           class="form-input" min="0" max="20" required>
                    @error('horas_teoria')<p class="form-error">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="form-label">Horas práctica <span class="text-danger">*</span></label>
                    <input type="number" name="horas_practica" value="{{ old('horas_practica', $materia->horas_practica) }}"
                           class="form-input" min="0" max="20" required>
                    @error('horas_practica')<p class="form-error">{{ $message }}</p>@enderror
                </div>
            </div>

            <div class="flex items-center gap-3">
                <label class="flex items-center gap-2 cursor-pointer select-none">
                    <input type="hidden" name="activa" value="0">
                    <input type="checkbox" name="activa" value="1" class="form-checkbox"
                           {{ old('activa', $materia->activa) ? 'checked' : '' }}>
                    <span class="text-sm font-medium text-carbon-700">Materia activa</span>
                </label>
            </div>

            <div class="flex items-center gap-3 pt-2">
                <button type="submit" class="btn-primary">Guardar cambios</button>
                <a href="{{ route('escolar.materias.show', $materia) }}" class="btn-secondary">Cancelar</a>
            </div>
        </form>
    </div>
</div>
@endsection
