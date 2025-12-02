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
        Schema::create('ponchado_detalles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ponchado_id') 
            ->references('id')
            ->on('ponchados')
            ->onUpdate('no action')
            ->onDelete('no action');
            $table->string('color_tela');
            $table->string('color');
            $table->string('codigo')->nullable();
            $table->string('otro')->nullable();
            $table->boolean('activo')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ponchado_detalles');
    }
};
