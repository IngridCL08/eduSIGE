<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReinscripcionMateria extends Model
{
    use HasFactory;

    protected $table = 'reinscripcion_materias';

    protected $fillable = ['reinscripcion_id', 'materia_id', 'grupo_id', 'tipo'];

    // ─── Relaciones ───────────────────────────────────────────

    public function reinscripcion(): BelongsTo
    {
        return $this->belongsTo(Reinscripcion::class);
    }

    public function materia(): BelongsTo
    {
        return $this->belongsTo(Materia::class);
    }

    public function grupo(): BelongsTo
    {
        return $this->belongsTo(Grupo::class);
    }
}
