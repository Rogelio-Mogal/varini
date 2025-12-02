<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VentaDetalle extends Model
{
    use HasFactory;

    protected $table = 'venta_detalles';    
    protected $fillable = [
        'venta_id',
        'tipo_item',
        'producto_id',
        'servicio_ponchado_id',
        'producto_comun',
        'cantidad',
        'precio',
        'total',
        'activo',
    ];

    public function producto()
    {
        return $this->belongsTo(Producto::class, 'producto_id');
    }

    public function servicioPonchado()
    {
        return $this->belongsTo(ServiciosPonchadosVenta::class, 'servicio_ponchado_id');
    }
}
