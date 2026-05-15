<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('examenes_admision', function (Blueprint $table) {
            $table->id();
            $table->foreignId('aspirante_id')->constrained('aspirantes')->cascadeOnDelete();
            $table->date('fecha_examen');
            $table->decimal('calificacion', 5, 2)->nullable();
            $table->enum('resultado', ['aprobado', 'reprobado', 'lista_espera'])->nullable();
            $table->text('observaciones')->nullable();
            $table->foreignId('aplicado_por')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index('aspirante_id');
            $table->index('resultado');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('examenes_admision');
    }
};
