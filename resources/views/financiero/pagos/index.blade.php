@extends('layouts.financiero')

@section('title', 'Pagos de Alumnos')

@section('breadcrumb')
    <span class="breadcrumb-separator">/</span>
    <span class="text-carbon-700 font-medium">Pagos de Alumnos</span>
@endsection

@section('content')

{{-- KPIs --}}
<div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
    <div class="stat-card">
        <div class="stat-card-icon bg-amber-100">
            <svg class="w-6 h-6 text-amber-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
        <div>
            <p class="stat-card-value text-amber-700">{{ $countPendiente }}</p>
            <p class="stat-card-label">Pagos pendientes</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-card-icon bg-red-100">
            <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
        <div>
            <p class="stat-card-value text-red-600">${{ number_format($totalPendiente, 2) }}</p>
            <p class="stat-card-label">Monto pendiente</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-card-icon bg-green-100">
            <svg class="w-6 h-6 text-green-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
        <div>
            <p class="stat-card-value text-green-700">${{ number_format($totalPagado, 2) }}</p>
            <p class="stat-card-label">Total validado</p>
        </div>
    </div>
</div>

{{-- Filtros --}}
<div class="card mb-5 p-4">
    <form method="GET" class="flex items-center gap-3 flex-wrap">
        <input type="text" name="buscar" value="{{ request('buscar') }}"
               class="form-input w-64" placeholder="Matrícula o nombre del alumno...">
        <select name="status" class="form-select w-36">
            <option value="">— Estado —</option>
            <option value="pendiente" {{ request('status') === 'pendiente' ? 'selected' : '' }}>Pendiente</option>
            <option value="pagado"    {{ request('status') === 'pagado'    ? 'selected' : '' }}>Pagado</option>
            <option value="vencido"   {{ request('status') === 'vencido'   ? 'selected' : '' }}>Vencido</option>
        </select>
        <select name="tipo" class="form-select w-48">
            <option value="">— Tipo —</option>
            @foreach($tipos as $key => $label)
            <option value="{{ $key }}" {{ request('tipo') === $key ? 'selected' : '' }}>{{ $label }}</option>
            @endforeach
        </select>
        <button type="submit" class="btn-primary btn-sm">Buscar</button>
        <a href="{{ route('financiero.pagos.index') }}" class="btn-secondary btn-sm">Limpiar</a>
    </form>
</div>

{{-- Tabla --}}
<div class="card p-0">
    <div class="table-wrapper border-0">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Alumno</th>
                    <th>Concepto</th>
                    <th>Tipo</th>
                    <th class="text-right">Monto</th>
                    <th class="text-center">Estado</th>
                    <th>Vencimiento</th>
                    <th>Validado por</th>
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
                    <td class="max-w-xs">
                        <p class="text-sm text-carbon-800 truncate">{{ $adeudo->concepto }}</p>
                    </td>
                    <td>
                        @if($adeudo->tipo)
                        <span class="badge-info badge text-xs">{{ $tipos[$adeudo->tipo] ?? $adeudo->tipo }}</span>
                        @else
                        <span class="text-carbon-400 text-xs">—</span>
                        @endif
                    </td>
                    <td class="text-right font-semibold text-carbon-950">
                        {{ $adeudo->monto ? '$' . number_format($adeudo->monto, 2) : '—' }}
                    </td>
                    <td class="text-center">
                        <span class="{{ $adeudo->status_color }}">{{ $adeudo->status_nombre }}</span>
                    </td>
                    <td class="text-sm text-carbon-500">
                        {{ $adeudo->fecha_vencimiento?->format('d/m/Y') ?? '—' }}
                    </td>
                    <td class="text-sm">
                        @if($adeudo->status === 'pagado')
                            <p class="text-carbon-700">{{ $adeudo->validadoPor?->name ?? $adeudo->registrado_por_nombre ?? '—' }}</p>
                            <p class="text-xs text-carbon-400">{{ $adeudo->fecha_pago?->format('d/m/Y') }}</p>
                            @if($adeudo->metodo_pago)
                            <p class="text-xs text-carbon-500">{{ $metodos[$adeudo->metodo_pago] ?? $adeudo->metodo_pago }}</p>
                            @endif
                        @else
                            <span class="text-carbon-400">—</span>
                        @endif
                    </td>
                    <td class="text-right">
                        @if($adeudo->status !== 'pagado')
                        {{-- Botón validar con modal --}}
                        <button type="button"
                                class="btn-success btn-sm"
                                onclick="abrirModalValidar({{ $adeudo->id }}, '{{ addslashes($adeudo->concepto) }}', '{{ $adeudo->alumno->nombre_completo }}')">
                            Validar pago
                        </button>
                        @else
                        <span class="text-xs text-carbon-400">Validado</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr><td colspan="8" class="text-center py-10 text-carbon-400">No hay adeudos registrados.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="px-6 py-4">{{ $adeudos->links() }}</div>
