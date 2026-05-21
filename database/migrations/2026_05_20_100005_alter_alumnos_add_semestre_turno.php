<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('alumnos', function (Blueprint $table) {
            $table->unsignedTinyInteger('semestre_actual')->default(1)->after('periodo_ingreso_id');
            $table->enum('turno', ['matutino', 'vespertino', 'mixto'])->nullable()->after('semestre_actual');
        });
    }

    public function down(): void
    {
        Schema::table('alumnos', function (Blueprint $table) {
            $table->dropColumn(['semestre_actual', 'turno']);
        });
    }
};
