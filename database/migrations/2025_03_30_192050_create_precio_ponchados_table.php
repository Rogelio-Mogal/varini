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
        Schema::create('precio_ponchados', function (Blueprint $table) {
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

            $table->decimal('precio',$precision = 12, $scale = 2);
            $table->string('ponchado');
            $table->boolean('activo')->default(1);


            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('precio_ponchados');
    }
};
