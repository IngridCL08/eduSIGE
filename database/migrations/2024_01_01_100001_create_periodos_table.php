<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('periodos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 100);           // Ej: "2024-A"
            $table->year('anio');
            $table->enum('ciclo', ['A', 'B', 'C']);  // semestral: A/B, cuatrimestral: A/B/C
            $table->date('fecha_inicio');
            $table->date('fecha_fin');
            $table->boolean('activo')->default(false);
            $table->timestamps();

            $table->index('activo');
            $table->index(['anio', 'ciclo']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('periodos');
    }
};
