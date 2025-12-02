<?php

namespace App\Http\Controllers;

use App\Models\Inventario;
use App\Models\Kardex;
use App\Models\Producto;
use App\Models\ProductoCaracteristica;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class ProductosController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
        //$this->middleware(['can:Gestión de roles']);
    }

    public function index()
    {
        $productoServicio = Producto::where('id', '>', 2)
            ->with(['marca_c', 'familia_c', 'subFamilia_c'])
            ->get();
        return view('producto-servicio.index', compact('productoServicio'));
    }

    public function create()
    {
        $productoServicio = new Producto();
        $tipoValues = ['PRODUCTO', 'SERVICIO'];
        $marcaValues = ProductoCaracteristica::where('tipo', 'MARCA')
            ->where('activo', 1)
            ->where('id', '>', 2)
            ->select('id', 'nombre')
            ->get();
        $metodo = 'create';
        return view('producto-servicio.create', compact('metodo','productoServicio','tipoValues','marcaValues' ));
    }

    public function store(Request $request)
    {
        $rules = [
            'nombre' => 'required|string|min:2|max:255|unique:productos',
            'tipo' => 'required|in:PRODUCTO,SERVICIO',
            'codigo_barra' => 'required|string|max:255|unique:productos',
            'marca' => 'required|integer|min:1',
            'color' => 'nullable|string|min:2|max:255',
            'cantidad' => 'nullable|integer|min:1',
            'precio_costo' => 'nullable|numeric|min:0',
            'precio_publico' => 'nullable|numeric|min:0', 
            'imagen_1' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'descripcion' => 'nullable|string|min:2|max:1500',
        ];

        if ($request->input('menuVisible') == 1) {
            $rules['cantidad'] = 'required|numeric|min:1';
            $rules['precio_costo'] = 'required|numeric|min:1';
            $rules['precio_publico'] = 'required|numeric|min:1';
        }

        $validatedData = $request->validate($rules);
        
        // PRODUCTO/SERVICIOS SIN INVENTARIO INICIAL
        try {
            if($request->menuVisible == 0){
                $productoServicio = new Producto();
                $productoServicio->tipo = $request->tipo;
                $productoServicio->nombre = $request->nombre;
                $productoServicio->codigo_barra = $request->codigo_barra;
                $productoServicio->marca = $request->marca;
                $productoServicio->color = $request->color;
                $productoServicio->precio = $request->input('precio', 0);
                $productoServicio->descripcion = $request->descripcion;
                $productoServicio->wci = auth()->user()->id;

                // Registrar la salida
                $imageStorage = null;
                $imageStorageThumb = null;

                //RUTA SIMBÓLICA
                if($request->file('imagen_1')){
                    $slug = Str::random(10); // Genera una cadena aleatoria de 10 caracteres
                    $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
                    $file_name = $slug.'_'.substr(str_shuffle($permitted_chars), 0, 3).'.'.$request->file('imagen_1')->getClientOriginalExtension();
                    $imageStorage = Storage::putFileAs('producto', $request->imagen_1, $file_name, [
                        'visibility' => 'public',
                    ]);
    
                    $imageStorageThumb = Storage::putFileAs('producto/thumbs', $request->imagen_1, $file_name, [
                        'visibility' => 'public',
                    ]);
                    
                    // IMAGEN NORMAL
                    $manager = new ImageManager(new Driver());

                    $img = $manager->read(storage_path('app/public/' . $imageStorage));
                    $img->save(storage_path('app/public/' . $imageStorage), 90, 'jpg');

                    // IMAGEN THUMB
                    $imgThumb = $manager->read(storage_path('app/public/' . $imageStorageThumb));
                    $imgThumb->scale(null, 210, function($constraint){
                        $constraint->aspectRatio();
                    }); // Redimenciona el ancho, alto, ajuste de aspecto (máximo)
                    $imgThumb->save(storage_path('app/public/' . $imageStorageThumb), 90, 'jpg');


                    $productoServicio->imagen_1 = $imageStorage;
                    $productoServicio->img_thumb = $imageStorageThumb;
                }

                $productoServicio->save();

                session()->flash('swal', [
                    'icon' => "success",
                    'title' => "Operación correcta",
                    'text' => "El producto/servicio se creó correctamente.",
                    'customClass' => [
                        'confirmButton' => 'text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800'  // Aquí puedes añadir las clases CSS que quieras
                    ],
                    'buttonsStyling' => false  // Deshabilitar el estilo predeterminado de SweetAlert2
                ]);
        
                return redirect()->route('admin.producto.servicio.index');
            }else if($request->tipo == 'SERVICIO'){
                $productoServicio = new Producto();
                $productoServicio->tipo = $request->tipo;
                $productoServicio->nombre = $request->nombre;
                $productoServicio->codigo_barra = $request->codigo_barra;
                $productoServicio->marca = $request->marca;
                $productoServicio->color = $request->color;
                $productoServicio->precio = $request->input('precio', 0); //$request->precio ?? 0;
                $productoServicio->descripcion = $request->descripcion;
                $productoServicio->wci = auth()->user()->id;

                // Registrar la salida
                $imageStorage = null;
                $imageStorageThumb = null;

                //RUTA SIMBÓLICA
                if($request->file('imagen_1')){
                    $slug = Str::random(10); // Genera una cadena aleatoria de 10 caracteres
                    $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
                    $file_name = $slug.'_'.substr(str_shuffle($permitted_chars), 0, 3).'.'.$request->file('imagen_1')->getClientOriginalExtension();
                    $imageStorage = Storage::putFileAs('producto', $request->imagen_1, $file_name, [
                        'visibility' => 'public',
                    ]);
    
                    $imageStorageThumb = Storage::putFileAs('producto/thumbs', $request->imagen_1, $file_name, [
                        'visibility' => 'public',
                    ]);
                    
                    // IMAGEN NORMAL
                    $manager = new ImageManager(new Driver());

                    $img = $manager->read(storage_path('app/public/' . $imageStorage));
                    $img->save(storage_path('app/public/' . $imageStorage), 90, 'jpg');

                    // IMAGEN THUMB
                    $imgThumb = $manager->read(storage_path('app/public/' . $imageStorageThumb));
                    $imgThumb->scale(null, 210, function($constraint){
                        $constraint->aspectRatio();
                    }); // Redimenciona el ancho, alto, ajuste de aspecto (máximo)
                    $imgThumb->save(storage_path('app/public/' . $imageStorageThumb), 90, 'jpg');


                    $productoServicio->imagen_1 = $imageStorage;
                    $productoServicio->img_thumb = $imageStorageThumb;
                }

                $productoServicio->save();

                session()->flash('swal', [
                    'icon' => "success",
                    'title' => "Operación correcta",
                    'text' => "El producto/servicio se creó correctamente.",
                    'customClass' => [
                        'confirmButton' => 'text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800'  // Aquí puedes añadir las clases CSS que quieras
                    ],
                    'buttonsStyling' => false  // Deshabilitar el estilo predeterminado de SweetAlert2
                ]);
        
                return redirect()->route('admin.producto.servicio.index');

            }
        } catch (\Exception $e) {
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

        // PRODUCTO/SERVICIOS CON INVENTARIO INICIAL
        try {
            if($request->menuVisible == 1 && $request->tipo == 'PRODUCTO'){
                DB::beginTransaction();
                // CREAMOS EL PRODUCTO
                $productoServicio = new Producto();
                $productoServicio->tipo = $request->tipo;
                $productoServicio->nombre = $request->nombre;
                $productoServicio->codigo_barra = $request->codigo_barra;
                $productoServicio->marca = $request->marca;
                $productoServicio->color = $request->color;
                $productoServicio->precio = $request->input('precio', 0);
                $productoServicio->descripcion = $request->descripcion;
                $productoServicio->wci = auth()->user()->id;

                //RUTA SIMBÓLICA
                if($request->file('imagen_1')){
                    $slug = Str::random(10); // Genera una cadena aleatoria de 10 caracteres
                    $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
                    $file_name = $slug.'_'.substr(str_shuffle($permitted_chars), 0, 3).'.'.$request->file('imagen_1')->getClientOriginalExtension();
                    $imageStorage = Storage::putFileAs('productos', $request->imagen_1, $file_name, [
                        'visibility' => 'public',
                    ]);
    
                    $imageStorageThumb = Storage::putFileAs('productos/thumbs', $request->imagen_1, $file_name, [
                        'visibility' => 'public',
                    ]);
                    
                    // IMAGEN NORMAL
                    $manager = new ImageManager(new Driver());

                    $img = $manager->read(storage_path('app/public/' . $imageStorage));
                    $img->save(storage_path('app/public/' . $imageStorage), 90, 'jpg');

                    // IMAGEN THUMB
                    $imgThumb = $manager->read(storage_path('app/public/' . $imageStorageThumb));
                    $imgThumb->scale(null, 210, function($constraint){
                        $constraint->aspectRatio();
                    }); // Redimenciona el ancho, alto, ajuste de aspecto (máximo)
                    $imgThumb->save(storage_path('app/public/' . $imageStorageThumb), 90, 'jpg');


                    $productoServicio->imagen_1 = $imageStorage;
                    $productoServicio->img_thumb = $imageStorageThumb;
                }

                $productoServicio->save();

                // Obtener el ID del registro recién insertado
                $insertedId = $productoServicio->id;

                // SE SUPONE QUE EL NOMBRE Y EL CODIGO SON UNICOS POR LO TANTO SE INSERTA EN INVENTARIO
                $inventario = new Inventario();
                $inventario->producto_id = $insertedId;
                $inventario->cantidad = $request->cantidad;
                //$inventario->cantidad_minima = $request->cantidad_minima;
                $inventario->precio_costo = $request->precio_costo;
                $inventario->precio_anterior = $request->precio_costo;
                $inventario->precio_publico = $request->precio_publico;
                $inventario->save();

                // INSERTAMOS EL MOVIMIENTO EN EL KARDEX
                $kardex = new Kardex();
                $kardex->producto_id = $insertedId;
                $kardex->movimiento_id = 0;
                $kardex->tipo_movimiento = 'ENTRADA';
                $kardex->tipo_detalle = 'INVENTARIO';
                $kardex->fecha = Carbon::now();
                $kardex->folio = 'S/N';
                $kardex->descripcion = 'Producto con inventario inicial';
                $kardex->debe = $inventario->cantidad;
                $kardex->haber = 0;
                $kardex->saldo = $inventario->cantidad;
                $kardex->wci = auth()->user()->id;
                $kardex->save();

                DB::commit();

                session()->flash('swal', [
                    'icon' => "success",
                    'title' => "Operación correcta",
                    'text' => "El producto/servicio se creó correctamente.",
                    'customClass' => [
                        'confirmButton' => 'text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800'  // Aquí puedes añadir las clases CSS que quieras
                    ],
                    'buttonsStyling' => false  // Deshabilitar el estilo predeterminado de SweetAlert2
                ]);
        
                return redirect()->route('admin.producto.servicio.index');
                
            }
        } catch (\Exception $e) {
            DB::rollback();
            $query = $e->getMessage();
            dd($e);
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

    public function show($id)
    {

        $productos = Producto::where('id', '!=', 1)
        ->whereHas('inventario', function ($query) use ($id) {
            $query->where('producto_id', $id);
        })
        ->with('inventario') // Carga la relación 'inventario'
        ->get();

        return json_encode($productos);
    }

    public function edit($id)
    {
        $productoServicio = Producto::with('inventario')->findorfail($id);
        $tipoValues = ['PRODUCTO', 'SERVICIO'];
        $marcaValues = ProductoCaracteristica::where('tipo', 'MARCA')
            ->where('activo', 1)
            ->where('id', '>', 2)
            ->select('id', 'nombre')
            ->get();

        if($productoServicio->activo == 1 && $productoServicio->id > 2){
            $metodo = 'edit';
            return view('producto-servicio.edit', compact('productoServicio','metodo','tipoValues','marcaValues'));
        }else{
            return redirect()->route('admin.producto.servicio.index');
        } 
    }

    public function update(Request $request, $id)
    {
        $productoServicio = Producto::findorfail($id);

        /*
        // Verificar si hubo cambios en los datos
        $changes = [
            'nombre' => $request->nombre,
            'tipo' => $request->tipo,
            'codigo_barra' => $request->codigo_barra,
            'marca' => $request->marca,
            'color' => $request->color,
            'imagen_1' => $request->imagen_1,
            'descripcion' => $request->imagen_1,
            'precio' => $request->precio,
        ];
        */

        // Asignar los valores del request al modelo
        $productoServicio->nombre = $request->nombre;
        $productoServicio->tipo = $request->tipo;
        $productoServicio->codigo_barra = $request->codigo_barra;
        $productoServicio->marca = $request->marca;
        $productoServicio->color = $request->color;
        $productoServicio->descripcion = $request->descripcion; // corregido
        $productoServicio->precio = $request->precio ?? 0;

        // Verificar si los campos de imagen cambiaron
        $imagen1Changed = $request->hasFile('imagen_1') && $request->file('imagen_1')->isValid();

        //if ($productoServicio->isDirty(array_keys($changes)) || $imagen1Changed) {
        if ($productoServicio->isDirty() || $imagen1Changed) {
            $rules = [
                'nombre' => "required|string|min:2|max:255|unique:productos,nombre,{$productoServicio->id}",
                'tipo' => 'required|in:PRODUCTO,SERVICIO',
                'codigo_barra' => "required|string|max:255|unique:productos,codigo_barra,{$productoServicio->id}",
                'marca' => 'required|integer|min:1',
                'color' => 'nullable|string|min:2|max:255',
                'imagen_1' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                'descripcion' => 'nullable|string|min:2|max:1500',
                'precio' => 'nullable|integer',
            ];

            $validatedData = $request->validate($rules);
        
            try {
                DB::beginTransaction();
                $productoServicio->tipo = $request->tipo;
                $productoServicio->nombre = $request->nombre;
                $productoServicio->codigo_barra = $request->codigo_barra;
                $productoServicio->marca = $request->marca;
                $productoServicio->color = $request->color;
                $productoServicio->precio = $request->input('precio', 0); //$request->precio ?? 0;
                $productoServicio->descripcion = $request->descripcion;


                if($request->file('imagen_1')){
                    if($productoServicio->imagen_1){
                        //dd('a');
                        // IMAGEB NORMAL
                        $imageUrl = $productoServicio->imagen_1;
                        // Extraer solo la ruta del archivo desde la URL
                        $imagePath = parse_url($imageUrl, PHP_URL_PATH);
                        $imagePath = str_replace('storage/', '', $imagePath);
                        
                        if (Storage::disk('public')->exists($imagePath)) {
                            Storage::disk('public')->delete($imagePath);
                        } 

                        // IMG THUMS
                        $imageUrlThum = $productoServicio->img_thumb;
                        // Extraer solo la ruta del archivo desde la URL
                        $imagePathThum = parse_url($imageUrlThum, PHP_URL_PATH);
                        $imagePathThum = str_replace('storage/', '', $imagePathThum);
                        
                        if (Storage::disk('public')->exists($imagePathThum)) {
                            Storage::disk('public')->delete($imagePathThum);
                        }
                    }


                    $slug = Str::random(10); // Genera una cadena aleatoria de 10 caracteres
                    $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
                    $file_name = $slug.'-'.substr(str_shuffle($permitted_chars), 0, 3).'.'.$request->file('imagen_1')->getClientOriginalExtension();
                    //$imageStorage = $request->file('imagen')->storeAs('productos', $file_name);
                    
                    $imageStorage = Storage::putFileAs('productos', $request->imagen_1 ,$file_name, [
                        'visibility' => 'public',
                    ]);

                    $imageStorageThumb = Storage::putFileAs('productos/thumbs', $request->imagen_1, $file_name, [
                        'visibility' => 'public',
                    ]);

                    // IMAGEN NORMAL
                    $manager = new ImageManager(new Driver());
                    $img = $manager->read('storage/'.$imageStorage);
                    $img->save('storage/'.$imageStorage, 90, 'jpg' ); // Ruta de la imagen, Calidad de imagen, trabajara la imagen como jpg pero no la cambiara de extencion   
                    
                    // IMAGEN THUMB
                    $imgThumb = $manager->read('storage/'.$imageStorageThumb);
                    $imgThumb->scale(null, 210, function($constraint){
                        $constraint->aspectRatio();
                    }); // Redimenciona el ancho, alto, ajuste de aspecto (máximo)
                    $imgThumb->save('storage/'.$imageStorageThumb, 90, 'jpg' );

                    $productoServicio->imagen_1 = $imageStorage;
                    $productoServicio->img_thumb = $imageStorageThumb;
                }

                $productoServicio->save();

                DB::commit();

                session()->flash('swal', [
                    'icon' => "success",
                    'title' => "Operación correcta",
                    'text' => "El producto/servicio se actualizó correctamente.",
                    'customClass' => [
                        'confirmButton' => 'text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800'  // Aquí puedes añadir las clases CSS que quieras
                    ],
                    'buttonsStyling' => false  // Deshabilitar el estilo predeterminado de SweetAlert2
                ]);

                return redirect()->route('admin.producto.servicio.index');
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
                'text' => "No se realizaron cambios en el producto o servicio.",
                'customClass' => [
                    'confirmButton' => 'text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800'
                ],
                'buttonsStyling' => false
            ]);
        
            return redirect()->route('admin.producto.servicio.index');
        }
    }

    public function destroy($id)
    {
        try {
            $productoServicio = Producto::findorfail($id);
            
            if($productoServicio->id > 2){
                if($productoServicio->activo == 0){
                    return response()->json([
                        'swal' => [
                            'icon' => "success",
                            'title' => "Operación correcta",
                            'text' => "El producto/servicio se eliminó correctamente.",
                            'customClass' => [
                                'confirmButton' => 'text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800'
                            ],
                            'buttonsStyling' => false
                        ],
                        'success' => 'La compra se eliminó correctamente.'
                    ], 200);
                }
                // Verifica si el registro es un producto o un servicio
                if ($productoServicio->tipo == 'PRODUCTO') {
                    // Es un producto, verifica la existencia en el inventario
                    $inventario = $productoServicio->inventario;

                    if ($inventario && $inventario->cantidad > 0) {
                        // Si el producto tiene stock, no permitir la actualización/eliminación
                        return response()->json([
                            'swal' => [
                                'icon' => "error",
                                'title' => "Operación fallida",
                                'text' => "No se puede eliminar el producto: " . $productoServicio->nombre . " porque tiene stock en existencia.",
                                'customClass' => [
                                    'confirmButton' => 'text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800'
                                ],
                                'buttonsStyling' => false
                            ],
                            'error' => 'No se puede eliminar el producto: ' . $productoServicio->nombre . ' porque tiene stock en existencia.'
                        ], 400);
                    }
                }

                // Si es un servicio o un producto sin stock, procede con la actualización/eliminación
                $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
                $productoServicio->update([
                    'nombre' => $productoServicio->nombre . '-' . substr(str_shuffle($permitted_chars), 0, 5),
                    'codigo_barra' => $productoServicio->codigo_barra . '-' . substr(str_shuffle($permitted_chars), 0, 5),
                    'activo' => 0
                ]);

                // Respuesta exitosa
                return response()->json([
                    'swal' => [
                        'icon' => "success",
                        'title' => "Operación correcta",
                        'text' => "El producto/servicio se eliminó correctamente.",
                        'customClass' => [
                            'confirmButton' => 'text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800'
                        ],
                        'buttonsStyling' => false
                    ],
                    'success' => 'La compra se eliminó correctamente.'
                ], 200);

            }else{
                return redirect()->route('admin.producto.servicio.index');
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

    public function productos_index_ajax(Request $request)
    {
        // TODOS LOS PRODUCTOS PARA EL INDEX DE LA TABLA PRODUCTOS/SERVICIOS
        if($request->origen == 'productos.index'){
            $productos = Producto::where('id', '>', 2)
                ->with(['inventario','marca_c'])
                ->get();

            // Modificar cada producto para agregar la URL completa de la imagen
            $productos = $productos->map(function ($producto) {
                return [
                    'id' => $producto->id,
                    //'img_thumb' => $producto->img_thumb ? url('storage/' . $producto->img_thumb) : asset('images/default.png'),
                    'img_thumb' => $producto->img_thumb ? asset('storage/' . ltrim($producto->img_thumb, '/')) : asset('images/default.png'),
                    'nombre' => $producto->nombre,
                    'codigo_barra' => $producto->codigo_barra,
                    'cantidad' => $producto->inventario ? $producto->inventario->cantidad : 0,
                    'marca' => $producto->marca_c ? $producto->marca_c->nombre : 'NO ASIGNADO',
                    'color' => $producto->color,
                    'activo' => $producto->activo
                ];
            });

            return response()->json(['data' => $productos]);
        }

        // PRODUCTOS PARA EL APARTADO DE COMPRAS (SOLO PRODUCTOS)
        if($request->origen == 'productos.compras'){
            $productos = Producto::where('id', '>', 2)
                ->where('activo', 1)
                ->where('tipo', '=' , 'PRODUCTO')
                ->with(['inventario'])
                ->get();

            return response()->json(['data' => $productos]);
        }

        // PRODUCTOS PARA EL APARTADO DE INVENTARIO (SOLO PRODUCTOS)
        if($request->origen == 'productos.inventario'){
            $productos = Producto::where('id', '>', 2)
                ->where('activo', 1)
                ->where('tipo', '=' , 'PRODUCTO')
                ->with(['inventario'])
                ->get();

            return response()->json(['data' => $productos]);
        }

        // PRODUCTOS PARA EL APARTADO DE COTIZACIONES (SOLO PRODUCTOS)
        if($request->origen == 'productos.cotizaciones'){
            $productos = Producto::where('id', '>', 2)
                ->where('activo', 1)
                ->where('tipo', '=' , 'PRODUCTO')
                ->with(['inventario'])
                ->get();

            return response()->json(['data' => $productos]);
        }

        // PRODUCTOS PARA EL APARTADO DE VENTAS (SOLO PRODUCTOS, CON INVENTARIO)
        if($request->origen == 'productos.ventas'){

            /*$productos = Producto::where('id', '>', 2)
            ->where('activo', 1)
            ->where('tipo', 'PRODUCTO')
            ->whereHas('inventario', function ($query) {
                $query->where('cantidad', '>', 0);
            })
            ->with(['inventario' => function ($query) {
                $query->where('cantidad', '>', 0);
            }])
            ->get();*/

            $productos = Producto::where('id', '>', 2)
            ->where('activo', 1)
            ->where(function ($query) {
                $query->where('tipo', 'PRODUCTO')
                    ->whereHas('inventario', function ($q) {
                        $q->where('cantidad', '>', 0);
                    })
                    ->orWhere('tipo', 'SERVICIO');
            })
            ->with(['inventario'])
            ->get();

            return response()->json(['data' => $productos]);
        }
    }

    public function busca_codbarra_productos_compra(Request $request)
    {
        // OBTENGO LOS PRODUCTOS POR SU CODIGO DE BARRAS (COMPRAS)
        if($request->origen == 'busca.producto.servicio.compra'){
            $productos = Producto::where('codigo_barra', '=', $request->codbarra)
                    ->where('tipo','PRODUCTO')
                    ->where('activo', 1)
                    //->with(['inventario'])
                    ->get();
                    
            return response()->json(['data' => $productos]);
        }

        // OBTENGO LOS PRODUCTOS POR SU CODIGO DE BARRAS (COTIZACIONES)
        if($request->origen == 'busca.producto.servicio.cotizaciones'){
            $productos = Producto::where('codigo_barra', '=', $request->codbarra)
                    ->where('tipo','PRODUCTO')
                    ->with(['inventario'])
                    ->get();
                    
            return response()->json(['data' => $productos]);
        }
    }
}
