<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('barberos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('rol')->default('Barbero');
            $table->string('especialidad')->nullable();
            $table->string('estado')->default('Disponible'); // Disponible, En corte, Descanso, Inactivo
            $table->decimal('rating', 2, 1)->default(5.0);
            $table->unsignedInteger('resenas')->default(0);
            $table->string('horario')->default('09:00 AM - 06:00 PM');
            $table->string('telefono')->nullable();
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('barberos');
    }
};
