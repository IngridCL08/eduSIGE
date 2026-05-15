<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('alumnos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('aspirante_id')->unique()->constrained('aspirantes')->restrictOnDelete();
            $table->string('matricula', 20)->unique();
            $table->foreignId('carrera_id')->constrained('carreras')->restrictOnDelete();
            $table->foreignId('periodo_ingreso_id')->constrained('periodos')->restrictOnDelete();
            $table->enum('status', [
                'activo',
                'baja_temporal',
                'baja_definitiva',
                'egresado',
                'titulado',
            ])->default('activo');
            $table->decimal('promedio_general', 4, 2)->nullable();
            $table->unsignedSmallInteger('creditos_acumulados')->default(0);
            $table->text('observaciones')->nullable();
            $table->timestamps();

            $table->index('status');
            $table->index(['carrera_id', 'periodo_ingreso_id']);
            $table->index('matricula');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('alumnos');
    }
};
