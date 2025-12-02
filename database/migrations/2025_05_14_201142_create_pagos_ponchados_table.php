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
        Schema::create('pagos_ponchados', function (Blueprint $table) {
            $table->id();
            // Relación al pedido y al cliente
            $table->foreignId('servicios_ponchados_ventas_id')
                ->constrained('servicios_ponchados_ventas')
                ->onDelete('no action');

            $table->foreignId('cliente_id')
                ->constrained('clientes')
                ->onDelete('no action');

            // Monto total abonado en este pago
            $table->decimal('total_adeudo', $precision = 12, $scale = 3)->default(0); //total_adeudo_en_momento (debia)
            $table->decimal('monto_pagado', $precision = 12, $scale = 3)->default(0); // monto_pagado (abona)
            $table->decimal('saldo_restante', $precision = 12, $scale = 3)->default(0);  // saldo_restante (debe)

            // Fecha del abono
            $table->dateTime('fecha_pago');

            // Opcional: observaciones o nota del abono
            $table->integer('wci');
            $table->boolean('activo')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pagos_ponchados');
    }
};
