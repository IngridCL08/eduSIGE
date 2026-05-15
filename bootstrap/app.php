<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Middleware global de sesión / CSRF
        $middleware->web(append: [
            \App\Http\Middleware\LogActivity::class,
        ]);

        // Alias de middleware usados en rutas
        $middleware->alias([
            'role'               => \Spatie\Permission\Middleware\RoleMiddleware::class,
            'permission'         => \Spatie\Permission\Middleware\PermissionMiddleware::class,
            'role.or.permission' => \Spatie\Permission\Middleware\RoleOrPermissionMiddleware::class,
            'check.role'         => \App\Http\Middleware\CheckRole::class,
            'auth.aspirante'     => \App\Http\Middleware\AspiranteAuthenticated::class,
            'auth.alumno'        => \App\Http\Middleware\AlumnoAuthenticated::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        // Redirigir 404 con mensaje claro
        $exceptions->render(function (NotFoundHttpException $e, Request $request) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Recurso no encontrado.'], 404);
            }
            return response()->view('errors.404', [], 404);
        });

        // Redirigir 403 con mensaje de acceso denegado
        $exceptions->render(function (AccessDeniedHttpException $e, Request $request) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Acceso denegado.'], 403);
            }
            return response()->view('errors.403', [], 403);
        });
    })->create();
