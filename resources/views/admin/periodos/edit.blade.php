@extends('layouts.admin')
@section('title','Editar Período')

@section('breadcrumb')
    <a href="{{ route('admin.periodos.index') }}">Períodos</a>
    <span class="breadcrumb-separator">/</span>
    <span class="text-carbon-700 font-medium">{{ $periodo->nombre }}</span>
@endsection

@section('content')
<div class="max-w-lg">
<div class="card">
    <div class="card-header"><h3 class="card-title">Editar Período: {{ $periodo->nombre }}</h3></div>
    <form method="POST" action="{{ route('admin.periodos.update', $periodo) }}" class="space-y-4">
        @csrf @method('PUT')
        <div>
            <label class="form-label">Nombre <span class="text-danger">*</span></label>
            <input name="nombre" value="{{ old('nombre', $periodo->nombre) }}"
                   class="form-input @error('nombre') border-danger @enderror" required>
        </div>
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="form-label">Fecha inicio <span class="text-danger">*</span></label>
                <input name="fecha_inicio" type="date" value="{{ old('fecha_inicio', $periodo->fecha_inicio->format('Y-m-d')) }}"
                       class="form-input" required>
            </div>
            <div>
                <label class="form-label">Fecha fin <span class="text-danger">*</span></label>
                <input name="fecha_fin" type="date" value="{{ old('fecha_fin', $periodo->fecha_fin->format('Y-m-d')) }}"
                       class="form-input" required>
            </div>
        </div>
        <div class="flex justify-end gap-3 pt-2">
            <a href="{{ route('admin.periodos.index') }}" class="btn-secondary">Cancelar</a>
            <button type="submit" class="btn-primary">Guardar Cambios</button>
        </div>
    </form>
</div>
</div>
@endsection
