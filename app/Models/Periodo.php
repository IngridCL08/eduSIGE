<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Periodo extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre', 'anio', 'ciclo', 'tipo', 'fecha_inicio', 'fecha_fin', 'activo',
        'num_semanas', 'semana_actual', 'estado',
        'abierto_calificaciones', 'fecha_apertura_calificaciones',
        'fecha_cierre_calificaciones', 'motivo_cierre',
    ];

    protected function casts(): array
    {
        return [
            'fecha_inicio'                 => 'date',
            'fecha_fin'                    => 'date',
            'fecha_apertura_calificaciones'=> 'date',
            'fecha_cierre_calificaciones'  => 'date',
            'activo'                       => 'boolean',
            'abierto_calificaciones'       => 'boolean',
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
        return $this->hasMany(HistorialAcademico::class, 'periodo_id');
    }

    public function grupos(): HasMany
    {
        return $this->hasMany(Grupo::class, 'periodo_id');
    }

    public function calendarioReinscripcion(): HasMany
    {
        return $this->hasMany(CalendarioReinscripcion::class, 'periodo_id');
    }

    public function reinscripciones(): HasMany
    {
        return $this->hasMany(Reinscripcion::class, 'periodo_id');
    }

    // ─── Helpers ──────────────────────────────────────────────

    public static function actual(): ?self
    {
        return static::where('activo', true)->first();
    }

    public static function enCurso(): ?self
    {
        return static::where('estado', 'en_curso')->first();
    }

    public function getPorcentajeAvanceAttribute(): int
    {
        if (! $this->semana_actual || ! $this->num_semanas) return 0;
        return (int) min(100, round(($this->semana_actual / $this->num_semanas) * 100));
    }

    public function getCicloNombreAttribute(): string
    {
        return match ($this->ciclo) {
            'A' => 'Enero – Junio',
            'B' => 'Agosto – Diciembre',
            'C' => 'Septiembre – Diciembre',
            default => $this->ciclo,
        };
    }

    public function getTipoNombreAttribute(): string
    {
        return match ($this->tipo ?? 'ene_jun') {
            'ene_jun' => 'Enero – Junio',
            'ago_dic' => 'Agosto – Diciembre',
            default   => $this->tipo,
        };
    }

    public function getEstadoColorAttribute(): string
    {
        return match ($this->estado ?? 'planeacion') {
            'en_curso'  => 'badge-success',
            'planeacion'=> 'badge-info',
            'cerrado'   => 'badge-neutral',
            default     => 'badge-neutral',
        };
    }
}
