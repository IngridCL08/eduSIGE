<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FichaPago extends Model
{
    use HasFactory;

    protected $table = 'fichas_pago';

    protected $fillable = [
        'aspirante_id', 'folio_ficha', 'monto', 'concepto',
        'fecha_emision', 'fecha_vencimiento',
        'status', 'fecha_pago', 'metodo_pago', 'referencia_pago',
        'gateway_order_id', 'gateway_response', 'generado_por',
    ];

    protected function casts(): array
    {
        return [
            'fecha_emision'     => 'date',
            'fecha_vencimiento' => 'date',
            'fecha_pago'        => 'datetime',
            'monto'             => 'decimal:2',
            'gateway_response'  => 'array',
        ];
    }

    // ─── Constantes ───────────────────────────────────────────

    const STATUS_PENDIENTE  = 'pendiente';
    const STATUS_PAGADO     = 'pagado';
    const STATUS_VENCIDO    = 'vencido';
    const STATUS_CANCELADO  = 'cancelado';

    // ─── Scopes ───────────────────────────────────────────────

    public function scopePendientes(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_PENDIENTE);
    }

    public function scopePagadas(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_PAGADO);
    }

    public function scopeVencidas(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_VENCIDO);
    }

    public function scopePorPeriodo(Builder $query, string $inicio, string $fin): Builder
    {
        return $query->whereBetween('fecha_pago', [$inicio, $fin]);
    }

    // ─── Relaciones ───────────────────────────────────────────

    public function aspirante(): BelongsTo
    {
        return $this->belongsTo(Aspirante::class);
    }

    public function generadoPor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'generado_por');
    }

    public function transacciones(): HasMany
    {
        return $this->hasMany(Transaccion::class);
    }

    // ─── Accessors ────────────────────────────────────────────

    public function getStatusNombreAttribute(): string
    {
        return match ($this->status) {
            self::STATUS_PENDIENTE => 'Pendiente',
            self::STATUS_PAGADO    => 'Pagado',
            self::STATUS_VENCIDO   => 'Vencido',
            self::STATUS_CANCELADO => 'Cancelado',
            default                => ucfirst($this->status),
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            self::STATUS_PENDIENTE => 'badge-warning',
            self::STATUS_PAGADO    => 'badge-success',
            self::STATUS_VENCIDO   => 'badge-danger',
            self::STATUS_CANCELADO => 'badge-neutral',
            default                => 'badge-neutral',
        };
    }

    public function getMontoFormateadoAttribute(): string
    {
        return '$' . number_format($this->monto, 2);
    }

    public function isVigente(): bool
    {
        return $this->status === self::STATUS_PENDIENTE
            && $this->fecha_vencimiento->isFuture();
    }

    public function isPagada(): bool
    {
        return $this->status === self::STATUS_PAGADO;
    }

    /** Genera el folio de ficha de pago */
    public static function generarFolio(int $anio): string
    {
        $ultimo = static::whereYear('created_at', $anio)->count();
        return 'FP-' . $anio . '-' . str_pad($ultimo + 1, 6, '0', STR_PAD_LEFT);
    }
}
