@extends('layouts.admin')
@section('title','Períodos Escolares')

@section('header-actions')
    <a href="{{ route('admin.periodos.create') }}" class="btn-primary btn-sm">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Nuevo Período
    </a>
@endsection

@section('content')
<div class="card p-0 overflow-hidden">
    <div class="table-wrapper">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Año</th>
                    <th>Ciclo</th>
                    <th>Inicio</th>
                    <th>Fin</th>
                    <th>Aspirantes</th>
                    <th>Estado</th>
                    <th class="text-right">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($periodos as $periodo)
                <tr class="{{ $periodo->activo ? 'bg-blue-50/50' : '' }}">
                    <td class="font-semibold">{{ $periodo->nombre }}</td>
                    <td>{{ $periodo->anio }}</td>
                    <td>{{ $periodo->ciclo }}</td>
                    <td>{{ $periodo->fecha_inicio->format('d/m/Y') }}</td>
                    <td>{{ $periodo->fecha_fin->format('d/m/Y') }}</td>
                    <td>{{ $periodo->aspirantes_count }}</td>
                    <td>
                        @if($periodo->activo)
                            <span class="badge-success">● Activo</span>
                        @else
                            <span class="badge-neutral">Inactivo</span>
                        @endif
                    </td>
                    <td>
                        <div class="flex items-center justify-end gap-1">
                            <a href="{{ route('admin.periodos.edit', $periodo) }}" class="btn-icon btn-sm btn-secondary" title="Editar">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                            </a>
                            @if(!$periodo->activo)
                            <form method="POST" action="{{ route('admin.periodos.activar', $periodo) }}">
                                @csrf @method('PATCH')
                                <button class="btn-primary btn-sm" title="Marcar como activo">Activar</button>
                            </form>
                            @endif
                            @if($periodo->aspirantes_count === 0)
                            <form method="POST" action="{{ route('admin.periodos.destroy', $periodo) }}" onsubmit="return confirm('¿Eliminar período?')">
                                @csrf @method('DELETE')
                                <button class="btn-icon btn-sm btn-danger" title="Eliminar">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="8" class="text-center py-8 text-carbon-400">No hay períodos registrados.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($periodos->hasPages())
    <div class="px-4 py-3 border-t border-carbon-100">{{ $periodos->links() }}</div>
    @endif
</div>
@endsection
