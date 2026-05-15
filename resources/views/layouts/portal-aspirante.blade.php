<!DOCTYPE html>
<html lang="es" class="h-full bg-slate-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Portal Aspirante') — {{ config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full flex flex-col" x-data>

{{-- ─── Navbar ──────────────────────────────────────────── --}}
<nav class="bg-indigo-700 text-white shadow-md">
    <div class="max-w-5xl mx-auto px-4 h-14 flex items-center justify-between">
        <div class="flex items-center gap-3">
            <span class="text-lg font-bold tracking-tight">{{ config('app.name') }}</span>
            <span class="text-indigo-300 text-sm font-medium">Portal Aspirante</span>
        </div>
        @auth('aspirante')
        <div class="flex items-center gap-4 text-sm">
            <span class="text-indigo-200 hidden sm:block">{{ auth('aspirante')->user()->nombre_completo }}</span>
            <form method="POST" action="{{ route('portal.aspirante.logout') }}">
                @csrf
                <button class="bg-indigo-600 hover:bg-indigo-500 px-3 py-1.5 rounded-md transition">
                    Cerrar sesión
                </button>
            </form>
        </div>
        @endauth
    </div>
</nav>

{{-- ─── Sidebar + contenido ─────────────────────────────── --}}
@auth('aspirante')
<div class="flex flex-1 max-w-5xl mx-auto w-full px-4 py-6 gap-6">
    {{-- Sidebar --}}
    <aside class="w-52 flex-shrink-0 hidden md:block">
        <nav class="space-y-1">
            @php
                $nav = [
                    ['route' => 'portal.aspirante.dashboard',   'label' => 'Inicio'],
                    ['route' => 'portal.aspirante.ficha',       'label' => 'Mi Ficha de Pago'],
                    ['route' => 'portal.aspirante.documentos',  'label' => 'Mis Documentos'],
                    ['route' => 'portal.aspirante.examen',      'label' => 'Mi Examen'],
                    ['route' => 'portal.aspirante.perfil',      'label' => 'Mi Perfil'],
                ];
            @endphp
            @foreach($nav as $item)
            <a href="{{ route($item['route']) }}"
               class="block px-3 py-2 rounded-lg text-sm font-medium transition
                      {{ request()->routeIs($item['route'])
                         ? 'bg-indigo-100 text-indigo-700'
                         : 'text-slate-600 hover:bg-slate-100' }}">
                {{ $item['label'] }}
            </a>
            @endforeach
        </nav>
    </aside>

    {{-- Contenido --}}
    <main class="flex-1 min-w-0">
        {{-- Alertas --}}
        @foreach(['success' => 'bg-green-50 text-green-800 border-green-200',
                  'error'   => 'bg-red-50 text-red-800 border-red-200',
                  'info'    => 'bg-blue-50 text-blue-800 border-blue-200'] as $tipo => $clases)
            @if(session($tipo))
            <div class="mb-4 px-4 py-3 rounded-lg border text-sm {{ $clases }}">
                {{ session($tipo) }}
            </div>
            @endif
        @endforeach

        @yield('content')
    </main>
</div>
@else
    <main class="flex-1">@yield('content')</main>
@endauth

</body>
</html>
