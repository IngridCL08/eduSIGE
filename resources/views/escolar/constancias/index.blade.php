@extends('layouts.escolar')

@section('title', 'Generación de Documentos')

@section('breadcrumb')
    <span class="breadcrumb-separator">/</span>
    <span class="text-carbon-700 font-medium">Documentos</span>
@endsection

@section('content')

<div class="card mb-5 p-4">
    <form method="GET" class="flex items-center gap-3">
        <input type="text" name="buscar" value="{{ request('buscar') }}"
               class="form-input w-80" placeholder="Buscar por matrícula o nombre del alumno...">
        <button type="submit" class="btn-primary btn-sm">Buscar</button>
        <a href="{{ route('escolar.constancias.index') }}" class="btn-secondary btn-sm">Limpiar</a>
    </form>
</div>

<div class="card p-0">
    <div class="card-header px-6 pt-5 pb-4">
        <h3 class="card-title">Alumnos activos</h3>
        <p class="text-sm text-carbon-500">Selecciona un alumno para generar sus documentos.</p>
    </div>
    <div class="table-wrapper rounded-t-none border-t-0">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Matrícula</th>
                    <th>Alumno</th>
                    <th>Carrera</th>
                    <th class="text-center">Semestre</th>
                    <th class="text-center">Promedio</th>
                    <th class="text-right">Documentos</th>
                </tr>
            </thead>
            <tbody>
                @forelse($alumnos as $alumno)
                <tr>
                    <td class="font-mono font-medium text-navy-800">{{ $alumno->matricula }}</td>
                    <td>
                        <p class="font-medium text-carbon-950">{{ $alumno->nombre_completo }}</p>
                    </td>
                    <td class="text-sm text-carbon-600">{{ $alumno->carrera->nombre ?? '—' }}</td>
                    <td class="text-center">
                        <span class="badge-info">{{ $alumno->semestre_actual }}°</span>
                    </td>
                    <td class="text-center font-semibold text-carbon-950">
                        {{ $alumno->promedio_general ? number_format($alumno->promedio_general, 1) : '—' }}
                    </td>
                    <td class="text-right">
                        <div class="flex items-center justify-end gap-1 flex-wrap">
                            <a href="{{ route('escolar.constancias.estudios', $alumno) }}"
                               target="_blank" class="btn-secondary btn-sm" title="Constancia de estudios">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                Estudios
                            </a>
                            <a href="{{ route('escolar.constancias.kardex', $alumno) }}"
                               target="_blank" class="btn-secondary btn-sm" title="Kárdex / historial">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                </svg>
                                Kárdex
                            </a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="text-center py-10 text-carbon-400">No hay alumnos activos.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="px-6 py-4">{{ $alumnos->links() }}</div>
</div>

@endsection
