<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('adeudos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('alumno_id')->constrained('alumnos')->cascadeOnDelete();
            $table->foreignId('periodo_id')->nullable()->constrained('periodos')->nullOnDelete();
            $table->string('concepto', 200);
            $table->decimal('monto', 10, 2);
            $table->date('fecha_vencimiento')->nullable();
            $table->enum('status', ['pendiente', 'pagado', 'vencido'])->default('pendiente');
            $table->timestamp('fecha_pago')->nullable();
            $table->string('referencia_pago', 100)->nullable();
            $table->timestamps();

            $table->index('alumno_id');
            $table->index(['status', 'fecha_vencimiento']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('adeudos');
    }
};
