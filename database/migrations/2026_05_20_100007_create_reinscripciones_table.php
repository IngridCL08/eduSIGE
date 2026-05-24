<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reinscripciones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('alumno_id')->constrained('alumnos')->cascadeOnDelete();
            $table->foreignId('periodo_id')->constrained()->cascadeOnDelete();
            $table->unsignedTinyInteger('semestre');
            $table->enum('status', ['pendiente', 'completada', 'cancelada'])->default('pendiente');
            $table->timestamp('fecha_reinscripcion')->nullable();
            $table->unsignedTinyInteger('creditos_cargados')->default(0);
            $table->foreignId('autorizado_por')->nullable()->constrained('users')->nullOnDelete();
            $table->text('observaciones')->nullable();
            $table->timestamps();

            $table->unique(['alumno_id', 'periodo_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reinscripciones');
    }
};
