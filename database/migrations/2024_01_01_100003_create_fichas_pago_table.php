<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fichas_pago', function (Blueprint $table) {
            $table->id();
            $table->foreignId('aspirante_id')->constrained('aspirantes')->cascadeOnDelete();
            $table->string('folio_ficha', 30)->unique();    // FP-2024-000123
            $table->decimal('monto', 10, 2);
            $table->string('concepto', 200)->default('Ficha de inscripción');
            $table->date('fecha_emision');
            $table->date('fecha_vencimiento');

            // Pago
            $table->enum('status', ['pendiente', 'pagado', 'vencido', 'cancelado'])
                  ->default('pendiente');
            $table->timestamp('fecha_pago')->nullable();
            $table->enum('metodo_pago', ['conekta', 'paypal', 'transferencia', 'efectivo', 'otro'])
                  ->nullable();
            $table->string('referencia_pago', 100)->nullable();

            // Respuesta del gateway
            $table->string('gateway_order_id', 200)->nullable();
            $table->json('gateway_response')->nullable();

            // Auditoría
            $table->foreignId('generado_por')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index(['status', 'fecha_vencimiento']);
            $table->index('fecha_pago');
            $table->index('metodo_pago');
            $table->index('aspirante_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fichas_pago');
    }
};
