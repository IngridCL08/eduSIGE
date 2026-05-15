@extends('layouts.financiero')

@section('title', 'Pago Cancelado')

@section('content')
<div class="max-w-md mx-auto mt-10">
    <div class="card text-center">
        <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
            <svg class="w-9 h-9 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
        <h2 class="text-xl font-bold text-carbon-950 mb-2">Pago Cancelado</h2>
        <p class="text-carbon-500 mb-6">El proceso de pago fue cancelado. La ficha sigue pendiente de pago.</p>
        @if($ficha)
            <p class="text-sm text-carbon-600 mb-6">
                Ficha: <span class="font-mono font-semibold">{{ $ficha->folio_ficha }}</span>
                — Vence el {{ $ficha->fecha_vencimiento->format('d/m/Y') }}
            </p>
        @endif
        <a href="{{ route('financiero.fichas.index') }}" class="btn-secondary btn-sm">
            ← Volver a fichas
        </a>
    </div>
</div>
@endsection
