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
        Schema::create('ponchados', function (Blueprint $table) {
            $table->id();
            $table->foreignId('clasificacion_ubicaciones_id') 
            ->references('id')
            ->on('clasificacion_ubicaciones')
            ->onUpdate('no action')
            ->onDelete('no action');
            $table->string('nombre')->unique();
            $table->integer('puntadas')->nullable();
            $table->text('ancho')->nullable();
            $table->text('largo')->nullable();
            $table->text('aro')->nullable();
            $table->string('imagen_1')->nullable();
            $table->string('archivo')->nullable();
            $table->text('nota')->nullable();
            $table->boolean('es_borrador')->default(0); // Marca ponchados incompletos
            $table->integer('wci');
            $table->boolean('activo')->default(1);
            $table->timestamps();
        });

        DB::table("ponchados")
            ->insert([
                [
                    'clasificacion_ubicaciones_id' => 1,
                    'nombre' => 'PONCHADO POR DEFINIR',
                    'puntadas' => 0,
                    'ancho' => 0,
                    'largo' => 0,
                    'aro' => 0,
                    'imagen_1' => '',
                    'nota' => '',
                    'wci' => 1,
                    'created_at'    =>  '2022-01-01 09:00:00',
                    'updated_at'    =>  '2022-01-01 09:00:00'
                ],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ponchados');
    }
};
