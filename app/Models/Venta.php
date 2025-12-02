<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Venta extends Model
{
    use HasFactory;

    protected $table = 'ventas';    
    protected $fillable = [
        'user_id',
        'cliente_id',
        'folio',
        'fecha',
        'total',
        'monto_credito',
        'monto_recibido',
        'cambio',
        'tipo_venta',
        'activo',
    ];

    public function creditos()
    {
        return $this->hasOne(VentaCredito::class, 'venta_id');
    }

    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'cliente_id');
    }

    public function detalles()
    {
        return $this->hasMany(VentaDetalle::class, 'venta_id');
    }
}
