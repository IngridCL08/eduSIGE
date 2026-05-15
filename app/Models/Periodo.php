<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Periodo extends Model
{
    use HasFactory;

    protected $fillable = ['nombre', 'anio', 'ciclo', 'fecha_inicio', 'fecha_fin', 'activo'];

    protected function casts(): array
    {
        return [
            'fecha_inicio' => 'date',
            'fecha_fin'    => 'date',
            'activo'       => 'boolean',
        ];
    }

    // ─── Scopes ───────────────────────────────────────────────

    public function scopeActivo(Builder $query): Builder
    {
        return $query->where('activo', true);
    }

    // ─── Relaciones ───────────────────────────────────────────

    public function aspirantes(): HasMany
    {
        return $this->hasMany(Aspirante::class);
    }

    public function alumnos(): HasMany
    {
        return $this->hasMany(Alumno::class, 'periodo_ingreso_id');
    }

    public function historialAcademico(): HasMany
    {
        return $this->hasMany(HistorialAcademico::class);
    }

    // ─── Helpers ──────────────────────────────────────────────

    public static function actual(): ?self
    {
        return static::where('activo', true)->first();
    }

    public function getCicloNombreAttribute(): string
    {
        return match ($this->ciclo) {
            'A' => 'Enero – Junio',
            'B' => 'Julio – Diciembre',
            'C' => 'Septiembre – Diciembre',
            default => $this->ciclo,
        };
    }
}
