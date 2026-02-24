<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\Ponchados;
use App\Models\PrecioPonchado;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\MessageBag;
use Illuminate\Support\Facades\Storage;

class PrecioPonchadoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
        //$this->middleware(['can:Gestión de roles']);
    }

    public function index()
    {
        return view('ponchados_precios.index');
    }

    public function create(Request $request)
    {
        /*
        $ponchado = new PrecioPonchado();
        $metodo = 'create';
        $detalle = [];

        $ponchado = null;
        $cliente  = null;
        $metodo   = 'create';
        $detalle  = [];
        return view('ponchados_precios.create', compact('metodo','ponchado','detalle' ));*/


        // Obtener query params
        $metodo = 'create';
        $detalle  = [];
        $cliente  = null;
        $cliente_id  = $request->query('cliente_id');
        $ponchado_id = $request->query('ponchado_id');

        if ($cliente_id) {
            $cliente = Cliente::find($cliente_id);
        }

        if ($ponchado_id) {
            $ponchado = Ponchados::find($ponchado_id);
        }else{
            $ponchado = new PrecioPonchado();
        }

        return view('ponchados_precios.create', compact('metodo','ponchado','detalle','cliente'));

    }

    public function store(Request $request)
    {
        $request->validate([
            'detalles_json' => 'required|string',
            'cliente_id' => 'required|exists:clientes,id'
        ]);

        $detalles = json_decode($request->detalles_json, true);

        if (!is_array($detalles) || count($detalles) === 0) {
            return back()->withErrors([
                'detalles_json' => 'Debe agregar al menos un ponchado.'
            ])->withInput();
        }

        foreach ($detalles as $index => $detalle) {

            if (empty($detalle['ponchado_id'])) {
                return back()->withErrors([
                    "Error en fila ".($index+1).": Ponchado requerido."
                ])->withInput();
            }

            if (!isset($detalle['precio']) || !is_numeric($detalle['precio']) || $detalle['precio'] < 0) {
                return back()->withErrors([
                    "Error en fila ".($index+1).": Precio inválido."
                ])->withInput();
            }

            PrecioPonchado::create([
                'ponchado_id' => $detalle['ponchado_id'],
                'cliente_id' => $request->cliente_id,
                'precio' => floatval($detalle['precio']),
                'ponchado' => $detalle['ponchado'],
            ]);
        }

        session()->flash('swal', [
            'icon' => "success",
            'title' => "Operación correcta",
            'text' => "El precio se creó correctamente.",
            'customClass' => [
                'confirmButton' => 'text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800'  // Aquí puedes añadir las clases CSS que quieras
            ],
            'buttonsStyling' => false
        ]);

        return redirect()->route('admin.precio.ponchado.index');
    }

    public function storeDos(Request $request)
    {
        $rules = [
            // Validación para arrays dinámicos (fondos clonables)
            'detalles' => 'required|array|min:1',
            'detalles.*.ponchado_id' => 'required|exists:ponchados,id',
            'detalles.*.precio' => 'required|numeric|min:0',
        ];

        // 3. Definir nombres personalizados para errores más legibles
        $customAttributes = [
            'detalles.*.ponchado_id' => 'ponchado',
            'detalles.*.precio.*' => 'precio',
        ];

        // 4. Ejecutar la validación manualmente
        $validator = Validator::make($request->all(), $rules);
        $validator->setAttributeNames($customAttributes);

        // 5. Comprobar si hay errores
        if ($validator->fails()) {
            $errors = $validator->errors();
            $customErrors = [];

            foreach ($errors->getMessages() as $field => $messages) {
                // Personaliza solo los errores de color o código en los bloques dinámicos
                //if (preg_match('/detalles\.(\d+)\.(ponchado_id|precio)\.(\d+)/', $field, $matches)) {
                if (preg_match('/detalles\.(\d+)\.(ponchado_id|precio)/', $field, $matches)) {
                    $bloque = $matches[1] + 1;
                    $campo = $matches[2];
                    $item = $matches[3] + 1;

                    $campoNombre = $campo === 'precio' ? 'Precio' : 'Código';

                    foreach ($messages as $message) {
                        $customErrors[$field][] = "Error en el bloque {$bloque}, {$campoNombre} {$item}: {$message}";
                    }
                } else {
                    // Otros errores se preservan tal cual
                    $customErrors[$field] = $messages;
                }
            }

            // Regresar errores personalizados a la vista
            $newMessageBag = new MessageBag($customErrors);
            return redirect()->back()->withErrors($newMessageBag)->withInput();
        }

        // 6. Si no hubo errores, obtener los datos validados
        $validatedData = $validator->validated();

        try {
            //dd($request->detalles);
            //1. array auxiliar para guardar los pedidos con sus montos
            $pedidos_data = [];

            foreach ($request->detalles as $detalle) {
                $pedidos_data[] = [
                    'detalle' => $detalle,
                ];
            }

            //2. Recorre los pedidos y crea cada ServiciosPonchadosVenta, guardando el ID y total
            foreach ($pedidos_data as $item) {
                $detalle = $item['detalle'];

                $precio = new PrecioPonchado();
                $precio->ponchado_id = $detalle['ponchado_id'];
                $precio->cliente_id = $request->cliente_id;
                $precio->precio = floatval($detalle['precio']);
                $precio->ponchado = $detalle['ponchado'];
                $precio->save();
            }

            session()->flash('swal', [
                'icon' => "success",
                'title' => "Operación correcta",
                'text' => "El precio se creó correctamente.",
                'customClass' => [
                    'confirmButton' => 'text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800'  // Aquí puedes añadir las clases CSS que quieras
                ],
                'buttonsStyling' => false
            ]);

            return redirect()->route('admin.precio.ponchado.index');

        } catch (\Exception $e) {
            session()->flash('swal', [
                'icon' => "error",
                'title' => "Operación fallida",
                'text' => "Hubo un error durante el proceso, por favor intente más tarde.",
                'customClass' => [
                    'confirmButton' => 'text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800'  // Aquí puedes añadir las clases CSS que quieras
                ],
                'buttonsStyling' => false
            ]);

            return redirect()->back()
                ->withInput($request->all()) // Aquí solo pasas los valores del formulario
                ->with('status', 'Hubo un error al ingresar los datos, por favor intente de nuevo.')
                ->withErrors(['error' => $e->getMessage()]); // Aquí pasas el mensaje de error
        }
    }

    public function show(PrecioPonchado $precioPonchado)
    {
        //
    }

    public function edit($id)
    {
        $ponchado = PrecioPonchado::where('id', $id)
                ->with(['cliente', 'ponchadoRelacionado'])
                ->get()
                ->first();

        if($ponchado->activo == 1){
            $metodo = 'edit';

            return view('ponchados_precios.edit', compact('ponchado','metodo'));
        }else{
            return redirect()->route('admin.precio.ponchado.index');
        }
    }

    public function update(Request $request, $id)
    {
        $ponchado = PrecioPonchado::findorfail($id);

        // Asignar los valores del request al modelo
        $ponchado->precio = $request->precio;
        $ponchado->cliente_id = $request->cliente_id;

        if ($ponchado->isDirty()) {
            $rules = [
                'ponchado_id' => 'required|exists:ponchados,id',
                'cliente_id' => 'required|exists:clientes,id',
                'precio' => 'required|numeric|min:0',
            ];

            $validatedData = $request->validate($rules);

            try {
                $ponchado->precio = $request->precio;
                $ponchado->cliente_id = $request->cliente_id;
                $ponchado->save();

                session()->flash('swal', [
                    'icon' => "success",
                    'title' => "Operación correcta",
                    'text' => "El precio se actualizó correctamente.",
                    'customClass' => [
                        'confirmButton' => 'text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800'  // Aquí puedes añadir las clases CSS que quieras
                    ],
                    'buttonsStyling' => false  // Deshabilitar el estilo predeterminado de SweetAlert2
                ]);

                return redirect()->route('admin.precio.ponchado.index');
            } catch (\Exception $e) {
                dd($e);
                session()->flash('swal', [
                    'icon' => "error",
                    'title' => "Operación fallida",
                    'text' => "Hubo un error durante el proceso, por favor intente más tarde.",
                    'customClass' => [
                        'confirmButton' => 'text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800'  // Aquí puedes añadir las clases CSS que quieras
                    ],
                    'buttonsStyling' => false  // Deshabilitar el estilo predeterminado de SweetAlert2
                ]);
                return redirect()->back()
                    ->withInput($request->all()) // Aquí solo pasas los valores del formulario
                    ->with('status', 'Hubo un error al ingresar los datos, por favor intente de nuevo.')
                    ->withErrors(['error' => $e->getMessage()]); // Aquí pasas el mensaje de error
            }
        } else {
            session()->flash('swal', [
                'icon' => "info",
                'title' => "Sin cambios",
                'text' => "No se realizaron cambios en el precio.",
                'customClass' => [
                    'confirmButton' => 'text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800'
                ],
                'buttonsStyling' => false
            ]);

            return redirect()->route('admin.precio.ponchado.index');
        }
    }


    public function destroy($id)
    {
        try {
            $ponchado = PrecioPonchado::findorfail($id);

            if($ponchado->activo == 0){
                return response()->json([
                    'swal' => [
                        'icon' => "success",
                        'title' => "Operación correcta",
                        'text' => "El precio se eliminó correctamente.",
                        'customClass' => [
                            'confirmButton' => 'text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800'
                        ],
                        'buttonsStyling' => false
                    ],
                    'success' => 'El precio se eliminó correctamente.'
                ], 200);
            }else{
                $ponchado->update([
                    'activo' => 0
                ]);

                // Respuesta exitosa
                return response()->json([
                    'swal' => [
                        'icon' => "success",
                        'title' => "Operación correcta",
                        'text' => "El precio se eliminó correctamente.",
                        'customClass' => [
                            'confirmButton' => 'text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800'
                        ],
                        'buttonsStyling' => false
                    ],
                    'success' => 'El precio se eliminó correctamente.'
                ], 200);
            }


        } catch (\Exception $e) {
            session()->flash('swal', [
                'icon' => "error",
                'title' => "Operación fallida",
                'text' => "Hubo un error durante el proceso, por favor intente más tarde.",
                'customClass' => [
                    'confirmButton' => 'text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800'  // Aquí puedes añadir las clases CSS que quieras
                ],
                'buttonsStyling' => false  // Deshabilitar el estilo predeterminado de SweetAlert2
            ]);
            return redirect()->back()
                ->with('status', 'Hubo un error al ingresar los datos, por favor intente de nuevo.')
                ->withErrors(['error' => $e->getMessage()]); // Aquí pasas el mensaje de error
        }
    }

    public function ponchados_precios_ajax(Request $request)
    {
        // TODOS LOS PONCHADOS-PRECIOS PARA EL INDEX
        if($request->origen == 'ponchados.precios'){
            $ponchados = PrecioPonchado::where('activo', 1)
                ->with(['cliente', 'ponchadoRelacionado'])
                ->get();

            // Modificar cada producto para agregar la URL completa de la imagen
            $ponchados = $ponchados->map(function ($producto) {
                /*
                $img = asset('images/default.png');

                if ($producto->ponchadoRelacionado && $producto->ponchadoRelacionado->imagen_1) {
                    $rutaImagen = ltrim($producto->ponchadoRelacionado->imagen_1, '/');

                    if (Storage::disk('public')->exists($rutaImagen)) {
                        $img = asset('storage/' . $rutaImagen);
                    }
                }
                */

                return [
                    'id' => $producto->id,
                    'img_thumb' => $producto->ponchadoRelacionado->imagen_1
                        ? asset('storage/' . ltrim($producto->ponchadoRelacionado->imagen_1, '/'))
                        : asset('images/default.png'),
                    //'img_thumb' => $img,
                    'nombre' => $producto->ponchadoRelacionado->nombre,
                    'ponchado_id' => $producto->ponchadoRelacionado->id,
                    'cliente' => $producto->cliente->full_name,
                    'precio' => $producto->precio,
                ];
            });

            return response()->json(['data' => $ponchados]);
        }

        // PONCHADOS-PRECIOS PARA EL APARTADO DE PEDIDOS
        if($request->origen == 'ponchados.precios.pedidos'){
            $ponchados = ServiciosPonchadosVenta::where('servicios_ponchados_ventas.activo', 1)
                ->with(['ponchado', 'cliente', 'clasificacionUbicacion'])
                ->get();

                // Modificar cada producto para agregar la URL completa de la imagen
            $ponchados = $ponchados->map(function ($producto) {
                return [
                    'id' => $producto->id,
                    'img_thumb' => $producto->ponchado->imagen_1 ? asset('storage/' . ltrim($producto->ponchado->imagen_1, '/')) : asset('images/default.png'),
                    'nombre' => $producto->referencia_cliente,
                    'clasificacion' =>  $producto->clasificacionUbicacion->nombre ?? null,
                    'puntadas' => $producto->ponchado->puntadas,
                    'ancho' => $producto->ponchado->ancho,
                    'largo' => $producto->ponchado->largo,
                    'aro' => $producto->ponchado->aro,
                    'estatus' => $producto->estatus,
                    'activo' => $producto->activo,
                    'piezas' => $producto->cantidad_piezas,
                    'total' => $producto->subtotal,
                    'fecha' => $producto->fecha_estimada_entrega,
                ];
            });

            return response()->json(['data' => $ponchados]);
        }

    }
}
