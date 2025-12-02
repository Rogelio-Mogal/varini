<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FormaPagos extends Model
{
    use HasFactory;

    protected $table = 'forma_pagos';    
    protected $fillable = [
        'pagable_id',
        'pagable_type',
        'metodo',
        'monto',
        'referencia',
        'wci',
        'activo',
    ];

    public function pagable()
    {
        return $this->morphTo();
    }
}
