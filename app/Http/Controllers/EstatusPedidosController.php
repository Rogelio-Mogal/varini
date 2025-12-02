<?php

namespace App\Http\Controllers;

use App\Models\ServicioPonchadoEstatus;
use App\Models\ServiciosPonchadosVenta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EstatusPedidosController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
        //$this->middleware(['can:Gestión de roles']);
    }

    public function index()
    {
        //
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        //
    }

    public function show(ServicioPonchadoEstatus $servicioPonchadoEstatus)
    {
        //
    }

    public function edit(ServicioPonchadoEstatus $servicioPonchadoEstatus)
    {
        //
    }

    public function update(Request $request, $id)
    {
        try {
            DB::beginTransaction();
                $pedido = ServiciosPonchadosVenta::findOrFail($id);

                // Validar si el estatus cambió
                if ($request->filled('estatus')) {
                    // Guardar el cambio de estatus en el historial
                    ServicioPonchadoEstatus::create([
                        'servicio_ponchado_venta_id' => $pedido->id,
                        'estatus' => $request->estatus,
                        'comentario' => $request->comentario,
                        'usuario_id' => auth()->id(),
                    ]);

                    // Actualizar el estatus actual en la tabla principal
                    $pedido->estatus = $request->estatus;
                    $pedido->save();
                }
            DB::commit();
            
            // VERIFICA EL ORIGEN DE AJAX O DE MANERA NORMAL
            if ($request->ajax() || $request->wantsJson()) {
                // Respuesta JSON para AJAX
                return response()->json([
                    'success' => true,
                    'message' => 'Estatus actualizado correctamente',
                    'data' => [
                        'id' => $pedido->id,
                        'estatus' => $pedido->estatus
                    ]
                ]);
            } else {
                // Comportamiento normal para submit
                session()->flash('swal', [
                    'icon' => "success",
                    'title' => "Operación correcta",
                    'text' => "Se actualizó el estatus.",
                    'customClass' => [
                        'confirmButton' => 'text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800'
                    ],
                    'buttonsStyling' => false
                ]);
                return redirect()->back()->with('success', 'Estatus actualizado correctamente');
            }

        } catch (\Exception $e) {
            DB::rollback();

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Hubo un error durante el proceso, por favor intente más tarde',
                ], 500);
            } else {
                session()->flash('swal', [
                    'icon' => "error",
                    'title' => "Operación fallida",
                    'text' => "Hubo un error durante el proceso, por favor intente más tarde.",
                    'customClass' => [
                        'confirmButton' => 'text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800'
                    ],
                    'buttonsStyling' => false
                ]);
                return redirect()->back();
            }
        }
        
    }

    public function destroy(ServicioPonchadoEstatus $servicioPonchadoEstatus)
    {
        //
    }
}
