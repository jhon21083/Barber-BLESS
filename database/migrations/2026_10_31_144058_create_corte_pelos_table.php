<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('corte_pelos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('filtro');           // slug para el filtro JS
            $table->text('descripcion')->nullable();
            $table->string('precio')->default('Bs. 60');
            $table->string('imagen')->nullable(); // ruta relativa: cortes/xxx.jpg
            $table->string('emoji')->default('✂');
            $table->boolean('activo')->default(true);
            $table->integer('orden')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('corte_pelos');
    }
};
