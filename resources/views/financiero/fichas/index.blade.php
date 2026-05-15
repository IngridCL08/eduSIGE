@extends('layouts.financiero')

@section('title', 'Fichas de Pago')

@section('breadcrumb')
    <span class="breadcrumb-separator">·</span>
    <span class="text-carbon-700 font-medium">Fichas de Pago</span>
@endsection

@section('header-actions')
    <a href="{{ route('financiero.reportes.exportar', ['tipo' => 'fichas'] + request()->only(['status','metodo_pago','carrera_id'])) }}"
       class="btn-outline btn-sm">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M12 10v6m0 0l-3-3m3 3l3-3M3 17V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z"/>
        </svg>
        Exportar Excel
    </a>
@endsection

@section('content')

    {{-- Filtros --}}
    <form method="GET" action="{{ route('financiero.fichas.index') }}" class="card mb-5">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <div>
                <label class="form-label">Buscar</label>
                <input name="buscar" value="{{ request('buscar') }}" type="text"
                       class="form-input" placeholder="Folio, nombre, CURP…">
            </div>
            <div>
                <label class="form-label">Estado</label>
                <select name="status" class="form-select">
                    <option value="">Todos</option>
                    @foreach(['pendiente' => 'Pendiente', 'pagado' => 'Pagado', 'vencido' => 'Vencido', 'cancelado' => 'Cancelado'] as $val => $label)
                        <option value="{{ $val }}" {{ request('status') == $val ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="form-label">Método de pago</label>
                <select name="metodo_pago" class="form-select">
                    <option value="">Todos</option>
                    @foreach(['conekta' => 'Conekta', 'paypal' => 'PayPal', 'transferencia' => 'Transferencia', 'efectivo' => 'Efectivo'] as $val => $label)
                        <option value="{{ $val }}" {{ request('metodo_pago') == $val ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="form-label">Carrera</label>
                <select name="carrera_id" class="form-select">
                    <option value="">Todas</option>
                    @foreach($carreras as $carrera)
                        <option value="{{ $carrera->id }}" {{ request('carrera_id') == $carrera->id ? 'selected' : '' }}>
                            {{ $carrera->nombre }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="flex justify-end gap-2 mt-4">
            <a href="{{ route('financiero.fichas.index') }}" class="btn-secondary btn-sm">Limpiar</a>
            <button type="submit" class="btn-primary btn-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                Filtrar
            </button>
        </div>
    </form>

    {{-- Tabla --}}
    <div class="card p-0 overflow-hidden">
        <div class="table-wrapper">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Folio Ficha</th>
                        <th>Aspirante</th>
                        <th>Carrera</th>
                        <th>Monto</th>
                        <th>Emisión</th>
                        <th>Vencimiento</th>
                        <th>Estado</th>
                        <th>Método</th>
                        <th class="text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($fichas as $ficha)
                        <tr>
                            <td>
                                <a href="{{ route('financiero.fichas.show', $ficha) }}"
                                   class="font-mono text-navy-800 font-medium hover:underline">
                                    {{ $ficha->folio_ficha }}
                                </a>
                            </td>
                            <td>
                                <div class="font-medium text-carbon-900">{{ $ficha->aspirante?->nombre_completo }}</div>
                                <div class="text-xs text-carbon-400">{{ $ficha->aspirante?->folio }}</div>
                            </td>
                            <td class="max-w-[160px] truncate">{{ $ficha->aspirante?->carrera?->nombre }}</td>
                            <td class="font-semibold">{{ $ficha->monto_formateado }}</td>
                            <td>{{ $ficha->fecha_emision->format('d/m/Y') }}</td>
                            <td>
                                <span class="{{ $ficha->fecha_vencimiento->isPast() && $ficha->status === 'pendiente' ? 'text-danger font-medium' : '' }}">
                                    {{ $ficha->fecha_vencimiento->format('d/m/Y') }}
                                </span>
                            </td>
                            <td><span class="{{ $ficha->status_color }}">{{ $ficha->status_nombre }}</span></td>
                            <td>{{ $ficha->metodo_pago ? ucfirst($ficha->metodo_pago) : '—' }}</td>
                            <td>
                                <div class="flex items-center justify-end gap-1">
                                    <a href="{{ route('financiero.fichas.show', $ficha) }}"
                                       class="btn-icon btn-sm btn-secondary" title="Ver detalle">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                    </a>
                                    <a href="{{ route('financiero.fichas.pdf', $ficha) }}"
                                       class="btn-icon btn-sm btn-secondary" title="Descargar PDF" target="_blank">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                        </svg>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center py-10 text-carbon-400">
                                No se encontraron fichas con los filtros aplicados.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($fichas->hasPages())
            <div class="px-4 py-3 border-t border-carbon-100">
                {{ $fichas->links() }}
            </div>
        @endif
    </div>

@endsection
