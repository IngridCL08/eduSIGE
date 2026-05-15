@extends('layouts.escolar')
@section('title', 'Exámenes de Admisión')

@section('breadcrumb')
    <span class="breadcrumb-separator">/</span>
    <span class="text-carbon-700 font-medium">Exámenes</span>
@endsection

@section('content')

<form method="GET" class="card mb-5">
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="sm:col-span-2">
            <label class="form-label">Buscar aspirante</label>
            <input name="buscar" value="{{ request('buscar') }}" class="form-input"
                   placeholder="Folio, nombre, email…">
        </div>
        <div>
            <label class="form-label">Período</label>
            <select name="periodo_id" class="form-select">
                <option value="">Todos</option>
                @foreach($periodos as $p)
                <option value="{{ $p->id }}" {{ request('periodo_id') == $p->id ? 'selected' : '' }}>
                    {{ $p->nombre }}
                </option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="flex gap-2 mt-3">
        <button type="submit" class="btn-primary btn-sm">Filtrar</button>
        <a href="{{ route('escolar.examenes.index') }}" class="btn-secondary btn-sm">Limpiar</a>
    </div>
</form>

<div class="card p-0 overflow-hidden">
    <div class="table-wrapper">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Folio</th>
                    <th>Aspirante</th>
                    <th>Carrera</th>
                    <th>Estatus</th>
                    <th>Examen</th>
                    <th>Calificación</th>
                    <th>Resultado</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse($aspirantes as $asp)
                @php $examen = $asp->examenAdmision; @endphp
                <tr>
                    <td class="font-mono text-xs">{{ $asp->folio }}</td>
                    <td>
                        <div class="font-medium text-carbon-900">{{ $asp->nombre_completo }}</div>
                        <div class="text-xs text-carbon-400">{{ $asp->email }}</div>
                    </td>
                    <td class="text-sm">{{ $asp->carrera?->clave ?? '—' }}</td>
                    <td><span class="badge {{ $asp->status_color }}">{{ $asp->status_nombre }}</span></td>
                    <td class="text-sm whitespace-nowrap">
                        {{ $examen?->fecha_examen?->format('d/m/Y') ?? '—' }}
                    </td>
                    <td class="font-medium text-center">
                        {{ $examen?->calificacion !== null ? number_format($examen->calificacion, 1) : '—' }}
                    </td>
                    <td>
                        @if($examen?->resultado)
                            <span class="badge {{ $examen->resultado_color }}">{{ $examen->resultado_nombre }}</span>
                        @else
                            <span class="badge badge-neutral">Sin registrar</span>
                        @endif
                    </td>
                    <td class="text-right">
                        @if($examen)
                            <a href="{{ route('escolar.examenes.edit', $examen) }}"
                               class="btn-outline btn-sm">Editar</a>
                        @else
                            <a href="{{ route('escolar.examenes.create', $asp) }}"
                               class="btn-primary btn-sm">Registrar</a>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center py-10 text-carbon-400">
                        No hay aspirantes con los filtros aplicados.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($aspirantes->hasPages())
    <div class="px-4 py-3 border-t border-carbon-100">{{ $aspirantes->links() }}</div>
    @endif
</div>

@endsection
