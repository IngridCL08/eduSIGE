<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('comprobantes_transferencia', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ficha_pago_id')->constrained('fichas_pago')->cascadeOnDelete();
            $table->string('archivo_path');
            $table->string('nombre_original', 255)->nullable();
            $table->enum('status', ['pendiente', 'aprobado', 'rechazado'])->default('pendiente');
            $table->foreignId('revisado_por')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('revisado_at')->nullable();
            $table->text('observaciones')->nullable();
            $table->timestamps();

            $table->index('ficha_pago_id');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('comprobantes_transferencia');
    }
};
