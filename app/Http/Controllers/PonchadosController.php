<?php

namespace App\Http\Controllers;

use App\Models\ClasificacionUbicaciones;
use App\Models\PonchadoDetalles;
use App\Models\Ponchados;
use App\Models\ServiciosPonchadosVenta;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\MessageBag;

class PonchadosController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:sanctum');
        //$this->middleware(['can:Gestión de roles']);
    }

    public function index()
    {
        return view('ponchados.index');
    }

    public function create()
    {
        $ponchado = new Ponchados();
        $marcaValues = ClasificacionUbicaciones::where('tipo', 'CLASIFICACIÓN')
            ->where('activo', 1)
            ->select('id', 'nombre')
            ->get();
        $metodo = 'create';
        $detalle = [];
        $fondos = [['fondo_tela' => '', 'color' => [], 'codigo' => [], 'otro' => []]];
        return view('ponchados.create', compact('metodo','ponchado','marcaValues','detalle','fondos' ));
    }

    public function store(Request $request)
    {
        $marcaValues = ClasificacionUbicaciones::where('tipo', 'CLASIFICACIÓN')
            ->where('activo', 1)
            ->select('id', 'nombre')
            ->get();

        $rules = [
            'nombre' => 'required|string|min:2|max:255|unique:ponchados',
            'clasificacion_ubicaciones_id' => 'required|in:' . implode(',', $marcaValues->pluck('id')->toArray()),
            'puntadas' => 'required|integer|min:1',
            'ancho' => 'required|string|min:1',
            'largo' => 'required|string|min:1',
            'aro' => 'required|string|min:1',
            'imagen_1' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'archivo' => 'nullable|max:10240', // max 10MB (puedes ajustar el tamaño)
            'nota' => 'nullable|string|min:2|max:1500',

            // Validación para arrays dinámicos (fondos clonables)
            'fondos.*.fondo_tela' => 'required|string|min:2',
            'fondos.*.color.*' => 'required|string|max:255',
            'fondos.*.codigo.*' => 'nullable|string|min:1|max:255',
        ];

        // 3. Definir nombres personalizados para errores más legibles
        $customAttributes = [
            'fondos.*.fondo_tela' => 'fondo',
            'fondos.*.color.*' => 'color',
            'fondos.*.codigo.*' => 'código',
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
                if (preg_match('/fondos\.(\d+)\.(color|codigo)\.(\d+)/', $field, $matches)) {
                    $bloque = $matches[1] + 1;
                    $campo = $matches[2];
                    $item = $matches[3] + 1;

                    $campoNombre = $campo === 'color' ? 'Color' : 'Código';

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
            DB::beginTransaction();

            $ponchado = new Ponchados();
            $ponchado->nombre = $request->nombre;
            $ponchado->clasificacion_ubicaciones_id = $request->clasificacion_ubicaciones_id;
            $ponchado->puntadas = $request->puntadas;
            $ponchado->ancho = $request->ancho;
            $ponchado->largo = $request->largo;
            $ponchado->aro = $request->aro;
            $ponchado->nota = $request->nota;
            $ponchado->wci = auth()->user()->id;

            // Registrar la salida
            $imageStorageThumb = null;

            // RUTA PÚBLICA
            if ($request->file('imagen_1')) {
                $slug = Str::random(10);
                $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
                $file_name = $slug . '_' . substr(str_shuffle($permitted_chars), 0, 3) . '.' . $request->file('imagen_1')->getClientOriginalExtension();

                // Ruta hacia public_html/storage/... (donde realmente se ve la imagen)
                //$destinationPath = base_path('public/storage/img_ponchado/thumbs');
                $destinationPath = $_SERVER['DOCUMENT_ROOT'] . '/storage/img_ponchado/thumbs';

                // Crea la carpeta si no existe
                if (!file_exists($destinationPath)) {
                    mkdir($destinationPath, 0775, true);
                }

                // Mueve la imagen al destino
                $request->file('imagen_1')->move($destinationPath, $file_name);

                // Redimensiona con intervención/image
                $manager = new ImageManager(new Driver());
                $imgThumb = $manager->read($destinationPath . '/' . $file_name);
                $imgThumb->scale(null, 210, function($constraint) {
                    $constraint->aspectRatio();
                });
                $imgThumb->save($destinationPath . '/' . $file_name, 90, 'jpg');

                // Guarda la ruta relativa
                $ponchado->imagen_1 = 'img_ponchado/thumbs/' . $file_name;
            }


            // SUBIMOS EL ARCHIVO
            if ($request->hasFile('archivo')) {
                $extension = $request->file('archivo')->getClientOriginalExtension();
                $intentos = 0;

                do {
                    $file_name = Str::random(10) . '.' . $extension;
                    $relativePath = 'archivos_ponchados/' . $file_name;
                    $fullPath = public_path('storage/' . $relativePath); // public_html/storage/archivos_ponchados
                    $intentos++;
                } while (file_exists($fullPath) && $intentos < 10);

                // Asegura que exista la carpeta
                $destination = public_path('storage/archivos_ponchados');
                if (!file_exists($destination)) {
                    mkdir($destination, 0775, true);
                }

                // Mueve el archivo
                $request->file('archivo')->move($destination, $file_name);

                // Guarda solo la ruta relativa en la BD
                $ponchado->archivo = $relativePath;
            }

            $ponchado->save();

            // CREAMOS EL DETALLES
            foreach ($request->fondos  as $fondoIndex  => $fondo) {
                $fondoTela = $fondo['fondo_tela'];
                $colores = $fondo['color'];
                $codigos = $fondo['codigo'];
                $otro = $fondo['otro'];

                foreach ($colores as $i => $color) {
                    PonchadoDetalles::insert([
                        'ponchado_id' => $ponchado->id,
                        'color_tela' => $fondoTela, // Este es el fondo (ej. BLANCO o NEGRO)
                        'color' => $color,
                        'codigo' => $codigos[$i] ?? null,
                        'otro' => $otro[$i] ?? null,
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now()
                    ]);
                }
            }

            DB::commit();

            session()->flash('swal', [
                'icon' => "success",
                'title' => "Operación correcta",
                'text' => "El ponchado se creó correctamente.",
                'customClass' => [
                    'confirmButton' => 'text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800'  // Aquí puedes añadir las clases CSS que quieras
                ],
                'buttonsStyling' => false  // Deshabilitar el estilo predeterminado de SweetAlert2
            ]);

            return redirect()->route('admin.precio.ponchado.create', [
                'cliente_id'   => $request->cliente_id,   // si viene del formulario
                'ponchado_id'  => $ponchado->id,
                'tipoPrecio'   => 'ponchado'              // si usas este campo
            ]);

            //return redirect()->route('admin.ponchados.index');
        } catch (\Exception $e) {
            DB::rollback();
            $query = $e->getMessage();
            session()->flash('swal', [
                'icon' => "error",
                'title' => "Operación fallida",
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

    public function show($id)
    {
        $ponchado = Ponchados::with(['detalles' => function ($query) {
            $query->where('activo', 1)->orderBy('color_tela');
        }])->findOrFail($id);

        $fondos = [];

        foreach ($ponchado->detalles as $detalle) {
            $key = $detalle->color_tela;
            if (!isset($fondos[$key])) {
                $fondos[$key] = [
                    'fondo_tela' => $detalle->color_tela,
                    'color' => [],
                    'codigo' => [],
                    'otro' => []
                ];
            }
            $fondos[$key]['color'][]  = $detalle->color;
            $fondos[$key]['codigo'][] = $detalle->codigo;
            $fondos[$key]['otro'][]   = $detalle->otro;
        }

        // Reindexa para que el foreach de Blade use índices 0,1,2...
        $fondos = array_values($fondos);

        $ponchado->imagen_1 = $ponchado->imagen_1 ? asset('storage/' . ltrim($ponchado->imagen_1, '/')) : asset('images/default.png');

        $marcaValues = ClasificacionUbicaciones::where('tipo', 'CLASIFICACIÓN')
            ->where('activo', 1)
            ->select('id', 'nombre')
            ->get();

        $detalle = PonchadoDetalles::where('ponchado_id',$id)
            ->where('activo',1)
            ->orderBy('color_tela')
            ->get()
            ->groupBy('color_tela');;

        $archivoUrl = null;

        if ($ponchado->archivo) {
            $rutaArchivo = public_path('storage/' . $ponchado->archivo);
            if (file_exists($rutaArchivo)) {
                $archivoUrl = asset('storage/' . $ponchado->archivo);
            }
        }

        if($ponchado->activo == 1 && $ponchado->id != 1){
            $metodo = 'edit';
            return view('ponchados.show', compact('ponchado','metodo','marcaValues','detalle','fondos','archivoUrl'));
        }else{
            return redirect()->route('admin.ponchados.index');
        }
    }

    public function edit($id)
    {
       // $ponchado = Ponchados::findorfail($id);

        $ponchado = Ponchados::with(['detalles' => function ($query) {
            $query->where('activo', 1)->orderBy('color_tela');
        }])->findOrFail($id);

        $fondos = [];

        foreach ($ponchado->detalles as $detalle) {
            $key = $detalle->color_tela;
            if (!isset($fondos[$key])) {
                $fondos[$key] = [
                    'fondo_tela' => $detalle->color_tela,
                    'color' => [],
                    'codigo' => [],
                    'otro' => []
                ];
            }
            $fondos[$key]['color'][]  = $detalle->color;
            $fondos[$key]['codigo'][] = $detalle->codigo;
            $fondos[$key]['otro'][]   = $detalle->otro;
        }

        // Reindexa para que el foreach de Blade use índices 0,1,2...
        $fondos = array_values($fondos);

        $ponchado->imagen_1 = $ponchado->imagen_1 ? asset('storage/' . ltrim($ponchado->imagen_1, '/')) : asset('images/default.png');

        $marcaValues = ClasificacionUbicaciones::where('tipo', 'CLASIFICACIÓN')
            ->where('activo', 1)
            ->select('id', 'nombre')
            ->get();

        //$ponchado->imagen_1 = $ponchado->imagen_1 ? asset('storage/' . ltrim($ponchado->imagen_1, '/')) : asset('images/default.png');

        $detalle = PonchadoDetalles::where('ponchado_id',$ponchado->id)
            ->where('activo',1)
            ->orderBy('color_tela')
            ->get();

        if($ponchado->activo == 1 && $ponchado->id != 1){
            $metodo = 'edit';
            return view('ponchados.edit', compact('ponchado','metodo','marcaValues','detalle','fondos'));
        }else{
            return redirect()->route('admin.ponchados.index');
        }
    }

    public function update(Request $request, $id)
    {
        $marcaValues = ClasificacionUbicaciones::where('tipo', 'CLASIFICACIÓN')
            ->where('activo', 1)
            ->select('id', 'nombre')
            ->get();

        $ponchado = Ponchados::findorfail($id);

        // Verificar si hubo cambios en los datos
        $changes = [
            'nombre' => $request->nombre,
            'clasificacion_ubicaciones_id' => $request->clasificacion_ubicaciones_id,
            'puntadas' => $request->puntadas,
            'ancho' => $request->ancho,
            'largo' => $request->largo,
            'aro' => $request->aro,
            'nota' => $request->nota,
        ];

        // Verificar si los campos de imagen ó el archivo cambiaron
        $imagen1Changed = $request->hasFile('imagen_1') && $request->file('imagen_1')->isValid();
        $fileChanged = $request->hasFile('archivo') && $request->file('archivo')->isValid();

        // Verificar cambios en los fondos dinámicos
        $fondosExistentes = PonchadoDetalles::where('ponchado_id', $ponchado->id)
            ->orderBy('color_tela')
            ->get()
            ->map(function ($detalle) {
                return [
                    'color_tela' => $detalle->color_tela,
                    'color' => $detalle->color,
                    'codigo' => $detalle->codigo,
                    'otro' => $detalle->otro,
                ];
            })->toArray();

        $fondosNuevos = [];

        foreach ($request->fondos as $fondo) {
            foreach ($fondo['color'] as $i => $color) {
                $fondosNuevos[] = [
                    'color_tela' => $fondo['fondo_tela'],
                    'color' => $color,
                    'codigo' => $fondo['codigo'][$i] ?? null,
                    'otro' => $fondo['otro'][$i] ?? null,
                ];
            }
        }

        // Ordenar para comparación precisa
        usort($fondosExistentes, fn($a, $b) => strcmp(json_encode($a), json_encode($b)));
        usort($fondosNuevos, fn($a, $b) => strcmp(json_encode($a), json_encode($b)));

        $cambiosFondos = $fondosExistentes != $fondosNuevos;

        // Asignar los nuevos valores
        $ponchado->fill($changes);

        // Comprobación final de si hubo cualquier cambio
        if ($ponchado->isDirty(array_keys($changes)) || $imagen1Changed || $fileChanged || $cambiosFondos) {

            $validator = Validator::make($request->all(), [
                'nombre' => "required|string|min:2|max:255|unique:ponchados,nombre,{$ponchado->id}",
                'clasificacion_ubicaciones_id' => 'required|in:' . implode(',', $marcaValues->pluck('id')->toArray()),
                'puntadas' => 'required|integer|min:1',
                'ancho' => 'required|string|min:1',
                'largo' => 'required|string|min:1',
                'aro' => 'required|string|min:1',
                'imagen_1' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                'archivo' => 'nullable|max:10240', // max 10MB (puedes ajustar el tamaño) 'nullable|mimes:zip,rar,emb,pdf|max:10240'
                'nota' => 'nullable|string|min:2|max:1500',

                // Validación para arrays dinámicos (fondos clonables)
                'fondos.*.fondo_tela' => 'required|string|min:2',
                'fondos.*.color.*' => 'required|string|max:255',
                'fondos.*.codigo.*' => 'nullable|string|min:1|max:255',
            ]);

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

            try {
                DB::beginTransaction();
                $ponchado->nombre = $request->nombre;
                $ponchado->clasificacion_ubicaciones_id = $request->clasificacion_ubicaciones_id;
                $ponchado->puntadas = $request->puntadas;
                $ponchado->ancho = $request->ancho;
                $ponchado->largo = $request->largo;
                $ponchado->aro = $request->aro;
                $ponchado->nota = $request->nota;
                $ponchado->wci = auth()->user()->id;

                // === ACTUALIZAR IMAGEN ===
                if ($request->hasFile('imagen_1') && $request->file('imagen_1')->isValid()) {
                    $nuevoNombreOriginal = $request->file('imagen_1')->getClientOriginalName();

                    // Evitar actualizar si el archivo es el mismo que ya está guardado
                    if (empty($ponchado->imagen_1) || !str_contains($ponchado->imagen_1, $nuevoNombreOriginal)) {
                        $slug = Str::random(10);
                        $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
                        $file_name = $slug . '_' . substr(str_shuffle($permitted_chars), 0, 3) . '.' . $request->file('imagen_1')->getClientOriginalExtension();

                        $destinationPath = $_SERVER['DOCUMENT_ROOT'] . '/storage/img_ponchado/thumbs';

                        // Crear carpeta si no existe
                        if (!file_exists($destinationPath)) {
                            mkdir($destinationPath, 0775, true);
                        }

                        // Mover la nueva imagen
                        $request->file('imagen_1')->move($destinationPath, $file_name);

                        // Redimensionar miniatura
                        $manager = new ImageManager(new Driver());
                        $imgThumb = $manager->read($destinationPath . '/' . $file_name);
                        $imgThumb->scale(null, 210, function ($constraint) {
                            $constraint->aspectRatio();
                        });
                        $imgThumb->save($destinationPath . '/' . $file_name, 90, 'jpg');

                        // Eliminar imagen anterior si existe
                        if (!empty($ponchado->imagen_1)) {
                            $oldPath = $_SERVER['DOCUMENT_ROOT'] . '/storage/' . $ponchado->imagen_1;
                            if (file_exists($oldPath)) {
                                unlink($oldPath);
                            }
                        }

                        // Guardar nueva ruta relativa
                        $ponchado->imagen_1 = 'img_ponchado/thumbs/' . $file_name;
                    }else{
                        // Mantener la imagen anterior
                        $ponchado->imagen_1 = $ponchado->getOriginal('imagen_1');
                    }
                }

                // === ACTUALIZAR ARCHIVO ===
                if ($request->hasFile('archivo') && $request->file('archivo')->isValid()) {
                    $nuevoNombreOriginal = $request->file('archivo')->getClientOriginalName();

                    if (empty($ponchado->archivo) || !str_contains($ponchado->archivo, $nuevoNombreOriginal)) {
                        $extension = $request->file('archivo')->getClientOriginalExtension();
                        $extension = $request->file('archivo')->getClientOriginalExtension();
                        $intentos = 0;

                        do {
                            $file_name = Str::random(10) . '.' . $extension;
                            $relativePath = 'archivos_ponchados/' . $file_name;
                            $fullPath = public_path('storage/' . $relativePath);
                            $intentos++;
                        } while (file_exists($fullPath) && $intentos < 10);

                        $destination = public_path('storage/archivos_ponchados');
                        if (!file_exists($destination)) {
                            mkdir($destination, 0775, true);
                        }

                        // Mover nuevo archivo
                        $request->file('archivo')->move($destination, $file_name);

                        // Eliminar archivo anterior si existe
                        if (!empty($ponchado->archivo)) {
                            $oldFile = public_path('storage/' . $ponchado->archivo);
                            if (file_exists($oldFile)) {
                                unlink($oldFile);
                            }
                        }

                        // Guardar nueva ruta relativa
                        $ponchado->archivo = $relativePath;
                    }else{
                        // Mantener el archivo anterior
                        $ponchado->archivo = $ponchado->getOriginal('archivo');
                    }
                }

                $ponchado->save();

                // Limpiar detalles anteriores
                PonchadoDetalles::where('ponchado_id', $ponchado->id)->delete();

                // Agregar nuevos detalles
                foreach ($request->fondos as $fondo) {
                    foreach ($fondo['color'] as $i => $color) {
                        PonchadoDetalles::create([
                            'ponchado_id' => $ponchado->id,
                            'color_tela' => $fondo['fondo_tela'],
                            'color' => $color,
                            'codigo' => $fondo['codigo'][$i] ?? null,
                            'otro' => $fondo['otro'][$i] ?? null,
                        ]);
                    }
                }

                DB::commit();

                session()->flash('swal', [
                    'icon' => "success",
                    'title' => "Operación correcta",
                    'text' => "El ponchado se actualizó correctamente.",
                    'customClass' => [
                        'confirmButton' => 'text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800'  // Aquí puedes añadir las clases CSS que quieras
                    ],
                    'buttonsStyling' => false  // Deshabilitar el estilo predeterminado de SweetAlert2
                ]);

                return redirect()->route('admin.ponchados.index');
            } catch (\Exception $e) {
                DB::rollback();
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
                'text' => "No se realizaron cambios en el ponchado.",
                'customClass' => [
                    'confirmButton' => 'text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800'
                ],
                'buttonsStyling' => false
            ]);

            return redirect()->route('admin.ponchados.index');
        }
    }

    public function destroy($id)
    {
        try {
            $ponchado = Ponchados::findorfail($id);

            if($ponchado->activo == 0){
                return response()->json([
                    'swal' => [
                        'icon' => "success",
                        'title' => "Operación correcta",
                        'text' => "El ponchado se eliminó correctamente.",
                        'customClass' => [
                            'confirmButton' => 'text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800'
                        ],
                        'buttonsStyling' => false
                    ],
                    'success' => 'El ponchado se eliminó correctamente.'
                ], 200);
            }

            if($ponchado->id != 1){
                // Si es un servicio o un producto sin stock, procede con la actualización/eliminación
                $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
                $ponchado->update([
                    'nombre' => $ponchado->nombre . '-' . substr(str_shuffle($permitted_chars), 0, 5),
                    'activo' => 0
                ]);

                // Respuesta exitosa
                return response()->json([
                    'swal' => [
                        'icon' => "success",
                        'title' => "Operación correcta",
                        'text' => "El ponchado se eliminó correctamente.",
                        'customClass' => [
                            'confirmButton' => 'text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800'
                        ],
                        'buttonsStyling' => false
                    ],
                    'success' => 'El ponchado se eliminó correctamente.'
                ], 200);
            }else{
                return response()->json([
                    'swal' => [
                        'icon' => "success",
                        'title' => "Operación correcta",
                        'text' => "No podrá eliminar este registro.",
                        'customClass' => [
                            'confirmButton' => 'text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800'
                        ],
                        'buttonsStyling' => false
                    ],
                    'success' => 'No podrá eliminar este registro.'
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

    public function ponchados_index_ajax(Request $request)
    {
        // TODOS LOS PONCHADOS PARA EL INDEX
        if($request->origen == 'ponchados.index'){
            $ponchados = Ponchados::with('clasificacion')
                ->where('id', '>=', 1)
                ->get();

            // Modificar cada producto para agregar la URL completa de la imagen
            $ponchados = $ponchados->map(function ($producto) {
                /*$rutaImagen = ltrim($producto->imagen_1, '/');

                if ($producto->imagen_1 && Storage::disk('public')->exists($rutaImagen)) {
                    $img = asset('storage/' . $rutaImagen);
                } else {
                    $img = asset('images/default.png');
                }*/
                return [
                    'id' => $producto->id,
                    'nombre' => $producto->nombre,
                    'clasificacion' =>  $producto->clasificacion->nombre ?? null,
                    'puntadas' => $producto->puntadas,
                    'ancho' => $producto->ancho,
                    'largo' => $producto->largo,
                    'aro' => $producto->aro,
                    'img_thumb' => $producto->imagen_1 ? asset('storage/' . ltrim($producto->imagen_1, '/')) : asset('images/default.png'),
                    //'img_thumb' => $img,
                    'archivo' => $producto->archivo,
                    'nota' => $producto->nota,
                    'es_borrador' => $producto->es_borrador,
                    'wci' => $producto->wci,
                    'activo' => $producto->activo
                ];
            });

            return response()->json(['data' => $ponchados]);
        }

        // PONCHADOS PARA EL APARTADO DE PEDIDOS
        if($request->origen == 'ponchados.pedidos'){
            /*$ponchados = ServiciosPonchadosVenta::whereIn('servicios_ponchados_ventas.activo', [1, 2])
                ->with(['ponchado', 'cliente', 'clasificacionUbicacion'])
                ->orderBy('fecha_estimada_entrega', 'asc')
                //->where('estatus','!=','Entregado')
                ->whereNotIn('estatus', ['Entregado', 'Finalizado']) // EXCLUYE AMBOS
                //->where('activo',1)
                //->whereIn('activo', [1, 2])
                ->whereDoesntHave('ventaDetalles')
                ->get()
                ->groupBy('referencia_cliente');*/


            /*
            $ponchados = ServiciosPonchadosVenta::query()
            // 1️⃣ SOLO REGISTROS VISIBLES
            ->where('activo', 1)

            // 2️⃣ RELACIONES
            ->with(['ponchado', 'cliente', 'clasificacionUbicacion'])

            // 3️⃣ EXCLUIR PEDIDOS CERRADOS (solo si TODOS los ACTIVOS están cerrados)
            ->whereNotIn('referencia_cliente', function ($q) {
                $q->select('referencia_cliente')
                ->from('servicios_ponchados_ventas')
                ->where('activo', 1) // 👈 CLAVE
                ->groupBy('referencia_cliente')
                ->havingRaw(
                    'COUNT(*) = SUM(estatus IN ("Entregado"))'
                );
            })

            // 4️⃣ OTRAS REGLAS
            ->whereDoesntHave('ventaDetalles')
            ->orderBy('fecha_estimada_entrega', 'asc')

            // 5️⃣ EJECUCIÓN
            ->get()
            ->groupBy('referencia_cliente');
            */


            $ponchados = ServiciosPonchadosVenta::query()

            // 1️⃣ SOLO REGISTROS ACTIVOS (1 y 2)
            ->whereIn('activo', [1, 2])

            // 2️⃣ EXCLUIR ESTATUS NO DESEADOS
            ->whereNotIn('estatus', ['Entregado', 'Eliminado', 'Finalizado'])


            // 3️⃣ RELACIONES
            ->with(['ponchado', 'cliente', 'clasificacionUbicacion'])

            // 4️⃣ EXCLUIR LOS QUE YA ESTÁN EN VENTA
            ->whereDoesntHave('ventaDetalles')

            // 5️⃣ ORDEN
            ->orderBy('fecha_estimada_entrega', 'asc')

            // 6️⃣ EJECUTAR
            ->get()

            // 7️⃣ AGRUPAR POR PEDIDO
            ->groupBy('referencia_cliente');

            // Modificar cada producto para agregar la URL completa de la imagen
            $data = $ponchados->map(function ($items, $referencia) {

                // Definir orden de prioridad
                $prioridad = [
                    'Diseño' => 1,
                    'Programado para bordar' => 2,
                    'Bordando' => 3,
                    'Finalizado' => 4,
                    'Entregado' => 5,
                    'Eliminado' => 6,
                ];

                // Buscar el estatus de mayor prioridad (menor número)
                $estatusPedido = collect($items)
                    ->sortBy(fn($i) => $prioridad[$i->estatus] ?? 999)
                    ->first()
                    ->estatus;

                return [
                    'id' => $referencia,
                    //'cliente' => $items->first()->cliente ? $items->first()->cliente->full_name : null,

                    'cliente' => $items->first()->cliente_id == 17
                    ? ($items->first()->cliente_alias ?? 'CLIENTE PÚBLICO')
                    : ($items->first()->cliente?->full_name ?? 'CLIENTE PÚBLICO'),

                    'referencia_cliente' => $referencia,
                    'fecha_estimada_entrega' => $items->first()->fecha_estimada_entrega,
                    //'estatus' => $items->first()->estatus,
                    'estatus' => $estatusPedido, // ÚNICO ESTATUS DEL PEDIDO
                    'activo' => $items->first()->activo,
                    'detalles' => $items->map(function ($producto) {
                        /*$img = asset('images/default.png');
                        if ($producto->ponchado && $producto->ponchado->imagen_1) {
                            $rutaImagen = ltrim($producto->ponchado->imagen_1, '/');

                            if (Storage::disk('public')->exists($rutaImagen)) {
                                $img = asset('storage/' . $rutaImagen);
                            }
                        }*/
                        return [
                            'id' => $producto->id,
                            'img_thumb' => $producto->ponchado->imagen_1 ? asset('storage/' . ltrim($producto->ponchado->imagen_1, '/')) : asset('images/default.png'),
                            //'img_thumb' => $img,
                            'ponchado' => $producto->ponchado->nombre,
                            'prenda' => $producto->prenda,
                            'clasificacion' => $producto->clasificacionUbicacion->nombre ?? null,
                            'cantidad_piezas' => $producto->cantidad_piezas,
                            'estatus' => $producto->estatus,
                            'cliente_id' => $producto->cliente_id,   // 🔹 incluir
                            'precio' => $producto->precioPonchado ? $producto->precioPonchado->precio : $producto->precio_unitario,
                        ];
                    })->values(),
                ];
            })->values();

            return response()->json(['data' => $data]);
        }

        // PONCHADOS-PEDIDOS PARA EL APARTADO DE VENTAS
        if($request->origen == 'ponchados.pedidos.ventas'){
            $ponchados = ServiciosPonchadosVenta::where('servicios_ponchados_ventas.activo', 1)
                ->where('servicios_ponchados_ventas.estatus','!=','Entregado')
                ->with(['ponchado', 'cliente', 'clasificacionUbicacion', 'formasPago'])
                ->get();

            // Modificar cada producto para agregar la URL completa de la imagen
            /*$ponchados = $ponchados->map(function ($producto) {
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
            */

            $ponchados = $ponchados->map(function ($producto) {
                $pagado = $producto->formasPago->where('activo', true)->sum('monto');
                $restante = $producto->subtotal - $pagado;

                return [
                    'id' => $producto->id,
                    'img_thumb' => $producto->ponchado->imagen_1
                        ? asset('storage/' . ltrim($producto->ponchado->imagen_1, '/'))
                        : asset('images/default.png'),
                    'nombre' => $producto->referencia_cliente,
                    'clasificacion' => $producto->clasificacionUbicacion->nombre ?? null,
                    'puntadas' => $producto->ponchado->puntadas,
                    'ancho' => $producto->ponchado->ancho,
                    'largo' => $producto->ponchado->largo,
                    'aro' => $producto->ponchado->aro,
                    'estatus' => $producto->estatus,
                    'activo' => $producto->activo,
                    'piezas' => $producto->cantidad_piezas,
                    'total' => $producto->subtotal,
                    'pagado' => $pagado,
                    'restante' => $restante,
                    'fecha' => $producto->fecha_estimada_entrega,
                ];
            });

            return response()->json(['data' => $ponchados]);
        }
    }
}
