<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Grupo extends Model
{
    use HasFactory;

    protected $fillable = [
        'materia_id', 'periodo_id', 'carrera_id', 'nombre', 'turno',
        'cupo_maximo', 'cupo_actual', 'docente_nombre', 'horario_descripcion', 'activo',
    ];

    protected function casts(): array
    {
        return ['activo' => 'boolean'];
    }

    // ─── Scopes ───────────────────────────────────────────────

    public function scopeActivos(Builder $query): Builder
    {
        return $query->where('activo', true);
    }

    public function scopeConCupo(Builder $query): Builder
    {
        return $query->whereColumn('cupo_actual', '<', 'cupo_maximo');
    }

    // ─── Relaciones ───────────────────────────────────────────

    public function materia(): BelongsTo
    {
        return $this->belongsTo(Materia::class);
    }

    public function periodo(): BelongsTo
    {
        return $this->belongsTo(Periodo::class);
    }

    public function carrera(): BelongsTo
    {
        return $this->belongsTo(Carrera::class);
    }

    public function reinscripcionMaterias(): HasMany
    {
        return $this->hasMany(ReinscripcionMateria::class);
    }

    // ─── Helpers ──────────────────────────────────────────────

    public function getTieneCupoAttribute(): bool
    {
        return $this->cupo_actual < $this->cupo_maximo;
    }

    public function getPorcentajeLlenadoAttribute(): int
    {
        if ($this->cupo_maximo === 0) return 100;
        return (int) round(($this->cupo_actual / $this->cupo_maximo) * 100);
    }
}
