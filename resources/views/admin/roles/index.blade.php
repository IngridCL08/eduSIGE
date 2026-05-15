@extends('layouts.admin')
@section('title','Roles y Permisos')

@section('breadcrumb')
    <span class="breadcrumb-separator">/</span>
    <span class="text-carbon-700 font-medium">Roles</span>
@endsection

@section('header-actions')
    <a href="{{ route('admin.roles.create') }}" class="btn-primary btn-sm">+ Nuevo Rol</a>
@endsection

@section('content')
<div class="card p-0 overflow-hidden">
    <div class="table-wrapper">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Rol</th>
                    <th>Guard</th>
                    <th>Permisos</th>
                    <th>Usuarios</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse($roles as $rol)
                <tr>
                    <td>
                        <div class="flex items-center gap-2">
                            <span class="w-2.5 h-2.5 rounded-full
                                {{ $rol->name === 'admin' ? 'bg-red-500' : ($rol->name === 'financiero' ? 'bg-amber-500' : 'bg-navy-500') }}">
                            </span>
                            <span class="font-semibold text-carbon-900">{{ ucfirst($rol->name) }}</span>
                        </div>
                    </td>
                    <td class="text-sm text-carbon-500">{{ $rol->guard_name }}</td>
                    <td>
                        <div class="flex flex-wrap gap-1">
                            @foreach($rol->permissions->take(5) as $perm)
                            <code class="text-xs bg-navy-50 text-navy-800 px-1.5 py-0.5 rounded">{{ $perm->name }}</code>
                            @endforeach
                            @if($rol->permissions->count() > 5)
                            <span class="text-xs text-carbon-400">+{{ $rol->permissions->count() - 5 }} más</span>
                            @endif
                        </div>
                    </td>
                    <td class="font-medium">{{ $rol->users_count ?? $rol->users()->count() }}</td>
                    <td class="text-right">
                        <div class="flex items-center justify-end gap-2">
                            <a href="{{ route('admin.roles.edit', $rol) }}" class="btn-outline btn-sm">Editar</a>
                            @if(!in_array($rol->name, ['admin','financiero','escolar']))
                            <form method="POST" action="{{ route('admin.roles.destroy', $rol) }}"
                                  onsubmit="return confirm('¿Eliminar el rol {{ $rol->name }}?')">
                                @csrf @method('DELETE')
                                <button class="btn-danger btn-sm">Eliminar</button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center py-8 text-carbon-400">No hay roles registrados.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- Permisos disponibles --}}
<div class="card mt-5">
    <div class="card-header"><h3 class="card-title">Permisos del Sistema</h3></div>
    <div class="flex flex-wrap gap-2">
        @foreach($permisos as $perm)
        <code class="text-xs bg-carbon-100 text-carbon-700 px-2 py-1 rounded">{{ $perm->name }}</code>
        @endforeach
    </div>
</div>
@endsection
