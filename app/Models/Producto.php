<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Facades\Storage;

class Producto extends Model
{
    use HasFactory;

    protected $table = 'productos';    
    protected $fillable = [
        'tipo',
        'nombre',
        'codigo_barra',
        'color',
        'marca',
        'familia',
        'sub_familia',
        'cantidad_minima',
        'precio',
        'descripcion',
        'garantia',
        'imagen_1',
        'imagen_2',
        'imagen_3',
        'img_thumb',
        'is_index',
        'serie',
        'wci',
        'activo',
    ];

    // RELACIONES
    public function inventario()
    {
        return $this->hasOne(Inventario::class, 'producto_id');
    }

     // Relación con DocumentoDetalles
     public function detallesDocumentos()
     {
         return $this->hasMany(DocumentoDetalle::class, 'producto_id');
     }



    // PRA OBTENER LOS DATOS EN UN AJAX
    protected $appends = ['image'];

    // RELACIONES PARA PRODUCTO CARACTERISTICA -MARCA, FAMILIA, SUBFAMILIA-
    public function marca_c()
    {
        return $this->belongsTo(ProductoCaracteristica::class, 'marca');
    }

    public function familia_c()
    {
        return $this->belongsTo(ProductoCaracteristica::class, 'familia');
    }

    public function subFamilia_c()
    {
        return $this->belongsTo(ProductoCaracteristica::class, 'sub_familia');
    }

    // OBTENER IMAGEN
    protected function image1(): Attribute
    {
        return Attribute::make(
            get: fn($value) => $this->getImagenUrl($this->imagen_1)
        );
    }
    protected function image2(): Attribute
    {
        return Attribute::make(
            get: fn($value) => $this->getImagenUrl($this->imagen_2)
        );
    }
    protected function image3(): Attribute
    {
        return Attribute::make(
            get: fn($value) => $this->getImagenUrl($this->imagen_3)
        );
    }
    protected function image(): Attribute
    {
        return Attribute::make(
            get: fn($value) => $this->getImagenUrl($this->img_thumb)
        );
    }

    private function getImagenUrl($value)
    {
        if ($value) {
            if (substr($value, 0, 8) === 'https://') {
                return $value;
            }
            return Storage::url($value);
            //return route('product.image', $this);
        } else {
            return 'https://pcserviciostc.com.mx/img/no_disponible.png';
        }
    }
}
