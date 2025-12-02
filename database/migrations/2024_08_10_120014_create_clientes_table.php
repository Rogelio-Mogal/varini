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
        Schema::create('clientes', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('last_name');
            $table->string('full_name')->unique();
            $table->string('telefono')->unique();
            $table->string('direccion')->nullable();
            $table->string('email')->unique();
            $table->decimal('precio_puntada',$precision = 10, $scale = 2);
            $table->enum('tipo_cliente',[
                'CLIENTE PÚBLICO',
                'CLIENTE MEDIO MAYOREO',
                'CLIENTE MAYOREO'
            ])->nullable();
            $table->mediumText('comentario')->nullable();
            $table->integer('ejecutivo_id')->nullable();
            $table->integer('wci');
            $table->boolean('activo')->default(1);
            $table->timestamps();
        });

        DB::table("clientes")
            ->insert([
                [
                    'name' => 'CLIENTE',
                    'last_name' => 'PÚBLICO',
                    'full_name' => 'CLIENTE PÚBLICO',
                    'telefono' => '0',
                    'direccion' => '',
                    'email' => 'cliente_publico@mail.com',
                    'precio_puntada' => 0,
                    'tipo_cliente' => 'CLIENTE PÚBLICO',
                    'comentario' => '',
                    'ejecutivo_id' => null,
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
        Schema::dropIfExists('clientes');
    }
};
