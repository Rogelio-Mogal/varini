<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServicioPonchadoEstatus extends Model
{
    use HasFactory;

    protected $table = 'servicio_ponchado_estatus';    
    protected $fillable = [
        'servicio_ponchado_venta_id',
        'estatus',
        'cambiado_en',
        'comentario',
        'usuario_id',
    ];
}
