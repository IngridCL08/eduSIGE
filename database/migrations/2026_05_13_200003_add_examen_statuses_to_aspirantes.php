<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Ampliar el ENUM de status para incluir examen_aplicado e inscrito
        DB::statement("ALTER TABLE aspirantes MODIFY status ENUM(
            'registrado',
            'ficha_generada',
            'ficha_pagada',
            'documentos_entregados',
            'examen_aplicado',
            'admitido',
            'no_admitido',
            'cancelado',
            'inscrito'
        ) NOT NULL DEFAULT 'registrado'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE aspirantes MODIFY status ENUM(
            'registrado',
            'ficha_generada',
            'ficha_pagada',
            'documentos_entregados',
            'admitido',
            'no_admitido',
            'cancelado'
        ) NOT NULL DEFAULT 'registrado'");
    }
};
