<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Alumno extends Model implements AuthenticatableContract
{
    use HasFactory, Authenticatable;

    protected $fillable = [
        'aspirante_id', 'matricula', 'carrera_id', 'periodo_ingreso_id',
        'semestre_actual', 'turno',
        'status', 'promedio_general', 'creditos_acumulados', 'observaciones',
        'password', 'remember_token', 'must_change_password',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'promedio_general'     => 'decimal:2',
            'creditos_acumulados'  => 'integer',
            'password'             => 'hashed',
            'must_change_password' => 'boolean',
        ];
    }

    // ─── Constantes ───────────────────────────────────────────

    public const STATUS_ACTIVO          = 'activo';
    public const STATUS_BAJA_TEMPORAL   = 'baja_temporal';
    public const STATUS_BAJA_DEFINITIVA = 'baja_definitiva';
    public const STATUS_EGRESADO        = 'egresado';
    public const STATUS_TITULADO        = 'titulado';

    public static function statuses(): array
    {
        return [
            self::STATUS_ACTIVO          => 'Activo',
            self::STATUS_BAJA_TEMPORAL   => 'Baja temporal',
            self::STATUS_BAJA_DEFINITIVA => 'Baja definitiva',
            self::STATUS_EGRESADO        => 'Egresado',
            self::STATUS_TITULADO        => 'Titulado',
        ];
    }

    // ─── Scopes ───────────────────────────────────────────────

    public function scopeActivos(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_ACTIVO);
    }

    public function scopePorCarrera(Builder $query, int $carreraId): Builder
    {
        return $query->where('carrera_id', $carreraId);
    }

    public function scopeBuscar(Builder $query, string $termino): Builder
    {
        return $query->where(function (Builder $q) use ($termino) {
            $q->where('matricula', 'like', "%{$termino}%")
              ->orWhereHas('aspirante', fn ($aq) =>
                  $aq->where('nombre', 'like', "%{$termino}%")
                     ->orWhere('apellido_paterno', 'like', "%{$termino}%")
                     ->orWhere('email', 'like', "%{$termino}%")
              );
        });
    }

    // ─── Relaciones ───────────────────────────────────────────

    public function aspirante(): BelongsTo
    {
        return $this->belongsTo(Aspirante::class);
    }

    public function carrera(): BelongsTo
    {
        return $this->belongsTo(Carrera::class);
    }

    public function periodoIngreso(): BelongsTo
    {
        return $this->belongsTo(Periodo::class, 'periodo_ingreso_id');
    }

    public function historialAcademico(): HasMany
    {
        return $this->hasMany(HistorialAcademico::class);
    }

    public function historial(): HasMany
    {
        return $this->hasMany(HistorialAcademico::class);
    }

    public function adeudos(): HasMany
    {
        return $this->hasMany(Adeudo::class);
    }

    public function documentos(): HasMany
    {
        return $this->hasMany(Documento::class, 'aspirante_id', 'aspirante_id');
    }

    // ─── Accessors ────────────────────────────────────────────

    public function getNombreCompletoAttribute(): string
    {
        return $this->aspirante?->nombre_completo ?? '—';
    }

    public function getEmailAttribute(): string
    {
        return $this->aspirante?->email ?? '';
    }

    public function getStatusNombreAttribute(): string
    {
        return self::statuses()[$this->status] ?? $this->status;
    }

    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            self::STATUS_ACTIVO          => 'badge-success',
            self::STATUS_BAJA_TEMPORAL   => 'badge-warning',
            self::STATUS_BAJA_DEFINITIVA => 'badge-danger',
            self::STATUS_EGRESADO        => 'badge-info',
            self::STATUS_TITULADO        => 'badge-navy',
            default                      => 'badge-neutral',
        };
    }

    // ─── Helpers ──────────────────────────────────────────────

    /**
     * Genera número de control: {AÑO}{CLAVE:3}{SEQ:04}
     * Ejemplo: 2026ISC0023
     */
    public static function generarNumeroControl(int $anio, string $claveCar): string
    {
        $prefijo = $anio . strtoupper(substr($claveCar, 0, 3));
        $ultimo  = static::where('matricula', 'like', "{$prefijo}%")->count();
        return $prefijo . str_pad((string) ($ultimo + 1), 4, '0', STR_PAD_LEFT);
    }

    /** @deprecated usar generarNumeroControl() */
    public static function generarMatricula(int $anio): string
    {
        $ultimo = static::whereYear('created_at', '=', $anio)->count();
        return 'MAT-' . $anio . '-' . str_pad((string) ($ultimo + 1), 6, '0', STR_PAD_LEFT);
    }

    public function recalcularPromedio(): void
    {
        $promedio = $this->historialAcademico()
            ->where('status', 'acreditada')
            ->avg('calificacion');

        $this->update(['promedio_general' => $promedio]);
    }

    public function reinscripciones(): HasMany
    {
        return $this->hasMany(Reinscripcion::class, 'alumno_id');
    }

    public function tieneAdeudosPendientes(): bool
    {
        return $this->adeudos()->where('status', 'pendiente')->exists();
    }

    public function getSemestreRomanoAttribute(): string
    {
        $romanos = ['I','II','III','IV','V','VI','VII','VIII','IX','X'];
        return $romanos[($this->semestre_actual ?? 1) - 1] ?? (string) $this->semestre_actual;
    }
}
