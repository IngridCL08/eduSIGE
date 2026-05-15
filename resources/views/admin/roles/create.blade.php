@extends('layouts.admin')
@section('title','Nuevo Rol')

@section('breadcrumb')
    <a href="{{ route('admin.roles.index') }}">Roles</a>
    <span class="breadcrumb-separator">/</span>
    <span class="text-carbon-700 font-medium">Nuevo</span>
@endsection

@section('content')
<div class="max-w-2xl">
<form method="POST" action="{{ route('admin.roles.store') }}" class="space-y-5">
    @csrf

    <div class="card">
        <div class="card-header"><h3 class="card-title">Datos del Rol</h3></div>
        <div class="space-y-4">
            <div>
                <label class="form-label">Nombre del Rol <span class="text-danger">*</span></label>
                <input name="name" value="{{ old('name') }}"
                       class="form-input @error('name') border-danger @enderror"
                       required placeholder="ej: supervisor">
                <p class="form-help">Solo letras minúsculas y guiones bajos. Sin espacios.</p>
                @error('name')<p class="form-error">{{ $message }}</p>@enderror
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header"><h3 class="card-title">Permisos</h3></div>
        <div class="grid grid-cols-2 sm:grid-cols-3 gap-2">
            @foreach($permisos as $perm)
            <label class="flex items-center gap-2 p-2 rounded-lg hover:bg-carbon-50 cursor-pointer">
                <input type="checkbox" name="permissions[]" value="{{ $perm->name }}"
                       class="w-4 h-4 text-navy-800 rounded border-carbon-300"
                       {{ in_array($perm->name, old('permissions', [])) ? 'checked' : '' }}>
                <span class="text-xs text-carbon-700 font-medium">{{ $perm->name }}</span>
            </label>
            @endforeach
        </div>
        @error('permissions')<p class="form-error mt-2">{{ $message }}</p>@enderror
    </div>

    <div class="flex justify-end gap-3">
        <a href="{{ route('admin.roles.index') }}" class="btn-secondary">Cancelar</a>
        <button type="submit" class="btn-primary">Crear Rol</button>
    </div>
</form>
</div>
@endsection
