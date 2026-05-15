@extends('layouts.admin')
@section('title','Nuevo Usuario')

@section('breadcrumb')
    <a href="{{ route('admin.usuarios.index') }}">Usuarios</a>
    <span class="breadcrumb-separator">/</span>
    <span class="text-carbon-700 font-medium">Nuevo</span>
@endsection

@section('content')
<div class="max-w-xl">
<div class="card">
    <div class="card-header"><h3 class="card-title">Crear Usuario del Sistema</h3></div>
    <form method="POST" action="{{ route('admin.usuarios.store') }}" class="space-y-4">
        @csrf
        <div>
            <label class="form-label">Nombre completo <span class="text-danger">*</span></label>
            <input name="name" value="{{ old('name') }}" class="form-input @error('name') border-danger @enderror" required>
            @error('name')<p class="form-error">{{ $message }}</p>@enderror
        </div>
        <div>
            <label class="form-label">Correo electrónico <span class="text-danger">*</span></label>
            <input name="email" type="email" value="{{ old('email') }}" class="form-input @error('email') border-danger @enderror" required>
            @error('email')<p class="form-error">{{ $message }}</p>@enderror
        </div>
        <div>
            <label class="form-label">Rol <span class="text-danger">*</span></label>
            <select name="role" class="form-select @error('role') border-danger @enderror" required>
                <option value="">— Seleccionar rol —</option>
                @foreach($roles as $rol)
                <option value="{{ $rol->name }}" {{ old('role') == $rol->name ? 'selected' : '' }}>
                    {{ ucfirst($rol->name) }} — {{ ['admin'=>'Super Administrador','financiero'=>'Recursos Financieros','escolar'=>'Control Escolar'][$rol->name] ?? $rol->name }}
                </option>
                @endforeach
            </select>
            @error('role')<p class="form-error">{{ $message }}</p>@enderror
        </div>
        <div x-data="{ show: false }">
            <label class="form-label">Contraseña <span class="text-danger">*</span></label>
            <div class="relative">
                <input name="password" :type="show ? 'text' : 'password'" class="form-input pr-10 @error('password') border-danger @enderror" required minlength="8">
                <button type="button" @click="show=!show" class="absolute right-3 top-1/2 -translate-y-1/2 text-carbon-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                </button>
            </div>
            @error('password')<p class="form-error">{{ $message }}</p>@enderror
        </div>
        <div>
            <label class="form-label">Confirmar contraseña <span class="text-danger">*</span></label>
            <input name="password_confirmation" type="password" class="form-input" required>
        </div>
        <div class="flex justify-end gap-3 pt-2">
            <a href="{{ route('admin.usuarios.index') }}" class="btn-secondary">Cancelar</a>
            <button type="submit" class="btn-primary">Crear Usuario</button>
        </div>
    </form>
</div>
</div>
@endsection
