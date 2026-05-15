<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('historial_academico', function (Blueprint $table) {
            $table->id();
            $table->foreignId('alumno_id')->constrained('alumnos')->cascadeOnDelete();
            $table->string('materia', 200);
            $table->string('clave_materia', 20)->nullable();
            $table->unsignedTinyInteger('creditos')->default(0);
            $table->foreignId('periodo_id')->constrained('periodos')->restrictOnDelete();
            $table->decimal('calificacion', 5, 2)->nullable();
            $table->enum('status', ['cursando', 'acreditada', 'reprobada', 'baja'])
                  ->default('cursando');
            $table->timestamps();

            $table->index(['alumno_id', 'periodo_id']);
            $table->index(['alumno_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('historial_academico');
    }
};
