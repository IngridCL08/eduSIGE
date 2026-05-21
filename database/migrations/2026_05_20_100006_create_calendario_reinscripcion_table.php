<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('calendario_reinscripcion', function (Blueprint $table) {
            $table->id();
            $table->foreignId('periodo_id')->constrained()->cascadeOnDelete();
            $table->foreignId('carrera_id')->constrained()->cascadeOnDelete();
            $table->unsignedTinyInteger('semestre');           // 1-9
            $table->enum('turno', ['matutino', 'vespertino', 'mixto', 'todos'])->default('todos');
            $table->dateTime('fecha_hora_inicio');
            $table->dateTime('fecha_hora_fin');
            $table->boolean('activo')->default(true);
            $table->text('instrucciones')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('calendario_reinscripcion');
    }
};
