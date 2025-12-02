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
        Schema::create('configurations', function (Blueprint $table) {
            $table->id();

            $table->string('imagen')->nullable();
            $table->string('razon_social');
            $table->string('direccion')->nullable();
            $table->string('rfc')->nullable();
            $table->string('correo')->nullable();
            $table->string('telefonos')->nullable();
            $table->text('horario')->nullable();
            $table->text('leyenda_inferior')->nullable();
            $table->string('campos_ticket')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('configurations');
    }
};
