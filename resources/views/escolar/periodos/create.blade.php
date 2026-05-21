@extends('layouts.escolar')

@section('title', 'Nuevo Período')

@section('breadcrumb')
    <span class="breadcrumb-separator">/</span>
    <a href="{{ route('escolar.periodos.index') }}">Períodos</a>
    <span class="breadcrumb-separator">/</span>
    <span class="text-carbon-700 font-medium">Nuevo</span>
@endsection

@section('content')
<div class="max-w-2xl">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Crear Período Escolar</h3>
        </div>

        <form method="POST" action="{{ route('escolar.periodos.store') }}" class="space-y-5">
            @csrf

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="form-label">Nombre del período <span class="text-danger">*</span></label>
                    <input type="text" name="nombre" value="{{ old('nombre') }}"
                           class="form-input" placeholder="Ej. 2026-A Enero-Junio" required>
                    @error('nombre')<p class="form-error">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="form-label">Año <span class="text-danger">*</span></label>
                    <input type="number" name="anio" value="{{ old('anio', date('Y')) }}"
                           class="form-input" min="2020" max="2040" required>
                    @error('anio')<p class="form-error">{{ $message }}</p>@enderror
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="form-label">Tipo de semestre <span class="text-danger">*</span></label>
                    <select name="tipo" class="form-select" required>
                        <option value="ene_jun" {{ old('tipo') === 'ene_jun' ? 'selected' : '' }}>Enero – Junio</option>
                        <option value="ago_dic" {{ old('tipo') === 'ago_dic' ? 'selected' : '' }}>Agosto – Diciembre</option>
                    </select>
                    @error('tipo')<p class="form-error">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="form-label">Ciclo (letra) <span class="text-danger">*</span></label>
                    <select name="ciclo" class="form-select" required>
                        <option value="A" {{ old('ciclo') === 'A' ? 'selected' : '' }}>A (Enero–Junio)</option>
                        <option value="B" {{ old('ciclo') === 'B' ? 'selected' : '' }}>B (Agosto–Diciembre)</option>
                    </select>
                    @error('ciclo')<p class="form-error">{{ $message }}</p>@enderror
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="form-label">Fecha de inicio <span class="text-danger">*</span></label>
                    <input type="date" name="fecha_inicio" value="{{ old('fecha_inicio') }}"
                           class="form-input" required>
                    @error('fecha_inicio')<p class="form-error">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="form-label">Fecha de fin <span class="text-danger">*</span></label>
                    <input type="date" name="fecha_fin" value="{{ old('fecha_fin') }}"
                           class="form-input" required>
                    @error('fecha_fin')<p class="form-error">{{ $message }}</p>@enderror
                </div>
            </div>

            <div class="max-w-xs">
                <label class="form-label">Número de semanas <span class="text-danger">*</span></label>
                <input type="number" name="num_semanas" value="{{ old('num_semanas', 16) }}"
                       class="form-input" min="1" max="26" required>
                <p class="form-help">El TECNM establece 16 semanas por semestre.</p>
                @error('num_semanas')<p class="form-error">{{ $message }}</p>@enderror
            </div>

            <div class="flex items-center gap-3 pt-2">
                <button type="submit" class="btn-primary">Crear período</button>
                <a href="{{ route('escolar.periodos.index') }}" class="btn-secondary">Cancelar</a>
            </div>
        </form>
    </div>
</div>
@endsection
