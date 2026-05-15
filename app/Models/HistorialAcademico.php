<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HistorialAcademico extends Model
{
    protected $table = 'historial_academico';

    protected $fillable = [
        'alumno_id', 'materia', 'clave_materia',
        'creditos', 'periodo_id', 'calificacion', 'status',
    ];

    protected function casts(): array
    {
        return [
            'calificacion' => 'decimal:2',
            'creditos'     => 'integer',
        ];
    }

    public function alumno(): BelongsTo
    {
        return $this->belongsTo(Alumno::class);
    }

    public function periodo(): BelongsTo
    {
        return $this->belongsTo(Periodo::class);
    }

    public function getStatusNombreAttribute(): string
    {
        return match ($this->status) {
            'cursando'   => 'Cursando',
            'acreditada' => 'Acreditada',
            'reprobada'  => 'Reprobada',
            'baja'       => 'Baja',
            default      => ucfirst($this->status),
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'cursando'   => 'badge-info',
            'acreditada' => 'badge-success',
            'reprobada'  => 'badge-danger',
            'baja'       => 'badge-neutral',
            default      => 'badge-neutral',
        };
    }
}
