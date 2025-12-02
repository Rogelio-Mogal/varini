<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PrecioPonchado extends Model
{
    use HasFactory;

    protected $table = 'precio_ponchados';    
    protected $fillable = [
        'ponchado_id',
        'cliente_id',
        'precio',
        'ponchado',
        'activo',
    ];

    // Cada registro pertenece a un ponchado
    public function ponchadoRelacionado()
    {
        return $this->belongsTo(Ponchados::class, 'ponchado_id');
    }

    // Cada registro pertenece a un cliente
    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'cliente_id');
    }
}
