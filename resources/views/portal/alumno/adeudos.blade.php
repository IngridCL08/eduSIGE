@extends('layouts.portal-alumno')
@section('title', 'Mis Adeudos')

@section('content')

<h2 class="text-xl font-bold text-slate-800 mb-6">Mis Adeudos</h2>

{{-- Resumen --}}
<div class="grid grid-cols-2 gap-4 mb-6">
    <div class="bg-white rounded-xl border border-slate-200 p-4 text-center">
        <p class="text-2xl font-bold text-amber-600">${{ number_format($totalPendiente, 2) }}</p>
        <p class="text-xs text-slate-400 mt-1">Total pendiente</p>
    </div>
    <div class="bg-white rounded-xl border border-slate-200 p-4 text-center">
        <p class="text-2xl font-bold text-red-600">${{ number_format($totalVencido, 2) }}</p>
        <p class="text-xs text-slate-400 mt-1">Total vencido</p>
    </div>
</div>

@forelse($adeudos as $adeudo)
<div class="bg-white rounded-xl border {{ $adeudo->status === 'vencido' ? 'border-red-200' : ($adeudo->status === 'pendiente' ? 'border-amber-200' : 'border-slate-200') }} p-5 mb-3">
    <div class="flex items-start justify-between">
        <div>
            <p class="font-semibold text-slate-800">{{ $adeudo->concepto }}</p>
            <p class="text-xs text-slate-400 mt-0.5">
                {{ $adeudo->periodo?->nombre ?? 'Sin período' }}
                @if($adeudo->fecha_vencimiento)
                    · Vence: {{ $adeudo->fecha_vencimiento->format('d/m/Y') }}
                    @if($adeudo->fecha_vencimiento->isPast() && $adeudo->status === 'pendiente')
                        <span class="text-red-500 font-medium">(vencido)</span>
                    @endif
                @endif
            </p>
        </div>
        <div class="text-right">
            <p class="text-lg font-bold text-slate-800">{{ $adeudo->monto_formateado }}</p>
            <span class="badge {{ $adeudo->status_color }}">{{ $adeudo->status_nombre }}</span>
        </div>
    </div>

    @if($adeudo->status === 'pagado' && $adeudo->fecha_pago)
    <p class="text-xs text-green-600 mt-2">
        Pagado el {{ $adeudo->fecha_pago->format('d/m/Y') }}
        @if($adeudo->referencia_pago) — Ref: {{ $adeudo->referencia_pago }} @endif
    </p>
    @endif
</div>
@empty
<div class="bg-white rounded-xl border border-slate-200 p-8 text-center">
    <p class="text-slate-400 font-medium">No tienes adeudos registrados.</p>
</div>
@endforelse

<p class="text-xs text-slate-400 mt-4">
    Para aclarar adeudos o registrar pagos, acude a la ventanilla de Recursos Financieros.
</p>

@endsection
