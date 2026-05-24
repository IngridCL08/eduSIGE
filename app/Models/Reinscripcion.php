<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Reinscripcion extends Model
{
    use HasFactory;

    protected $fillable = [
        'alumno_id', 'periodo_id', 'semestre', 'status',
        'fecha_reinscripcion', 'creditos_cargados', 'autorizado_por', 'observaciones',
    ];

    protected function casts(): array
    {
        return [
            'fecha_reinscripcion' => 'datetime',
        ];
    }

    public const STATUS_PENDIENTE  = 'pendiente';
    public const STATUS_COMPLETADA = 'completada';
    public const STATUS_CANCELADA  = 'cancelada';

    // ─── Scopes ───────────────────────────────────────────────

    public function scopeCompletadas(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_COMPLETADA);
    }

    // ─── Relaciones ───────────────────────────────────────────

    public function alumno(): BelongsTo
    {
        return $this->belongsTo(Alumno::class);
    }

    public function periodo(): BelongsTo
    {
        return $this->belongsTo(Periodo::class);
    }

    public function autorizador(): BelongsTo
    {
        return $this->belongsTo(User::class, 'autorizado_por');
    }

    public function materias(): HasMany
    {
        return $this->hasMany(ReinscripcionMateria::class);
    }

    // ─── Accessors ────────────────────────────────────────────

    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            self::STATUS_COMPLETADA => 'badge-success',
            self::STATUS_PENDIENTE  => 'badge-warning',
            self::STATUS_CANCELADA  => 'badge-danger',
            default                 => 'badge-neutral',
        };
    }
}
