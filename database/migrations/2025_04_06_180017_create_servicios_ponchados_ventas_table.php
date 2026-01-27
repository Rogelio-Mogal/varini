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
        Schema::create('servicios_ponchados_ventas', function (Blueprint $table) {
            $table->id();
            // Relaciones
            $table->foreignId('ponchado_id')
            ->references('id')
            ->on('ponchados')
            ->onUpdate('no action')
            ->onDelete('no action');
            $table->foreignId('cliente_id')
            ->references('id')
            ->on('clientes')
            ->onUpdate('no action')
            ->onDelete('no action');
            $table->foreignId('producto_id')->nullable()->constrained('productos')->onDelete('set null');

            // Datos del pedido
            $table->string('cliente_alias')->nullable();
            $table->string('prenda')->nullable();
            $table->foreignId('clasificacion_ubicaciones_id')
            ->references('id')
            ->on('clasificacion_ubicaciones')
            ->onUpdate('no action')
            ->onDelete('no action');

            $table->integer('cantidad_piezas');
            $table->decimal('precio_unitario', 10, 2)->nullable();
            $table->decimal('subtotal', 10, 2)->nullable();
            $table->date('fecha_recepcion');
            $table->date('fecha_estimada_entrega');

            // Campos adicionales recomendados
            $table->string('referencia_cliente')->nullable(); // Nombre alternativo del diseño para el cliente
            $table->date('fecha_entrega_real')->nullable();   // Fecha real de entrega

            // Estatus
            $table->enum('estatus', ['Diseño','Bordando','Finalizado', 'Entregado','Eliminado','Programado para bordar'])->default('Diseño');
            $table->text('nota')->nullable();
            $table->integer('wci');
            $table->integer('activo')->default(1);

            $table->timestamps();

            // Índice para mejorar búsquedas
            $table->index(['ponchado_id', 'cliente_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('servicios_ponchados_ventas');
    }
};
