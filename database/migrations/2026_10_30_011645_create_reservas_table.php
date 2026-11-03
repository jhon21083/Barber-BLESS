<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('reservas', function (Blueprint $table) {
            $table->id();
            $table->string('folio')->unique();
            $table->string('cliente');
            $table->string('telefono');
            $table->string('email')->nullable();
            $table->string('servicio');
            $table->string('barbero');
            $table->string('fecha');
            $table->string('hora')->nullable();
            $table->string('duracion')->default('45 min');
            $table->string('precio')->default('Bs. 60');
            $table->text('detalles')->nullable();
            $table->string('metodo_pago')->default('qr'); // qr, local
            $table->string('estado_pago')->default('Pendiente'); // Pagado, Pendiente, En local
            $table->string('comprobante')->nullable();
            $table->string('estado')->default('Confirmada'); // Confirmada, Pendiente, En proceso, Completada, Cancelada
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservas');
    }
};
