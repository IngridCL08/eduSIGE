<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Documento extends Model
{
    protected $fillable = [
        'aspirante_id', 'tipo', 'nombre_archivo',
        'ruta_archivo', 'verificado', 'verificado_por', 'verificado_at',
    ];

    protected function casts(): array
    {
        return [
            'verificado'    => 'boolean',
            'verificado_at' => 'datetime',
        ];
    }

    public static function tipos(): array
    {
        return [
            'acta_nacimiento'         => 'Acta de nacimiento',
            'certificado_bachillerato' => 'Certificado de bachillerato',
            'curp'                    => 'CURP',
            'identificacion'          => 'Identificación oficial',
            'foto'                    => 'Fotografía',
            'comprobante_domicilio'   => 'Comprobante de domicilio',
            'otro'                    => 'Otro documento',
        ];
    }

    public function aspirante(): BelongsTo
    {
        return $this->belongsTo(Aspirante::class);
    }

    public function verificadoPor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verificado_por');
    }

    public function getTipoNombreAttribute(): string
    {
        return static::tipos()[$this->tipo] ?? $this->tipo;
    }

    public function getUrlAttribute(): string
    {
        return asset('storage/' . $this->ruta_archivo);
    }
}
