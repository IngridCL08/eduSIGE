@extends('layouts.escolar')
@section('title','Historial — ' . $aspirante->nombre_completo)

@section('breadcrumb')
    <a href="{{ route('escolar.aspirantes.index') }}">Aspirantes</a>
    <span class="breadcrumb-separator">/</span>
    <a href="{{ route('escolar.aspirantes.show', $aspirante) }}">{{ $aspirante->folio }}</a>
    <span class="breadcrumb-separator">/</span>
    <span class="text-carbon-700 font-medium">Historial</span>
@endsection

@section('content')
<div class="max-w-3xl">

    {{-- Perfil compacto --}}
    <div class="card mb-5">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 bg-navy-100 rounded-full flex items-center justify-center flex-shrink-0">
                <span class="text-navy-800 font-bold">
                    {{ strtoupper(substr($aspirante->nombre, 0, 1) . substr($aspirante->apellido_paterno, 0, 1)) }}
                </span>
            </div>
            <div>
                <p class="font-semibold text-carbon-900">{{ $aspirante->nombre_completo }}</p>
                <p class="text-xs text-carbon-400 font-mono">{{ $aspirante->folio }}</p>
            </div>
            <div class="ml-auto">
                <span class="badge {{ $aspirante->status_color }}">{{ $aspirante->status_nombre }}</span>
            </div>
        </div>
    </div>

    {{-- Timeline de bitácora --}}
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Historial de Cambios</h3>
            <span class="text-sm text-carbon-400">{{ $registros->count() }} eventos</span>
        </div>

        @forelse($registros as $reg)
        <div class="flex gap-4 py-4 border-b border-carbon-100 last:border-0">
            <div class="flex-shrink-0 w-8 h-8 bg-navy-50 rounded-full flex items-center justify-center mt-0.5">
                <svg class="w-4 h-4 text-navy-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
            </div>
            <div class="flex-1 min-w-0">
                <div class="flex items-start justify-between gap-2">
                    <div>
                        <code class="text-xs bg-navy-50 text-navy-800 px-2 py-0.5 rounded">{{ $reg->accion }}</code>
                        @if($reg->descripcion)
                        <p class="text-sm text-carbon-600 mt-1">{{ $reg->descripcion }}</p>
                        @endif
                    </div>
                    <span class="text-xs text-carbon-400 whitespace-nowrap flex-shrink-0">
                        {{ $reg->created_at->format('d/m/Y H:i') }}
                    </span>
                </div>
                <p class="text-xs text-carbon-400 mt-1">
                    Por: {{ $reg->user?->name ?? 'Sistema' }}
                    @if($reg->ip) · IP: {{ $reg->ip }} @endif
                </p>
                @if($reg->datos_antes || $reg->datos_despues)
                <details class="mt-2">
                    <summary class="text-xs text-navy-600 cursor-pointer hover:underline">Ver datos del cambio</summary>
                    <div class="mt-2 grid grid-cols-2 gap-2 text-xs">
                        @if($reg->datos_antes)
                        <div class="bg-red-50 rounded p-2">
                            <p class="font-semibold text-red-700 mb-1">Antes</p>
                            <pre class="whitespace-pre-wrap font-mono text-red-800">{{ json_encode($reg->datos_antes, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                        </div>
                        @endif
                        @if($reg->datos_despues)
                        <div class="bg-green-50 rounded p-2">
                            <p class="font-semibold text-green-700 mb-1">Después</p>
                            <pre class="whitespace-pre-wrap font-mono text-green-800">{{ json_encode($reg->datos_despues, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                        </div>
                        @endif
                    </div>
                </details>
                @endif
            </div>
        </div>
        @empty
        <div class="py-12 text-center text-carbon-400">
            <svg class="w-10 h-10 mx-auto mb-3 text-carbon-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
            </svg>
            <p class="text-sm">No hay registros de cambios para este aspirante.</p>
        </div>
        @endforelse
    </div>

</div>
@endsection
