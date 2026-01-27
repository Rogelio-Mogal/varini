<?php

namespace App\Http\Controllers;

use App\Models\ClasificacionUbicaciones;
use App\Models\FormaPagos;
use App\Models\PonchadoDetalles;
use App\Models\Ponchados;
use App\Models\ServicioPonchadoEstatus;
use App\Models\ServiciosPonchadosVenta;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Illuminate\Support\Str;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use PDF;

class PonchadosPedidosController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
        //$this->middleware(['can:Gestión de roles']);
    }

    public function index()
    {
        return view('ponchados_pedidos.index');
    }

    public function create()
    {
        $pedidoBase = new ServiciosPonchadosVenta();
        $ponchado = collect();

        $marcaValues = ClasificacionUbicaciones::where('tipo', 'UBICACIÓN')
            ->where('activo', 1)
            ->select('id', 'nombre')
            ->get();

        $metodo2 = 'create';

        $detalle = collect();

        $formasPago = [
            ['metodo' => '', 'monto' => '', 'referencia' => '']
        ];

        // Año actual
        $anio = date('Y');

        // Obtener el último folio del año
        $ultimoFolio = ServiciosPonchadosVenta::whereYear('created_at', $anio)
            ->where('referencia_cliente', 'like', "PEDIDO-%-$anio%")
            ->orderBy('id', 'desc')
            ->first();

        if ($ultimoFolio) {
            // Extraer el número del folio (PEDIDO-12-2025 → 12)
            preg_match('/PEDIDO-(\d+)-' . $anio . '/', $ultimoFolio->referencia_cliente, $matches);
            $numero = isset($matches[1]) ? intval($matches[1]) + 1 : 1;
        } else {
            $numero = 1;
        }

        $folioGenerado = "PEDIDO-{$numero}-{$anio}";

        return view('ponchados_pedidos.create', compact('metodo2', 'pedidoBase', 'ponchado', 'marcaValues', 'detalle', 'formasPago', 'folioGenerado'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            //'ponchado_id' => 'required|exists:ponchados,id',
            'cliente_id' => 'required|exists:clientes,id',
            'clasificacion_ubicaciones_id' => 'required|exists:clasificacion_ubicaciones,id',
            'cantidad_piezas' => 'required|integer|min:1',
            'precio_unitario' => 'required|numeric|min:0',
            'subtotal' => 'required|numeric|min:0',
            'fecha_estimada_entrega' => 'required|date',
            'referencia_cliente' => 'required|string|min:2|max:255',

        ]);

        /*
        // Personalizar nombres de atributos
        $customAttributes = [
            'fondo_tela.*' => 'fondo',
            'color.*' => 'color',
            'codigo.*' => 'código',
        ];
        $validator->setAttributeNames($customAttributes);

        // Check validation and add custom messages
        if ($validator->fails()) {
            $errors = $validator->errors();
            $customErrors = [];

            foreach ($errors->getMessages() as $field => $messages) {
                if (preg_match('/fondos\.(\d+)\.(color|codigo)\.(\d+)/', $field, $matches)) {
                    $bloque = $matches[1] + 1;
                    $campo = $matches[2];
                    $item = $matches[3] + 1;

                    $campoNombre = $campo === 'color' ? 'Color' : 'Código';

                    foreach ($messages as $message) {
                        $customErrors[$field][] = "Error en el bloque {$bloque}, {$campoNombre} {$item}: {$message}";
                    }
                } else {
                    // Preserva los errores que no coinciden con el patrón
                    $customErrors[$field] = $messages;
                }
            }

            $newMessageBag = new \Illuminate\Support\MessageBag($customErrors);

            return redirect()->back()->withErrors($newMessageBag)->withInput();
        }
        */

        try {
            DB::beginTransaction();

            //1. array auxiliar para guardar los pedidos con sus montos
            $precio_puntada = floatval($request->precio_puntada);
            $pedidos_data = [];

            foreach ($request->detalles as $detalle) {
                //$cantidad = intval($detalle['cantidad']);
                //$total = $cantidad * $precio_puntada;

                $cantidad = intval($detalle['cantidad']);
                $precio   = floatval($detalle['precio']);
                $total    = $cantidad * $precio;

                $pedidos_data[] = [
                    'detalle' => $detalle,
                    'total' => $total,
                ];
            }

            //2. Recorre los pedidos y crea cada ServiciosPonchadosVenta, guardando el ID y total
            $pedidos = [];

            // Año actual
            //$anio = date('Y');

            // Obtener el último folio del año
            //$ultimoFolio = ServiciosPonchadosVenta::whereYear('created_at', $anio)
            //    ->where('referencia_cliente', 'like', "PEDIDO-%-$anio")
            //    ->orderBy('id', 'desc')
            //    ->first();

            /* ESTE NO --- if ($ultimoFolio) {
                // Extraer el número del folio (PEDIDO-12-2025 → 12)
                preg_match('/PEDIDO-(\d+)-' . $anio . '/', $ultimoFolio->referencia_cliente, $matches);
                $numero = isset($matches[1]) ? intval($matches[1]) + 1 : 1;
            } else {
                $numero = 1;
            }*/


            //if ($ultimoFolio) {
            //    preg_match('/PEDIDO-(\d+)-' . $anio . '/', $ultimoFolio->referencia_cliente, $matches);
            //    $numero = isset($matches[1]) ? intval($matches[1]) + 1 : 1;
            //} else {
            //    $numero = 1;
            //}

            //$folioGenerado = "PEDIDO-{$numero}-{$anio}";


            ////    CODIGO TEMPORAL, ASIGNA CONSECUTIVO DE FOLIO ////
            $anio = date('Y');

            // Obtener todos los folios válidos del año actual
            $folios = ServiciosPonchadosVenta::whereYear('created_at', $anio)
                ->where('referencia_cliente', 'like', "PEDIDO-%-$anio")
                ->pluck('referencia_cliente');

            // Extraer números reales PEDIDO-XX-2025
            $mayor = 0;

            foreach ($folios as $f) {
                if (preg_match('/PEDIDO-(\d+)-'.$anio.'/', $f, $m)) {
                    $num = intval($m[1]);
                    if ($num > $mayor) {
                        $mayor = $num;
                    }
                }
            }

            // El siguiente folio es:
            $numero = $mayor + 1;

            $folioGenerado = "PEDIDO-{$numero}-{$anio}";
            ////    CODIGO TEMPORAL, ASIGNA CONSECUTIVO DE FOLIO ////

            $ultimoPedido = null;

            foreach ($pedidos_data as $item) {
                $detalle = $item['detalle'];
                $total = $item['total'];

                $pedido = new ServiciosPonchadosVenta();
                $pedido->ponchado_id = $detalle['ponchado_id'];
                $pedido->cliente_id = $request->cliente_id;
                $pedido->producto_id = 2;
                $pedido->cliente_alias = $request->cliente_alias;
                $pedido->prenda = $detalle['prenda'];
                $pedido->clasificacion_ubicaciones_id = $detalle['clasificacion_ubicaciones_id'];
                $pedido->cantidad_piezas = $detalle['cantidad'];
                $pedido->precio_unitario = $detalle['precio'];
                $pedido->subtotal = $total;
                $pedido->fecha_recepcion = now();
                $pedido->fecha_estimada_entrega = $request->fecha_estimada_entrega;
                $pedido->referencia_cliente = $folioGenerado;
                $pedido->estatus = 'Diseño';
                $pedido->nota = $request->nota;
                $pedido->wci = auth()->id();
                $pedido->activo = $request->input('urgente', 1);
                $pedido->save();

                $ultimoPedido = $pedido; // guardamos el último modelo

                $pedidos[] = [
                    'id' => $pedido->id,
                    'monto_restante' => $total,
                ];
            }

            //3. Recorre las formas de pago y distribúye sobre los pedidos
            foreach ($request->formas_pago as $fp) {

                if (!empty($fp['monto']) && $fp['monto'] > 0) {

                    $monto_forma_pago = floatval($fp['monto']);

                    // Si es efectivo y hay cambio, ajusta
                    if ($fp['metodo'] === 'Efectivo' && $request->total_cambio > 0) {
                        $monto_forma_pago -= floatval($request->total_cambio);
                    }

                    foreach ($pedidos as &$pedido) {
                        // dd($monto_forma_pago, $pedido['monto_restante']);
                        if ($monto_forma_pago <= 0) break;

                        if ($pedido['monto_restante'] <= 0) continue;

                        $monto_aplicar = min($pedido['monto_restante'], $monto_forma_pago);

                        FormaPagos::create([
                            'pagable_id' => $pedido['id'],
                            'pagable_type' => ServiciosPonchadosVenta::class,
                            'metodo' => $fp['metodo'],
                            'monto' => $monto_aplicar,
                            'referencia' => $fp['referencia'] ?? null,
                            'wci' => auth()->id(),
                            'activo' => true,
                        ]);

                        $pedido['monto_restante'] -= $monto_aplicar;
                        $monto_forma_pago -= $monto_aplicar;
                    }
                }
            }


            DB::commit();
            session()->flash('swal', [
                'icon' => "success",
                'title' => "Operación correcta",
                'text' => "El pedido se creó correctamente.",
                'customClass' => [
                    'confirmButton' => 'text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800'  // Aquí puedes añadir las clases CSS que quieras
                ],
                'buttonsStyling' => false
            ]);

            return redirect()
                ->route('admin.pedidos.ponchados.index')
                ->with(['id' => $ultimoPedido->referencia_cliente]);
        } catch (\Exception $e) {
            DB::rollback();
            //dd($request->all());
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

    public function show(Request $request, $id)
    {
        // PARA VER LOS DATOS EN EL MODAL DEL EDIT
        if ($request->origen == 'show.edit') {
            /* $pedido = ServiciosPonchadosVenta::where('id', $id)
                ->where('servicios_ponchados_ventas.activo', 1)
                ->with(['ponchado', 'cliente', 'clasificacionUbicacion'])
                ->get()
                ->first();*/

            //$pedido = ServiciosPonchadosVenta::with(['ponchado', 'cliente', 'clasificacionUbicacion'])
            //    ->whereIn('activo', [1,2])
            //    ->where('activo', 1) // No es necesario usar el nombre completo de la tabla aquí
            //    ->first();

            $pedido = ServiciosPonchadosVenta::with(['ponchado', 'cliente', 'clasificacionUbicacion'])
            ->where('id', $id)
            ->whereIn('activo', [1, 2])
            ->firstOrFail();

            return response()->json(['data' => $pedido->toArray()]);

            //return response()->json(['data' => $pedido]);
        }

        //DETALLE PEDIDO
        $pedidos = ServiciosPonchadosVenta::where('referencia_cliente', $id)
            ->whereIn('servicios_ponchados_ventas.activo', [1,2])
            ->with([
                'ponchado.detalles' => function ($q) {
                    $q->where('activo', 1)->orderBy('color_tela');
                },
                'cliente',
                'clasificacionUbicacion'
            ])
            ->get();

        // preparar archivos e imágenes
        foreach ($pedidos as $pedido) {
            // imagen
            $pedido->ponchado->imagen_1 = $pedido->ponchado->imagen_1
                ? asset('storage/' . ltrim($pedido->ponchado->imagen_1, '/'))
                : asset('images/default.png');

            // archivo
            $archivoUrl = null;
            if ($pedido->ponchado->archivo) {
                $rutaArchivo = public_path('storage/' . $pedido->ponchado->archivo);
                if (file_exists($rutaArchivo)) {
                    $archivoUrl = asset('storage/' . $pedido->ponchado->archivo);
                }
            }
            $pedido->archivoUrl = $archivoUrl; // lo guardamos dentro del objeto
        }

        return view('ponchados_pedidos.show', compact('pedidos'));
    }

    public function edit($referencia)
    {
        $ponchado = ServiciosPonchadosVenta::with([
            'ponchado',
            'cliente',
            'clasificacionUbicacion',
            'formasPago'
        ])
            ->where('referencia_cliente', $referencia)
            ->whereIn('activo', [1,2])
            ->get();

        // como hay varios registros, tomo el primero para datos generales
        $pedidoBase = $ponchado->first();

        $marcaValues = ClasificacionUbicaciones::where('tipo', 'UBICACIÓN')
            ->where('activo', 1)
            ->select('id', 'nombre')
            ->get();

        $formasPago = $pedidoBase->formasPago->map(function ($fp) {
            return [
                'id' => $fp->id,
                'metodo' => $fp->metodo,
                'monto' => $fp->monto,
                'referencia' => $fp->referencia,
            ];
        })->toArray();

        if (empty($formasPago)) {
            $formasPago = [
                ['metodo' => '', 'monto' => '', 'referencia' => '']
            ];
        }

        $detalle = collect();

        // Sumatoria de todas las formas de pago activas
        $totalFormasPago = $ponchado->flatMap(function($detalle) {
                return $detalle->formasPago->where('activo', 1); // solo activas
            })->sum('monto');

        if ($pedidoBase->activo == 1 || $pedidoBase->activo == 2) {
            $metodo2 = 'edit';
            return view('ponchados_pedidos.edit', compact('ponchado', 'pedidoBase', 'detalle', 'metodo2', 'marcaValues', 'formasPago','totalFormasPago'));
        } else {
            return redirect()->route('admin.ponchados.index');
        }
    }

    public function update(Request $request, $referencia)
    {
        //dd($referencia);
        $validator = Validator::make($request->all(), [
            'cliente_id' => 'required|exists:clientes,id',
            'fecha_estimada_entrega' => 'required|date',
            'detalles.*.id' => 'nullable|exists:servicios_ponchados_ventas,id',
            'detalles.*.ponchado_id' => 'required|exists:ponchados,id',
            'detalles.*.prenda' => 'required|string|max:255',
            'detalles.*.cantidad' => 'required|integer|min:1',
            'detalles.*.precio' => 'required|numeric|min:0',
            'detalles.*.clasificacion_ubicaciones_id' => 'required|exists:clasificacion_ubicaciones,id',
            'detalles.*.nuevo_campo' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            DB::beginTransaction();

            // Obtener todos los registros existentes de esta referencia
            $pedidosExistentes = ServiciosPonchadosVenta::where('referencia_cliente', $referencia)->get();

            if ($pedidosExistentes->isEmpty()) {
                throw new \Exception("No se encontraron registros para la referencia proporcionada.");
            }

            // Actualizar datos generales de todos los registros existentes
            foreach ($pedidosExistentes as $pedido) {
                $pedido->cliente_id = $request->cliente_id;
                $pedido->fecha_estimada_entrega = $request->fecha_estimada_entrega;
                $pedido->nota = $request->nota;
                $pedido->save();
            }

            // Tomamos todos los ids enviados en el formulario
            $idsFormulario = collect($request->detalles)->pluck('id')->filter()->toArray();

            // Marcar como inactivos los registros que ya no están en el formulario
            ServiciosPonchadosVenta::where('referencia_cliente', $referencia)
                ->whereNotIn('id', $idsFormulario)
                ->update(['activo' => 0]);

            // Actualizar o crear registros de detalle
            foreach ($request->detalles as $detalleInput) {
                if (!empty($detalleInput['id'])) {
                    // Editar registro existente
                    $detalle = ServiciosPonchadosVenta::findOrFail($detalleInput['id']);
                } else {
                    // Crear nuevo registro
                    $detalle = new ServiciosPonchadosVenta();
                    $detalle->referencia_cliente = $referencia;
                    $detalle->producto_id = 2; // valor por defecto si aplica
                    $detalle->cliente_alias = $request->cliente_alias;
                    $detalle->cliente_id = $request->cliente_id;
                    $detalle->fecha_recepcion = now();
                    $detalle->fecha_estimada_entrega = $request->fecha_estimada_entrega;
                    $detalle->estatus = 'Diseño';
                    $detalle->nota = $request->nota;
                    $detalle->activo = $request->input('urgente', 1);
                    $detalle->wci = auth()->id();
                }

                // Campos comunes a editar/crear
                $detalle->ponchado_id = $detalleInput['ponchado_id'];
                $detalle->prenda = $detalleInput['prenda'];
                $detalle->clasificacion_ubicaciones_id = $detalleInput['clasificacion_ubicaciones_id'];
                $detalle->cantidad_piezas = intval($detalleInput['cantidad']);
                $detalle->precio_unitario = floatval($detalleInput['precio']);
                $detalle->subtotal = $detalle->cantidad_piezas * $detalle->precio_unitario;

                if (isset($detalleInput['nuevo_campo'])) {
                    $detalle->nuevo_campo = $detalleInput['nuevo_campo'];
                }

                $detalle->save();
            }

            // Después de actualizar/crear los detalles y reasignar pagos antiguos
            $detallesActivos = ServiciosPonchadosVenta::where('referencia_cliente', $referencia)
                ->where('activo', 1)
                ->orderBy('id')
                ->get();

            // Preparamos array de pedidos con monto restante
            $pedidos = $detallesActivos->map(function ($detalle) {
                return [
                    'id' => $detalle->id,
                    'monto_restante' => $detalle->subtotal,
                ];
            })->toArray();

            // Recorre las formas de pago y distribuye sobre los pedidos
            if (!empty($request->formas_pago)) {
                foreach ($request->formas_pago as $fp) {
                    if (!empty($fp['monto']) && $fp['monto'] > 0) {
                        $monto_forma_pago = floatval($fp['monto']);

                        // Ajusta efectivo si hay cambio
                        if ($fp['metodo'] === 'Efectivo' && isset($request->total_cambio) && $request->total_cambio > 0) {
                            $monto_forma_pago -= floatval($request->total_cambio);
                        }

                        foreach ($pedidos as &$pedido) {
                            if ($monto_forma_pago <= 0) break;
                            if ($pedido['monto_restante'] <= 0) continue;

                            $monto_aplicar = min($pedido['monto_restante'], $monto_forma_pago);

                            FormaPagos::create([
                                'pagable_id' => $pedido['id'],
                                'pagable_type' => ServiciosPonchadosVenta::class,
                                'metodo' => $fp['metodo'],
                                'monto' => $monto_aplicar,
                                'referencia' => $fp['referencia'] ?? null,
                                'wci' => auth()->id(),
                                'activo' => true,
                            ]);

                            $pedido['monto_restante'] -= $monto_aplicar;
                            $monto_forma_pago -= $monto_aplicar;
                        }
                    }
                }
            }

            DB::commit();

            session()->flash('swal', [
                'icon' => "success",
                'title' => "Actualización correcta",
                'text' => "El pedido se actualizó correctamente.",
                'customClass' => [
                    'confirmButton' => 'text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2'
                ],
                'buttonsStyling' => false
            ]);

            return redirect()->route('admin.pedidos.ponchados.index');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->withInput()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function updateCantidad(Request $request, $id)
    {
        $request->validate([
            'cantidad' => 'required|integer|min:1',
        ]);

        try {
            DB::beginTransaction();

            $ponchado = ServiciosPonchadosVenta::where('id', $id)
                ->whereIn('activo', [1,2])
                ->firstOrFail();

            // 1️⃣ Actualizar cantidad
            $ponchado->cantidad_piezas = $request->cantidad;
            $ponchado->subtotal = $ponchado->cantidad_piezas * $ponchado->precio_unitario;
            $ponchado->save();

            // 2️⃣ Recalcular totales del pedido
            $totales = ServiciosPonchadosVenta::where('referencia_cliente', $ponchado->referencia_cliente)
                ->whereIn('activo', [1,2])
                ->selectRaw('
                    SUM(subtotal) as total,
                    COUNT(*) as items
                ')
                ->first();

            DB::commit();

            return response()->json([
                'success' => true,
                'item' => [
                    'id' => $ponchado->id,
                    'cantidad' => $ponchado->cantidad_piezas,
                    'subtotal' => round($ponchado->subtotal, 2),
                ],
                'totales' => [
                    'total' => round($totales->total, 2),
                    'items' => $totales->items,
                ]
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            DB::beginTransaction();
            $pedidos = ServiciosPonchadosVenta::where('referencia_cliente', $id)
                ->get();

            if ($pedidos->isEmpty()) {
                return response()->json([
                    'swal' => [
                        'icon' => "info",
                        'title' => "Sin cambios",
                        'text' => "El pedido ya estaba eliminado..",
                        'customClass' => [
                            'confirmButton' => 'text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800'
                        ],
                        'buttonsStyling' => false
                    ],
                    'success' => 'El pedido ya estaba eliminado..'
                ], 200);
            }

            foreach ($pedidos as $pedido) {

                if ($pedido->activo == 0) {
                    continue; // ya estaba eliminado, saltamos
                }

                // Validar si tiene abonos (formas de pago asociadas)
                $tiene_abonos = FormaPagos::where('pagable_type', ServiciosPonchadosVenta::class)
                    ->where('pagable_id', $pedido->id)
                    ->where('activo', true)
                    ->exists();

                if ($tiene_abonos) {
                    return response()->json([
                        'swal' => [
                            'icon' => "error",
                            'title' => "Operación fallada.",
                            'text' => "No se puede eliminar el pedido porque ya tiene abonos registrados.",
                            'customClass' => [
                                'confirmButton' => 'text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800'
                            ],
                            'buttonsStyling' => false
                        ],
                        'success' => 'No se puede eliminar el pedido porque ya tiene abonos registrados.'
                    ], 400);
                }

                // Proceder con la eliminación lógica
                $pedido->update([
                    'estatus' => 'Eliminado',
                    'activo' => 0,
                    'updated_at' => now()
                ]);

                // Guardar el cambio de estatus en el historial
                ServicioPonchadoEstatus::create([
                    'servicio_ponchado_venta_id' => $pedido->id,
                    'estatus' => 'Eliminado',
                    'usuario_id' => auth()->id(),
                ]);
            }

            DB::commit();
            // Retornar respuesta exitosa si todos los pedidos fueron eliminados
            return response()->json([
                'swal' => [
                    'icon' => "success",
                    'title' => "Operación exitosa",
                    'text' => "Todos los pedidos fueron eliminados correctamente.",
                    'customClass' => [
                        'confirmButton' => 'text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800'
                    ],
                    'buttonsStyling' => false
                ],
                'success' => 'Todos los pedidos fueron eliminados correctamente.'
            ], 200);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'swal' => [
                    'icon' => "error",
                    'title' => "Operación fallida...",
                    'text' => "Hubo un error durante el proceso, por favor intente más tarde.",
                    'customClass' => [
                        'confirmButton' => 'text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800'
                    ],
                    'buttonsStyling' => false
                ]
            ], 500);

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

    public function descargarArchivo($id)
    {
        /*
        $ponchado = Ponchados::findOrFail($id);

        if (!$ponchado->archivo || !Storage::disk('public')->exists($ponchado->archivo)) {
            abort(404, 'Archivo no encontrado');
        }

        // Obtener nombre original del archivo
        $extension = pathinfo($ponchado->archivo, PATHINFO_EXTENSION);
        //$extension = Str::afterLast($ponchado->archivo, '.');

        // Nombre sugerido para la descarga, con extensión real
        $nombreDescarga = 'ponchado_' . $ponchado->id . '.' . $extension;

        return Storage::disk('public')->download($ponchado->archivo, $nombreDescarga);
        */

        $ponchado = Ponchados::findOrFail($id);

        if (!$ponchado->archivo) {
            abort(404, 'Archivo no disponible');
        }

        $rutaRelativa = $ponchado->archivo;
        $rutaCompleta = public_path('storage/' . $rutaRelativa);

        if (!file_exists($rutaCompleta)) {
            abort(404, 'Archivo no encontrado');
        }

        $extension = pathinfo($rutaCompleta, PATHINFO_EXTENSION);

        // Limpiar nombre para evitar problemas
        $nombreSeguro = Str::slug($ponchado->nombre, '_'); // reemplaza espacios y acentos por _
        $nombreDescarga = $nombreSeguro . '.' . $extension;

        return response()->download($rutaCompleta, $nombreDescarga);
    }

    public function ticketPedido($pedido)
    {
        $pedidos = ServiciosPonchadosVenta::where('referencia_cliente', $pedido)
            ->where('servicios_ponchados_ventas.activo', 1)
            ->with([
                'ponchado.detalles' => function ($q) {
                    $q->where('activo', 1)->orderBy('color_tela');
                },
                'cliente',
                'clasificacionUbicacion',
                'formasPago'
            ])
            ->get();

        // preparar archivos e imágenes y calcular totales
        $totalVenta = 0;
        $totalPagado = 0;
        $totalFaltante = 0;

        // preparar archivos e imágenes
        $detalle = $pedidos->map(function ($p, $index) use (&$totalVenta, &$totalPagado, &$totalFaltante) {
            $total = ($p->precio_unitario ?? 0) * $p->cantidad_piezas;
            $pagado = $p->formasPago->sum('monto');

            $totalVenta += $total;
            $totalPagado += $pagado;

            // imagen
            $p->ponchado->imagen_1 = $p->ponchado->imagen_1
                ? asset('storage/' . ltrim($p->ponchado->imagen_1, '/'))
                : asset('images/default.png');

            // archivo
            $archivoUrl = null;
            if ($p->ponchado->archivo) {
                $rutaArchivo = public_path('storage/' . $p->ponchado->archivo);
                if (file_exists($rutaArchivo)) {
                    $archivoUrl = asset('storage/' . $p->ponchado->archivo);
                }
            }
            $p->archivoUrl = $archivoUrl;

            return $p;
        });

        $totalFaltante = $totalVenta - $totalPagado;
        //dd($totalFaltante);

        //  - CREAMOS EL PDF ----
        $user = auth()->user();
        $userPrinterSize = 80;

        $size = match ($userPrinterSize) {
            58 => [0, 0, 140, 1440],
            80 => [0, 0, 212, 1440],
            default => [0, 0, 0, 0],
        };

        $pdf = PDF::loadView('comprobantes.ticket_ponchado', compact(
            'pedidos',
            'userPrinterSize',
            'totalVenta',
            'totalPagado',
            'totalFaltante'
        ))
            ->setPaper($size, 'portrait');

        return $pdf->stream();
    }
}
