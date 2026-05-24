<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('grupos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('materia_id')->constrained()->cascadeOnDelete();
            $table->foreignId('periodo_id')->constrained()->cascadeOnDelete();
            $table->foreignId('carrera_id')->constrained()->cascadeOnDelete();
            $table->string('nombre', 20);                      // ISC-3A, ISC-3B
            $table->enum('turno', ['matutino', 'vespertino', 'mixto'])->default('matutino');
            $table->unsignedSmallInteger('cupo_maximo')->default(35);
            $table->unsignedSmallInteger('cupo_actual')->default(0);
            $table->string('docente_nombre', 120)->nullable();
            $table->text('horario_descripcion')->nullable();    // "Lun-Mié 7:00-9:00 / Aula 12"
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('grupos');
    }
};
