<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Aspirante extends Model implements AuthenticatableContract
{
    use HasFactory, Authenticatable;

    protected $fillable = [
        'folio', 'nombre', 'apellido_paterno', 'apellido_materno',
        'curp', 'fecha_nacimiento', 'sexo',
        'email', 'telefono',
        'direccion', 'colonia', 'municipio', 'estado', 'cp',
        'bachillerato', 'promedio_bachillerato', 'anio_egreso',
        'carrera_id', 'periodo_id', 'status', 'observaciones',
        'password', 'remember_token',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'fecha_nacimiento'      => 'date',
            'promedio_bachillerato' => 'decimal:2',
            'password'              => 'hashed',
        ];
    }

    // ─── Constantes de estatus ────────────────────────────────

    public const STATUS_REGISTRADO             = 'registrado';
    public const STATUS_FICHA_GENERADA         = 'ficha_generada';
    public const STATUS_FICHA_PAGADA           = 'ficha_pagada';
    public const STATUS_DOCUMENTOS_ENTREGADOS  = 'documentos_entregados';
    public const STATUS_EXAMEN_APLICADO        = 'examen_aplicado';
    public const STATUS_ADMITIDO               = 'admitido';
    public const STATUS_NO_ADMITIDO            = 'no_admitido';
    public const STATUS_CANCELADO              = 'cancelado';
    public const STATUS_INSCRITO               = 'inscrito';

    public static function statuses(): array
    {
        return [
            self::STATUS_REGISTRADO            => 'Registrado',
            self::STATUS_FICHA_GENERADA        => 'Ficha generada',
            self::STATUS_FICHA_PAGADA          => 'Ficha pagada',
            self::STATUS_DOCUMENTOS_ENTREGADOS => 'Documentos entregados',
            self::STATUS_EXAMEN_APLICADO       => 'Examen aplicado',
            self::STATUS_ADMITIDO              => 'Admitido',
            self::STATUS_NO_ADMITIDO           => 'No admitido',
            self::STATUS_CANCELADO             => 'Cancelado',
            self::STATUS_INSCRITO              => 'Inscrito',
        ];
    }

    // ─── Scopes ───────────────────────────────────────────────

    public function scopeActivos(Builder $query): Builder
    {
        return $query->whereNotIn('status', [self::STATUS_CANCELADO, self::STATUS_NO_ADMITIDO]);
    }

    public function scopePorPeriodo(Builder $query, int $periodoId): Builder
    {
        return $query->where('periodo_id', $periodoId);
    }

    public function scopePorCarrera(Builder $query, int $carreraId): Builder
    {
        return $query->where('carrera_id', $carreraId);
    }

    public function scopeBuscar(Builder $query, string $termino): Builder
    {
        return $query->where(function (Builder $q) use ($termino) {
            $q->where('folio', 'like', "%{$termino}%")
              ->orWhere('nombre', 'like', "%{$termino}%")
              ->orWhere('apellido_paterno', 'like', "%{$termino}%")
              ->orWhere('apellido_materno', 'like', "%{$termino}%")
              ->orWhere('email', 'like', "%{$termino}%")
              ->orWhere('curp', 'like', "%{$termino}%");
        });
    }

    // ─── Relaciones ───────────────────────────────────────────

    public function carrera(): BelongsTo
    {
        return $this->belongsTo(Carrera::class);
    }

    public function periodo(): BelongsTo
    {
        return $this->belongsTo(Periodo::class);
    }

    public function fichasPago(): HasMany
    {
        return $this->hasMany(FichaPago::class);
    }

    public function fichas(): HasMany
    {
        return $this->hasMany(FichaPago::class);
    }

    public function fichaPago(): HasOne
    {
        return $this->hasOne(FichaPago::class)->latestOfMany();
    }

    public function documentos(): HasMany
    {
        return $this->hasMany(Documento::class);
    }

    public function alumno(): HasOne
    {
        return $this->hasOne(Alumno::class);
    }

    public function examenesAdmision(): HasMany
    {
        return $this->hasMany(ExamenAdmision::class);
    }

    public function examenAdmision(): HasOne
    {
        return $this->hasOne(ExamenAdmision::class)->latestOfMany();
    }

    // ─── Accessors ────────────────────────────────────────────

    public function getNombreCompletoAttribute(): string
    {
        return trim("{$this->nombre} {$this->apellido_paterno} {$this->apellido_materno}");
    }

    public function getStatusNombreAttribute(): string
    {
        return self::statuses()[$this->status] ?? $this->status;
    }

    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            self::STATUS_REGISTRADO            => 'badge-neutral',
            self::STATUS_FICHA_GENERADA        => 'badge-info',
            self::STATUS_FICHA_PAGADA          => 'badge-warning',
            self::STATUS_DOCUMENTOS_ENTREGADOS => 'badge-navy',
            self::STATUS_EXAMEN_APLICADO       => 'badge-info',
            self::STATUS_ADMITIDO              => 'badge-success',
            self::STATUS_INSCRITO              => 'badge-success',
            self::STATUS_NO_ADMITIDO           => 'badge-danger',
            self::STATUS_CANCELADO             => 'badge-danger',
            default                            => 'badge-neutral',
        };
    }

    /** Paso actual del proceso (1-5) para el stepper del portal */
    public function getPasoProcesoAttribute(): int
    {
        return match ($this->status) {
            self::STATUS_REGISTRADO, self::STATUS_FICHA_GENERADA => 1,
            self::STATUS_FICHA_PAGADA                            => 2,
            self::STATUS_DOCUMENTOS_ENTREGADOS                   => 3,
            self::STATUS_EXAMEN_APLICADO                         => 4,
            self::STATUS_ADMITIDO, self::STATUS_INSCRITO         => 5,
            default                                              => 1,
        };
    }

    public function getSexoNombreAttribute(): string
    {
        return match ($this->sexo) {
            'M' => 'Masculino',
            'F' => 'Femenino',
            'O' => 'Otro',
            default => '—',
        };
    }

    public function tieneFichaPagada(): bool
    {
        return $this->fichasPago()->where('status', 'pagado')->exists();
    }

    public function puedeGenerarFicha(): bool
    {
        return !$this->fichasPago()->whereIn('status', ['pendiente', 'pagado'])->exists();
    }

    public function getDomicilioAttribute(): string
    {
        return trim(implode(', ', array_filter([
            $this->direccion, $this->colonia, $this->municipio, $this->estado, $this->cp,
        ]))) ?: '—';
    }

    public function tieneDocumentosCompletos(): bool
    {
        $requeridos = ['acta_nacimiento', 'certificado_bachillerato', 'curp', 'identificacion', 'foto'];
        $entregados = $this->documentos()->whereIn('tipo', $requeridos)->pluck('tipo')->toArray();
        return empty(array_diff($requeridos, $entregados));
    }
}
