<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClasificacionUbicaciones extends Model
{
    use HasFactory;
    protected $table = 'clasificacion_ubicaciones';    
    protected $fillable = [
        'tipo',
        'nombre',
        'wci',
        'activo',
    ];

    // RELACIONES

    public function ponchados()
    {
        return $this->hasMany(Ponchados::class, 'clasificacion_ubicaciones_id');
    }
}
