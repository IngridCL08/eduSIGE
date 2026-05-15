@extends('layouts.escolar')
@section('title','Editar Alumno — ' . $alumno->matricula)

@section('breadcrumb')
    <a href="{{ route('escolar.alumnos.index') }}">Alumnos</a>
    <span class="breadcrumb-separator">/</span>
    <a href="{{ route('escolar.alumnos.show', $alumno) }}">{{ $alumno->matricula }}</a>
    <span class="breadcrumb-separator">/</span>
    <span class="text-carbon-700 font-medium">Editar</span>
@endsection

@section('content')
<div class="max-w-2xl">
<form method="POST" action="{{ route('escolar.alumnos.update', $alumno) }}" class="space-y-5">
    @csrf @method('PUT')

    {{-- Info no editable --}}
    <div class="card bg-carbon-50/50">
        <div class="flex items-center gap-4">
            <div class="w-10 h-10 bg-navy-100 rounded-full flex items-center justify-center flex-shrink-0">
                <span class="text-navy-800 font-bold text-sm">
                    {{ strtoupper(substr($alumno->aspirante->nombre, 0, 1) . substr($alumno->aspirante->apellido_paterno, 0, 1)) }}
                </span>
            </div>
            <div>
                <p class="font-semibold text-carbon-900">{{ $alumno->aspirante->nombre_completo }}</p>
                <p class="text-xs text-carbon-400 font-mono">Matrícula: {{ $alumno->matricula }}</p>
            </div>
        </div>
    </div>

    {{-- Datos académicos editables --}}
    <div class="card">
        <div class="card-header"><h3 class="card-title">Datos Académicos</h3></div>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="form-label">Carrera <span class="text-danger">*</span></label>
                <select name="carrera_id" class="form-select @error('carrera_id') border-danger @enderror" required>
                    @foreach($carreras as $c)
                    <option value="{{ $c->id }}" {{ old('carrera_id', $alumno->carrera_id) == $c->id ? 'selected' : '' }}>
                        {{ $c->clave }} — {{ $c->nombre }}
                    </option>
                    @endforeach
                </select>
                @error('carrera_id')<p class="form-error">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="form-label">Período de Ingreso <span class="text-danger">*</span></label>
                <select name="periodo_ingreso_id" class="form-select @error('periodo_ingreso_id') border-danger @enderror" required>
                    @foreach($periodos as $p)
                    <option value="{{ $p->id }}" {{ old('periodo_ingreso_id', $alumno->periodo_ingreso_id) == $p->id ? 'selected' : '' }}>
                        {{ $p->nombre }}
                    </option>
                    @endforeach
                </select>
                @error('periodo_ingreso_id')<p class="form-error">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="form-label">Estatus <span class="text-danger">*</span></label>
                <select name="status" class="form-select @error('status') border-danger @enderror" required>
                    @foreach(['activo'=>'Activo','baja_temporal'=>'Baja Temporal','baja_definitiva'=>'Baja Definitiva','egresado'=>'Egresado','titulado'=>'Titulado'] as $k=>$v)
                    <option value="{{ $k }}" {{ old('status', $alumno->status) === $k ? 'selected' : '' }}>{{ $v }}</option>
                    @endforeach
                </select>
                @error('status')<p class="form-error">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="form-label">Promedio General</label>
                <input type="number" step="0.01" min="0" max="10" name="promedio_general"
                       value="{{ old('promedio_general', $alumno->promedio_general) }}"
                       class="form-input @error('promedio_general') border-danger @enderror">
                <p class="form-help">Se recalcula automáticamente desde el historial</p>
                @error('promedio_general')<p class="form-error">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="form-label">Créditos Acumulados</label>
                <input type="number" min="0" name="creditos_acumulados"
                       value="{{ old('creditos_acumulados', $alumno->creditos_acumulados) }}"
                       class="form-input @error('creditos_acumulados') border-danger @enderror">
                @error('creditos_acumulados')<p class="form-error">{{ $message }}</p>@enderror
            </div>
        </div>
    </div>

    <div class="flex justify-end gap-3">
        <a href="{{ route('escolar.alumnos.show', $alumno) }}" class="btn-secondary">Cancelar</a>
        <button type="submit" class="btn-primary">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
            Guardar Cambios
        </button>
    </div>
</form>
</div>
@endsection
