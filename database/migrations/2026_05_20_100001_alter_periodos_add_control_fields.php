<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('periodos', function (Blueprint $table) {
            $table->enum('tipo', ['ene_jun', 'ago_dic'])->default('ene_jun')->after('ciclo');
            $table->unsignedTinyInteger('num_semanas')->default(16)->after('tipo');
            $table->unsignedTinyInteger('semana_actual')->nullable()->after('num_semanas');
            $table->enum('estado', ['planeacion', 'en_curso', 'cerrado'])->default('planeacion')->after('activo');
            $table->boolean('abierto_calificaciones')->default(false)->after('estado');
            $table->date('fecha_apertura_calificaciones')->nullable()->after('abierto_calificaciones');
            $table->date('fecha_cierre_calificaciones')->nullable()->after('fecha_apertura_calificaciones');
            $table->string('motivo_cierre', 255)->nullable()->after('fecha_cierre_calificaciones');
        });
    }

    public function down(): void
    {
        Schema::table('periodos', function (Blueprint $table) {
            $table->dropColumn([
                'tipo', 'num_semanas', 'semana_actual', 'estado',
                'abierto_calificaciones', 'fecha_apertura_calificaciones',
                'fecha_cierre_calificaciones', 'motivo_cierre',
            ]);
        });
    }
};
