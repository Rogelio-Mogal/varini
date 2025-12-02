<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PonchadoDetalles extends Model
{
    use HasFactory;

    protected $table = 'ponchado_detalles';    
    protected $fillable = [
        'ponchado_id',
        'color_tela',
        'color',
        'codigo',
        'otro',
        'activo'
    ];

    public function ponchado()
    {
        return $this->belongsTo(Ponchados::class, 'ponchado_id');
    }
}
