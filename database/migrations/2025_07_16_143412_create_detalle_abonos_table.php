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
        Schema::create('detalle_abonos', function (Blueprint $table) {
            $table->id();

            $table->foreignId('abono_id')->constrained('abonos')->onDelete('cascade');
            $table->foreignId('venta_id')->constrained('ventas')->onDelete('cascade');

            $table->decimal('monto_antes', 12, 2);  // Cuánto debía antes del abono
            $table->decimal('abonado', 12, 2);      // Cuánto abonó en esta operación
            $table->decimal('saldo_despues', 12, 2);// Cuánto debe después del abono
            $table->boolean('activo')->default(1);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detalle_abonos');
    }
};
