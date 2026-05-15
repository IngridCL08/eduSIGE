<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transacciones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ficha_pago_id')->constrained('fichas_pago')->cascadeOnDelete();
            $table->enum('gateway', ['conekta', 'paypal']);
            $table->string('referencia_externa', 200);       // ID en el gateway externo
            $table->decimal('monto', 10, 2);
            $table->char('moneda', 3)->default('MXN');
            $table->enum('status', ['pendiente', 'exitosa', 'fallida', 'reembolsada'])
                  ->default('pendiente');
            $table->json('respuesta_raw')->nullable();
            $table->string('ip_cliente', 45)->nullable();
            $table->timestamps();

            $table->index(['ficha_pago_id', 'status']);
            $table->index('referencia_externa');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transacciones');
    }
};
