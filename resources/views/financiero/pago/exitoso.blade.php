@extends('layouts.financiero')

@section('title', 'Pago Exitoso')

@section('content')
<div class="max-w-md mx-auto mt-10">
    <div class="card text-center">
        <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
            <svg class="w-9 h-9 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
        <h2 class="text-xl font-bold text-carbon-950 mb-2">¡Pago Procesado!</h2>
        <p class="text-carbon-500 mb-6">El pago se ha registrado exitosamente en el sistema.</p>
        @if($ficha)
            <div class="bg-green-50 rounded-lg p-4 mb-6 text-left">
                <div class="flex justify-between text-sm mb-2">
                    <span class="text-carbon-500">Folio Ficha:</span>
                    <span class="font-mono font-semibold">{{ $ficha->folio_ficha }}</span>
                </div>
                <div class="flex justify-between text-sm mb-2">
                    <span class="text-carbon-500">Aspirante:</span>
                    <span class="font-medium">{{ $ficha->aspirante?->nombre_completo }}</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-carbon-500">Monto:</span>
                    <span class="font-semibold text-green-700">{{ $ficha->monto_formateado }}</span>
                </div>
            </div>
            <a href="{{ route('financiero.fichas.pdf', $ficha) }}"
               class="btn-success btn-sm mb-3" target="_blank">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                Descargar comprobante PDF
            </a>
        @endif
        <a href="{{ route('financiero.fichas.index') }}" class="btn-secondary btn-sm">
            ← Volver a fichas
        </a>
    </div>
</div>
@endsection
