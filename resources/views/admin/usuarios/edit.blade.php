@extends('layouts.admin')
@section('title','Editar Usuario')

@section('breadcrumb')
    <a href="{{ route('admin.usuarios.index') }}">Usuarios</a>
    <span class="breadcrumb-separator">/</span>
    <span class="text-carbon-700 font-medium">{{ $usuario->name }}</span>
@endsection

@section('content')
<div class="max-w-xl">
<div class="card">
    <div class="card-header"><h3 class="card-title">Editar Usuario</h3></div>
    <form method="POST" action="{{ route('admin.usuarios.update', $usuario) }}" class="space-y-4">
        @csrf @method('PUT')
        <div>
            <label class="form-label">Nombre completo <span class="text-danger">*</span></label>
            <input name="name" value="{{ old('name', $usuario->name) }}" class="form-input @error('name') border-danger @enderror" required>
            @error('name')<p class="form-error">{{ $message }}</p>@enderror
        </div>
        <div>
            <label class="form-label">Correo electrónico <span class="text-danger">*</span></label>
            <input name="email" type="email" value="{{ old('email', $usuario->email) }}" class="form-input @error('email') border-danger @enderror" required>
            @error('email')<p class="form-error">{{ $message }}</p>@enderror
        </div>
        <div>
            <label class="form-label">Rol <span class="text-danger">*</span></label>
            <select name="role" class="form-select" required>
                @foreach($roles as $rol)
                <option value="{{ $rol->name }}" {{ $rolActual == $rol->name ? 'selected' : '' }}>
                    {{ ucfirst($rol->name) }}
                </option>
                @endforeach
            </select>
        </div>
        <div class="border-t border-carbon-100 pt-4">
            <p class="text-xs text-carbon-400 mb-3">Dejar en blanco para no cambiar la contraseña.</p>
            <div x-data="{ show: false }">
                <label class="form-label">Nueva contraseña</label>
                <div class="relative">
                    <input name="password" :type="show ? 'text' : 'password'" class="form-input pr-10" minlength="8">
                    <button type="button" @click="show=!show" class="absolute right-3 top-1/2 -translate-y-1/2 text-carbon-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    </button>
                </div>
            </div>
            <div class="mt-3">
                <label class="form-label">Confirmar nueva contraseña</label>
                <input name="password_confirmation" type="password" class="form-input">
            </div>
        </div>
        <div class="flex justify-end gap-3 pt-2">
            <a href="{{ route('admin.usuarios.index') }}" class="btn-secondary">Cancelar</a>
            <button type="submit" class="btn-primary">Guardar Cambios</button>
        </div>
    </form>
</div>
</div>
@endsection
