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
        Schema::create('abonos', function (Blueprint $table) {
            $table->id();

            $table->string('folio')->nullable();
            $table->date('fecha');
            $table->decimal('total_abonado', 12, 2);

            $table->foreignId('cliente_id')->constrained('clientes');
            $table->foreignId('user_id')->constrained('users');
            $table->string('referencia')->nullable();
            
            $table->boolean('activo')->default(true);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('abonos');
    }
};
