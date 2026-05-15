@extends('layouts.portal-aspirante')
@section('title', 'Inicio')

@section('content')

<h1 class="text-xl font-bold text-slate-800 mb-1">Bienvenido, {{ $aspirante->nombre }}</h1>
<p class="text-sm text-slate-500 mb-6">Folio: <span class="font-mono">{{ $aspirante->folio }}</span></p>

{{-- ─── Stepper de proceso ──────────────────────────────── --}}
@php
    $paso = $aspirante->paso_proceso;
    $pasos = [
        1 => ['label' => 'Pago de Ficha',     'desc' => 'Realiza el pago de tu ficha de inscripción'],
        2 => ['label' => 'Entrega de Docs.',   'desc' => 'Sube los documentos requeridos'],
        3 => ['label' => 'Examen de Admisión', 'desc' => 'Presenta tu examen de admisión'],
        4 => ['label' => 'Resultado',          'desc' => 'Revisa tu resultado de admisión'],
        5 => ['label' => 'Inscripción',        'desc' => 'Completa tu inscripción como alumno'],
    ];
@endphp

<div class="bg-white rounded-xl border border-slate-200 p-6 mb-6">
    <h2 class="text-sm font-semibold text-slate-500 uppercase tracking-wider mb-4">Tu proceso de admisión</h2>
    <div class="flex items-start gap-0 overflow-x-auto pb-2">
        @foreach($pasos as $n => $p)
        @php
            $completado = $paso > $n;
            $activo     = $paso === $n;
        @endphp
        <div class="flex flex-col items-center flex-1 min-w-[90px]">
            {{-- Línea conectora --}}
            <div class="flex items-center w-full">
                <div class="flex-1 h-0.5 {{ $n === 1 ? 'invisible' : ($completado ? 'bg-indigo-500' : 'bg-slate-200') }}"></div>
                <div class="w-8 h-8 rounded-full flex items-center justify-center text-sm font-bold flex-shrink-0
                            {{ $completado ? 'bg-indigo-500 text-white' : ($activo ? 'bg-indigo-100 text-indigo-600 ring-2 ring-indigo-500' : 'bg-slate-100 text-slate-400') }}">
                    @if($completado) ✓ @else {{ $n }} @endif
                </div>
                <div class="flex-1 h-0.5 {{ $n === 5 ? 'invisible' : ($completado ? 'bg-indigo-500' : 'bg-slate-200') }}"></div>
            </div>
            <p class="text-xs font-medium mt-2 text-center px-1
                       {{ $completado ? 'text-indigo-600' : ($activo ? 'text-slate-800' : 'text-slate-400') }}">
                {{ $p['label'] }}
            </p>
        </div>
        @endforeach
    </div>
    @if(isset($pasos[$paso]))
    <p class="text-sm text-slate-600 mt-4 text-center">
        <span class="font-medium">Paso actual:</span> {{ $pasos[$paso]['desc'] }}
    </p>
    @endif
</div>

{{-- ─── Tarjetas de resumen ─────────────────────────────── --}}
<div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
    {{-- Ficha --}}
    <div class="bg-white rounded-xl border border-slate-200 p-4">
        <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Ficha de Pago</p>
        @if($aspirante->fichaPago)
            <span class="badge {{ $aspirante->fichaPago->status_color }}">
                {{ $aspirante->fichaPago->status_nombre }}
            </span>
            <p class="text-lg font-bold text-slate-800 mt-2">{{ $aspirante->fichaPago->monto_formateado }}</p>
        @else
            <span class="badge badge-neutral">Sin ficha</span>
            <p class="text-xs text-slate-400 mt-2">Pendiente de generación</p>
        @endif
        <a href="{{ route('portal.aspirante.ficha') }}" class="text-indigo-600 text-xs font-medium mt-3 block hover:underline">
            Ver detalle →
        </a>
    </div>

    {{-- Documentos --}}
    @php
        $tiposReq  = ['acta_nacimiento','certificado_bachillerato','curp','identificacion','foto'];
        $entregados = $aspirante->documentos->pluck('tipo')->toArray();
        $docCount   = count(array_intersect($tiposReq, $entregados));
    @endphp
    <div class="bg-white rounded-xl border border-slate-200 p-4">
        <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Documentos</p>
        <p class="text-lg font-bold text-slate-800">{{ $docCount }} / {{ count($tiposReq) }}</p>
        <div class="mt-2 h-2 bg-slate-100 rounded-full overflow-hidden">
            <div class="h-full bg-indigo-500 rounded-full transition-all"
                 style="width: {{ count($tiposReq) > 0 ? round($docCount / count($tiposReq) * 100) : 0 }}%"></div>
        </div>
        <a href="{{ route('portal.aspirante.documentos') }}" class="text-indigo-600 text-xs font-medium mt-3 block hover:underline">
            Gestionar →
        </a>
    </div>

    {{-- Carrera --}}
    <div class="bg-white rounded-xl border border-slate-200 p-4">
        <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Carrera Solicitada</p>
        <p class="font-semibold text-slate-800 text-sm leading-tight">{{ $aspirante->carrera?->nombre ?? '—' }}</p>
        <p class="text-xs text-slate-400 mt-1">{{ $aspirante->periodo?->nombre ?? '—' }}</p>
        <p class="mt-3">
            <span class="badge {{ $aspirante->status_color }}">{{ $aspirante->status_nombre }}</span>
        </p>
    </div>
</div>

{{-- Examen si aplica --}}
@if($aspirante->examenAdmision)
<div class="bg-white rounded-xl border border-slate-200 p-4">
    <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-3">Resultado de Examen</p>
    <div class="flex items-center gap-4">
        <div>
            <p class="text-2xl font-bold text-slate-800">{{ $aspirante->examenAdmision->calificacion ?? '—' }}</p>
            <p class="text-xs text-slate-400">Calificación</p>
        </div>
        <div>
            <span class="badge {{ $aspirante->examenAdmision->resultado_color }}">
                {{ $aspirante->examenAdmision->resultado_nombre }}
            </span>
        </div>
    </div>
</div>
@endif

@endsection
