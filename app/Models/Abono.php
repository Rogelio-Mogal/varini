<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Abono extends Model
{
    use HasFactory;

    protected $table = 'abonos';    
    protected $fillable = [
        'folio',
        'fecha',
        'total_abonado',
        'cliente_id',
        'user_id',
        'referencia',
        'activo',
    ];

    public function detalles()
    {
        return $this->hasMany(DetalleAbono::class, 'abono_id');
    }

}
