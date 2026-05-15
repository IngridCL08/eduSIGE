@extends('layouts.escolar')
@section('title','Editar Aspirante — ' . $aspirante->nombre_completo)

@section('breadcrumb')
    <a href="{{ route('escolar.aspirantes.index') }}">Aspirantes</a>
    <span class="breadcrumb-separator">/</span>
    <a href="{{ route('escolar.aspirantes.show', $aspirante) }}">{{ $aspirante->folio }}</a>
    <span class="breadcrumb-separator">/</span>
    <span class="text-carbon-700 font-medium">Editar</span>
@endsection

@section('content')
<div class="max-w-4xl">
<form method="POST" action="{{ route('escolar.aspirantes.update', $aspirante) }}" class="space-y-5">
    @csrf @method('PUT')

    {{-- Datos personales --}}
    <div class="card">
        <div class="card-header"><h3 class="card-title">Datos Personales</h3></div>
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div>
                <label class="form-label">Nombre(s) <span class="text-danger">*</span></label>
                <input name="nombre" value="{{ old('nombre', $aspirante->nombre) }}"
                       class="form-input @error('nombre') border-danger @enderror" required>
                @error('nombre')<p class="form-error">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="form-label">Apellido Paterno <span class="text-danger">*</span></label>
                <input name="apellido_paterno" value="{{ old('apellido_paterno', $aspirante->apellido_paterno) }}"
                       class="form-input @error('apellido_paterno') border-danger @enderror" required>
                @error('apellido_paterno')<p class="form-error">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="form-label">Apellido Materno</label>
                <input name="apellido_materno" value="{{ old('apellido_materno', $aspirante->apellido_materno) }}"
                       class="form-input @error('apellido_materno') border-danger @enderror">
                @error('apellido_materno')<p class="form-error">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="form-label">Sexo <span class="text-danger">*</span></label>
                <select name="sexo" class="form-select @error('sexo') border-danger @enderror" required>
                    <option value="">— Seleccionar —</option>
                    <option value="M" {{ old('sexo', $aspirante->sexo) === 'M' ? 'selected' : '' }}>Masculino</option>
                    <option value="F" {{ old('sexo', $aspirante->sexo) === 'F' ? 'selected' : '' }}>Femenino</option>
                    <option value="O" {{ old('sexo', $aspirante->sexo) === 'O' ? 'selected' : '' }}>Otro / Prefiero no decir</option>
                </select>
                @error('sexo')<p class="form-error">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="form-label">Fecha de Nacimiento <span class="text-danger">*</span></label>
                <input type="date" name="fecha_nacimiento"
                       value="{{ old('fecha_nacimiento', $aspirante->fecha_nacimiento?->format('Y-m-d')) }}"
                       class="form-input @error('fecha_nacimiento') border-danger @enderror" required>
                @error('fecha_nacimiento')<p class="form-error">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="form-label">CURP</label>
                <input name="curp" value="{{ old('curp', $aspirante->curp) }}" maxlength="18"
                       class="form-input uppercase @error('curp') border-danger @enderror">
                @error('curp')<p class="form-error">{{ $message }}</p>@enderror
            </div>
        </div>
    </div>

    {{-- Contacto --}}
    <div class="card">
        <div class="card-header"><h3 class="card-title">Contacto y Domicilio</h3></div>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="form-label">Correo Electrónico <span class="text-danger">*</span></label>
                <input type="email" name="email" value="{{ old('email', $aspirante->email) }}"
                       class="form-input @error('email') border-danger @enderror" required>
                @error('email')<p class="form-error">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="form-label">Teléfono</label>
                <input type="tel" name="telefono" value="{{ old('telefono', $aspirante->telefono) }}"
                       class="form-input @error('telefono') border-danger @enderror">
                @error('telefono')<p class="form-error">{{ $message }}</p>@enderror
            </div>
            <div class="sm:col-span-2">
                <label class="form-label">Dirección / Calle y Número</label>
                <input name="direccion" value="{{ old('direccion', $aspirante->direccion) }}"
                       class="form-input @error('direccion') border-danger @enderror">
                @error('direccion')<p class="form-error">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="form-label">Colonia</label>
                <input name="colonia" value="{{ old('colonia', $aspirante->colonia) }}" class="form-input">
            </div>
            <div>
                <label class="form-label">Municipio / Ciudad</label>
                <input name="municipio" value="{{ old('municipio', $aspirante->municipio) }}" class="form-input">
            </div>
            <div>
                <label class="form-label">Estado</label>
                <input name="estado" value="{{ old('estado', $aspirante->estado) }}" class="form-input">
            </div>
            <div>
                <label class="form-label">Código Postal</label>
                <input name="cp" value="{{ old('cp', $aspirante->cp) }}" class="form-input" maxlength="10">
            </div>
        </div>
    </div>

    {{-- Escolar --}}
    <div class="card">
        <div class="card-header"><h3 class="card-title">Información Escolar</h3></div>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="form-label">Carrera Solicitada <span class="text-danger">*</span></label>
                <select name="carrera_id" class="form-select @error('carrera_id') border-danger @enderror" required>
                    <option value="">— Seleccionar —</option>
                    @foreach($carreras as $c)
                    <option value="{{ $c->id }}" {{ old('carrera_id', $aspirante->carrera_id) == $c->id ? 'selected' : '' }}>
                        {{ $c->clave }} — {{ $c->nombre }}
                    </option>
                    @endforeach
                </select>
                @error('carrera_id')<p class="form-error">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="form-label">Período <span class="text-danger">*</span></label>
                <select name="periodo_id" class="form-select @error('periodo_id') border-danger @enderror" required>
                    <option value="">— Seleccionar —</option>
                    @foreach($periodos as $p)
                    <option value="{{ $p->id }}" {{ old('periodo_id', $aspirante->periodo_id) == $p->id ? 'selected' : '' }}>
                        {{ $p->nombre }}
                    </option>
                    @endforeach
                </select>
                @error('periodo_id')<p class="form-error">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="form-label">Bachillerato de Procedencia</label>
                <input name="bachillerato" value="{{ old('bachillerato', $aspirante->bachillerato) }}"
                       class="form-input @error('bachillerato') border-danger @enderror">
                @error('bachillerato')<p class="form-error">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="form-label">Promedio de Bachillerato</label>
                <input type="number" step="0.01" min="0" max="10" name="promedio_bachillerato"
                       value="{{ old('promedio_bachillerato', $aspirante->promedio_bachillerato) }}"
                       class="form-input @error('promedio_bachillerato') border-danger @enderror">
                @error('promedio_bachillerato')<p class="form-error">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="form-label">Año de Egreso</label>
                <input type="number" name="anio_egreso" value="{{ old('anio_egreso', $aspirante->anio_egreso) }}"
                       class="form-input" min="1980" max="{{ date('Y') }}">
            </div>
        </div>
    </div>

    <div class="flex justify-end gap-3">
        <a href="{{ route('escolar.aspirantes.show', $aspirante) }}" class="btn-secondary">Cancelar</a>
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
