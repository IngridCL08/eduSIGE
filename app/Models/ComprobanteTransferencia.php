<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ComprobanteTransferencia extends Model
{
    protected $table = 'comprobantes_transferencia';

    protected $fillable = [
        'ficha_pago_id', 'archivo_path', 'nombre_original',
        'status', 'revisado_por', 'revisado_at', 'observaciones',
    ];

    protected function casts(): array
    {
        return [
            'revisado_at' => 'datetime',
        ];
    }

    public const STATUS_PENDIENTE = 'pendiente';
    public const STATUS_APROBADO  = 'aprobado';
    public const STATUS_RECHAZADO = 'rechazado';

    public function fichaPago(): BelongsTo
    {
        return $this->belongsTo(FichaPago::class);
    }

    public function revisadoPor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'revisado_por');
    }

    public function getUrlAttribute(): string
    {
        return asset('storage/' . $this->archivo_path);
    }

    public function getStatusNombreAttribute(): string
    {
        return match ($this->status) {
            self::STATUS_PENDIENTE => 'Pendiente de revisión',
            self::STATUS_APROBADO  => 'Aprobado',
            self::STATUS_RECHAZADO => 'Rechazado',
            default                => $this->status,
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            self::STATUS_PENDIENTE => 'badge-warning',
            self::STATUS_APROBADO  => 'badge-success',
            self::STATUS_RECHAZADO => 'badge-danger',
            default                => 'badge-neutral',
        };
    }
}
