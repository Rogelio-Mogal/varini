<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetalleAbono extends Model
{
    use HasFactory;

    protected $table = 'detalle_abonos';    
    protected $fillable = [
        'abono_id',
        'venta_id',
        'monto_antes',
        'abonado',
        'saldo_despues',
        'activo',
    ];

    public function venta()
    {
        return $this->belongsTo(\App\Models\Venta::class, 'venta_id');
    }

    public function abono()   // (opcional, si aún no lo tienes)
    {
        return $this->belongsTo(\App\Models\Abono::class, 'abono_id');
    }
}
