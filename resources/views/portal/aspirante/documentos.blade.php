@extends('layouts.portal-aspirante')
@section('title', 'Mis Documentos')

@section('content')

<h2 class="text-xl font-bold text-slate-800 mb-2">Mis Documentos</h2>
<p class="text-sm text-slate-500 mb-6">
    Sube los documentos requeridos en formato JPG, PNG o PDF (máx. 8 MB cada uno).
</p>

@if(! $aspirante->tieneFichaPagada())
<div class="bg-amber-50 border border-amber-200 text-amber-700 rounded-lg px-4 py-3 text-sm mb-6">
    ⚠ Debes tener la ficha de pago cubierta antes de subir documentos.
    <a href="{{ route('portal.aspirante.ficha') }}" class="underline font-medium">Ir a mi ficha →</a>
</div>
@endif

<div class="grid grid-cols-1 md:grid-cols-2 gap-5">
    @foreach($tipos as $clave => $nombre)
    @php
        $doc = $documentos->firstWhere('tipo', $clave);
    @endphp
    <div class="bg-white rounded-xl border {{ $doc ? ($doc->verificado ? 'border-green-200' : 'border-amber-200') : 'border-slate-200' }} p-5">
        <div class="flex items-start justify-between mb-3">
            <div>
                <p class="font-semibold text-slate-800 text-sm">{{ $nombre }}</p>
                @if($doc)
                    <p class="text-xs text-slate-400 mt-0.5 truncate max-w-[200px]">{{ $doc->nombre_archivo }}</p>
                @endif
            </div>
            @if($doc)
                @if($doc->verificado)
                    <span class="badge badge-success">Verificado</span>
                @else
                    <span class="badge badge-warning">En revisión</span>
                @endif
            @else
                <span class="badge badge-neutral">Pendiente</span>
            @endif
        </div>

        @if($aspirante->tieneFichaPagada())
        <form method="POST" action="{{ route('portal.aspirante.documentos.store') }}"
              enctype="multipart/form-data" class="mt-2">
            @csrf
            <input type="hidden" name="tipo" value="{{ $clave }}">
            <div class="flex gap-2">
                <input type="file" name="archivo" accept=".jpg,.jpeg,.png,.pdf"
                       class="flex-1 text-xs text-slate-600 file:mr-2 file:py-1 file:px-2
                              file:rounded file:border-0 file:text-xs file:bg-indigo-50
                              file:text-indigo-700 hover:file:bg-indigo-100">
                <button class="btn-primary btn-sm flex-shrink-0">
                    {{ $doc ? 'Reemplazar' : 'Subir' }}
                </button>
            </div>
        </form>
        @endif

        @if($doc && $doc->verificado_at)
        <p class="text-xs text-green-600 mt-2">
            Verificado el {{ $doc->verificado_at->format('d/m/Y') }}
        </p>
        @endif
    </div>
    @endforeach
</div>

@endsection
