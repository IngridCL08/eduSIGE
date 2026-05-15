@extends('layouts.portal-alumno')
@section('title', 'Historial Académico')

@section('content')

<div class="flex items-center justify-between mb-6">
    <h2 class="text-xl font-bold text-slate-800">Historial Académico</h2>
    @if($promedioGeneral)
    <div class="text-right">
        <p class="text-2xl font-bold text-emerald-600">{{ number_format($promedioGeneral, 2) }}</p>
        <p class="text-xs text-slate-400">Promedio general</p>
    </div>
    @endif
</div>

@forelse($historial as $periodo => $materias)
<div class="mb-6">
    <h3 class="text-sm font-semibold text-slate-600 uppercase tracking-wider mb-3 px-1">{{ $periodo }}</h3>
    <div class="bg-white rounded-xl border border-slate-200 overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-slate-50 border-b border-slate-100">
                <tr>
                    <th class="px-4 py-2.5 text-left text-xs font-semibold text-slate-500">Materia</th>
                    <th class="px-4 py-2.5 text-center text-xs font-semibold text-slate-500">Créditos</th>
                    <th class="px-4 py-2.5 text-center text-xs font-semibold text-slate-500">Calificación</th>
                    <th class="px-4 py-2.5 text-center text-xs font-semibold text-slate-500">Estatus</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
                @foreach($materias as $h)
                <tr class="hover:bg-slate-50">
                    <td class="px-4 py-3 font-medium text-slate-700">{{ $h->materia }}</td>
                    <td class="px-4 py-3 text-center text-slate-500">{{ $h->creditos ?? '—' }}</td>
                    <td class="px-4 py-3 text-center">
                        <span class="font-bold {{ ($h->calificacion ?? 0) >= 6 ? 'text-slate-800' : 'text-red-600' }}">
                            {{ $h->calificacion !== null ? number_format($h->calificacion, 1) : '—' }}
                        </span>
                    </td>
                    <td class="px-4 py-3 text-center">
                        @php
                            $color = match($h->status ?? '') {
                                'acreditada'     => 'badge-success',
                                'no_acreditada'  => 'badge-danger',
                                'en_curso'       => 'badge-info',
                                default          => 'badge-neutral',
                            };
                            $label = match($h->status ?? '') {
                                'acreditada'    => 'Acreditada',
                                'no_acreditada' => 'No acreditada',
                                'en_curso'      => 'En curso',
                                default         => $h->status ?? '—',
                            };
                        @endphp
                        <span class="badge {{ $color }}">{{ $label }}</span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@empty
<div class="bg-white rounded-xl border border-slate-200 p-8 text-center text-slate-400">
    No hay materias registradas en tu historial aún.
</div>
@endforelse

@endsection
