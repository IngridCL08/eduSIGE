@extends('layouts.escolar')
@section('title','Nuevo Alumno')

@section('breadcrumb')
    <a href="{{ route('escolar.alumnos.index') }}">Alumnos</a>
    <span class="breadcrumb-separator">/</span>
    <span class="text-carbon-700 font-medium">Nuevo</span>
@endsection

@section('content')
<div class="max-w-2xl">

    <div class="bg-amber-50 border border-amber-200 rounded-xl p-4 mb-5 text-sm text-amber-800">
        <strong>Nota:</strong> Los alumnos normalmente se crean automáticamente desde el expediente del aspirante
        al confirmar su admisión. Usa este formulario solo para casos especiales.
    </div>

<form method="POST" action="{{ route('escolar.alumnos.store') }}" class="space-y-5">
    @csrf

    <div class="card">
        <div class="card-header"><h3 class="card-title">Datos del Alumno</h3></div>
        <div class="space-y-4">
            <div>
                <label class="form-label">Aspirante <span class="text-danger">*</span></label>
                <select name="aspirante_id" class="form-select @error('aspirante_id') border-danger @enderror" required>
                    <option value="">— Seleccionar aspirante —</option>
                    @foreach($aspirantes as $a)
                    <option value="{{ $a->id }}" {{ old('aspirante_id') == $a->id ? 'selected' : '' }}>
                        {{ $a->folio }} — {{ $a->nombre_completo }}
                        ({{ $a->carrera?->clave ?? 'sin carrera' }})
                    </option>
                    @endforeach
                </select>
                @error('aspirante_id')<p class="form-error">{{ $message }}</p>@enderror
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="form-label">Carrera <span class="text-danger">*</span></label>
                    <select name="carrera_id" class="form-select @error('carrera_id') border-danger @enderror" required>
                        <option value="">— Seleccionar —</option>
                        @foreach($carreras as $c)
                        <option value="{{ $c->id }}" {{ old('carrera_id') == $c->id ? 'selected' : '' }}>
                            {{ $c->clave }} — {{ $c->nombre }}
                        </option>
                        @endforeach
                    </select>
                    @error('carrera_id')<p class="form-error">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="form-label">Período de Ingreso <span class="text-danger">*</span></label>
                    <select name="periodo_ingreso_id" class="form-select @error('periodo_ingreso_id') border-danger @enderror" required>
                        <option value="">— Seleccionar —</option>
                        @foreach($periodos as $p)
                        <option value="{{ $p->id }}" {{ old('periodo_ingreso_id') == $p->id ? 'selected' : '' }}>
                            {{ $p->nombre }}
                        </option>
                        @endforeach
                    </select>
                    @error('periodo_ingreso_id')<p class="form-error">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="form-label">Estatus</label>
                    <select name="status" class="form-select">
                        <option value="activo">Activo</option>
                        <option value="baja_temporal">Baja Temporal</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <div class="flex justify-end gap-3">
        <a href="{{ route('escolar.alumnos.index') }}" class="btn-secondary">Cancelar</a>
        <button type="submit" class="btn-primary">Registrar Alumno</button>
    </div>
</form>
</div>
@endsection
