<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('adeudos', function (Blueprint $table) {
            $table->enum('tipo', [
                'financiero', 'academico', 'biblioteca',
                'credencial', 'seguro_social', 'documentacion', 'otro',
            ])->default('financiero')->after('concepto');
            $table->text('descripcion')->nullable()->after('tipo');
            $table->string('registrado_por_nombre', 100)->nullable()->after('descripcion');
        });
    }

    public function down(): void
    {
        Schema::table('adeudos', function (Blueprint $table) {
            $table->dropColumn(['tipo', 'descripcion', 'registrado_por_nombre']);
        });
    }
};
