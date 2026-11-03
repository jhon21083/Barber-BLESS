<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('servicios', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('categoria')->default('Cortes'); // Cortes, Barba, Combos VIP, Tratamientos
            $table->text('descripcion')->nullable();
            $table->string('precio')->default('0');         // solo el número, ej. "60"
            $table->string('duracion')->default('30 min');
            $table->string('imagen')->nullable();           // ruta relativa: servicios/xxx.jpg
            $table->boolean('popular')->default(false);
            $table->boolean('activo')->default(true);
            $table->integer('orden')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('servicios');
    }
};
