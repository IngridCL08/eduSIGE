@extends('layouts.admin')
@section('title','Editar Rol — ' . ucfirst($rol->name))

@section('breadcrumb')
    <a href="{{ route('admin.roles.index') }}">Roles</a>
    <span class="breadcrumb-separator">/</span>
    <span class="text-carbon-700 font-medium">{{ ucfirst($rol->name) }}</span>
@endsection

@section('content')
<div class="max-w-2xl">
<form method="POST" action="{{ route('admin.roles.update', $rol) }}" class="space-y-5">
    @csrf @method('PUT')

    <div class="card">
        <div class="card-header"><h3 class="card-title">Datos del Rol</h3></div>
        <div>
            <label class="form-label">Nombre del Rol</label>
            @if(in_array($rol->name, ['admin','financiero','escolar']))
            <div class="flex items-center gap-2">
                <input value="{{ ucfirst($rol->name) }}" class="form-input bg-carbon-50 text-carbon-500" disabled>
                <span class="text-xs text-carbon-400 whitespace-nowrap">Rol del sistema — no editable</span>
            </div>
            <input type="hidden" name="name" value="{{ $rol->name }}">
            @else
            <input name="name" value="{{ old('name', $rol->name) }}"
                   class="form-input @error('name') border-danger @enderror" required>
            @error('name')<p class="form-error">{{ $message }}</p>@enderror
            @endif
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Permisos</h3>
            <div class="flex gap-2">
                <button type="button" onclick="toggleAll(true)"
                        class="text-xs text-navy-600 hover:underline">Seleccionar todos</button>
                <span class="text-carbon-300">|</span>
                <button type="button" onclick="toggleAll(false)"
                        class="text-xs text-carbon-500 hover:underline">Ninguno</button>
            </div>
        </div>
        <div class="grid grid-cols-2 sm:grid-cols-3 gap-2">
            @php $rolPermisos = $rol->permissions->pluck('name')->toArray(); @endphp
            @foreach($permisos as $perm)
            <label class="flex items-center gap-2 p-2 rounded-lg hover:bg-carbon-50 cursor-pointer">
                <input type="checkbox" name="permissions[]" value="{{ $perm->name }}"
                       class="perm-check w-4 h-4 text-navy-800 rounded border-carbon-300"
                       {{ in_array($perm->name, old('permissions', $rolPermisos)) ? 'checked' : '' }}>
                <span class="text-xs text-carbon-700 font-medium">{{ $perm->name }}</span>
            </label>
            @endforeach
        </div>
    </div>

    <div class="flex justify-end gap-3">
        <a href="{{ route('admin.roles.index') }}" class="btn-secondary">Cancelar</a>
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

@push('scripts')
<script>
function toggleAll(checked) {
    document.querySelectorAll('.perm-check').forEach(cb => cb.checked = checked);
}
</script>
@endpush
