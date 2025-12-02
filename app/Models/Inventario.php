<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inventario extends Model
{
    use HasFactory;

    protected $table = 'inventarios';    
    protected $fillable = [
        'producto_id',
        'cantidad',
        'producto_apartado',
        'producto_servicio',
        'precio_costo',
        'precio_anterior',
        'precio_publico',
        'precio_medio_mayoreo',
        'precio_mayoreo',
        'activo',
    ];

    // RELACIONES
    public function producto()
    {
        return $this->belongsTo(Producto::class, 'producto_id');
    }

}
