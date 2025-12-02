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
        Schema::create('clasificacion_ubicaciones', function (Blueprint $table) {
            $table->id();
            $table->enum('tipo', ['CLASIFICACIÓN','UBICACIÓN']);
            $table->string('nombre');
            $table->integer('wci');
            $table->boolean('activo')->default(1);
            $table->timestamps();

            // Definir la combinación única de nombre y tipo
            $table->unique(['nombre', 'tipo']);
        });

        DB::table("clasificacion_ubicaciones")
            ->insert([
                [
                    'tipo' => 'CLASIFICACIÓN',
                    'nombre' => 'SIN CLASIFICACIÓN',
                    'wci' => 1,
                    'created_at'    =>  '2022-01-01 09:00:00',
                    'updated_at'    =>  '2022-01-01 09:00:00'
                ],
                [
                    'tipo' => 'CLASIFICACIÓN',
                    'nombre' => 'JARDÍN',
                    'wci' => 1,
                    'created_at'    =>  '2022-01-01 09:00:00',
                    'updated_at'    =>  '2022-01-01 09:00:00'
                ],
                [
                    'tipo' => 'CLASIFICACIÓN',
                    'nombre' => 'PRIMARIA',
                    'wci' => 1,
                    'created_at'    =>  '2022-01-01 09:00:00',
                    'updated_at'    =>  '2022-01-01 09:00:00'
                ],
                [
                    'tipo' => 'CLASIFICACIÓN',
                    'nombre' => 'SECUNDARIA',
                    'wci' => 1,
                    'created_at'    =>  '2022-01-01 09:00:00',
                    'updated_at'    =>  '2022-01-01 09:00:00'
                ],
                [
                    'tipo' => 'CLASIFICACIÓN',
                    'nombre' => 'PREPARATORIA',
                    'wci' => 1,
                    'created_at'    =>  '2022-01-01 09:00:00',
                    'updated_at'    =>  '2022-01-01 09:00:00'
                ],
                [
                    'tipo' => 'CLASIFICACIÓN',
                    'nombre' => 'UNIVERSIDAD',
                    'wci' => 1,
                    'created_at'    =>  '2022-01-01 09:00:00',
                    'updated_at'    =>  '2022-01-01 09:00:00'
                ],
                [
                    'tipo' => 'CLASIFICACIÓN',
                    'nombre' => 'LOGOTIPO',
                    'wci' => 1,
                    'created_at'    =>  '2022-01-01 09:00:00',
                    'updated_at'    =>  '2022-01-01 09:00:00'
                ],

                [
                    'tipo' => 'UBICACIÓN',
                    'nombre' => 'FRENTE IZQUIEDO',
                    'wci' => 1,
                    'created_at'    =>  '2022-01-01 09:00:00',
                    'updated_at'    =>  '2022-01-01 09:00:00'
                ],
                [
                    'tipo' => 'UBICACIÓN',
                    'nombre' => 'FRENTE DERECHO',
                    'wci' => 1,
                    'created_at'    =>  '2022-01-01 09:00:00',
                    'updated_at'    =>  '2022-01-01 09:00:00'
                ],
                [
                    'tipo' => 'UBICACIÓN',
                    'nombre' => 'MANGA IZQUIERDA',
                    'wci' => 1,
                    'created_at'    =>  '2022-01-01 09:00:00',
                    'updated_at'    =>  '2022-01-01 09:00:00'
                ],
                [
                    'tipo' => 'UBICACIÓN',
                    'nombre' => 'MANGA DERECHA',
                    'wci' => 1,
                    'created_at'    =>  '2022-01-01 09:00:00',
                    'updated_at'    =>  '2022-01-01 09:00:00'
                ],
                [
                    'tipo' => 'UBICACIÓN',
                    'nombre' => 'ESPALDA',
                    'wci' => 1,
                    'created_at'    =>  '2022-01-01 09:00:00',
                    'updated_at'    =>  '2022-01-01 09:00:00'
                ],
                [
                    'tipo' => 'UBICACIÓN',
                    'nombre' => 'GORRA FRENTE',
                    'wci' => 1,
                    'created_at'    =>  '2022-01-01 09:00:00',
                    'updated_at'    =>  '2022-01-01 09:00:00'
                ],
                [
                    'tipo' => 'UBICACIÓN',
                    'nombre' => 'COSTADO DERECHO',
                    'wci' => 1,
                    'created_at'    =>  '2022-01-01 09:00:00',
                    'updated_at'    =>  '2022-01-01 09:00:00'
                ],
                [
                    'tipo' => 'UBICACIÓN',
                    'nombre' => 'COSTADO IZQUIERDO',
                    'wci' => 1,
                    'created_at'    =>  '2022-01-01 09:00:00',
                    'updated_at'    =>  '2022-01-01 09:00:00'
                ],
                [
                    'tipo' => 'UBICACIÓN',
                    'nombre' => 'ATRAS',
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
        Schema::dropIfExists('clasificacion_ubicaciones');
    }
};
