<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VentaCredito extends Model
{
    use HasFactory;

    protected $table = 'venta_creditos';    
    protected $fillable = [
        'venta_id',
        'monto_credito',
        'saldo_actual',
        'liquidado',
        'activo',
    ];
}
