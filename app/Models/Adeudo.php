<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Adeudo extends Model
{
    protected $fillable = [
        'alumno_id', 'periodo_id', 'tipo', 'concepto', 'descripcion', 'monto',
        'fecha_vencimiento', 'status', 'fecha_pago', 'referencia_pago', 'registrado_por_nombre',
    ];

    protected function casts(): array
    {
        return [
            'monto'            => 'decimal:2',
            'fecha_vencimiento' => 'date',
            'fecha_pago'       => 'datetime',
        ];
    }

    public const STATUS_PENDIENTE = 'pendiente';
    public const STATUS_PAGADO    = 'pagado';
    public const STATUS_VENCIDO   = 'vencido';

    public function alumno(): BelongsTo
    {
        return $this->belongsTo(Alumno::class);
    }

    public function periodo(): BelongsTo
    {
        return $this->belongsTo(Periodo::class);
    }

    public function getMontoFormateadoAttribute(): string
    {
        return '$' . number_format((float) $this->monto, 2);
    }

    public function getStatusNombreAttribute(): string
    {
        return match ($this->status) {
            self::STATUS_PENDIENTE => 'Pendiente',
            self::STATUS_PAGADO    => 'Pagado',
            self::STATUS_VENCIDO   => 'Vencido',
            default                => $this->status,
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            self::STATUS_PENDIENTE => 'badge-warning',
            self::STATUS_PAGADO    => 'badge-success',
            self::STATUS_VENCIDO   => 'badge-danger',
            default                => 'badge-neutral',
        };
    }
}
