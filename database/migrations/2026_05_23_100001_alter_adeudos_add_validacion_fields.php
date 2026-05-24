<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('adeudos', function (Blueprint $table) {
            $table->unsignedBigInteger('validado_por_id')->nullable()->after('registrado_por_nombre');
            $table->foreign('validado_por_id')->references('id')->on('users')->nullOnDelete();
            $table->string('metodo_pago', 60)->nullable()->after('referencia_pago');
            $table->string('comprobante_path')->nullable()->after('metodo_pago');
        });
    }

    public function down(): void
    {
        Schema::table('adeudos', function (Blueprint $table) {
            $table->dropForeign(['validado_por_id']);
            $table->dropColumn(['validado_por_id', 'metodo_pago', 'comprobante_path']);
        });
    }
};
