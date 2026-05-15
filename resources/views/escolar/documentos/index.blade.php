@extends('layouts.escolar')
@section('title','Documentos')

@section('breadcrumb')
    <span class="breadcrumb-separator">/</span>
    <span class="text-carbon-700 font-medium">Documentos</span>
@endsection

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    {{-- Subida de documento --}}
    <div class="lg:col-span-1">
        <div class="card">
            <div class="card-header"><h3 class="card-title">Cargar Documento</h3></div>
            <form method="POST" action="{{ route('escolar.documentos.store') }}" enctype="multipart/form-data" class="space-y-4">
                @csrf
                <div>
                    <label class="form-label">Aspirante <span class="text-danger">*</span></label>
                    <select name="aspirante_id" class="form-select" required>
                        <option value="">— Seleccionar —</option>
                        @foreach($aspirantes as $a)
                        <option value="{{ $a->id }}" {{ request('aspirante_id') == $a->id ? 'selected' : '' }}>
                            {{ $a->folio }} — {{ $a->nombre_completo }}
                        </option>
                        @endforeach
                    </select>
                    @error('aspirante_id')<p class="form-error">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="form-label">Tipo de Documento <span class="text-danger">*</span></label>
                    <select name="tipo" class="form-select" required>
                        <option value="">— Seleccionar —</option>
                        @foreach([
                            'acta_nacimiento'      => 'Acta de Nacimiento',
                            'certificado_bachillerato' => 'Certificado de Bachillerato',
                            'curp'                 => 'CURP',
                            'identificacion'       => 'Identificación Oficial',
                            'foto'                 => 'Fotografía',
                            'comprobante_domicilio'=> 'Comprobante de Domicilio',
                            'otro'                 => 'Otro',
                        ] as $k => $v)
                        <option value="{{ $k }}">{{ $v }}</option>
                        @endforeach
                    </select>
                    @error('tipo')<p class="form-error">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="form-label">Archivo <span class="text-danger">*</span></label>
                    <input type="file" name="archivo" accept=".pdf,.jpg,.jpeg,.png"
                           class="form-input @error('archivo') border-danger @enderror" required>
                    <p class="form-help">PDF, JPG o PNG — máx. 5 MB</p>
                    @error('archivo')<p class="form-error">{{ $message }}</p>@enderror
                </div>
                <button type="submit" class="btn-primary w-full justify-center">Cargar Documento</button>
            </form>
        </div>
    </div>

    {{-- Lista de documentos --}}
    <div class="lg:col-span-2 space-y-4">

        {{-- Filtro rápido --}}
        <form method="GET" class="card py-3">
            <div class="flex gap-3 flex-wrap">
                <div class="flex-1 min-w-40">
                    <select name="aspirante_id" class="form-select" onchange="this.form.submit()">
                        <option value="">— Todos los aspirantes —</option>
                        @foreach($aspirantes as $a)
                        <option value="{{ $a->id }}" {{ request('aspirante_id') == $a->id ? 'selected' : '' }}>
                            {{ $a->folio }} — {{ $a->nombre_completo }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <select name="verificado" class="form-select" onchange="this.form.submit()">
                        <option value="">Todos los estados</option>
                        <option value="1" {{ request('verificado') === '1' ? 'selected' : '' }}>Verificados</option>
                        <option value="0" {{ request('verificado') === '0' ? 'selected' : '' }}>Pendientes</option>
                    </select>
                </div>
                <a href="{{ route('escolar.documentos.index') }}" class="btn-secondary btn-sm self-center">Limpiar</a>
            </div>
        </form>

        {{-- Documentos --}}
        @forelse($documentos as $doc)
        <div class="card">
            <div class="flex items-start gap-4">
                {{-- Ícono tipo --}}
                <div class="w-10 h-10 bg-navy-50 rounded-lg flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-navy-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>

                <div class="flex-1 min-w-0">
                    <div class="flex items-start justify-between gap-2">
                        <div>
                            <p class="font-semibold text-carbon-900 text-sm">{{ $doc->tipo_nombre }}</p>
                            <p class="text-xs text-carbon-400 mt-0.5">{{ $doc->aspirante->folio }} — {{ $doc->aspirante->nombre_completo }}</p>
                        </div>
                        @if($doc->verificado)
                        <span class="badge-success badge flex-shrink-0">Verificado</span>
                        @else
                        <span class="badge-warning badge flex-shrink-0">Pendiente</span>
                        @endif
                    </div>

                    <p class="text-xs text-carbon-500 mt-1 truncate">{{ $doc->nombre_archivo }}</p>

                    @if($doc->verificado)
                    <p class="text-xs text-green-600 mt-1">
                        Verificado por {{ $doc->verificadoPor?->name ?? '—' }}
                        {{ $doc->verificado_at ? '· ' . $doc->verificado_at->format('d/m/Y') : '' }}
                    </p>
                    @endif

                    <div class="flex items-center gap-2 mt-3">
                        <a href="{{ Storage::url($doc->ruta_archivo) }}" target="_blank"
                           class="btn-outline btn-sm">Ver archivo</a>

                        @if(!$doc->verificado)
                        <form method="POST" action="{{ route('escolar.documentos.verificar', $doc) }}">
                            @csrf @method('PATCH')
                            <button class="btn-success btn-sm">Verificar</button>
                        </form>
                        @endif

                        <form method="POST" action="{{ route('escolar.documentos.destroy', $doc) }}"
                              onsubmit="return confirm('¿Eliminar este documento?')">
                            @csrf @method('DELETE')
                            <button class="btn-danger btn-sm">Eliminar</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="card py-16 text-center text-carbon-400">
            <svg class="w-12 h-12 mx-auto mb-3 text-carbon-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            <p class="text-sm">No hay documentos cargados.</p>
        </div>
        @endforelse

        @if(isset($documentos) && method_exists($documentos, 'hasPages') && $documentos->hasPages())
        <div>{{ $documentos->links() }}</div>
        @endif
    </div>

</div>
@endsection
