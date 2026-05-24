@extends('layouts.escolar')

@section('title', 'Catálogo de Materias')

@section('breadcrumb')
    <span class="breadcrumb-separator">/</span>
    <span class="text-carbon-700 font-medium">Materias</span>
@endsection

@section('header-actions')
    <a href="{{ route('escolar.materias.create') }}" class="btn-primary btn-sm">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        Nueva Materia
    </a>
@endsection

@section('content')

<div class="card mb-5 p-4">
    <form method="GET" class="flex items-center gap-3 flex-wrap">
        <input type="text" name="buscar" value="{{ request('buscar') }}"
               class="form-input w-64" placeholder="Buscar por clave o nombre...">
        <select name="semestre" class="form-select w-40">
            <option value="">— Semestre —</option>
            @for($i = 1; $i <= 9; $i++)
            <option value="{{ $i }}" {{ request('semestre') == $i ? 'selected' : '' }}>Semestre {{ $i }}</option>
            @endfor
        </select>
        <button type="submit" class="btn-primary btn-sm">Buscar</button>
        <a href="{{ route('escolar.materias.index') }}" class="btn-secondary btn-sm">Limpiar</a>
    </form>
</div>

<div class="card p-0">
    <div class="table-wrapper border-0">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Clave</th>
                    <th>Nombre</th>
                    <th class="text-center">Créditos</th>
                    <th class="text-center">H. Teoría</th>
                    <th class="text-center">H. Práctica</th>
                    <th class="text-center">Sem. Sugerido</th>
                    <th class="text-center">Estado</th>
                    <th class="text-right">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($materias as $m)
                <tr>
                    <td><span class="font-mono font-medium text-navy-800">{{ $m->clave }}</span></td>
                    <td class="max-w-xs">
                        <p class="font-medium text-carbon-950 truncate">{{ $m->nombre }}</p>
                    </td>
                    <td class="text-center font-semibold">{{ $m->creditos }}</td>
                    <td class="text-center text-carbon-600">{{ $m->horas_teoria }}h</td>
                    <td class="text-center text-carbon-600">{{ $m->horas_practica }}h</td>
                    <td class="text-center">
                        @if($m->semestre_sugerido)
                            <span class="badge-info">{{ $m->semestre_sugerido }}°</span>
                        @else
                            <span class="text-carbon-400">—</span>
                        @endif
                    </td>
                    <td class="text-center">
                        @if($m->activa)
                            <span class="badge-success">Activa</span>
                        @else
                            <span class="badge-neutral">Inactiva</span>
                        @endif
                    </td>
                    <td class="text-right">
                        <div class="flex items-center justify-end gap-1">
                            <a href="{{ route('escolar.materias.show', $m) }}" class="btn-secondary btn-sm">Ver</a>
                            <a href="{{ route('escolar.materias.edit', $m) }}" class="btn-secondary btn-sm">Editar</a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="8" class="text-center py-10 text-carbon-400">No hay materias registradas.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="px-6 py-4">{{ $materias->links() }}</div>
</div>

@endsection
