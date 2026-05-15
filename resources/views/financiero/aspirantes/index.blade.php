@extends('layouts.financiero')
@section('title','Aspirantes')

@section('breadcrumb')
    <span class="breadcrumb-separator">/</span>
    <span class="text-carbon-700 font-medium">Aspirantes</span>
@endsection

@section('content')

{{-- Filtros --}}
<form method="GET" class="card mb-5">
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
        <div class="lg:col-span-2">
            <label class="form-label">Buscar</label>
            <input name="buscar" value="{{ request('buscar') }}" class="form-input"
                   placeholder="Folio, nombre, CURP, email…">
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
            <label class="form-label">Estado de Ficha</label>
            <select name="ficha_status" class="form-select">
                <option value="">Todos</option>
                <option value="sin_ficha"  {{ request('ficha_status') === 'sin_ficha'  ? 'selected' : '' }}>Sin ficha</option>
                <option value="pendiente"  {{ request('ficha_status') === 'pendiente'  ? 'selected' : '' }}>Pendiente</option>
                <option value="pagado"     {{ request('ficha_status') === 'pagado'     ? 'selected' : '' }}>Pagada</option>
                <option value="vencido"    {{ request('ficha_status') === 'vencido'    ? 'selected' : '' }}>Vencida</option>
            </select>
        </div>
        <div class="flex items-end gap-2">
            <button type="submit" class="btn-primary btn-sm flex-1 justify-center">Filtrar</button>
            <a href="{{ route('financiero.aspirantes.index') }}" class="btn-secondary btn-sm">Limpiar</a>
        </div>
    </div>
</form>

{{-- Tabla --}}
<div class="card p-0 overflow-hidden">
    <div class="table-wrapper">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Folio</th>
                    <th>Aspirante</th>
                    <th>Carrera</th>
                    <th>Período</th>
                    <th>Ficha</th>
                    <th>Estado Ficha</th>
                    <th>Monto</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse($aspirantes as $asp)
                @php $ficha = $asp->fichaPago; @endphp
                <tr>
                    <td class="font-mono text-xs">{{ $asp->folio }}</td>
                    <td>
                        <div class="font-medium text-carbon-900">{{ $asp->nombre_completo }}</div>
                        <div class="text-xs text-carbon-400">{{ $asp->email }}</div>
                    </td>
                    <td class="text-sm">{{ $asp->carrera?->clave ?? '—' }}</td>
                    <td class="text-sm whitespace-nowrap">{{ $asp->periodo?->nombre ?? '—' }}</td>
                    <td class="font-mono text-xs">{{ $ficha?->folio_ficha ?? '—' }}</td>
                    <td>
                        @if($ficha)
                            <span class="badge {{ $ficha->status_color }}">{{ $ficha->status_nombre }}</span>
                        @else
                            <span class="badge badge-neutral">Sin ficha</span>
                        @endif
                    </td>
                    <td class="font-medium">{{ $ficha ? $ficha->monto_formateado : '—' }}</td>
                    <td class="text-right">
                        <a href="{{ route('financiero.aspirantes.show', $asp) }}"
                           class="btn-outline btn-sm">Ver</a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center py-10 text-carbon-400">
                        No se encontraron aspirantes con los filtros aplicados.
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
