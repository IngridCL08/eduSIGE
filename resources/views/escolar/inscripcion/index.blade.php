@extends('layouts.escolar')
@section('title', 'Inscripción de Aspirantes')

@section('breadcrumb')
    <span class="breadcrumb-separator">/</span>
    <span class="text-carbon-700 font-medium">Inscripción</span>
@endsection

@section('content')

{{-- Alerta de inscripción exitosa con credenciales --}}
@if(session('inscripcion_exitosa'))
@php $datos = session('inscripcion_exitosa'); @endphp
<div class="bg-green-50 border border-green-300 rounded-xl p-5 mb-6" x-data>
    <p class="font-semibold text-green-800 text-base mb-2">✓ Inscripción completada</p>
    <p class="text-sm text-green-700 mb-3">
        <strong>{{ $datos['nombre'] }}</strong> ha sido inscrito exitosamente como alumno.
    </p>
    <div class="bg-white rounded-lg border border-green-200 p-4 text-sm font-mono space-y-1">
        <p><span class="text-slate-500">Matrícula:</span>
           <span class="font-bold text-slate-900">{{ $datos['matricula'] }}</span></p>
        <p><span class="text-slate-500">Contraseña inicial:</span>
           <span class="font-bold text-slate-900">{{ $datos['password'] }}</span></p>
    </div>
    <p class="text-xs text-green-600 mt-3">
        ⚠ Anota o comparte estas credenciales con el alumno. La contraseña no se mostrará de nuevo.
    </p>
</div>
@endif

{{-- Filtros --}}
<form method="GET" class="card mb-5">
    <div class="grid grid-cols-1 sm:grid-cols-4 gap-4">
        <div class="sm:col-span-2">
            <label class="form-label">Buscar</label>
            <input name="buscar" value="{{ request('buscar') }}" class="form-input"
                   placeholder="Folio, nombre…">
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
        <div>
            <label class="form-label">Carrera</label>
            <select name="carrera_id" class="form-select">
                <option value="">Todas</option>
                @foreach($carreras as $c)
                <option value="{{ $c->id }}" {{ request('carrera_id') == $c->id ? 'selected' : '' }}>
                    {{ $c->clave }}
                </option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="flex gap-2 mt-3">
        <button type="submit" class="btn-primary btn-sm">Filtrar</button>
        <a href="{{ route('escolar.inscripcion.index') }}" class="btn-secondary btn-sm">Limpiar</a>
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
                    <th>Examen</th>
                    <th>Estatus</th>
                    <th>Matrícula</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse($aspirantes as $asp)
                <tr>
                    <td class="font-mono text-xs">{{ $asp->folio }}</td>
                    <td>
                        <div class="font-medium text-carbon-900">{{ $asp->nombre_completo }}</div>
                        <div class="text-xs text-carbon-400">{{ $asp->email }}</div>
                    </td>
                    <td class="text-sm">{{ $asp->carrera?->clave ?? '—' }}</td>
                    <td class="text-sm text-center">
                        @if($asp->examenAdmision)
                            <span class="badge {{ $asp->examenAdmision->resultado_color }}">
                                {{ $asp->examenAdmision->resultado_nombre }}
                            </span>
                            <p class="text-xs text-carbon-400 mt-0.5">
                                {{ $asp->examenAdmision->calificacion !== null
                                   ? number_format($asp->examenAdmision->calificacion, 1)
                                   : '—' }}
                            </p>
                        @else
                            <span class="badge badge-neutral">Sin examen</span>
                        @endif
                    </td>
                    <td>
                        <span class="badge {{ $asp->status_color }}">{{ $asp->status_nombre }}</span>
                    </td>
                    <td class="font-mono text-xs">
                        {{ $asp->alumno?->matricula ?? '—' }}
                    </td>
                    <td class="text-right">
                        @if($asp->status === \App\Models\Aspirante::STATUS_ADMITIDO && ! $asp->alumno)
                        <form method="POST"
                              action="{{ route('escolar.inscripcion.store', $asp) }}"
                              onsubmit="return confirm('¿Inscribir a {{ $asp->nombre_completo }}? Esto generará su número de matrícula.')">
                            @csrf
                            <button class="btn-success btn-sm">Inscribir</button>
                        </form>
                        @elseif($asp->alumno)
                            <span class="text-xs text-carbon-400">Ya inscrito</span>
                        @else
                            <span class="text-xs text-carbon-400">—</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center py-10 text-carbon-400">
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
