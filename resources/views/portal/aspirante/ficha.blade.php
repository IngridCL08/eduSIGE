@extends('layouts.portal-aspirante')
@section('title', 'Mi Ficha de Pago')

@section('content')

<h2 class="text-xl font-bold text-slate-800 mb-6">Mi Ficha de Pago</h2>

@if(! $ficha)
<div class="bg-white rounded-xl border border-slate-200 p-8 text-center text-slate-500">
    <p class="text-lg font-medium mb-2">No tienes una ficha de pago generada</p>
    <p class="text-sm">Contacta al área de Control Escolar para que te la asignen.</p>
</div>
@else

{{-- Datos de la ficha --}}
<div class="bg-white rounded-xl border border-slate-200 p-6 mb-6">
    <div class="flex items-start justify-between mb-4">
        <div>
            <p class="text-xs text-slate-400">Folio de ficha</p>
            <p class="text-lg font-mono font-bold text-slate-800">{{ $ficha->folio_ficha }}</p>
        </div>
        <span class="badge {{ $ficha->status_color }} text-sm px-3 py-1">{{ $ficha->status_nombre }}</span>
    </div>

    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 text-sm">
        <div>
            <p class="text-slate-400 text-xs">Monto</p>
            <p class="font-bold text-slate-800 text-lg">{{ $ficha->monto_formateado }}</p>
        </div>
        <div>
            <p class="text-slate-400 text-xs">Concepto</p>
            <p class="font-medium text-slate-700">{{ $ficha->concepto }}</p>
        </div>
        <div>
            <p class="text-slate-400 text-xs">Fecha emisión</p>
            <p class="font-medium text-slate-700">{{ $ficha->fecha_emision->format('d/m/Y') }}</p>
        </div>
        <div>
            <p class="text-slate-400 text-xs">Vencimiento</p>
            <p class="font-medium {{ $ficha->fecha_vencimiento->isPast() ? 'text-red-600' : 'text-slate-700' }}">
                {{ $ficha->fecha_vencimiento->format('d/m/Y') }}
            </p>
        </div>
    </div>

    @if($ficha->status === 'pagado')
    <div class="mt-4 p-3 bg-green-50 border border-green-200 rounded-lg text-green-700 text-sm">
        ✓ Pago confirmado el {{ $ficha->fecha_pago?->format('d/m/Y H:i') }}
        — Método: {{ $ficha->metodo_pago }}
        @if($ficha->referencia_pago) — Ref: {{ $ficha->referencia_pago }} @endif
    </div>
    @endif
</div>

{{-- Acciones de pago (solo si pendiente y vigente) --}}
@if($ficha->status === 'pendiente' && ! $ficha->fecha_vencimiento->isPast())
<div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6">

    {{-- Pago en línea --}}
    <div class="bg-white rounded-xl border border-slate-200 p-5">
        <h3 class="font-semibold text-slate-800 mb-3">Pagar en línea</h3>
        <p class="text-sm text-slate-500 mb-4">Paga de forma segura con tarjeta de crédito o débito.</p>
        <form method="POST" action="{{ route('portal.aspirante.ficha.pagar') }}" class="space-y-3">
            @csrf
            <input type="hidden" name="gateway" value="conekta">
            <button class="btn-primary w-full justify-center">
                Pagar con tarjeta (Conekta)
            </button>
        </form>
        <form method="POST" action="{{ route('portal.aspirante.ficha.pagar') }}" class="mt-2">
            @csrf
            <input type="hidden" name="gateway" value="paypal">
            <button class="btn-secondary w-full justify-center mt-2">
                Pagar con PayPal
            </button>
        </form>
    </div>

    {{-- Transferencia bancaria --}}
    <div class="bg-white rounded-xl border border-slate-200 p-5">
        <h3 class="font-semibold text-slate-800 mb-3">Transferencia bancaria</h3>

        @php
            $clabe  = config('app.edusige.clabe_bancaria', '000000000000000000');
            $banco  = config('app.edusige.banco', 'Institución');
            $ref    = $ficha->referencia_bancaria ?? $ficha->folio_ficha;
        @endphp

        <div class="text-sm space-y-2 mb-4">
            <div class="flex justify-between">
                <span class="text-slate-500">Banco:</span>
                <span class="font-medium text-slate-800">{{ $banco }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-slate-500">CLABE:</span>
                <span class="font-mono font-medium text-slate-800">{{ $clabe }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-slate-500">Referencia:</span>
                <span class="font-mono font-bold text-indigo-700">{{ $ref }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-slate-500">Monto exacto:</span>
                <span class="font-bold text-slate-800">{{ $ficha->monto_formateado }}</span>
            </div>
        </div>

        <p class="text-xs text-slate-400 mb-3">Sube tu comprobante después de realizar la transferencia.</p>

        <form method="POST" action="{{ route('portal.aspirante.ficha.comprobante') }}"
              enctype="multipart/form-data">
            @csrf
            <input type="file" name="comprobante" accept=".jpg,.jpeg,.png,.pdf"
                   class="block w-full text-xs text-slate-600 file:mr-3 file:py-1.5 file:px-3
                          file:rounded file:border-0 file:text-xs file:bg-indigo-50
                          file:text-indigo-700 hover:file:bg-indigo-100 mb-2">
            @error('comprobante')
                <p class="text-red-500 text-xs mb-2">{{ $message }}</p>
            @enderror
            <button class="btn-primary btn-sm w-full justify-center">
                Subir comprobante
            </button>
        </form>
    </div>
</div>
@endif

{{-- Comprobantes enviados --}}
@if($aspirante->fichaPago?->comprobantes?->isNotEmpty())
<div class="bg-white rounded-xl border border-slate-200 p-5">
    <h3 class="font-semibold text-slate-800 mb-3">Comprobantes enviados</h3>
    <div class="space-y-2">
        @foreach($aspirante->fichaPago->comprobantes as $comp)
        <div class="flex items-center justify-between p-3 bg-slate-50 rounded-lg text-sm">
            <div>
                <p class="font-medium text-slate-700">{{ $comp->nombre_original }}</p>
                <p class="text-xs text-slate-400">{{ $comp->created_at->format('d/m/Y H:i') }}</p>
            </div>
            <div class="flex items-center gap-3">
                <span class="badge {{ $comp->status_color }}">{{ $comp->status_nombre }}</span>
                <a href="{{ $comp->url }}" target="_blank"
                   class="text-indigo-600 text-xs hover:underline">Ver</a>
            </div>
        </div>
        @if($comp->observaciones && $comp->status === 'rechazado')
        <p class="text-xs text-red-600 px-3">Motivo de rechazo: {{ $comp->observaciones }}</p>
        @endif
        @endforeach
    </div>
</div>
@endif

@endif
@endsection
