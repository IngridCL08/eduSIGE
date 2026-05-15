@extends('layouts.admin')
@section('title','Nuevo Período')

@section('breadcrumb')
    <a href="{{ route('admin.periodos.index') }}">Períodos</a>
    <span class="breadcrumb-separator">/</span>
    <span class="text-carbon-700 font-medium">Nuevo</span>
@endsection

@section('content')
<div class="max-w-lg">
<div class="card">
    <div class="card-header"><h3 class="card-title">Registrar Período Escolar</h3></div>
    <form method="POST" action="{{ route('admin.periodos.store') }}" class="space-y-4">
        @csrf
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="form-label">Nombre <span class="text-danger">*</span></label>
                <input name="nombre" value="{{ old('nombre') }}" class="form-input @error('nombre') border-danger @enderror"
                       placeholder="2025-A" required>
                <p class="form-help">Ej: 2025-A, 2025-B</p>
                @error('nombre')<p class="form-error">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="form-label">Año <span class="text-danger">*</span></label>
                <input name="anio" type="number" value="{{ old('anio', date('Y')) }}"
                       class="form-input" min="2000" max="2100" required>
            </div>
        </div>
        <div>
            <label class="form-label">Ciclo <span class="text-danger">*</span></label>
            <select name="ciclo" class="form-select" required>
                <option value="A" {{ old('ciclo') == 'A' ? 'selected' : '' }}>A — Enero a Junio</option>
                <option value="B" {{ old('ciclo') == 'B' ? 'selected' : '' }}>B — Julio a Diciembre</option>
                <option value="C" {{ old('ciclo') == 'C' ? 'selected' : '' }}>C — Cuatrimestral</option>
            </select>
        </div>
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="form-label">Fecha inicio <span class="text-danger">*</span></label>
                <input name="fecha_inicio" type="date" value="{{ old('fecha_inicio') }}"
                       class="form-input @error('fecha_inicio') border-danger @enderror" required>
            </div>
            <div>
                <label class="form-label">Fecha fin <span class="text-danger">*</span></label>
                <input name="fecha_fin" type="date" value="{{ old('fecha_fin') }}"
                       class="form-input @error('fecha_fin') border-danger @enderror" required>
            </div>
        </div>
        <div class="flex justify-end gap-3 pt-2">
            <a href="{{ route('admin.periodos.index') }}" class="btn-secondary">Cancelar</a>
            <button type="submit" class="btn-primary">Guardar Período</button>
        </div>
    </form>
</div>
</div>
@endsection
