<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('documentos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('aspirante_id')->constrained('aspirantes')->cascadeOnDelete();
            $table->enum('tipo', [
                'acta_nacimiento',
                'certificado_bachillerato',
                'curp',
                'identificacion',
                'foto',
                'comprobante_domicilio',
                'otro',
            ]);
            $table->string('nombre_archivo', 255);
            $table->string('ruta_archivo', 500);
            $table->boolean('verificado')->default(false);
            $table->foreignId('verificado_por')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('verificado_at')->nullable();
            $table->timestamps();

            $table->index(['aspirante_id', 'tipo']);
            $table->index('verificado');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('documentos');
    }
};
