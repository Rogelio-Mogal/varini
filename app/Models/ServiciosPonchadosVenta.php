<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiciosPonchadosVenta extends Model
{
    use HasFactory;
    protected $table = 'servicios_ponchados_ventas';
    protected $fillable = [
        'ponchado_id',
        'cliente_id',
        'producto_id',
        'cliente_alias',
        'prenda',
        'clasificacion_ubicaciones_id',
        'cantidad_piezas',
        'precio_unitario',
        'subtotal',
        'fecha_recepcion',
        'fecha_estimada_entrega',
        'referencia_cliente',
        'fecha_entrega_real',
        'estatus',
        'nota',
        'wci',
        'activo',
    ];

    public function ponchado()
    {
        return $this->belongsTo(Ponchados::class);
    }

    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    public function clasificacionUbicacion()
    {
        return $this->belongsTo(ClasificacionUbicaciones::class, 'clasificacion_ubicaciones_id');
    }

    public function formasPago()
    {
        return $this->morphMany(FormaPagos::class, 'pagable');
    }

    public function ventaDetalles()
    {
        return $this->hasMany(VentaDetalle::class, 'servicio_ponchado_id');
    }

}
