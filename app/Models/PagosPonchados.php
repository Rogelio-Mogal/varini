<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PagosPonchados extends Model
{
    use HasFactory;

    protected $table = 'pagos_ponchados';    
    protected $fillable = [
        'servicios_ponchados_ventas_id',
        'cliente_id',
        'total_adeudo',
        'monto_pagado',
        'saldo_restante',
        'fecha_pago',
        'wci',
        'activo',
    ];
}
