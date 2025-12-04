<?php

namespace App\Http\Controllers;

use App\Models\Configuration;
use App\Models\FormaPagos;
use App\Models\Inventario;
use App\Models\Kardex;
use App\Models\Producto;
use App\Models\ServiciosPonchadosVenta;
use App\Models\Venta;
use App\Models\VentaCredito;
use App\Models\VentaDetalle;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use PDF;
use Illuminate\Support\Facades\Validator;

class VentaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
        //$this->middleware(['can:Gestión de roles']);
    }

    public function index()
    {
        $hoy = Carbon::today();
        $hace7dias = $hoy->copy()->subDays(7);

        // --- PEDIDOS FINALIZADOS --- //
        $pedidos = ServiciosPonchadosVenta::whereIn('activo', [1, 2])
            ->where('estatus', 'Finalizado')
            //->whereBetween('fecha_estimada_entrega', [$hace7dias, $hoy])
            ->with(['ponchado', 'cliente', 'clasificacionUbicacion'])
            ->orderBy('fecha_estimada_entrega', 'asc')
            ->get()
            ->groupBy('referencia_cliente')
            ->filter(function($items) {
                return $items->every(fn($item) => $item->estatus === 'Finalizado');
            })
            ->map(function ($items, $referencia) {
                  return collect ([
                    'id' => $referencia,
                    'tipo' => 'pedido',
                    'referencia_cliente' => $referencia,
                    'cliente' => $items->first()->cliente->full_name ?? 'CLIENTE PÚBLICO',
                    'fecha' => $items->first()->fecha_estimada_entrega,
                    'estatus' => $items->first()->estatus,
                    'activo' => $items->first()->activo, // 👈 lo agregamos
                    'detalles' => $items->map(function ($producto) {
                        return [
                            'id' => $producto->id,
                            'img_thumb' => $producto->ponchado->imagen_1
                                ? asset('storage/' . ltrim($producto->ponchado->imagen_1, '/'))
                                : asset('images/default.png'),
                            'nombre' => $producto->ponchado->nombre,
                            'clasificacion' => $producto->clasificacionUbicacion->nombre ?? null,
                            'cantidad' => $producto->cantidad_piezas,
                            'activo' => $producto->activo,
                        ];
                    })->values(),
                ]);
            });

        // --- VENTAS --- //
        $ventas = Venta::with(['cliente','detalles.producto','detalles.servicioPonchado'])
            ->whereBetween('fecha', [$hace7dias, $hoy])
            ->orderBy('fecha', 'asc')
            ->get()
            ->map(function ($venta) {
                return collect([
                    'id' => $venta->id,
                    'tipo' => 'venta',
                    'referencia_cliente' => $venta->folio,
                    'cliente' => $venta->cliente->full_name ?? 'CLIENTE PÚBLICO',
                    'fecha' => $venta->fecha,
                    'estatus' => 'Vendido',
                    'activo' => $venta->activo ?? 1, // 👈 aseguras que exista
                    'detalles' => $venta->detalles->map(function ($detalle) {
                        return [
                            'id' => $detalle->id,
                            'img_thumb' => $detalle->producto?->imagen
                                ? asset('storage/' . ltrim($detalle->producto->imagen, '/'))
                                : asset('images/default.png'),
                            'nombre' => $detalle->producto->nombre
                                ?? $detalle->servicioPonchado->ponchado->nombre
                                ?? $detalle->producto_comun,
                            'clasificacion' => $detalle->servicioPonchado?->clasificacionUbicacion->nombre,
                            'cantidad' => $detalle->cantidad,
                            'activo' => $detalle->activo,
                        ];
                    })->values(),
                ]);
            });

        /*
        // --- UNIFICAR --- //
        $data = $pedidos->values()->toBase()     // Base Collection (no Eloquent)
        ->concat($ventas->values()->toBase())
        ->sortBy('fecha')                // opcional: ordenar por fecha
        ->values();

        // Pasar a la vista
        return view('ventas.index', ['ventas' => $data]);
        */
        // --- UNIFICAR --- //
        $data = $pedidos->values()->toBase()
            ->concat($ventas->values()->toBase())
            ->sortBy('fecha')
            ->values();

        // --- AGRUPAR POR CLIENTE --- //
        $agrupado = $pedidos->values()->toBase()
        ->concat($ventas->values()->toBase())
        ->sortBy([
            ['cliente', 'asc'],
            ['fecha', 'asc'],
        ])
        ->values();

        return view('ventas.index', ['ventas' => $agrupado]);

    }

    public function create(Request $request)
    {
        $ventas = new Venta();
        $ventas->cliente_id = 1; // CLIENTE PÚBLICO por defecto
        $metodo = 'create';
        $detalle = collect();

        // Inicializar variables por defecto
        $totalVenta = 0;
        $totalPagado = 0;
        $totalFaltante = 0;

        // Verificar si viene referencia_cliente


        /*if ($request->has('referencia_cliente')) {
            $referencia = $request->input('referencia_cliente');

            $ponchados = ServiciosPonchadosVenta::where('activo',1)
                ->where('referencia_cliente', $referencia)
                ->with(['ponchado', 'clasificacionUbicacion', 'formasPago']) // 👈 importante
                ->where('estatus','=','Finalizado')
                ->where('activo',1)
                ->get();

            if ($ponchados->isNotEmpty()) {
                // Tomar cliente del primer item
                $ventas->cliente_id = $ponchados->first()->cliente_id ?? 1;
                $ventas->nombre_cliente = $ponchados->first()->cliente->full_name ?? 'CLIENTE PÚBLICO';
            }

            // calcular detalle
            $detalle = $ponchados->map(function($p, $index){
                $total = ($p->precio_unitario ?? 0) * $p->cantidad_piezas;
                $pagado = $p->formasPago->sum('monto'); // 👈 suma pagos hechos

                return [
                    'index' => $index,
                    'producto_id' => $p->id,
                    'name_producto' => $p->ponchado->nombre,
                    'cantidad' => $p->cantidad_piezas,
                    'precio' => $p->precio_unitario ?? 0,
                    'total' => $total,
                    'pagado' => $pagado,  // 👈 nuevo
                    'faltante' => $total - $pagado, // 👈 nuevo
                    'tipo_item' => 'SERVICIO',
                    'clasificacion' => $p->clasificacionUbicacion->nombre ?? null,
                    'img_thumb' => $p->ponchado->imagen_1
                        ? asset('storage/' . ltrim($p->ponchado->imagen_1, '/'))
                        : asset('images/default.png'),
                ];
            });

            // totales globales
            $totalVenta = $detalle->sum('total');
            $totalPagado = $detalle->sum('pagado');
            $totalFaltante = $totalVenta - $totalPagado;
        }
        */

        if ($request->has('referencia_cliente')) {
            $referencias = explode(',', $request->input('referencia_cliente'));

            $ponchados = ServiciosPonchadosVenta::whereIn('activo', [1, 2])
                ->whereIn('referencia_cliente', $referencias)
                ->with(['ponchado', 'clasificacionUbicacion', 'formasPago'])
                ->where('estatus','=','Finalizado')
                ->get();

            if ($ponchados->isNotEmpty()) {
                $ventas->cliente_id = $ponchados->first()->cliente_id ?? 1;
                $ventas->nombre_cliente = $ponchados->first()->cliente->full_name ?? 'CLIENTE PÚBLICO';
            }

            $detalle = $ponchados->map(function($p, $index){
                $total = ($p->precio_unitario ?? 0) * $p->cantidad_piezas;
                $pagado = $p->formasPago->sum('monto');
                //dd($total);

                return [
                    'index' => $index,
                    'producto_id' => $p->id,
                    'name_producto' => $p->ponchado->nombre,
                    'cantidad' => $p->cantidad_piezas,
                    'precio' => $p->precio_unitario ?? 0,
                    'total' => $total,
                    'pagado' => $pagado,
                    'faltante' => $total - $pagado,
                    'tipo_item' => 'SERVICIO',
                    'clasificacion' => $p->clasificacionUbicacion->nombre ?? null,
                    'img_thumb' => $p->ponchado->imagen_1
                        ? asset('storage/' . ltrim($p->ponchado->imagen_1, '/'))
                        : asset('images/default.png'),
                ];
            });

            $totalVenta = $detalle->sum('total');
            $totalPagado = $detalle->sum('pagado');
            $totalFaltante = $totalVenta - $totalPagado;
        }


        $formasPago = [
            ['metodo' => '', 'monto' => '', 'referencia' => '']
        ];

        return view('ventas.create', compact(
            'metodo','ventas','detalle','formasPago',
            'totalVenta','totalPagado','totalFaltante'
        ));
    }

    public function create1()
    {
        $ventas = new Venta();

        // CLIENTE PÚBLICO por defecto
        $ventas->cliente_id = 1;

        $metodo = 'create';

        $detalle = collect();

        $formasPago = [
            ['metodo' => '', 'monto' => '', 'referencia' => '']
        ];

        return view('ventas.create', compact('metodo','ventas','detalle','formasPago' ));
    }

    public function store(Request $request)
    {
        /*
        // 1. Validación rápida con reglas base
        $rules = [
            'tipo_venta' => 'required|in:CONTADO,CRÉDITO',
            'cliente_id' => 'required|exists:clientes,id',
            'total_venta' => 'required|numeric|min:0.01',
            'monto_credito' => 'nullable|numeric|min:0',
            'formas_pago' => 'required|array|min:1',
            'detalles' => 'required|array|min:1', // los productos o ponchados
        ];

        $messages = [
            'cliente_id.required' => 'Debes seleccionar un cliente.',
            'detalles.required' => 'Debes agregar al menos un producto o ponchado.',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $tipoVenta = $request->tipo_venta;
        $clienteId = $request->cliente_id;
        $totalVenta = floatval($request->total_venta);
        $montoCredito = floatval($request->monto_credito ?? 0);

        // 1. Cliente público no puede comprar a crédito
        if ($tipoVenta === 'CRÉDITO' && $clienteId == 1) {
            return response()->json(['message' => 'No puedes seleccionar CLIENTE PÚBLICO para una venta a Crédito.'], 400);
        }

        // 2. Monto a crédito debe ser válido y menor al total
        if ($tipoVenta === 'CRÉDITO') {
            if ($montoCredito <= 0) {
                return response()->json(['message' => 'Debes especificar un monto válido a crédito.'], 400);
            }

            if ($montoCredito > $totalVenta) {
                return response()->json(['message' => 'El monto a crédito no puede ser mayor al total de la venta.'], 400);
            }
        }

         $totalPagado = 0;
        foreach ($request->formas_pago as $fp) {
            $totalPagado += floatval($fp['monto'] ?? 0);
        }

        $basePago = $tipoVenta === 'CRÉDITO' ? $totalVenta - $montoCredito : $totalVenta;
        $excedente = $totalPagado - $basePago;

        $efectivo = collect($request->formas_pago)->firstWhere('metodo', 'Efectivo')['monto'] ?? 0;

        // 4. Contado: monto pagado debe cubrir el total
        if ($tipoVenta !== 'CRÉDITO' && $totalPagado < $totalVenta) {
            return response()->json(['message' => 'El monto pagado no cubre el total de la venta.'], 400);
        }

        // 5. No se puede dar más cambio del efectivo recibido
        if ($excedente > 0 && $efectivo < $excedente) {
            return response()->json(['message' => 'El cambio no puede ser mayor al efectivo entregado.'], 400);
        }

        // 6. Venta a crédito no puede cubrir todo el total
        if ($tipoVenta === 'CRÉDITO' && $montoCredito < $totalVenta && $totalPagado > ($totalVenta - $montoCredito)) {
            return response()->json(['message' => 'En una venta a Crédito, no puedes cubrir todo con formas de pago. Deja una parte a crédito.'], 400);
        }
        */

        foreach ($request->detalles as $detalle) {
           // dd($detalle['producto_id']);
            $productoId = $detalle['producto_id'] ?? 0;
            $cantidadSolicitada = intval($detalle['cantidad']);

            $producto = Producto::with('inventario')->find($productoId);

            // Solo aplica si es producto (no servicio)
            if ($producto && $producto->tipo === 'PRODUCTO') {
                $stock = $producto->inventario->cantidad ?? 0;

                if ($cantidadSolicitada > $stock) {
                    session()->flash('swal', [
                        'icon' => "error",
                        'title' => "Operación fallida.",
                        'text' => "El producto {$producto->nombre} no tiene suficiente stock. Disponible: $stock",
                        'customClass' => [
                            'confirmButton' => 'text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800'  // Aquí puedes añadir las clases CSS que quieras
                        ],
                        'buttonsStyling' => false  // Deshabilitar el estilo predeterminado de SweetAlert2
                    ]);

                    return redirect()->back()
                        ->withInput($request->all()) // Aquí solo pasas los valores del formulario
                        ->with('status', 'Hubo un error al ingresar los datos, por favor intente de nuevo.');
                        //->withErrors(['error' => $e->getMessage()]); // Aquí pasas el mensaje de error

                }
            }
        }

        try {
            DB::beginTransaction();

            //FOLIO
            $anioActual = now()->year;

            // Buscar el último folio del año actual
            $ultimoFolio = Venta::whereYear('created_at', $anioActual)
                ->orderByDesc('id')
                ->value('folio');

            // Extraer el número del folio anterior (ej: "VENTA-25-2025" → 25)
            $ultimoNumero = 0;
            if ($ultimoFolio && preg_match('/VENTA-(\d+)-' . $anioActual . '/', $ultimoFolio, $match)) {
                $ultimoNumero = intval($match[1]);
            }

            $nuevoNumero = $ultimoNumero + 1;
            $folio = "VENTA-{$nuevoNumero}-{$anioActual}";

            // CREAMOS LA VENTA
            $venta = new Venta();
            $venta->user_id = auth()->user()->id;
            $venta->cliente_id = $request->cliente_id;
            $venta->folio = $folio;
            $venta->fecha = Carbon::now();
            $venta->total = $request->total_venta;
            $venta->monto_credito = floatval($request->monto_credito) ?: 0;
            $venta->monto_recibido = 0;
            $venta->cambio = $request->total_cambio;
            $venta->tipo_venta = $request->tipo_venta;
            $venta->save();

            // Inicializamos una variable para sumar los montos finales de las formas de pago
            $montoRecibidoNetoTotal = 0;

            // INSERTA LA FORMA DE PAGO
            foreach ($request->formas_pago as $fp) {
                if (!empty($fp['monto']) && $fp['monto'] > 0){
                    $monto_final = $fp['monto'];

                    // Si el método de pago es 'Efectivo', ajustamos el monto
                    if ($fp['metodo'] === 'Efectivo' && $venta->cambio > 0) {
                        $monto_final = $monto_final - $venta->cambio;
                    }
                    FormaPagos::create([
                        'pagable_id' => $venta->id,
                        'pagable_type' => Venta::class,
                        'metodo' => $fp['metodo'],
                        'monto' => $monto_final,
                        'referencia' => $fp['referencia'] ?? null,
                        'wci' => auth()->id(),
                        'activo' => true,
                    ]);

                    // Sumamos el monto final ajustado al total neto recibido
                    $montoRecibidoNetoTotal += $monto_final;
                }
            }

            // ACTUALIZAR LA VENTA con el monto total neto de las formas de pago
            $venta->monto_recibido = $montoRecibidoNetoTotal;
            $venta->save(); // O $venta->update(['monto_recibido' => $montoRecibidoNetoTotal]);

            //VENTA DETALLE
            foreach ($request->detalles as $detalle) {
                VentaDetalle::create([
                    'venta_id' => $venta->id,
                    'tipo_item' => $detalle['tipo_item'] ?? null,
                    'producto_id' => $detalle['producto_id'] ?? null,
                    'servicio_ponchado_id' => $detalle['servicio_ponchado_id'] ?? null,
                    'producto_comun' => $detalle['producto_comun'] ?? null,
                    'cantidad' => $detalle['cantidad'],
                    'precio' => $detalle['precio'],
                    'total' => $detalle['total'],
                    'activo' => 1,
                ]);

                // Verificar si el producto ya existe en el inventario y es un PRODUCTO
                if ($detalle['tipo_item'] == 'PRODUCTO') {

                    $inventario = Inventario::where('producto_id', $detalle['producto_id'])->first();


                    if ($inventario) {
                        // Actualizar el registro existente
                        $inventario->cantidad = $inventario->cantidad - $detalle['cantidad']; // Sumar cantidad si ya existe
                        $inventario->updated_at = Carbon::now();
                        $inventario->save();

                        //OBTENGO EL ULTIMO REGISTROS DEL KARDEX PARA EL PRODUCTO
                        $ultimoRegistro = Kardex::where('producto_id', $detalle['producto_id'])
                            ->orderBy('created_at', 'desc')
                            ->first();

                        // Inicializar variables para las cantidades
                        $saldoActual = $ultimoRegistro ? $ultimoRegistro->saldo : 0;

                        // Cantidades a agregar
                        $cantidadEntrada = 0;
                        $cantidadSalida = $detalle['cantidad'];

                        // Calcular el nuevo saldo
                        $nuevoSaldo = $saldoActual + $cantidadEntrada - $cantidadSalida;

                        // Crear el nuevo registro en el kardex
                        $kardex = new Kardex();
                        $kardex->producto_id = $detalle['producto_id'];
                        $kardex->movimiento_id = $venta->id; // Asume que ya tienes el ID de la compra
                        $kardex->tipo_movimiento = 'SALIDA'; // O 'SALIDA' según corresponda
                        $kardex->tipo_detalle = 'VENTA';
                        $kardex->fecha = Carbon::now();
                        $kardex->folio = $folio; // Asume que ya tienes el número de factura
                        $kardex->debe = $cantidadEntrada;
                        $kardex->haber = $cantidadSalida;
                        $kardex->saldo = $nuevoSaldo;
                        $kardex->wci = auth()->user()->id;
                        $kardex->save();

                    }
                }

                // Cambio el estado del ponchado
                if ($detalle['tipo_item'] == 'PONCHADO') {
                     $ponchado = ServiciosPonchadosVenta::where('id', $detalle['servicio_ponchado_id'])->first();

                    if ($ponchado) {
                        // Actualizar el registro existente
                        $ponchado->fecha_entrega_real = Carbon::now();
                        $ponchado->estatus = 'Entregado';
                        $ponchado->updated_at = Carbon::now();
                        $ponchado->save();
                    }

                }

            }

            //VENTA A CRÉDITO
            if($request->tipo_venta == 'CRÉDITO'){
                VentaCredito::create([
                    'venta_id' => $venta->id,
                    'monto_credito' => floatval($request->monto_credito) ?: 0,
                    'saldo_actual' => floatval($request->monto_credito) ?: 0,
                ]);
            }

            DB::commit();

            session()->flash('swal', [
                'icon' => "success",
                'title' => "Operación correcta",
                'text' => "La venta se creó correctamente.",
                'customClass' => [
                    'confirmButton' => 'text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800'  // Aquí puedes añadir las clases CSS que quieras
                ],
                'buttonsStyling' => false  // Deshabilitar el estilo predeterminado de SweetAlert2
            ]);

            return redirect()->route('admin.ventas.index')->with(['id'=> $venta->id]);

            //return redirect()->route('admin.ventas.index');


        } catch (\Exception $e) {
            DB::rollback();
            $query = $e->getMessage();
            session()->flash('swal', [
                'icon' => "error",
                'title' => "Operación fallida.",
                'text' => "Hubo un error durante el proceso, por favor intente más tarde.".$query,
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
    }

    public function show(Venta $venta)
    {
        //
    }

    public function edit($id)
    {
        $ventas = Venta::findorfail($id);
        $detalle = collect();
        $formasPago = [
            ['metodo' => '', 'monto' => '', 'referencia' => '']
        ];

        if($ventas->activo == 1){
            $metodo = 'edit';
            return view('ventas.edit', compact('ventas','metodo','detalle','formasPago'));
        }else{
            return redirect()->route('admin.ventas.index');
        }
    }

    public function update(Request $request, Venta $venta)
    {
        //
    }

    public function destroy(Venta $venta)
    {
        //
    }

    public function ticketMixto(Request $request)
    {
        //$referencias = explode(',', $request->input('referencias'));
        $referencias = explode(',', $request->input('referencias'));

        // separar pedidos (strings) y ventas (IDs numéricos)
        $pedidosRefs = array_filter($referencias, fn($r) => !is_numeric($r));
        $ventasIds   = array_filter($referencias, fn($r) => is_numeric($r));

        // 1. Traer pedidos
        $pedidos = ServiciosPonchadosVenta::whereIn('referencia_cliente', $pedidosRefs)
            ->where('estatus', 'Finalizado')
            ->with(['ponchado','clasificacionUbicacion','cliente'])
            ->get()
            ->groupBy('referencia_cliente');

        // 2. Traer ventas
        $ventas = Venta::with(['cliente','detalles.producto','detalles.servicioPonchado'])
            ->whereIn('id', $ventasIds)
            ->get();

        $user = auth()->user();
        $userPrinterSize = 80;
        $size = match($userPrinterSize) {
            58 => [0,0,140,1440],
            80 => [0,0,212,1440],
            default => [0,0,0,0],
        };

        $pdf = PDF::loadView('comprobantes.ticket_venta_mixto', compact('pedidos','ventas','userPrinterSize'))
            ->setPaper($size,'portrait');
        return $pdf->stream();
    }


    public function ticket($id){

        $venta = Venta::with([
            'cliente',
            'detalles.producto',            // si el item es un producto
            'detalles.servicioPonchado'     // si el item es un servicio ponchado
        ])->findOrFail($id);


        //  - CREAMOS EL PDF DE LA VENTA ----
        $user = auth()->user();
        $userPrinterSize = 80;

        $size = match($userPrinterSize) {
            58 => [0,0,140,1440],
            80 => [0,0,212,1440],
            default => [0,0,0,0],
        };
        //dd($venta,$userPrinterSize);

        $pdf = PDF::loadView('comprobantes.ticket_venta', compact('venta','userPrinterSize'))
            ->setPaper($size,'portrait');
        return $pdf->stream();
    }
}
