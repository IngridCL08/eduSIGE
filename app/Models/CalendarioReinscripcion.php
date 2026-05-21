<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class CalendarioReinscripcion extends Model
{
    use HasFactory;

    protected $table = 'calendario_reinscripcion';

    protected $fillable = [
        'periodo_id', 'carrera_id', 'semestre', 'turno',
        'fecha_hora_inicio', 'fecha_hora_fin', 'activo', 'instrucciones',
    ];

    protected function casts(): array
    {
        return [
            'fecha_hora_inicio' => 'datetime',
            'fecha_hora_fin'    => 'datetime',
            'activo'            => 'boolean',
        ];
    }

    // ─── Scopes ───────────────────────────────────────────────

    public function scopeActivos(Builder $query): Builder
    {
        return $query->where('activo', true);
    }

    public function scopeVigentes(Builder $query): Builder
    {
        $now = Carbon::now();
        return $query->where('activo', true)
                     ->where('fecha_hora_inicio', '<=', $now)
                     ->where('fecha_hora_fin', '>=', $now);
    }

    // ─── Relaciones ───────────────────────────────────────────

    public function periodo(): BelongsTo
    {
        return $this->belongsTo(Periodo::class);
    }

    public function carrera(): BelongsTo
    {
        return $this->belongsTo(Carrera::class);
    }

    // ─── Helpers ──────────────────────────────────────────────

    public function getEstaAbiertaAttribute(): bool
    {
        if (! $this->activo) return false;
        $now = Carbon::now();
        return $now->between($this->fecha_hora_inicio, $this->fecha_hora_fin);
    }

    public function getEstadoAttribute(): string
    {
        $now = Carbon::now();
        if (! $this->activo) return 'inactivo';
        if ($now->lt($this->fecha_hora_inicio)) return 'pendiente';
        if ($now->gt($this->fecha_hora_fin))    return 'cerrado';
        return 'abierto';
    }

    public function getEstadoColorAttribute(): string
    {
        return match ($this->estado) {
            'abierto'  => 'badge-success',
            'pendiente'=> 'badge-info',
            'cerrado'  => 'badge-neutral',
            default    => 'badge-warning',
        };
    }

    public static function vigentePara(int $carreraId, int $semestre): ?self
    {
        return static::vigentes()
                     ->where('carrera_id', $carreraId)
                     ->where('semestre', $semestre)
                     ->first();
    }
}
