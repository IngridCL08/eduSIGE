<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;

    protected $fillable = [
        'name',
        'email',
        'password',
        'activo',
        'ultimo_acceso',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'ultimo_acceso'     => 'datetime',
            'activo'            => 'boolean',
            'password'          => 'hashed',
        ];
    }

    // ─── Relaciones ───────────────────────────────────────────

    public function fichasGeneradas(): HasMany
    {
        return $this->hasMany(FichaPago::class, 'generado_por');
    }

    public function bitacora(): HasMany
    {
        return $this->hasMany(Bitacora::class);
    }

    // ─── Helpers ──────────────────────────────────────────────

    /** Retorna la ruta del dashboard según el rol del usuario */
    public function dashboardRoute(): string
    {
        return match (true) {
            $this->hasRole('admin')      => 'admin.dashboard',
            $this->hasRole('financiero') => 'financiero.dashboard',
            $this->hasRole('escolar')    => 'escolar.dashboard',
            default                      => 'login',
        };
    }

    /** Retorna el nombre del rol en español */
    public function rolNombre(): string
    {
        return match ($this->roles->first()?->name) {
            'admin'      => 'Super Administrador',
            'financiero' => 'Recursos Financieros',
            'escolar'    => 'Control Escolar',
            default      => 'Sin rol',
        };
    }

    public function getFullNameAttribute(): string
    {
        return $this->name;
    }
}
