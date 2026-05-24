@extends('layouts.financiero')
@section('title', 'Comprobantes de Transferencia')

@section('breadcrumb')
    <span class="breadcrumb-separator">/</span>
    <span class="text-carbon-700 font-medium">Comprobantes</span>
@endsection

@section('content')

@if(session('success'))
<div class="alert-success mb-4">{{ session('success') }}</div>
@endif

{{-- Filtros --}}
<form method="GET" class="card mb-5">
    <div class="flex gap-3 items-end flex-wrap">
        <div class="flex-1 min-w-[160px]">
            <label class="form-label">Estado</label>
            <select name="status" class="form-select">
                <option value="">Todos</option>
                <option value="pendiente" {{ request('status') === 'pendiente' ? 'selected' : '' }}>Pendientes</option>
                <option value="aprobado"  {{ request('status') === 'aprobado'  ? 'selected' : '' }}>Aprobados</option>
                <option value="rechazado" {{ request('status') === 'rechazado' ? 'selected' : '' }}>Rechazados</option>
            </select>
        </div>
        <div class="flex gap-2">
            <button type="submit" class="btn-primary btn-sm">Filtrar</button>
            <a href="{{ route('financiero.comprobantes.index') }}" class="btn-secondary btn-sm">Limpiar</a>
        </div>
    </div>
</form>

<div class="card p-0 overflow-hidden">
    <div class="table-wrapper">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Aspirante</th>
                    <th>Folio Ficha</th>
                    <th>Comprobante</th>
                    <th>Fecha envío</th>
                    <th>Estado</th>
                    <th>Revisado por</th>
                    <th class="text-right">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($comprobantes as $comp)
                <tr>
                    <td>
                        <div class="font-medium text-carbon-900">
                            {{ $comp->fichaPago?->aspirante?->nombre_completo ?? '—' }}
                        </div>
                        <div class="text-xs text-carbon-400 font-mono">
                            {{ $comp->fichaPago?->aspirante?->folio ?? '' }}
                        </div>
                    </td>
                    <td class="font-mono text-xs">{{ $comp->fichaPago?->folio_ficha ?? '—' }}</td>
                    <td>
                        <a href="{{ $comp->url }}" target="_blank"
                           class="text-sm hover:underline truncate max-w-[180px] block"
                           style="color: #5b35c0">
                            {{ $comp->nombre_original ?? 'Ver archivo' }}
                        </a>
                        @if($comp->observaciones && $comp->status === 'rechazado')
                        <p class="text-xs text-red-500 mt-0.5">{{ $comp->observaciones }}</p>
                        @endif
                    </td>
                    <td class="text-sm whitespace-nowrap text-carbon-500">
                        {{ $comp->created_at->format('d/m/Y H:i') }}
                    </td>
                    <td><span class="badge {{ $comp->status_color }}">{{ $comp->status_nombre }}</span></td>
                    <td class="text-sm text-carbon-500">
                        {{ $comp->revisadoPor?->name ?? '—' }}
                        @if($comp->revisado_at)
                        <div class="text-xs text-carbon-400">{{ $comp->revisado_at->format('d/m/Y') }}</div>
                        @endif
                    </td>
                    <td class="text-right">
                        @if($comp->status === 'pendiente')
                        <div class="flex justify-end gap-2" x-data="{ open: false }">
                            <form method="POST" action="{{ route('financiero.comprobantes.aprobar', $comp) }}">
                                @csrf @method('PATCH')
                                <button class="btn-success btn-sm"
                                        onclick="return confirm('¿Aprobar este comprobante y marcar la ficha como pagada?')">
                                    Aprobar
                                </button>
                            </form>
                            <button @click="open = !open" class="btn-danger btn-sm">Rechazar</button>

                            {{-- Modal rechazo --}}
                            <div x-show="open" x-cloak
                                 class="fixed inset-0 bg-black/40 flex items-center justify-center z-50"
                                 @keydown.escape.window="open = false">
                                <div class="bg-white rounded-xl p-6 w-full max-w-md shadow-xl" @click.stop>
                                    <h3 class="font-semibold text-carbon-900 mb-4">Rechazar comprobante</h3>
                                    <p class="text-sm text-carbon-500 mb-4">
                                        El aspirante verá este motivo en su portal y podrá subir un nuevo comprobante.
                                    </p>
                                    <form method="POST"
                                          action="{{ route('financiero.comprobantes.rechazar', $comp) }}">
                                        @csrf @method('PATCH')
                                        <div class="mb-4">
                                            <label class="form-label">
                                                Motivo de rechazo <span class="text-danger">*</span>
                                            </label>
                                            <textarea name="observaciones" rows="3"
                                                      class="form-input"
                                                      placeholder="Ej. El comprobante no corresponde al monto indicado…"
                                                      required></textarea>
                                        </div>
                                        <div class="flex justify-end gap-2">
                                            <button type="button" @click="open = false"
                                                    class="btn-secondary btn-sm">Cancelar</button>
                                            <button type="submit" class="btn-danger btn-sm">
                                                Confirmar rechazo
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        @else
                            <span class="text-xs text-carbon-400">—</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center py-10 text-carbon-400">
                        No hay comprobantes con los filtros aplicados.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($comprobantes->hasPages())
    <div class="px-4 py-3 border-t border-carbon-100">{{ $comprobantes->links() }}</div>
    @endif
</div>

@endsection
