@extends('layouts.portal-alumno')
@section('title', 'Mis Documentos')

@section('content')

<h2 class="text-xl font-bold text-slate-800 mb-6">Mis Documentos</h2>

@php
    $verificados = $documentos->where('verificado', true)->count();
    $total       = count($tipos);
@endphp

<div class="bg-white rounded-xl border border-slate-200 p-5 mb-5">
    <div class="flex items-center justify-between mb-2">
        <span class="text-sm font-medium text-slate-600">Expediente completo</span>
        <span class="text-sm font-bold text-slate-800">{{ $verificados }} / {{ $total }} verificados</span>
    </div>
    <div class="h-2 bg-slate-100 rounded-full overflow-hidden">
        <div class="h-full bg-emerald-500 rounded-full transition-all"
             style="width: {{ $total > 0 ? round($verificados / $total * 100) : 0 }}%"></div>
    </div>
</div>

<div class="bg-white rounded-xl border border-slate-200 overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-slate-50 border-b border-slate-100">
            <tr>
                <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500">Documento</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 hidden sm:table-cell">Archivo</th>
                <th class="px-4 py-3 text-center text-xs font-semibold text-slate-500">Estado</th>
                <th class="px-4 py-3 text-center text-xs font-semibold text-slate-500">Acciones</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-50">
            @foreach($tipos as $clave => $nombre)
            @php $doc = $documentos->firstWhere('tipo', $clave); @endphp
            <tr class="hover:bg-slate-50">
                <td class="px-4 py-3 font-medium text-slate-700">{{ $nombre }}</td>
                <td class="px-4 py-3 text-slate-500 hidden sm:table-cell text-xs truncate max-w-[160px]">
                    {{ $doc?->nombre_archivo ?? '—' }}
                </td>
                <td class="px-4 py-3 text-center">
                    @if($doc)
                        <span class="badge {{ $doc->verificado ? 'badge-success' : 'badge-warning' }}">
                            {{ $doc->verificado ? 'Verificado' : 'En revisión' }}
                        </span>
                    @else
                        <span class="badge badge-neutral">No entregado</span>
                    @endif
                </td>
                <td class="px-4 py-3 text-center">
                    @if($doc)
                    <a href="{{ $doc->url }}" target="_blank"
                       class="text-emerald-600 text-xs hover:underline">Ver</a>
                    @else
                    <span class="text-slate-300 text-xs">—</span>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<p class="text-xs text-slate-400 mt-4">
    Para entregar o reemplazar documentos, acude a la ventanilla de Control Escolar.
</p>

@endsection
