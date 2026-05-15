@extends('layouts.admin')
@section('title','Editar Carrera')

@section('breadcrumb')
    <a href="{{ route('admin.carreras.index') }}">Carreras</a>
    <span class="breadcrumb-separator">/</span>
    <span class="text-carbon-700 font-medium">{{ $carrera->clave }}</span>
@endsection

@section('content')
<div class="max-w-lg">
<div class="card">
    <div class="card-header"><h3 class="card-title">Editar Carrera</h3></div>
    <form method="POST" action="{{ route('admin.carreras.update', $carrera) }}" class="space-y-4">
        @csrf @method('PUT')
        <div class="grid grid-cols-3 gap-4">
            <div>
                <label class="form-label">Clave <span class="text-danger">*</span></label>
                <input name="clave" value="{{ old('clave', $carrera->clave) }}"
                       class="form-input uppercase @error('clave') border-danger @enderror" required>
                @error('clave')<p class="form-error">{{ $message }}</p>@enderror
            </div>
            <div class="col-span-2">
                <label class="form-label">Nombre <span class="text-danger">*</span></label>
                <input name="nombre" value="{{ old('nombre', $carrera->nombre) }}"
                       class="form-input @error('nombre') border-danger @enderror" required>
                @error('nombre')<p class="form-error">{{ $message }}</p>@enderror
            </div>
        </div>
        <div>
            <label class="form-label">Descripción</label>
            <textarea name="descripcion" rows="3" class="form-input">{{ old('descripcion', $carrera->descripcion) }}</textarea>
        </div>
        <div class="flex justify-end gap-3 pt-2">
            <a href="{{ route('admin.carreras.index') }}" class="btn-secondary">Cancelar</a>
            <button type="submit" class="btn-primary">Guardar Cambios</button>
        </div>
    </form>
</div>
</div>
@endsection
