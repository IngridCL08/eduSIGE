@extends('layouts.escolar')

@section('title', 'Nueva Materia')

@section('breadcrumb')
    <span class="breadcrumb-separator">/</span>
    <a href="{{ route('escolar.materias.index') }}">Materias</a>
    <span class="breadcrumb-separator">/</span>
    <span class="text-carbon-700 font-medium">Nueva</span>
@endsection

@section('content')
<div class="max-w-2xl">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Nueva Materia</h3>
        </div>

        <form method="POST" action="{{ route('escolar.materias.store') }}" class="space-y-5">
            @csrf

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="form-label">Clave <span class="text-danger">*</span></label>
                    <input type="text" name="clave" value="{{ old('clave') }}"
                           class="form-input font-mono uppercase" placeholder="Ej. MAT-101" required maxlength="20">
                    <p class="form-help">Identificador único de la materia.</p>
                    @error('clave')<p class="form-error">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="form-label">Semestre sugerido</label>
                    <select name="semestre_sugerido" class="form-select">
                        <option value="">— Sin asignar —</option>
                        @for($i = 1; $i <= 8; $i++)
                        <option value="{{ $i }}" {{ old('semestre_sugerido') == $i ? 'selected' : '' }}>
                            Semestre {{ $i }}
                        </option>
                        @endfor
                    </select>
                    @error('semestre_sugerido')<p class="form-error">{{ $message }}</p>@enderror
                </div>
            </div>

            <div>
                <label class="form-label">Nombre completo <span class="text-danger">*</span></label>
                <input type="text" name="nombre" value="{{ old('nombre') }}"
                       class="form-input" placeholder="Ej. Matemáticas Discretas" required maxlength="150">
                @error('nombre')<p class="form-error">{{ $message }}</p>@enderror
            </div>

            <div class="grid grid-cols-3 gap-4">
                <div>
                    <label class="form-label">Créditos <span class="text-danger">*</span></label>
                    <input type="number" name="creditos" value="{{ old('creditos', 5) }}"
                           class="form-input" min="1" max="20" required>
                    @error('creditos')<p class="form-error">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="form-label">Horas teoría <span class="text-danger">*</span></label>
                    <input type="number" name="horas_teoria" value="{{ old('horas_teoria', 3) }}"
                           class="form-input" min="0" max="20" required>
                    @error('horas_teoria')<p class="form-error">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="form-label">Horas práctica <span class="text-danger">*</span></label>
                    <input type="number" name="horas_practica" value="{{ old('horas_practica', 2) }}"
                           class="form-input" min="0" max="20" required>
                    @error('horas_practica')<p class="form-error">{{ $message }}</p>@enderror
                </div>
            </div>

            <div class="bg-carbon-50 rounded-lg px-4 py-3 text-sm text-carbon-600">
                <span class="font-medium text-carbon-950">Fórmula TECNM:</span>
                Créditos = (Horas teoría × 2) + (Horas práctica × 1)
            </div>

            <div class="flex items-center gap-3 pt-2">
                <button type="submit" class="btn-primary">Guardar materia</button>
                <a href="{{ route('escolar.materias.index') }}" class="btn-secondary">Cancelar</a>
            </div>
        </form>
    </div>
</div>
@endsection
