<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Carrera extends Model
{
    use HasFactory;

    protected $fillable = ['clave', 'nombre', 'descripcion', 'activa'];

    protected function casts(): array
    {
        return ['activa' => 'boolean'];
    }

    // ─── Scopes ───────────────────────────────────────────────

    public function scopeActivas(Builder $query): Builder
    {
        return $query->where('activa', true);
    }

    // ─── Relaciones ───────────────────────────────────────────

    public function aspirantes(): HasMany
    {
        return $this->hasMany(Aspirante::class);
    }

    public function alumnos(): HasMany
    {
        return $this->hasMany(Alumno::class);
    }

    // ─── Accessors ────────────────────────────────────────────

    public function getNombreCompletoAttribute(): string
    {
        return "[{$this->clave}] {$this->nombre}";
    }

    public function getTotalAspirantesAttribute(): int
    {
        return $this->aspirantes()->count();
    }

    public function getTotalAlumnosActivosAttribute(): int
    {
        return $this->alumnos()->where('status', 'activo')->count();
    }
}
