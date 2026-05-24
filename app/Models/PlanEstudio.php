<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PlanEstudio extends Model
{
    use HasFactory;

    protected $table = 'plan_estudios';

    protected $fillable = [
        'carrera_id', 'materia_id', 'semestre', 'obligatoria', 'prerequisitos',
    ];

    protected function casts(): array
    {
        return [
            'obligatoria'  => 'boolean',
            'prerequisitos' => 'array',
        ];
    }

    // ─── Relaciones ───────────────────────────────────────────

    public function carrera(): BelongsTo
    {
        return $this->belongsTo(Carrera::class);
    }

    public function materia(): BelongsTo
    {
        return $this->belongsTo(Materia::class);
    }
}
