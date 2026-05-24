@extends('layouts.escolar')

@section('title', 'Nuevo Adeudo')

@section('breadcrumb')
    <span class="breadcrumb-separator">/</span>
    <a href="{{ route('escolar.adeudos.index') }}">Adeudos</a>
    <span class="breadcrumb-separator">/</span>
    <span class="text-carbon-700 font-medium">Nuevo</span>
@endsection

@section('content')
<div class="max-w-2xl">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Registrar Adeudo</h3>
        </div>

        <form method="POST" action="{{ route('escolar.adeudos.store') }}" class="space-y-5">
            @csrf

            <div>
                <label class="form-label">Alumno <span class="text-danger">*</span></label>
                <select name="alumno_id" class="form-select" required>
                    <option value="">— Seleccionar alumno —</option>
                    @foreach($alumnos as $alumno)
                    <option value="{{ $alumno->id }}"
                            {{ old('alumno_id', $alumnoId) == $alumno->id ? 'selected' : '' }}>
                        {{ $alumno->matricula }} — {{ $alumno->nombre_completo }}
                    </option>
                    @endforeach
                </select>
                @error('alumno_id')<p class="form-error">{{ $message }}</p>@enderror
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="form-label">Tipo <span class="text-danger">*</span></label>
                    <select name="tipo" class="form-select" required>
                        <option value="">— Seleccionar —</option>
                        @foreach($tipos as $key => $label)
                        <option value="{{ $key }}" {{ old('tipo') === $key ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('tipo')<p class="form-error">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="form-label">Período (opcional)</label>
                    <select name="periodo_id" class="form-select">
                        <option value="">— Sin período —</option>
                        @foreach($periodos as $periodo)
                        <option value="{{ $periodo->id }}" {{ old('periodo_id') == $periodo->id ? 'selected' : '' }}>
                            {{ $periodo->nombre }}
                        </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div>
                <label class="form-label">Concepto <span class="text-danger">*</span></label>
                <input type="text" name="concepto" value="{{ old('concepto') }}"
                       class="form-input" placeholder="Ej. Colegiatura enero 2026, Libro no devuelto..." required maxlength="150">
                @error('concepto')<p class="form-error">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="form-label">Descripción adicional</label>
                <textarea name="descripcion" rows="2" class="form-input"
                          placeholder="Detalles o notas adicionales...">{{ old('descripcion') }}</textarea>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="form-label">Monto (opcional)</label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-carbon-400 text-sm">$</span>
                        <input type="number" name="monto" value="{{ old('monto') }}"
                               class="form-input pl-6" step="0.01" min="0" placeholder="0.00">
                    </div>
                </div>
                <div>
                    <label class="form-label">Fecha de vencimiento</label>
                    <input type="date" name="fecha_vencimiento" value="{{ old('fecha_vencimiento') }}"
                           class="form-input">
                </div>
            </div>

            <div class="flex items-center gap-3 pt-2">
                <button type="submit" class="btn-primary">Registrar adeudo</button>
                <a href="{{ route('escolar.adeudos.index') }}" class="btn-secondary">Cancelar</a>
            </div>
        </form>
    </div>
</div>
@endsection