</div>

{{-- Modal validar pago --}}
<div id="modal-validar"
     class="fixed inset-0 bg-black/40 flex items-center justify-center z-50 hidden"
     x-data="{ open: false }" x-show="open">
</div>

<div id="modal-validar-wrapper" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/40">
    <div class="bg-white rounded-xl shadow-xl w-full max-w-md mx-4 p-6">
        <h3 class="text-base font-bold text-carbon-950 mb-1">Validar Pago</h3>
        <p class="text-sm text-carbon-500 mb-4">
            Alumno: <strong id="modal-alumno-nombre" class="text-carbon-800"></strong><br>
            Concepto: <strong id="modal-concepto" class="text-carbon-800"></strong>
        </p>

        <form id="form-validar" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf @method('PATCH')

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="form-label">Fecha de pago <span class="text-danger">*</span></label>
                    <input type="date" name="fecha_pago" class="form-input"
                           value="{{ date('Y-m-d') }}" required max="{{ date('Y-m-d') }}">
                </div>
                <div>
                    <label class="form-label">Método <span class="text-danger">*</span></label>
                    <select name="metodo_pago" class="form-select" required>
                        @foreach($metodos as $key => $label)
                        <option value="{{ $key }}">{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div>
                <label class="form-label">Referencia / Folio</label>
                <input type="text" name="referencia_pago" class="form-input"
                       placeholder="Número de operación, folio, etc.">
            </div>

            <div>
                <label class="form-label">Comprobante (PDF / imagen)</label>
                <input type="file" name="comprobante" class="form-input text-sm"
                       accept=".pdf,.jpg,.jpeg,.png">
                <p class="form-help">Opcional. Máx. 4 MB.</p>
            </div>

            <div>
                <label class="form-label">Observaciones</label>
                <textarea name="observaciones" rows="2" class="form-input"
                          placeholder="Notas adicionales sobre el pago..."></textarea>
            </div>

            <div class="flex gap-3 pt-1">
                <button type="submit" class="btn-success flex-1">Confirmar validación</button>
                <button type="button" onclick="cerrarModal()" class="btn-secondary">Cancelar</button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
const baseUrl = '{{ url('/financiero/pagos') }}';

function abrirModalValidar(id, concepto, alumno) {
    document.getElementById('modal-alumno-nombre').textContent = alumno;
    document.getElementById('modal-concepto').textContent = concepto;
    document.getElementById('form-validar').action = baseUrl + '/' + id + '/validar';
    document.getElementById('modal-validar-wrapper').classList.remove('hidden');
    document.getElementById('modal-validar-wrapper').classList.add('flex');
}

function cerrarModal() {
    document.getElementById('modal-validar-wrapper').classList.add('hidden');
    document.getElementById('modal-validar-wrapper').classList.remove('flex');
}

document.getElementById('modal-validar-wrapper').addEventListener('click', function(e) {
    if (e.target === this) cerrarModal();
});
</script>
@endpush
