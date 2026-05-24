<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Materia extends Model
{
    use HasFactory;

    protected $fillable = [
        'clave', 'nombre', 'creditos',
        'horas_teoria', 'horas_practica', 'semestre_sugerido', 'activa',
    ];

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

    public function planEstudios(): HasMany
    {
        return $this->hasMany(PlanEstudio::class);
    }

    public function grupos(): HasMany
    {
        return $this->hasMany(Grupo::class);
    }

    public function carreras(): BelongsToMany
    {
        return $this->belongsToMany(Carrera::class, 'plan_estudios')
                    ->withPivot(['semestre', 'obligatoria', 'prerequisitos'])
                    ->withTimestamps();
    }

    // ─── Accessors ────────────────────────────────────────────

    public function getHorasTotalesAttribute(): int
    {
        return $this->horas_teoria + $this->horas_practica;
    }

    public function getNombreCompletoAttribute(): string
    {
        return "{$this->clave} — {$this->nombre}";
    }
}
