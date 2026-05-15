@extends('layouts.financiero')
@section('title','Aspirante — ' . $aspirante->nombre_completo)

@section('breadcrumb')
    <a href="{{ route('financiero.aspirantes.index') }}">Aspirantes</a>
    <span class="breadcrumb-separator">/</span>
    <span class="text-carbon-700 font-medium">{{ $aspirante->folio }}</span>
@endsection

@section('header-actions')
    @if($aspirante->puedeGenerarFicha())
    <form method="POST" action="{{ route('financiero.aspirantes.generar-ficha', $aspirante) }}">
        @csrf
        <button class="btn-primary btn-sm">Generar Ficha de Pago</button>
    </form>
    @endif
@endsection

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    {{-- Columna principal --}}
    <div class="lg:col-span-2 space-y-5">

        {{-- Datos personales --}}
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Datos del Aspirante</h3>
                <span class="badge {{ $aspirante->status_color }}">{{ $aspirante->status_nombre }}</span>
            </div>
            <div class="grid grid-cols-2 sm:grid-cols-3 gap-4 text-sm">
                @foreach([
                    ['Folio',       $aspirante->folio],
                    ['Nombre',      $aspirante->nombre_completo],
                    ['CURP',        $aspirante->curp ?? '—'],
                    ['Email',       $aspirante->email],
                    ['Teléfono',    $aspirante->telefono ?? '—'],
                    ['Sexo',        $aspirante->sexo === 'M' ? 'Masculino' : ($aspirante->sexo === 'F' ? 'Femenino' : '—')],
                    ['Carrera',     $aspirante->carrera?->nombre ?? '—'],
                    ['Período',     $aspirante->periodo?->nombre ?? '—'],
                    ['Registrado',  $aspirante->created_at->format('d/m/Y')],
                ] as [$label, $val])
                <div>
                    <p class="text-carbon-500 text-xs mb-0.5">{{ $label }}</p>
                    <p class="font-medium text-carbon-900 break-all">{{ $val }}</p>
                </div>
                @endforeach
            </div>
        </div>

        {{-- Fichas de pago --}}
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Fichas de Pago</h3>
                <span class="text-sm text-carbon-400">{{ $aspirante->fichas->count() }} ficha(s)</span>
            </div>

            @forelse($aspirante->fichas()->latest()->get() as $ficha)
            <div class="border border-carbon-100 rounded-xl p-4 mb-3 last:mb-0">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <p class="font-mono text-sm font-bold text-navy-800">{{ $ficha->folio_ficha }}</p>
                        <p class="text-xs text-carbon-400 mt-0.5">{{ $ficha->concepto }}</p>
                    </div>
                    <div class="text-right flex-shrink-0">
                        <p class="text-lg font-black text-carbon-900">{{ $ficha->monto_formateado }}</p>
                        <span class="badge text-xs {{ $ficha->status_color }}">{{ $ficha->status_nombre }}</span>
                    </div>
                </div>
                <div class="grid grid-cols-3 gap-3 mt-3 text-xs text-carbon-500">
                    <div>
                        <span class="block">Emisión</span>
                        <span class="font-medium text-carbon-700">{{ $ficha->fecha_emision->format('d/m/Y') }}</span>
                    </div>
                    <div>
                        <span class="block">Vencimiento</span>
                        <span class="font-medium text-carbon-700">{{ $ficha->fecha_vencimiento->format('d/m/Y') }}</span>
                    </div>
                    <div>
                        <span class="block">Pago</span>
                        <span class="font-medium text-carbon-700">{{ $ficha->fecha_pago?->format('d/m/Y') ?? '—' }}</span>
                    </div>
                </div>
                @if($ficha->metodo_pago)
                <p class="text-xs text-carbon-400 mt-2">
                    Método: <span class="font-medium">{{ ucfirst($ficha->metodo_pago) }}</span>
                    @if($ficha->referencia_pago)
                        · Ref: <span class="font-mono">{{ $ficha->referencia_pago }}</span>
                    @endif
                </p>
                @endif
                <div class="flex justify-end gap-2 mt-3">
                    <a href="{{ route('financiero.fichas.show', $ficha) }}" class="btn-outline btn-sm">Detalle</a>
                    <a href="{{ route('financiero.fichas.pdf', $ficha) }}" target="_blank" class="btn-secondary btn-sm">PDF</a>
                </div>
            </div>
            @empty
            <div class="py-8 text-center text-carbon-400">
                <p class="text-sm">Este aspirante aún no tiene fichas de pago.</p>
            </div>
            @endforelse
        </div>

    </div>

    {{-- Sidebar --}}
    <div class="space-y-5">

        {{-- Resumen financiero --}}
        <div class="card">
            <div class="card-header"><h3 class="card-title">Resumen Financiero</h3></div>
            <div class="space-y-3">
                @php
                    $fichas      = $aspirante->fichas;
                    $totalPagado = $fichas->where('status','pagado')->sum('monto');
                    $pendientes  = $fichas->where('status','pendiente')->count();
                @endphp
                <div class="flex justify-between py-2 border-b border-carbon-100">
                    <span class="text-sm text-carbon-500">Total fichas</span>
                    <span class="font-semibold">{{ $fichas->count() }}</span>
                </div>
                <div class="flex justify-between py-2 border-b border-carbon-100">
                    <span class="text-sm text-carbon-500">Fichas pagadas</span>
                    <span class="font-semibold text-green-700">{{ $fichas->where('status','pagado')->count() }}</span>
                </div>
                <div class="flex justify-between py-2 border-b border-carbon-100">
                    <span class="text-sm text-carbon-500">Fichas pendientes</span>
                    <span class="font-semibold text-amber-600">{{ $pendientes }}</span>
                </div>
                <div class="flex justify-between py-2">
                    <span class="text-sm text-carbon-500">Total pagado</span>
                    <span class="font-bold text-navy-800">${{ number_format($totalPagado, 2) }}</span>
                </div>
            </div>
        </div>

        {{-- Acciones --}}
        @if($aspirante->puedeGenerarFicha())
        <div class="card border-2 border-dashed border-navy-300 bg-navy-50/30">
            <p class="text-sm text-carbon-600 mb-3">Este aspirante no tiene una ficha activa.</p>
            <form method="POST" action="{{ route('financiero.aspirantes.generar-ficha', $aspirante) }}">
                @csrf
                <button class="btn-primary w-full justify-center">Generar Ficha de Pago</button>
            </form>
        </div>
        @endif

    </div>
</div>
@endsection
