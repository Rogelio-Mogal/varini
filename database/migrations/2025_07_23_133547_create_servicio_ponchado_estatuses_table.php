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
        Schema::create('servicio_ponchado_estatus', function (Blueprint $table) {
            $table->id();

            $table->foreignId('servicio_ponchado_venta_id')->constrained('servicios_ponchados_ventas')->onDelete('cascade');
            $table->enum('estatus', ['Diseño','Bordando','Finalizado', 'Entregado','Eliminado','Programado para bordar']);
            $table->timestamp('cambiado_en')->useCurrent(); // Cuándo ocurrió el cambio
            $table->text('comentario')->nullable(); // Opcional: motivo o detalle del cambio
            $table->foreignId('usuario_id')->nullable()->constrained('users')->onDelete('set null'); // Quién hizo el cambio, si aplica

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('servicio_ponchado_estatus');
    }
};
