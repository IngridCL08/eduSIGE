@extends('layouts.admin')
@section('title','Bitácora del Sistema')

@section('content')
<form method="GET" class="card mb-5">
    <div class="grid grid-cols-1 sm:grid-cols-4 gap-4">
        <div>
            <label class="form-label">Usuario</label>
            <select name="user_id" class="form-select">
                <option value="">Todos</option>
                @foreach($usuarios as $u)
                <option value="{{ $u->id }}" {{ request('user_id') == $u->id ? 'selected' : '' }}>{{ $u->name }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="form-label">Acción</label>
            <input name="accion" value="{{ request('accion') }}" class="form-input" placeholder="login, crear_aspirante…">
        </div>
        <div>
            <label class="form-label">Fecha</label>
            <input name="fecha" type="date" value="{{ request('fecha') }}" class="form-input">
        </div>
        <div class="flex items-end gap-2">
            <button type="submit" class="btn-primary btn-sm">Filtrar</button>
            <a href="{{ route('admin.bitacora') }}" class="btn-secondary btn-sm">Limpiar</a>
        </div>
    </div>
</form>

<div class="card p-0 overflow-hidden">
    <div class="table-wrapper">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Fecha / Hora</th>
                    <th>Usuario</th>
                    <th>Acción</th>
                    <th>Descripción</th>
                    <th>IP</th>
                </tr>
            </thead>
            <tbody>
                @forelse($registros as $reg)
                <tr>
                    <td class="text-xs text-carbon-500 whitespace-nowrap">{{ $reg->created_at->format('d/m/Y H:i:s') }}</td>
                    <td class="font-medium text-sm">{{ $reg->user?->name ?? '<em class="text-carbon-400">Sistema</em>' }}</td>
                    <td><code class="text-xs bg-navy-50 text-navy-800 px-2 py-0.5 rounded">{{ $reg->accion }}</code></td>
                    <td class="text-sm text-carbon-600 max-w-xs truncate" title="{{ $reg->descripcion }}">{{ $reg->descripcion ?? '—' }}</td>
                    <td class="text-xs text-carbon-400 font-mono">{{ $reg->ip ?? '—' }}</td>
                </tr>
                @empty
                <tr><td colspan="5" class="text-center py-8 text-carbon-400">No hay registros en la bitácora.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($registros->hasPages())
    <div class="px-4 py-3 border-t border-carbon-100">{{ $registros->links() }}</div>
    @endif
</div>
@endsection
