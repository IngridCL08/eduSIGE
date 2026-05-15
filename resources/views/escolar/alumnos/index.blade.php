@extends('layouts.escolar')
@section('title','Alumnos')

@section('breadcrumb')
    <span class="breadcrumb-separator">/</span>
    <span class="text-carbon-700 font-medium">Alumnos</span>
@endsection

@section('header-actions')
    <a href="{{ route('escolar.alumnos.exportar') }}" class="btn-secondary btn-sm">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
        </svg>
        Exportar Excel
    </a>
@endsection

@section('content')

{{-- Filtros --}}
<form method="GET" class="card mb-5">
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
        <div class="lg:col-span-2">
            <label class="form-label">Buscar</label>
            <input name="buscar" value="{{ request('buscar') }}" class="form-input"
                   placeholder="Matrícula, nombre, CURP…">
        </div>
        <div>
            <label class="form-label">Carrera</label>
            <select name="carrera_id" class="form-select">
                <option value="">Todas</option>
                @foreach($carreras as $c)
                <option value="{{ $c->id }}" {{ request('carrera_id') == $c->id ? 'selected' : '' }}>
                    {{ $c->clave }} — {{ $c->nombre }}
                </option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="form-label">Estatus</label>
            <select name="status" class="form-select">
                <option value="">Todos</option>
                @foreach(['activo'=>'Activo','baja_temporal'=>'Baja Temporal','baja_definitiva'=>'Baja Definitiva','egresado'=>'Egresado','titulado'=>'Titulado'] as $k=>$v)
                <option value="{{ $k }}" {{ request('status') === $k ? 'selected' : '' }}>{{ $v }}</option>
                @endforeach
            </select>
        </div>
        <div class="flex items-end gap-2">
            <button type="submit" class="btn-primary btn-sm flex-1 justify-center">Filtrar</button>
            <a href="{{ route('escolar.alumnos.index') }}" class="btn-secondary btn-sm">Limpiar</a>
        </div>
    </div>
</form>

{{-- Tabla --}}
<div class="card p-0 overflow-hidden">
    <div class="table-wrapper">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Matrícula</th>
                    <th>Alumno</th>
                    <th>Carrera</th>
                    <th>Período ingreso</th>
                    <th>Promedio</th>
                    <th>Créditos</th>
                    <th>Estatus</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse($alumnos as $alumno)
                @php
                    $statusClasses = [
                        'activo'          => 'badge-success',
                        'baja_temporal'   => 'badge-warning',
                        'baja_definitiva' => 'badge-danger',
                        'egresado'        => 'badge-info',
                        'titulado'        => 'badge-neutral',
                    ];
                    $statusLabels = [
                        'activo'          => 'Activo',
                        'baja_temporal'   => 'Baja Temporal',
                        'baja_definitiva' => 'Baja Definitiva',
                        'egresado'        => 'Egresado',
                        'titulado'        => 'Titulado',
                    ];
                @endphp
                <tr>
                    <td class="font-mono text-xs font-bold">{{ $alumno->matricula }}</td>
                    <td>
                        <div class="font-medium text-carbon-900">{{ $alumno->aspirante->nombre_completo }}</div>
                        <div class="text-xs text-carbon-400">{{ $alumno->aspirante->email }}</div>
                    </td>
                    <td class="text-sm">{{ $alumno->carrera?->clave ?? '—' }}</td>
                    <td class="text-sm whitespace-nowrap">{{ $alumno->periodoIngreso?->nombre ?? '—' }}</td>
                    <td class="font-semibold">
                        {{ $alumno->promedio_general ? number_format($alumno->promedio_general, 2) : '—' }}
                    </td>
                    <td>{{ $alumno->creditos_acumulados ?? 0 }}</td>
                    <td>
                        <span class="{{ $statusClasses[$alumno->status] ?? 'badge-neutral' }} badge">
                            {{ $statusLabels[$alumno->status] ?? $alumno->status }}
                        </span>
                    </td>
                    <td class="text-right">
                        <a href="{{ route('escolar.alumnos.show', $alumno) }}" class="btn-outline btn-sm">Ver</a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center py-10 text-carbon-400">
                        No se encontraron alumnos con los filtros aplicados.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($alumnos->hasPages())
    <div class="px-4 py-3 border-t border-carbon-100">{{ $alumnos->links() }}</div>
    @endif
</div>
@endsection
