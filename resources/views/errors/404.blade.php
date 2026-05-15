<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 — Página no encontrada | eduSIGE</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-slate-900 flex items-center justify-center p-6">
    <div class="text-center max-w-md">
        <div class="text-8xl font-black text-slate-700 mb-4">404</div>
        <h1 class="text-2xl font-bold text-white mb-2">Página no encontrada</h1>
        <p class="text-slate-400 mb-8">El recurso que buscas no existe o fue eliminado.</p>
        <a href="{{ url('/') }}"
           class="inline-flex items-center gap-2 px-5 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors text-sm font-medium">
            ← Ir al inicio
        </a>
    </div>
</body>
</html>
