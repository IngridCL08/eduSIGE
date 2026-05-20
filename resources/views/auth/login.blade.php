<!DOCTYPE html>
<html lang="es" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Iniciar Sesión — {{ config('app.name') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        [x-cloak] { display: none !important; }
        .brand-gradient {
            background: linear-gradient(155deg, #5b35c0 0%, #7c55d8 60%, #9572e8 100%);
        }
        .bubble {
            position: absolute;
            border-radius: 50%;
            background: rgba(255,255,255,0.08);
        }
    </style>
</head>
<body class="min-h-full bg-slate-100 flex items-center justify-center p-4"
      x-data="{ tab: '{{ old('_tab', 'estudiantes') }}' }">

<div class="w-full max-w-4xl bg-white rounded-2xl shadow-2xl overflow-hidden">
    <div class="flex flex-col lg:flex-row" style="min-height: 540px">

        {{-- ── Panel izquierdo decorativo ────────────────────── --}}
        <div class="lg:w-5/12 brand-gradient relative flex flex-col items-center justify-between p-8 overflow-hidden select-none">

            {{-- Burbujas decorativas de fondo --}}
            <div class="bubble w-56 h-56 -top-20 -right-16"></div>
            <div class="bubble w-72 h-72 -bottom-24 -left-20"></div>
            <div class="bubble w-32 h-32 top-1/2 right-4 opacity-50"></div>

            {{-- Zona superior: ícono y título --}}
            <div class="relative z-10 w-full text-center">
                {{-- Ícono flotante derecha --}}
                <div class="absolute right-0 top-2 w-10 h-10 rounded-xl bg-white/15 backdrop-blur-sm
                            flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>

                {{-- Graduación --}}
                <div class="mx-auto w-16 h-16 rounded-2xl bg-white/20 backdrop-blur-sm
                            flex items-center justify-center mb-5 shadow-lg">
                    <svg class="w-9 h-9 text-white" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 3L1 9l4 2.18V16l7 4 7-4v-4.82L23 9 12 3zm6.18 9L12 15.72 5.82 12 12 8.28 18.18 12zM5 13.18V17l7 4 7-4v-3.82L12 17l-7-3.82z"/>
                    </svg>
                </div>

                <h1 class="text-3xl font-extrabold text-white tracking-wide drop-shadow-sm">
                    {{ config('app.name', 'Sistema Integral') }}
                </h1>
            </div>

            {{-- Zona inferior: avatar + cita --}}
            <div class="relative z-10 w-full text-center">
                {{-- Ícono flotante izquierda --}}
                <div class="absolute left-0 top-0 w-10 h-10 rounded-xl bg-white/15 backdrop-blur-sm
                            flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                    </svg>
                </div>

                {{-- Ícono flotante derecha --}}
                <div class="absolute right-0 bottom-12 w-10 h-10 rounded-xl bg-white/15 backdrop-blur-sm
                            flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                    </svg>
                </div>

                {{-- Avatar --}}
                <div class="w-20 h-20 mx-auto rounded-full border-4 border-white/30
                            bg-white/20 backdrop-blur-sm flex items-center justify-center mb-5 shadow-lg">
                    <svg class="w-11 h-11 text-white" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 12c2.7 0 4.8-2.1 4.8-4.8S14.7 2.4 12 2.4 7.2 4.5 7.2 7.2 9.3 12 12 12zm0 2.4c-3.2 0-9.6 1.6-9.6 4.8v2.4h19.2v-2.4c0-3.2-6.4-4.8-9.6-4.8z"/>
                    </svg>
                </div>

                {{-- Cita --}}
                <blockquote class="text-white/80 text-xs italic leading-relaxed px-4">
                    "La educación es el arma más poderosa que puedes usar para cambiar el mundo"
                    <footer class="text-white/50 not-italic mt-1">— Nelson Mandela</footer>
                </blockquote>

                <p class="text-white/30 text-xs mt-5">
                    {{ config('app.name') }} &copy; {{ date('Y') }}
                </p>
            </div>
        </div>

        {{-- ── Panel derecho: formulario ──────────────────────── --}}
        <div class="flex-1 flex flex-col justify-center px-8 lg:px-10 py-10">

            {{-- Nombre institución --}}
            <h2 class="text-lg font-semibold text-slate-800 text-center mb-7">
                {{ config('app.edusige.institucion', 'Sistema Integral') }}
            </h2>

            {{-- Tabs --}}
            <div class="flex rounded-xl border border-slate-200 bg-slate-50 p-1 mb-6 gap-1">
                @foreach(['estudiantes' => 'Estudiantes', 'personal' => 'Personal', 'aspirantes' => 'Aspirantes'] as $key => $label)
                <button type="button"
                        @click="tab = '{{ $key }}'"
                        :class="tab === '{{ $key }}'
                            ? 'bg-[#5b35c0] text-white shadow-sm'
                            : 'text-slate-500 hover:text-slate-700 hover:bg-white/60'"
                        class="flex-1 py-2 px-2 rounded-lg text-sm font-medium transition-all duration-200">
                    {{ $label }}
                </button>
                @endforeach
            </div>

            {{-- Errores (compartidos entre tabs) --}}
            @if ($errors->any())
            <div class="mb-5 px-4 py-3 bg-red-50 border border-red-200 text-red-700 rounded-xl text-sm flex gap-2 items-start">
                <svg class="w-4 h-4 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                </svg>
                <div>
                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- ── Formulario Estudiantes ── --}}
            <form x-show="tab === 'estudiantes'" x-cloak
                  method="POST" action="{{ route('portal.alumno.login.post') }}"
                  class="space-y-4">
                @csrf
                <input type="hidden" name="_tab" value="estudiantes">

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">
                        Número de control
                    </label>
                    <input type="text" name="matricula"
                           value="{{ old('matricula') }}"
                           class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm
                                  focus:outline-none focus:ring-2 focus:ring-[#5b35c0]/25 focus:border-[#5b35c0]
                                  transition-colors"
                           placeholder="Ej. 2026ISC0001"
                           autofocus>
                    @error('matricula')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div x-data="{ show: false }">
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Contraseña</label>
                    <div class="relative">
                        <input :type="show ? 'text' : 'password'" name="password"
                               class="w-full px-4 py-2.5 pr-10 border border-slate-200 rounded-xl text-sm
                                      focus:outline-none focus:ring-2 focus:ring-[#5b35c0]/25 focus:border-[#5b35c0]
                                      transition-colors"
                               placeholder="••••••••" required>
                        <button type="button" @click="show = !show"
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600">
                            <svg x-show="!show" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0zM2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                            <svg x-show="show" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                            </svg>
                        </button>
                    </div>
                </div>

                <button type="submit"
                        class="w-full py-3 rounded-xl text-white text-sm font-semibold
                               hover:opacity-90 active:scale-[0.98] transition-all duration-150 shadow-md"
                        style="background-color: #5b35c0">
                    Iniciar sesión
                </button>
            </form>

            {{-- ── Formulario Personal (staff) ── --}}
            <form x-show="tab === 'personal'" x-cloak
                  method="POST" action="{{ route('login') }}"
                  class="space-y-4">
                @csrf
                <input type="hidden" name="_tab" value="personal">

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">
                        Correo electrónico
                    </label>
                    <input type="email" name="email"
                           value="{{ old('email') }}"
                           class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm
                                  focus:outline-none focus:ring-2 focus:ring-[#5b35c0]/25 focus:border-[#5b35c0]
                                  transition-colors"
                           placeholder="usuario@ejemplo.com"
                           autocomplete="email">
                    @error('email')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div x-data="{ show: false }">
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Contraseña</label>
                    <div class="relative">
                        <input :type="show ? 'text' : 'password'" name="password"
                               class="w-full px-4 py-2.5 pr-10 border border-slate-200 rounded-xl text-sm
                                      focus:outline-none focus:ring-2 focus:ring-[#5b35c0]/25 focus:border-[#5b35c0]
                                      transition-colors"
                               placeholder="••••••••" required>
                        <button type="button" @click="show = !show"
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600">
                            <svg x-show="!show" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0zM2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                            <svg x-show="show" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                            </svg>
                        </button>
                    </div>
                    @error('password')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center">
                    <input id="remember" name="remember" type="checkbox"
                           class="w-4 h-4 rounded border-slate-300 accent-[#5b35c0]">
                    <label for="remember" class="ml-2 text-sm text-slate-600">
                        Mantener sesión iniciada
                    </label>
                </div>

                <button type="submit"
                        class="w-full py-3 rounded-xl text-white text-sm font-semibold
                               hover:opacity-90 active:scale-[0.98] transition-all duration-150 shadow-md"
                        style="background-color: #5b35c0">
                    Iniciar sesión
                </button>
            </form>

            {{-- ── Formulario Aspirantes ── --}}
            <form x-show="tab === 'aspirantes'" x-cloak
                  method="POST" action="{{ route('portal.aspirante.login.post') }}"
                  class="space-y-4">
                @csrf
                <input type="hidden" name="_tab" value="aspirantes">

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">
                        Folio o correo electrónico
                    </label>
                    <input type="text" name="credencial"
                           value="{{ old('credencial') }}"
                           class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm
                                  focus:outline-none focus:ring-2 focus:ring-[#5b35c0]/25 focus:border-[#5b35c0]
                                  transition-colors"
                           placeholder="ASP-2026-000001 o tu@correo.com">
                    @error('credencial')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div x-data="{ show: false }">
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Contraseña</label>
                    <div class="relative">
                        <input :type="show ? 'text' : 'password'" name="password"
                               class="w-full px-4 py-2.5 pr-10 border border-slate-200 rounded-xl text-sm
                                      focus:outline-none focus:ring-2 focus:ring-[#5b35c0]/25 focus:border-[#5b35c0]
                                      transition-colors"
                               placeholder="••••••••" required>
                        <button type="button" @click="show = !show"
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600">
                            <svg x-show="!show" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0zM2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                            <svg x-show="show" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                            </svg>
                        </button>
                    </div>
                </div>

                <button type="submit"
                        class="w-full py-3 rounded-xl text-white text-sm font-semibold
                               hover:opacity-90 active:scale-[0.98] transition-all duration-150 shadow-md"
                        style="background-color: #5b35c0">
                    Iniciar sesión
                </button>

                <p class="text-center text-xs text-slate-400">
                    ¿No tienes cuenta?
                    <a href="{{ route('portal.aspirante.registro') }}"
                       class="text-[#5b35c0] hover:underline font-medium">
                        Regístrate aquí
                    </a>
                </p>
            </form>

        </div>
    </div>
</div>

</body>
</html>
