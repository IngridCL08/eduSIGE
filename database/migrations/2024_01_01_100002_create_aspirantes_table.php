<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('aspirantes', function (Blueprint $table) {
            $table->id();
            $table->string('folio', 20)->unique();          // Generado: ASP-2024-000001

            // Datos personales
            $table->string('nombre', 100);
            $table->string('apellido_paterno', 100);
            $table->string('apellido_materno', 100)->nullable();
            $table->char('curp', 18)->nullable()->unique();
            $table->date('fecha_nacimiento')->nullable();
            $table->enum('sexo', ['M', 'F', 'O'])->nullable();

            // Contacto
            $table->string('email', 191);
            $table->string('telefono', 20)->nullable();

            // Domicilio
            $table->string('direccion', 255)->nullable();
            $table->string('colonia', 150)->nullable();
            $table->string('municipio', 150)->nullable();
            $table->string('estado', 100)->nullable();
            $table->string('cp', 10)->nullable();

            // Procedencia académica
            $table->string('bachillerato', 200)->nullable();
            $table->decimal('promedio_bachillerato', 4, 2)->nullable();
            $table->year('anio_egreso')->nullable();

            // Inscripción
            $table->foreignId('carrera_id')->constrained('carreras')->restrictOnDelete();
            $table->foreignId('periodo_id')->constrained('periodos')->restrictOnDelete();
            $table->enum('status', [
                'registrado',
                'ficha_generada',
                'ficha_pagada',
                'documentos_entregados',
                'admitido',
                'no_admitido',
                'cancelado',
            ])->default('registrado');
            $table->text('observaciones')->nullable();
            $table->timestamps();

            $table->index('status');
            $table->index('folio');
            $table->index(['carrera_id', 'periodo_id']);
            $table->index('email');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('aspirantes');
    }
};
