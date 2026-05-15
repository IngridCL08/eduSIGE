<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('fichas_pago', function (Blueprint $table) {
            $table->string('referencia_bancaria', 30)->nullable()->after('concepto');
            $table->string('clabe_destino', 18)->nullable()->after('referencia_bancaria');
            $table->string('banco_destino', 60)->nullable()->after('clabe_destino');
        });
    }

    public function down(): void
    {
        Schema::table('fichas_pago', function (Blueprint $table) {
            $table->dropColumn(['referencia_bancaria', 'clabe_destino', 'banco_destino']);
        });
    }
};
