<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ExamenAdmision extends Model
{
    protected $table = 'examenes_admision';

    protected $fillable = [
        'aspirante_id', 'fecha_examen', 'calificacion',
        'resultado', 'observaciones', 'aplicado_por',
    ];

    protected function casts(): array
    {
        return [
            'fecha_examen' => 'date',
            'calificacion' => 'decimal:2',
        ];
    }

    public const RESULTADO_APROBADO    = 'aprobado';
    public const RESULTADO_REPROBADO   = 'reprobado';
    public const RESULTADO_LISTA_ESPERA = 'lista_espera';

    public static function resultados(): array
    {
        return [
            self::RESULTADO_APROBADO     => 'Aprobado',
            self::RESULTADO_REPROBADO    => 'Reprobado',
            self::RESULTADO_LISTA_ESPERA => 'Lista de espera',
        ];
    }

    public function aspirante(): BelongsTo
    {
        return $this->belongsTo(Aspirante::class);
    }

    public function aplicadoPor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'aplicado_por');
    }

    public function getResultadoNombreAttribute(): string
    {
        return self::resultados()[$this->resultado] ?? '—';
    }

    public function getResultadoColorAttribute(): string
    {
        return match ($this->resultado) {
            self::RESULTADO_APROBADO     => 'badge-success',
            self::RESULTADO_REPROBADO    => 'badge-danger',
            self::RESULTADO_LISTA_ESPERA => 'badge-warning',
            default                      => 'badge-neutral',
        };
    }
}
