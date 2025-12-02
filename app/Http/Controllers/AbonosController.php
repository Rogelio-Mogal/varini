<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAbonoRequest;
use App\Models\Abono;
use App\Models\Cliente;
use App\Models\DetalleAbono;
use App\Models\FormaPagos;
use App\Models\Venta;
use App\Models\VentaCredito;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AbonosController extends Controller
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

    public function store(StoreAbonoRequest $request)
    {
        //dd($request->input('ventas_seleccionadas', []));
        $data = $request->validated();
  
        try {
            $clienteId = $data['cliente_id'];
            \DB::transaction(function () use ($data) {
                //dd($data);

                //FOLIO
                $anioActual = now()->year;

                // Buscar el último folio del año actual
                $ultimoFolio = Abono::whereYear('created_at', $anioActual)
                    ->orderByDesc('id')
                    ->value('folio');

                // Extraer el número del folio anterior (ej: "VENTA-25-2025" → 25)
                $ultimoNumero = 0;
                if ($ultimoFolio && preg_match('/ABONO-(\d+)-' . $anioActual . '/', $ultimoFolio, $match)) {
                    $ultimoNumero = intval($match[1]);
                }

                $nuevoNumero = $ultimoNumero + 1;
                $folio = "ABONO-{$nuevoNumero}-{$anioActual}";

                /* 1. Crear encabezado de abono  --------------------------- */
                $abono = Abono::create([
                    'folio' => $folio,
                    'fecha' => now(),
                    'total_abonado' => $data['total_formas_pago'],
                    'cliente_id' => $data['cliente_id'],
                    'user_id' => auth()->id(),
                    'referencia' => $data['referencia'] ?? null,
                ]);

                /* 2. Guardar formas de pago ------------------------------ */
                // INSERTA LA FORMA DE PAGO
                foreach ($data['formas_pago'] as $fp) {
                    if (!empty($fp['monto']) && $fp['monto'] > 0){
                        FormaPagos::create([
                            'pagable_id' => $abono->id,
                            'pagable_type' => Abono::class,
                            'metodo' => $fp['metodo'],
                            'monto' => $fp['monto'],
                            'referencia' => $abono->referencia ?? null,
                            'wci' => auth()->id(),
                            'activo' => 1,
                        ]);
                    }
                }

                /* 3. Distribuir el abono entre ventas -------------------- */
                if ($data['tipo_abono'] === 'monto') {
                    // Trae TODAS las ventas a crédito activas con saldo, de la más vieja a la más nueva
                    $ventas = Venta::with('creditos')
                    ->where('cliente_id', $data['cliente_id'])
                    ->where('tipo_venta', 'CRÉDITO')
                    ->whereHas('creditos', fn ($q) => 
                        $q->where('saldo_actual', '>', 0)                                
                    ->where('activo', true))
                    ->orderBy('fecha')          // ← primero las más antiguas
                    ->lockForUpdate()           // evita condiciones de carrera
                    ->get();

                    $restante = $data['total_formas_pago']; // saldo del abono por repartir

                    foreach ($ventas as $venta) {

                        if ($restante <= 0) break; // ya no queda nada por repartir

                        $credito  = $venta->creditos;            // relación 1‑a‑1
                        $antes    = $credito->saldo_actual;      // saldo antes del abono
                        $aplicar  = min($antes, $restante);      // lo que realmente se abona aquí
                        $despues  = $antes - $aplicar;           // saldo después

                        /* Guardar renglón en detalle_abonos */
                        DetalleAbono::create([
                            'abono_id'      => $abono->id,
                            'venta_id'      => $venta->id,
                            'monto_antes'   => $antes,
                            'abonado'       => $aplicar,
                            'saldo_despues' => $despues,
                            'activo'        => true,
                        ]);

                        /* Actualizar saldo y estado de la venta */
                        $credito->update([
                            'saldo_actual' => $despues,
                            'liquidado'    => $despues == 0,
                        ]);

                        /* Reducir lo que queda del abono */
                        $restante -= $aplicar;
                    }

                    // Por diseño nunca debería ocurrir, pero si sobra algo avisa
                    if ($restante > 0) {
                        throw new \RuntimeException('No se pudo aplicar $' . number_format($restante, 2) .
                                                    ' porque el cliente ya no tiene saldo pendiente.');
                    }
                } else { // B) seleccionar ventas
                    $ids = $data['ventas_seleccionadas'];              // array de IDs

                    $ventas = Venta::with('creditos')
                    ->whereIn('id', $ids)
                    ->whereHas('creditos', fn ($q) =>
                        $q->where('saldo_actual', '>', 0)
                        ->where('activo', true)
                    )
                    ->orderBy('fecha')    // las más antiguas primero
                    ->lockForUpdate()
                    ->get();


                    $restante = $data['total_formas_pago'];

                    foreach ($ventas as $venta) {

                        if ($restante <= 0) break;

                        $credito = $venta->creditos;
                        $antes   = $credito->saldo_actual;
                        $aplicar = min($antes, $restante);
                        $despues = $antes - $aplicar;

                        DetalleAbono::create([
                            'abono_id'      => $abono->id,
                            'venta_id'      => $venta->id,
                            'monto_antes'   => $antes,
                            'abonado'       => $aplicar,
                            'saldo_despues' => $despues,
                            'activo'        => true,
                        ]);

                        $credito->update([
                            'saldo_actual' => $despues,
                            'liquidado'    => $despues == 0,
                        ]);

                        $restante -= $aplicar;
                    }

                    if ($restante > 0) {
                        throw new \RuntimeException('No se pudo aplicar $' . number_format($restante, 2) .
                                                    ' porque excede los saldos seleccionados.');
                    }
                }
            });

            session()->flash('swal', [
                'icon' => "success",
                'title' => "Operación correcta",
                'text' => "El abono se creó correctamente.",
                'customClass' => [
                    'confirmButton' => 'text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800'  // Aquí puedes añadir las clases CSS que quieras
                ],
                'buttonsStyling' => false
            ]);

            return redirect()
                ->route('admin.abonos.show', $clienteId)
                ->with('success', 'Abono registrado correctamente.');

        } catch (\Exception $e) {
            dd($e);
            //DB::rollback();
            $query = $e->getMessage();
            session()->flash('swal', [
                'icon' => "error",
                'title' => "Operación fallida.",
                'text' => "Hubo un error durante el proceso, por favor intente más tarde." . $query,
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

    public function show($id)
    {

        $userId = $id;

        $clientes = Cliente::whereHas('ventas.creditos', function ($query) use ($userId) {
            $query->where('activo', 1)
                ->where('liquidado', 0);
            })
            ->whereHas('ventas', function ($query) use ($userId) {
                $query->where('cliente_id', $userId);
            })
            ->with([
                'ventas' => function ($query) use ($userId) {
                    $query->where('cliente_id', $userId)
                        ->whereHas('creditos', function ($q) {
                            $q->where('activo', 1)
                                ->where('liquidado', 0);
                        });
                },
                'ventas.creditos' => function ($query) {
                    $query->where('activo', 1)
                        ->where('liquidado', 0);
                },
                'abonos.detalles.venta'
            ])
        ->get();

        $creditos = $clientes->map(function ($cliente) {
            $totalCredito = 0;
            $totalDeuda = 0;
            $detalles = [];

            foreach ($cliente->ventas as $venta) {
                if ($venta->creditos) {
                    $totalCredito += $venta->creditos->saldo_actual;
                    $totalDeuda += $venta->creditos->monto_credito;

                    $detalles[] = [
                        'venta_id'       => $venta->id,
                        'folio'          => $venta->folio,
                        'fecha'          => $venta->fecha,
                        'monto_credito'  => $venta->creditos->monto_credito,
                        'saldo_actual'   => $venta->creditos->saldo_actual,
                        'liquidado'      => $venta->creditos->liquidado,
                    ];
                }
            }

            // Total abonado sumando todos los abonos activos
            $totalAbonado = $cliente->abonos
                ->where('activo', 1)
                ->sum('total_abonado');

            // Historial de abonos
            $historial = $cliente->abonos
                ->where('activo', 1)
                ->sortBy('fecha')
                ->map(function ($abono) {
                    return [
                        'id_abono'      => $abono->id,
                        'folio_abono'   => $abono->folio,
                        'fecha'         => $abono->fecha,
                        'total_abonado' => $abono->total_abonado,
                        'detalles'      => $abono->detalles->map(function ($d) {
                            return [
                                'folio_venta'   => optional($d->venta)->folio,
                                'monto_antes'   => $d->monto_antes,
                                'abonado'       => $d->abonado,
                                'saldo_despues' => $d->saldo_despues,
                            ];
                        })->toArray()
                    ];
                });

            return [
                'cliente_id'     => $cliente->id,
                'full_name'      => $cliente->full_name,
                'deuda_credito'  => $totalCredito,
                'total_credito'  => $totalDeuda,
                'ventas_credito' => $detalles,
                'total_abonado'  => $totalAbonado,
                'historial_abonos' => $historial,
            ];
        });

        $formasPago = [
            ['metodo' => '', 'monto' => '', 'referencia' => '']
        ];

        return view('abonos.show', compact('creditos', 'formasPago'));
    }

    public function edit(Abono $abono)
    {
        //
    }

    public function update(Request $request, Abono $abono)
    {
        //
    }

    public function destroy(Abono $abono)
    {
        //
    }
}
