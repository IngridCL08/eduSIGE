<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Bitacora extends Model
{
    public $timestamps = false;

    protected $table = 'bitacora';

    protected $fillable = [
        'user_id', 'accion', 'modelo', 'modelo_id',
        'descripcion', 'ip', 'user_agent',
        'datos_antes', 'datos_despues',
    ];

    protected function casts(): array
    {
        return [
            'datos_antes'   => 'array',
            'datos_despues' => 'array',
            'created_at'    => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /** Registra una entrada en la bitácora */
    public static function registrar(
        string  $accion,
        ?string $descripcion = null,
        ?string $modelo = null,
        ?int    $modeloId = null,
        ?array  $datosAntes = null,
        ?array  $datosDespues = null,
    ): void {
        static::create([
            'user_id'       => auth()->id(),
            'accion'        => $accion,
            'descripcion'   => $descripcion,
            'modelo'        => $modelo,
            'modelo_id'     => $modeloId,
            'ip'            => request()->ip(),
            'user_agent'    => request()->userAgent(),
            'datos_antes'   => $datosAntes,
            'datos_despues' => $datosDespues,
            'created_at'    => now(),
        ]);
    }
}
