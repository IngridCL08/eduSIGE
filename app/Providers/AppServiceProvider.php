<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Blade;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Bindings de servicios
        $this->app->singleton(\App\Services\PagoService::class);
        $this->app->singleton(\App\Services\ReporteService::class);
        $this->app->singleton(\App\Services\EstadisticaService::class);
    }

    public function boot(): void
    {
        // Usar paginación con Tailwind CSS
        Paginator::useTailwind();

        // Directiva Blade para verificar rol
        Blade::if('role', function (string $role) {
            return auth()->check() && auth()->user()->hasRole($role);
        });

        // Directiva Blade para verificar permiso
        Blade::if('permission', function (string $permission) {
            return auth()->check() && auth()->user()->can($permission);
        });

        // Gate de super admin (omite todos los permisos)
        Gate::before(function ($user, $ability) {
            if ($user->hasRole('admin')) {
                return true;
            }
        });
    }
}
