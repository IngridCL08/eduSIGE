@extends('layouts.portal-aspirante')
@section('title', 'Mi Examen')

@section('content')

<h2 class="text-xl font-bold text-slate-800 mb-6">Examen de Admisión</h2>

@php $examen = $aspirante->examenesAdmision->first(); @endphp

@if(! $examen)
<div class="bg-white rounded-xl border border-slate-200 p-8 text-center">
    <p class="text-slate-500 text-lg font-medium mb-2">Aún no tienes examen asignado</p>
    <p class="text-sm text-slate-400">
        Una vez que hayas cubierto tu ficha y entregado tus documentos, Control Escolar
        te asignará la fecha de tu examen de admisión.
    </p>
</div>
@else
<div class="bg-white rounded-xl border border-slate-200 p-6 mb-5">
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-6">
        <div>
            <p class="text-xs text-slate-400 mb-1">Fecha del examen</p>
            <p class="font-semibold text-slate-800">{{ $examen->fecha_examen->format('d/m/Y') }}</p>
        </div>
        <div>
            <p class="text-xs text-slate-400 mb-1">Calificación</p>
            <p class="text-2xl font-bold text-slate-800">
                {{ $examen->calificacion !== null ? number_format($examen->calificacion, 1) : '—' }}
            </p>
        </div>
        <div>
            <p class="text-xs text-slate-400 mb-1">Resultado</p>
            @if($examen->resultado)
                <span class="badge {{ $examen->resultado_color }} text-sm px-3 py-1">
                    {{ $examen->resultado_nombre }}
                </span>
            @else
                <span class="badge badge-neutral">Pendiente</span>
            @endif
        </div>
        <div>
            <p class="text-xs text-slate-400 mb-1">Registrado por</p>
            <p class="text-sm text-slate-600">{{ $examen->aplicadoPor?->name ?? '—' }}</p>
        </div>
    </div>

    @if($examen->observaciones)
    <div class="mt-4 p-3 bg-slate-50 rounded-lg text-sm text-slate-600">
        <span class="font-medium">Observaciones:</span> {{ $examen->observaciones }}
    </div>
    @endif
</div>

@if($examen->resultado === 'aprobado')
<div class="bg-green-50 border border-green-200 rounded-xl p-5 text-green-700">
    <p class="font-semibold text-lg mb-1">¡Felicidades! Has sido aprobado.</p>
    <p class="text-sm">
        Tu expediente está siendo revisado por Control Escolar para proceder con tu inscripción.
        Recibirás tu número de matrícula una vez que el proceso se complete.
    </p>
</div>
@elseif($examen->resultado === 'reprobado')
<div class="bg-red-50 border border-red-200 rounded-xl p-5 text-red-700">
    <p class="font-semibold text-lg mb-1">No alcanzaste el puntaje mínimo.</p>
    <p class="text-sm">Puedes contactar a Control Escolar para más información sobre tu proceso.</p>
</div>
@elseif($examen->resultado === 'lista_espera')
<div class="bg-amber-50 border border-amber-200 rounded-xl p-5 text-amber-700">
    <p class="font-semibold text-lg mb-1">Estás en lista de espera.</p>
    <p class="text-sm">
        Quedaste en lista de espera. Control Escolar te contactará si hay un lugar disponible.
    </p>
</div>
@endif
@endif

@endsection
