@extends('layouts.admin')
@section('title','Nueva Carrera')

@section('breadcrumb')
    <a href="{{ route('admin.carreras.index') }}">Carreras</a>
    <span class="breadcrumb-separator">/</span>
    <span class="text-carbon-700 font-medium">Nueva</span>
@endsection

@section('content')
<div class="max-w-lg">
<div class="card">
    <div class="card-header"><h3 class="card-title">Registrar Nueva Carrera</h3></div>
    <form method="POST" action="{{ route('admin.carreras.store') }}" class="space-y-4">
        @csrf
        <div class="grid grid-cols-3 gap-4">
            <div>
                <label class="form-label">Clave <span class="text-danger">*</span></label>
                <input name="clave" value="{{ old('clave') }}" class="form-input uppercase @error('clave') border-danger @enderror"
                       placeholder="ISIA" maxlength="20" required>
                @error('clave')<p class="form-error">{{ $message }}</p>@enderror
            </div>
            <div class="col-span-2">
                <label class="form-label">Nombre <span class="text-danger">*</span></label>
                <input name="nombre" value="{{ old('nombre') }}" class="form-input @error('nombre') border-danger @enderror"
                       placeholder="Ingeniería en Sistemas…" required>
                @error('nombre')<p class="form-error">{{ $message }}</p>@enderror
            </div>
        </div>
        <div>
            <label class="form-label">Descripción</label>
            <textarea name="descripcion" rows="3" class="form-input">{{ old('descripcion') }}</textarea>
        </div>
        <div class="flex justify-end gap-3 pt-2">
            <a href="{{ route('admin.carreras.index') }}" class="btn-secondary">Cancelar</a>
            <button type="submit" class="btn-primary">Guardar Carrera</button>
        </div>
    </form>
</div>
</div>
@endsection
