<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    use HasFactory;

    protected $table = 'clientes';    
    protected $fillable = [
        'name',
        'last_name',
        'full_name',
        'telefono',
        'direccion',
        'email',
        'precio_puntada',
        'tipo_cliente',
        'comentario',
        'ejecutivo_id',
        'wci',
        'activo',
    ];

    // Relación con Documentos
    public function documentos()
    {
        return $this->hasMany(Documento::class, 'cliente_id');
    }

    public function ventas()
    {
        return $this->hasMany(Venta::class, 'cliente_id');
    }

    public function abonos()
    {
        return $this->hasMany(Abono::class, 'cliente_id');
    }

     // Relación con precios de ponchados
    public function preciosPonchados()
    {
        return $this->hasMany(PrecioPonchado::class, 'cliente_id');
    }

    public function getDeudaCreditoAttribute()
    {
        return $this->ventas()
        ->where('tipo_venta', 'CRÉDITO')
        ->whereHas('creditos', function ($q) {
            $q->where('saldo_actual', '>', 0)
              ->where('activo', true);
        })
        ->with('creditos') // Carga los créditos para acceder al saldo
        ->get()
        ->sum(fn($venta) => $venta->creditos->saldo_actual ?? 0);
    }

    public function ventasCredito()        // todas las ventas a crédito
    {
        return $this->hasMany(Venta::class, 'cliente_id')
                    ->where('tipo_venta', 'CRÉDITO');
    }

    /** Ventas a crédito con saldo pendiente (saldo_actual > 0 y activas) */
    public function ventasCreditoActivas()
    {
        return $this->hasMany(Venta::class, 'cliente_id')
                    ->where('tipo_venta', 'CRÉDITO')
                    ->whereHas('creditos', function ($q) {
                        $q->where('saldo_actual', '>', 0)
                        ->where('activo', true);
                    })
                    ->with('creditos');  // para acceder a saldo_actual sin N+1
    }
}
