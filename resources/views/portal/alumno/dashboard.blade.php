@extends('layouts.portal-alumno')
@section('title', 'Inicio')

@section('content')

<h1 class="text-xl font-bold text-slate-800 mb-1">Bienvenido, {{ $alumno->aspirante?->nombre ?? $alumno->matricula }}</h1>
<p class="text-sm text-slate-500 mb-6">Matrícula: <span class="font-mono font-semibold">{{ $alumno->matricula }}</span></p>

{{-- Stats --}}
<div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-6">
    <div class="bg-white rounded-xl border border-slate-200 p-4 text-center">
        <p class="text-2xl font-bold text-slate-800">{{ $alumno->promedio_general ?? '—' }}</p>
        <p class="text-xs text-slate-400 mt-1">Promedio general</p>
    </div>
    <div class="bg-white rounded-xl border border-slate-200 p-4 text-center">
        <p class="text-2xl font-bold text-slate-800">{{ $alumno->creditos_acumulados }}</p>
        <p class="text-xs text-slate-400 mt-1">Créditos acumulados</p>
    </div>
    <div class="bg-white rounded-xl border border-slate-200 p-4 text-center">
        <p class="text-2xl font-bold text-slate-800">{{ $avanceCreditos }}%</p>
        <p class="text-xs text-slate-400 mt-1">Avance de carrera</p>
    </div>
    <div class="bg-white rounded-xl border border-slate-200 p-4 text-center">
        <span class="badge {{ $alumno->status_color }}">{{ $alumno->status_nombre }}</span>
        <p class="text-xs text-slate-400 mt-2">Estatus</p>
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-5">

    {{-- Carrera --}}
    <div class="bg-white rounded-xl border border-slate-200 p-5">
        <h3 class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-3">Mi Carrera</h3>
        <p class="font-bold text-slate-800 text-base">{{ $alumno->carrera?->nombre ?? '—' }}</p>
        <p class="text-sm text-slate-500 mt-1">Clave: <span class="font-mono">{{ $alumno->carrera?->clave ?? '—' }}</span></p>
        @if($alumno->carrera?->creditos_totales)
        <div class="mt-3">
            <div class="flex justify-between text-xs text-slate-400 mb-1">
                <span>Créditos</span>
                <span>{{ $alumno->creditos_acumulados }} / {{ $alumno->carrera->creditos_totales }}</span>
            </div>
            <div class="h-2 bg-slate-100 rounded-full overflow-hidden">
                <div class="h-full bg-emerald-500 rounded-full" style="width: {{ $avanceCreditos }}%"></div>
            </div>
        </div>
        @endif
        <p class="text-xs text-slate-400 mt-3">Período de ingreso: {{ $alumno->periodoIngreso?->nombre ?? '—' }}</p>
    </div>

    {{-- Historial reciente --}}
    <div class="bg-white rounded-xl border border-slate-200 p-5">
        <h3 class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-3">Últimas materias</h3>
        @forelse($alumno->historial as $h)
        <div class="flex items-center justify-between py-2 border-b border-slate-50 last:border-0 text-sm">
            <div>
                <p class="font-medium text-slate-700">{{ $h->materia }}</p>
                <p class="text-xs text-slate-400">{{ $h->periodo?->nombre ?? '—' }}</p>
            </div>
            <span class="font-bold text-slate-800">{{ $h->calificacion ?? '—' }}</span>
        </div>
        @empty
        <p class="text-sm text-slate-400">Sin materias registradas aún.</p>
        @endforelse
        <a href="{{ route('portal.alumno.historial') }}"
           class="text-emerald-600 text-xs font-medium mt-3 block hover:underline">
            Ver historial completo →
        </a>
    </div>

</div>

{{-- Adeudos pendientes --}}
@if($alumno->adeudos->isNotEmpty())
<div class="mt-5 bg-amber-50 border border-amber-200 rounded-xl p-4">
    <div class="flex items-start justify-between">
        <div>
            <p class="font-semibold text-amber-800 text-sm">Tienes adeudos pendientes</p>
            <p class="text-xs text-amber-600 mt-0.5">{{ $alumno->adeudos->count() }} concepto(s) sin pagar</p>
        </div>
        <a href="{{ route('portal.alumno.adeudos') }}" class="btn-sm text-amber-700 border border-amber-300 bg-white hover:bg-amber-50 rounded-lg px-3 py-1.5 text-xs font-medium">
            Ver adeudos →
        </a>
    </div>
</div>
@endif

@endsection
