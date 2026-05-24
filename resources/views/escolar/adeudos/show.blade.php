@extends('layouts.escolar')

@section('title', 'Detalle de Adeudo')

@section('breadcrumb')
    <span class="breadcrumb-separator">/</span>
    <a href="{{ route('escolar.adeudos.index') }}">Adeudos</a>
    <span class="breadcrumb-separator">/</span>
    <span class="text-carbon-700 font-medium">Adeudo #{{ $adeudo->id }}</span>
@endsection

@section('content')
<div class="max-w-2xl space-y-5">

    <div class="card">
        <div class="card-header">
            <div>
                <p class="text-xs text-carbon-500 uppercase tracking-wider mb-1">Adeudo #{{ $adeudo->id }}</p>
                <h2 class="text-lg font-bold text-carbon-950">{{ $adeudo->concepto }}</h2>
                @if($adeudo->tipo)
                <p class="text-sm text-carbon-500 mt-0.5">{{ $tipos[$adeudo->tipo] ?? $adeudo->tipo }}</p>
                @endif
            </div>
            <span class="{{ $adeudo->status_color }} text-sm px-3 py-1">{{ $adeudo->status_nombre }}</span>
        </div>

        <dl class="grid grid-cols-2 gap-4 text-sm">
            <div>
                <dt class="text-carbon-500 text-xs uppercase tracking-wider mb-1">Alumno</dt>
                <dd class="font-medium text-carbon-950">{{ $adeudo->alumno->nombre_completo }}</dd>
                <dd class="text-xs font-mono text-carbon-500">{{ $adeudo->alumno->matricula }}</dd>
            </div>
            <div>
                <dt class="text-carbon-500 text-xs uppercase tracking-wider mb-1">Carrera</dt>
                <dd class="font-medium text-carbon-950">{{ $adeudo->alumno->carrera->nombre ?? '—' }}</dd>
            </div>
            <div>
                <dt class="text-carbon-500 text-xs uppercase tracking-wider mb-1">Monto</dt>
                <dd class="font-medium text-carbon-950 text-base">
                    {{ $adeudo->monto ? $adeudo->monto_formateado : '—' }}
                </dd>
            </div>
            <div>
                <dt class="text-carbon-500 text-xs uppercase tracking-wider mb-1">Vencimiento</dt>
                <dd class="font-medium">{{ $adeudo->fecha_vencimiento?->format('d/m/Y') ?? '—' }}</dd>
            </div>
            <div>
                <dt class="text-carbon-500 text-xs uppercase tracking-wider mb-1">Registrado</dt>
                <dd class="font-medium">{{ $adeudo->created_at->format('d/m/Y H:i') }}</dd>
                @if($adeudo->registrado_por_nombre)
                <dd class="text-xs text-carbon-500">por {{ $adeudo->registrado_por_nombre }}</dd>
                @endif
            </div>
            @if($adeudo->fecha_pago)
            <div>
                <dt class="text-carbon-500 text-xs uppercase tracking-wider mb-1">Pagado</dt>
                <dd class="font-medium">{{ $adeudo->fecha_pago->format('d/m/Y H:i') }}</dd>
            </div>
            @endif
        </dl>

        @if($adeudo->descripcion)
        <div class="mt-4 pt-4 border-t border-carbon-100">
            <p class="text-xs uppercase tracking-wider text-carbon-500 mb-2">Notas</p>
            <p class="text-sm text-carbon-700">{{ $adeudo->descripcion }}</p>
        </div>
        @endif

        @if($adeudo->periodo)
        <div class="mt-3 pt-3 border-t border-carbon-100">
            <p class="text-xs uppercase tracking-wider text-carbon-500 mb-1">Período</p>
            <p class="text-sm font-medium">{{ $adeudo->periodo->nombre }}</p>
        </div>
        @endif
    </div>

    <div class="flex items-center gap-3">
        @if($adeudo->status === 'pendiente')
        <form method="POST" action="{{ route('escolar.adeudos.liquidar', $adeudo) }}"
              onsubmit="return confirm('¿Confirmar liquidación de este adeudo?')">
            @csrf @method('PATCH')
            <button type="submit" class="btn-success">Marcar como liquidado</button>
        </form>
        @endif
        <a href="{{ route('escolar.adeudos.index') }}" class="btn-secondary">Volver</a>
        @if($adeudo->status !== 'pagado')
        <form method="POST" action="{{ route('escolar.adeudos.destroy', $adeudo) }}"
              onsubmit="return confirm('¿Eliminar este adeudo?')" class="ml-auto">
            @csrf @method('DELETE')
            <button type="submit" class="btn-danger btn-sm">Eliminar</button>
        </form>
        @endif
    </div>

</div>
@endsection
