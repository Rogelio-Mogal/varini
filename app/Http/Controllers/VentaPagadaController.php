<?php

namespace App\Http\Controllers;

use App\Models\ServiciosPonchadosVenta;
use App\Models\Venta;
use Illuminate\Http\Request;
use Carbon\Carbon;

class VentaPagadaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
        //$this->middleware(['can:Gestión de roles']);
    }

    public function index()
    {
        $now = new \DateTime();
        return view('ventas_pagadas.index', compact('now'));
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        //
    }

    public function show(ServiciosPonchadosVenta $serviciosPonchadosVenta)
    {
        //
    }

    public function edit(ServiciosPonchadosVenta $serviciosPonchadosVenta)
    {
        //
    }

    public function update(Request $request, ServiciosPonchadosVenta $serviciosPonchadosVenta)
    {
        //
    }

    public function destroy(ServiciosPonchadosVenta $serviciosPonchadosVenta)
    {
        //
    }

    public function ventas_pagadas_index_ajax(Request $request)
    {
        $hoy = Carbon::today();
        $hace7dias = $hoy->copy()->subDays(360);

        // VENTAS PAGADAS
        if ($request->origen == 'ventas.pagadas') {

            setlocale(LC_ALL, "Spanish");

            $mes         = $request->mes;
            $fechaInicio = $request->fechaInicio;
            $fechaFin    = $request->fechaFin;

            /*
            |--------------------------------------------------------------------------
            | 1. BASE QUERY (SIN EJECUTAR)
            |--------------------------------------------------------------------------
            */
            $query = Venta::with([
                    'cliente',
                    'detalles.producto',
                    'detalles.servicioPonchado'
                ])
                ->where('activo', 1);

            /*
            |--------------------------------------------------------------------------
            | 2. APLICAR FILTROS DE FECHA
            |--------------------------------------------------------------------------
            | - Nada enviado     → Mes actual
            | - MES              → Mes seleccionado
            | - RANGO            → Entre fechas
            */
            //if (is_null($mes) && is_null($fechaInicio) && is_null($fechaFin)) {
            if ($request->tipoFiltro === 'NINGUNO') {

                // Mes actual
                $mesActual = Carbon::now()->month;

                $query->whereMonth('fecha', $mesActual);

            } elseif ($request->tipoFiltro === 'MES') {

                // Ej: "2025-07" → 07
                $digitosMes = substr($mes, -2);

                $query->whereMonth('fecha', $digitosMes);

            } elseif ($request->tipoFiltro === 'RANGO') {

                $query->whereBetween('fecha', [
                    "$fechaInicio 00:00:00",
                    "$fechaFin 23:59:59"
                ]);
            }

            /*
            |--------------------------------------------------------------------------
            | 3. EJECUTAR CONSULTA
            |--------------------------------------------------------------------------
            */
            $ventas = $query
                ->orderBy('fecha', 'asc')
                ->get();

            /*
            |--------------------------------------------------------------------------
            | 4. MAPEAR RESULTADO (TU LÓGICA ORIGINAL)
            |--------------------------------------------------------------------------
            */
            $pagado = $ventas->map(function ($venta) {

                return collect([
                    'id' => $venta->id,
                    'tipo' => 'venta',
                    'referencia_cliente' => $venta->folio,
                    //'cliente' => $venta->cliente->full_name ?? 'CLIENTE PÚBLICO',

                    'cliente' => $venta->cliente_id == 17
                            ? ($venta->cliente_alias ?? 'CLIENTE PÚBLICO')
                            : ($venta->cliente->full_name ?? 'CLIENTE PÚBLICO'),

                    'fecha' => $venta->fecha,
                    'estatus' => 'Vendido',
                    'activo' => $venta->activo ?? 1,
                    'acciones' => view('ventas.partials.acciones', [
                        'item' => [
                            'id' => $venta->id,
                            'tipo' => 'venta',
                            'estatus' => 'Vendido',
                            'activo' => $venta->activo,
                            'referencia_cliente' => $venta->folio,
                        ]
                    ])->render(),
                    'detalles' => $venta->detalles->map(function ($detalle) {
                        return [
                            'id' => $detalle->id,
                            'img_thumb' => $detalle->producto?->imagen
                                ? asset('storage/' . ltrim($detalle->producto->imagen, '/'))
                                : asset('images/default.png'),
                            'nombre' =>
                                $detalle->producto->nombre
                                ?? $detalle->servicioPonchado->ponchado->nombre
                                ?? $detalle->producto_comun,
                            'clasificacion' =>
                                $detalle->servicioPonchado?->clasificacionUbicacion->nombre,
                            'cantidad' => $detalle->cantidad,
                            'activo' => $detalle->activo,
                        ];
                    })->values(),
                ]);
            })
            ->sortBy([
                ['cliente', 'asc'],
                ['fecha', 'asc'],
            ])
            ->values();

            /*
            |--------------------------------------------------------------------------
            | 5. RESPUESTA
            |--------------------------------------------------------------------------
            */
            return response()->json(['data' => $pagado]);
        }
    }
}
