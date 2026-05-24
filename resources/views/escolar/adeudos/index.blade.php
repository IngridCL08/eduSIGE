@extends('layouts.escolar')

@section('title', 'Adeudos')

@section('breadcrumb')
    <span class="breadcrumb-separator">/</span>
    <span class="text-carbon-700 font-medium">Adeudos</span>
@endsection

@section('header-actions')
    <a href="{{ route('escolar.adeudos.create') }}" class="btn-primary btn-sm">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        Nuevo Adeudo
    </a>
@endsection

@section('content')

<div class="card mb-5 p-4">
    <form method="GET" class="flex items-center gap-3 flex-wrap">
        <input type="text" name="buscar" value="{{ request('buscar') }}"
               class="form-input w-64" placeholder="Buscar por matrícula o nombre...">
        <select name="tipo" class="form-select w-48">
            <option value="">— Tipo —</option>
            @foreach($tipos as $key => $label)
            <option value="{{ $key }}" {{ request('tipo') === $key ? 'selected' : '' }}>{{ $label }}</option>
            @endforeach
        </select>
        <select name="status" class="form-select w-36">
            <option value="">— Estado —</option>
            <option value="pendiente" {{ request('status') === 'pendiente' ? 'selected' : '' }}>Pendiente</option>
            <option value="pagado"    {{ request('status') === 'pagado'    ? 'selected' : '' }}>Pagado</option>
            <option value="vencido"   {{ request('status') === 'vencido'   ? 'selected' : '' }}>Vencido</option>
        </select>
        <button type="submit" class="btn-primary btn-sm">Buscar</button>
        <a href="{{ route('escolar.adeudos.index') }}" class="btn-secondary btn-sm">Limpiar</a>
    </form>
</div>

<div class="card p-0">
    <div class="table-wrapper border-0">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Alumno</th>
                    <th>Tipo</th>
                    <th>Concepto</th>
                    <th class="text-right">Monto</th>
                    <th class="text-center">Estado</th>
                    <th>Vencimiento</th>
                    <th class="text-right">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($adeudos as $adeudo)
                <tr>
                    <td>
                        <p class="font-medium text-carbon-950">{{ $adeudo->alumno->nombre_completo }}</p>
                        <p class="text-xs text-carbon-400 font-mono">{{ $adeudo->alumno->matricula }}</p>
                    </td>
                    <td>
                        @if($adeudo->tipo)
                            <span class="badge-info badge text-xs">{{ $tipos[$adeudo->tipo] ?? $adeudo->tipo }}</span>
                        @else
                            <span class="text-carbon-400">—</span>
                        @endif
                    </td>
                    <td class="max-w-xs">
                        <p class="text-sm text-carbon-700 truncate">{{ $adeudo->concepto }}</p>
                    </td>
                    <td class="text-right font-medium text-carbon-950">
                        {{ $adeudo->monto ? $adeudo->monto_formateado : '—' }}
                    </td>
                    <td class="text-center">
                        <span class="{{ $adeudo->status_color }}">{{ $adeudo->status_nombre }}</span>
                    </td>
                    <td class="text-sm text-carbon-500">
                        {{ $adeudo->fecha_vencimiento?->format('d/m/Y') ?? '—' }}
                    </td>
                    <td class="text-right">
                        <div class="flex items-center justify-end gap-1">
                            <a href="{{ route('escolar.adeudos.show', $adeudo) }}" class="btn-secondary btn-sm">Ver</a>
                            @if($adeudo->status === 'pendiente')
                            <form method="POST" action="{{ route('escolar.adeudos.liquidar', $adeudo) }}"
                                  onsubmit="return confirm('¿Marcar como liquidado?')">
                                @csrf @method('PATCH')
                                <button type="submit" class="btn-success btn-sm">Liquidar</button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="text-center py-10 text-carbon-400">No hay adeudos registrados.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="px-6 py-4">{{ $adeudos->links() }}</div>
</div>

@endsection
