<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ponchados extends Model
{
    use HasFactory;

    protected $table = 'ponchados';    
    protected $fillable = [
        'clasificacion_ubicaciones_id',
        'nombre',
        'puntadas',
        'ancho',
        'largo',
        'aro',
        'imagen_1',
        'archivo',
        'nota',
        'es_borrador',
        'wci',
        'activo'
    ];

    // RELACIONES

    public function clasificacion()
    {
        return $this->belongsTo(ClasificacionUbicaciones::class, 'clasificacion_ubicaciones_id');
    }

    // Un ponchado puede tener varios precios para distintos clientes
    public function precios()
    {
        return $this->hasMany(PrecioPonchado::class, 'ponchado_id');
    }

    public function detalles()
    {
        return $this->hasMany(PonchadoDetalles::class, 'ponchado_id');
    }
}
