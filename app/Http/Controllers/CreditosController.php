<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\VentaCredito;
use Illuminate\Http\Request;

class CreditosController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
        //$this->middleware(['can:Gestión de roles']);
    }

    public function index()
    {
        return view('creditos.index');
    }

    public function create()
    {
        return redirect()->route('admin.creditos.index');
    }

    public function store(Request $request)
    {
        //
    }

    public function show(VentaCredito $ventaCredito)
    {
        //
    }

    public function edit(VentaCredito $ventaCredito)
    {
        return redirect()->route('admin.creditos.index');
    }

    public function update(Request $request, VentaCredito $ventaCredito)
    {
        //
    }

    public function destroy(VentaCredito $ventaCredito)
    {
        //
    }

    public function creditos_index_ajax ()
    {
        $clientes = Cliente::whereHas('ventas.creditos', function ($query) {
            $query->where('activo', 1)
                ->where('liquidado', 0);
        })
        ->with(['ventas.creditos' => function ($query) {
            $query->where('activo', 1)
                ->where('liquidado', 0);
        }])
        ->get();

        $creditos = $clientes->map(function($cliente) {
            $totalCredito = $cliente->ventas->sum(function($venta) {
                return $venta->creditos ? $venta->creditos->saldo_actual : 0;
            });

            return [
                'cliente_id' => $cliente->id,
                'full_name' => $cliente->full_name,
                'total_credito' => $totalCredito,
            ];
        });

        return response()->json(['data' => $creditos]);
    }
}
