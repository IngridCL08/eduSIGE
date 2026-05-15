<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('carreras', function (Blueprint $table) {
            $table->id();
            $table->string('clave', 20)->unique();
            $table->string('nombre', 200);
            $table->text('descripcion')->nullable();
            $table->boolean('activa')->default(true);
            $table->timestamps();

            $table->index('activa');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('carreras');
    }
};
