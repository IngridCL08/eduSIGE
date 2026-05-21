<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('plan_estudios', function (Blueprint $table) {
            $table->id();
            $table->foreignId('carrera_id')->constrained()->cascadeOnDelete();
            $table->foreignId('materia_id')->constrained()->cascadeOnDelete();
            $table->unsignedTinyInteger('semestre');            // 1-9
            $table->boolean('obligatoria')->default(true);
            $table->json('prerequisitos')->nullable();          // [materia_id, ...]
            $table->timestamps();

            $table->unique(['carrera_id', 'materia_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('plan_estudios');
    }
};
