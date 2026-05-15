<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transaccion extends Model
{
    protected $table = 'transacciones';

    protected $fillable = [
        'ficha_pago_id', 'gateway', 'referencia_externa',
        'monto', 'moneda', 'status', 'respuesta_raw', 'ip_cliente',
    ];

    protected function casts(): array
    {
        return [
            'monto'        => 'decimal:2',
            'respuesta_raw' => 'array',
        ];
    }

    public function fichaPago(): BelongsTo
    {
        return $this->belongsTo(FichaPago::class);
    }

    public function getGatewayNombreAttribute(): string
    {
        return match ($this->gateway) {
            'conekta' => 'Conekta',
            'paypal'  => 'PayPal',
            default   => ucfirst($this->gateway),
        };
    }

    public function getStatusNombreAttribute(): string
    {
        return match ($this->status) {
            'pendiente'   => 'Pendiente',
            'exitosa'     => 'Exitosa',
            'fallida'     => 'Fallida',
            'reembolsada' => 'Reembolsada',
            default       => ucfirst($this->status),
        };
    }
}
