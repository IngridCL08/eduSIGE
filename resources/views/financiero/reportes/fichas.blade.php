@extends('layouts.financiero')
@section('title','Fichas de Pago — Reporte')

@section('breadcrumb')
    <span class="breadcrumb-separator">/</span>
    <span class="text-carbon-700 font-medium">Reporte de Fichas</span>
@endsection

@section('header-actions')
    <a href="{{ route('financiero.reportes.exportar', array_merge(request()->all(), ['tipo'=>'fichas'])) }}"
       class="btn-secondary btn-sm">
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
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-6 gap-4">
        <div class="lg:col-span-2">
            <label class="form-label">Buscar aspirante</label>
            <input name="buscar" value="{{ request('buscar') }}" class="form-input" placeholder="Folio, nombre…">
        </div>
        <div>
            <label class="form-label">Período</label>
            <select name="periodo_id" class="form-select">
                <option value="">Todos</option>
                @foreach($periodos as $p)
                <option value="{{ $p->id }}" {{ request('periodo_id') == $p->id ? 'selected' : '' }}>{{ $p->nombre }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="form-label">Estado</label>
            <select name="status" class="form-select">
                <option value="">Todos</option>
                @foreach(['pendiente'=>'Pendiente','pagado'=>'Pagada','vencido'=>'Vencida','cancelado'=>'Cancelada'] as $k=>$v)
                <option value="{{ $k }}" {{ request('status') === $k ? 'selected' : '' }}>{{ $v }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="form-label">Método pago</label>
            <select name="metodo_pago" class="form-select">
                <option value="">Todos</option>
                @foreach(['conekta'=>'Conekta','paypal'=>'PayPal','transferencia'=>'Transferencia','efectivo'=>'Efectivo'] as $k=>$v)
                <option value="{{ $k }}" {{ request('metodo_pago') === $k ? 'selected' : '' }}>{{ $v }}</option>
                @endforeach
            </select>
        </div>
        <div class="flex items-end gap-2">
            <button type="submit" class="btn-primary btn-sm flex-1 justify-center">Filtrar</button>
            <a href="{{ route('financiero.reportes.fichas') }}" class="btn-secondary btn-sm">Limpiar</a>
        </div>
    </div>
</form>

{{-- Resumen rápido --}}
<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-5">
    @foreach([
        ['Total',      $resumen['total'],     'text-carbon-900',  'bg-carbon-100'],
        ['Pagadas',    $resumen['pagadas'],    'text-green-700',   'bg-green-100'],
        ['Pendientes', $resumen['pendientes'], 'text-amber-700',   'bg-amber-100'],
        ['Vencidas',   $resumen['vencidas'],   'text-red-700',     'bg-red-100'],
    ] as [$label, $count, $textCls, $bgCls])
    <div class="rounded-xl {{ $bgCls }} px-4 py-3">
        <p class="text-2xl font-black {{ $textCls }}">{{ number_format($count) }}</p>
        <p class="text-xs text-carbon-500 mt-0.5">{{ $label }}</p>
    </div>
    @endforeach
</div>

{{-- Tabla --}}
<div class="card p-0 overflow-hidden">
    <div class="table-wrapper">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Folio Ficha</th>
                    <th>Aspirante</th>
                    <th>Carrera</th>
                    <th>Período</th>
                    <th>Monto</th>
                    <th>Estado</th>
                    <th>Método</th>
                    <th>Emisión</th>
                    <th>Vencimiento</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse($fichas as $ficha)
                <tr>
                    <td class="font-mono text-xs font-bold">{{ $ficha->folio_ficha }}</td>
                    <td>
                        <div class="font-medium text-sm">{{ $ficha->aspirante->nombre_completo }}</div>
                        <div class="text-xs text-carbon-400 font-mono">{{ $ficha->aspirante->folio }}</div>
                    </td>
                    <td class="text-sm">{{ $ficha->aspirante->carrera?->clave ?? '—' }}</td>
                    <td class="text-sm whitespace-nowrap">{{ $ficha->aspirante->periodo?->nombre ?? '—' }}</td>
                    <td class="font-semibold">{{ $ficha->monto_formateado }}</td>
                    <td><span class="badge {{ $ficha->status_color }}">{{ $ficha->status_nombre }}</span></td>
                    <td class="text-sm">{{ $ficha->metodo_pago ? ucfirst($ficha->metodo_pago) : '—' }}</td>
                    <td class="text-xs text-carbon-500 whitespace-nowrap">{{ $ficha->fecha_emision->format('d/m/Y') }}</td>
                    <td class="text-xs text-carbon-500 whitespace-nowrap">{{ $ficha->fecha_vencimiento->format('d/m/Y') }}</td>
                    <td class="text-right">
                        <a href="{{ route('financiero.fichas.show', $ficha) }}" class="btn-outline btn-sm">Ver</a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="10" class="text-center py-10 text-carbon-400">
                        No hay fichas con los filtros aplicados.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($fichas->hasPages())
    <div class="px-4 py-3 border-t border-carbon-100">{{ $fichas->links() }}</div>
    @endif
</div>
@endsection
