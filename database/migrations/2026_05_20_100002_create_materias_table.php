<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('materias', function (Blueprint $table) {
            $table->id();
            $table->string('clave', 20)->unique();
            $table->string('nombre', 120);
            $table->unsignedTinyInteger('creditos');
            $table->unsignedTinyInteger('horas_teoria')->default(0);
            $table->unsignedTinyInteger('horas_practica')->default(0);
            $table->unsignedTinyInteger('semestre_sugerido')->nullable();
            $table->boolean('activa')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('materias');
    }
};
