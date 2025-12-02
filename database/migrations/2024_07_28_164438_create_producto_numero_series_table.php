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
        Schema::create('producto_numero_series', function (Blueprint $table) {
            $table->id();
            $table->foreignId('producto_id')
                ->references('id')
                ->on('productos')
                ->onUpdate('no action')
                ->onDelete('no action');
            $table->foreignId('proveedor_id')
                ->references('id')
                ->on('proveedores')
                ->onUpdate('no action')
                ->onDelete('no action');
            $table->foreignId('compra_id')
                ->references('id')
                ->on('compras')
                ->onUpdate('no action')
                ->onDelete('no action');
            $table->string('numero_serie')->unique();
            $table->boolean('activo')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('producto_numero_series');
    }
};
