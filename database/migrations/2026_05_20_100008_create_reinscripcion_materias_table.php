<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reinscripcion_materias', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reinscripcion_id')->constrained('reinscripciones')->cascadeOnDelete();
            $table->foreignId('materia_id')->constrained()->cascadeOnDelete();
            $table->foreignId('grupo_id')->nullable()->constrained('grupos')->nullOnDelete();
            $table->enum('tipo', ['regular', 'repeticion', 'curso_especial'])->default('regular');
            $table->timestamps();

            $table->unique(['reinscripcion_id', 'materia_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reinscripcion_materias');
    }
};
