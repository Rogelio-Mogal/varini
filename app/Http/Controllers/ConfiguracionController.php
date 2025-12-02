<?php

namespace App\Http\Controllers;

use App\Models\Configuration;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class ConfiguracionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
        //$this->middleware(['can:Gestión de roles']);
    }

    public function index()
    {
        // Buscar configuración
        $configuracion = Configuration::first() ?? new Configuration();
        $metodo = $configuracion->exists ? 'edit' : 'create';
        $configuracion->imagen = $configuracion->imagen ? asset('storage/' . ltrim($configuracion->imagen, '/')) : asset('images/default.png');

        return view('configuracion.index', compact('configuracion', 'metodo'));

    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $request->validate([
            'razon_social' => 'required|string|max:255',
            'direccion' => 'nullable|string|max:255',
            'rfc' => 'nullable|string|max:255',
            'correo' => 'nullable|string|max:255',
            'telefonos' => 'nullable|string|max:255',
            'opc' => ['required', 'string', 'max:1000', function ($attribute, $value, $fail) {
                $json = json_decode($value, true);

                if (!is_array($json)) {
                    return $fail('El campo opciones debe ser un JSON válido.');
                }

                // Validar que al menos una opción esté activada
                $hayActivos = collect($json)->contains(function ($v) {
                    return (int)$v === 1;
                });

                if (!$hayActivos) {
                    return $fail('Debes seleccionar al menos una opción para imprimir.');
                }
            }],
            'horario' => 'nullable|string|max:255',
            'leyenda_inferior' => 'nullable|string|max:255',
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',

        ]);

        try{
            $configuracion = new Configuration();
            $configuracion->razon_social = $request->razon_social;
            $configuracion->direccion = $request->direccion;
            $configuracion->rfc = $request->rfc;
            $configuracion->correo = $request->correo;
            $configuracion->telefonos = $request->telefonos;
            $configuracion->campos_ticket = $request->opc;
            $configuracion->horario = $request->horario;
            $configuracion->leyenda_inferior = $request->leyenda_inferior;
            // Registrar la salida
            //$imageStorage = null;
            $imageStorageThumb = null;

            
            // RUTA PÚBLICA
            if ($request->file('imagen')) {
                $slug = Str::random(10);
                $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
                $file_name = $slug . '_' . substr(str_shuffle($permitted_chars), 0, 3) . '.' . $request->file('imagen')->getClientOriginalExtension();
            
                // Ruta hacia public_html/storage/... (donde realmente se ve la imagen)
                //$destinationPath = base_path('public/storage/img_ponchado/thumbs');
                $destinationPath = $_SERVER['DOCUMENT_ROOT'] . '/storage/configuracion/thumbs';
            
                // Crea la carpeta si no existe
                if (!file_exists($destinationPath)) {
                    mkdir($destinationPath, 0775, true);
                }
            
                // Mueve la imagen al destino
                $request->file('imagen')->move($destinationPath, $file_name);
            
                // Redimensiona con intervención/image
                $manager = new ImageManager(new Driver());
                $imgThumb = $manager->read($destinationPath . '/' . $file_name);
                $imgThumb->scale(null, 210, function($constraint) {
                    $constraint->aspectRatio();
                });
                $imgThumb->save($destinationPath . '/' . $file_name, 90, 'jpg');
            
                // Guarda la ruta relativa
                $configuracion->imagen = 'configuracion/thumbs/' . $file_name;
            }

            //RUTA SIMBÓLICA
            /*
            if($request->file('imagen')){
                $slug = Str::random(10); // Genera una cadena aleatoria de 10 caracteres
                $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
                $file_name = $slug.'_'.substr(str_shuffle($permitted_chars), 0, 3).'.'.$request->file('imagen')->getClientOriginalExtension();
                //$imageStorage = Storage::putFileAs('producto', $request->imagen_1, $file_name, [
                //    'visibility' => 'public',
                //]);

                $imageStorageThumb = Storage::putFileAs('configuracion/thumbs', $request->imagen, $file_name, [
                    'visibility' => 'public',
                ]);
                
                // IMAGEN NORMAL
                $manager = new ImageManager(new Driver());

                //$img = $manager->read(storage_path('app/public/' . $imageStorage));
                //$img->save(storage_path('app/public/' . $imageStorage), 90, 'jpg');

                // IMAGEN THUMB
                $imgThumb = $manager->read(storage_path('app/public/' . $imageStorageThumb));
                $imgThumb->scale(null, 210, function($constraint){
                    $constraint->aspectRatio();
                }); // Redimenciona el ancho, alto, ajuste de aspecto (máximo)
                $imgThumb->save(storage_path('app/public/' . $imageStorageThumb), 90, 'jpg');
                


                $configuracion->imagen = $imageStorageThumb;
                //$productoServicio->img_thumb = $imageStorageThumb;
            }
            */

            $configuracion->save();


            session()->flash('swal', [
                'icon' => "success",
                'title' => "Operación correcta",
                'text' => "Configuración guardada correctamente.",
                'customClass' => [
                    'confirmButton' => 'text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800'  // Aquí puedes añadir las clases CSS que quieras
                ],
                'buttonsStyling' => false  // Deshabilitar el estilo predeterminado de SweetAlert2
            ]);

            return redirect()->route('admin.configuracion.index');
        } catch (\Exception $e) {
            session()->flash('swal', [
                'icon' => "error",
                'title' => "Operación fallida",
                'text' => 'Hubo un error durante el proceso, por favor intente más tarde.',
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

    public function show(Configuration $configuration)
    {
        //
    }

    public function edit(Configuration $configuration)
    {
        //
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'razon_social' => 'required|string|max:255',
            'direccion' => 'nullable|string|max:255',
            'rfc' => 'nullable|string|max:255',
            'correo' => 'nullable|string|max:255',
            'telefonos' => 'nullable|string|max:255',
            'opc' => ['required', 'string', 'max:1000', function ($attribute, $value, $fail) {
                $json = json_decode($value, true);

                if (!is_array($json)) {
                    return $fail('El campo opciones debe ser un JSON válido.');
                }

                // Validar que al menos una opción esté activada
                $hayActivos = collect($json)->contains(function ($v) {
                    return (int)$v === 1;
                });

                if (!$hayActivos) {
                    return $fail('Debes seleccionar al menos una opción para imprimir.');
                }
            }],
            'horario' => 'nullable|string|max:255',
            'leyenda_inferior' => 'nullable|string|max:255',
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        try{
            $configuracion = Configuration::findOrFail($id);
            $configuracion->razon_social = $request->razon_social;
            $configuracion->direccion = $request->direccion;
            $configuracion->rfc = $request->rfc;
            $configuracion->correo = $request->correo;
            $configuracion->telefonos = $request->telefonos;
            $configuracion->campos_ticket = $request->opc;
            $configuracion->horario = $request->horario;
            $configuracion->leyenda_inferior = $request->leyenda_inferior;
            // Registrar la salida
            $imageStorageThumb = null;

            // === ACTUALIZAR IMAGEN ===
            if ($request->file('imagen')) {
                $slug = Str::random(10);
                $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
                $file_name = $slug . '_' . substr(str_shuffle($permitted_chars), 0, 3) . '.' . $request->file('imagen')->getClientOriginalExtension();
            
                $destinationPath = $_SERVER['DOCUMENT_ROOT'] . '/storage/configuracion/thumbs';
            
                // Crear carpeta si no existe
                if (!file_exists($destinationPath)) {
                    mkdir($destinationPath, 0775, true);
                }
            
                // Mover la nueva imagen
                $request->file('imagen')->move($destinationPath, $file_name);
            
                // Redimensionar miniatura
                $manager = new ImageManager(new Driver());
                $imgThumb = $manager->read($destinationPath . '/' . $file_name);
                $imgThumb->scale(null, 210, function ($constraint) {
                    $constraint->aspectRatio();
                });
                $imgThumb->save($destinationPath . '/' . $file_name, 90, 'jpg');
            
                // Eliminar imagen anterior si existe
                if (!empty($configuracion->imagen)) {
                    $oldPath = $_SERVER['DOCUMENT_ROOT'] . '/storage/' . $configuracion->imagen;
                    if (file_exists($oldPath)) {
                        unlink($oldPath);
                    }
                }
            
                // Guardar nueva ruta relativa
                $configuracion->imagen = 'configuracion/thumbs/' . $file_name;
            }

            //RUTA SIMBÓLICA
            /*
            if($request->file('imagen')){
                // Opción: eliminar la imagen anterior si existe
                if ($configuracion->imagen && Storage::disk('public')->exists($configuracion->imagen)) {
                    Storage::disk('public')->delete($configuracion->imagen);
                }

                $slug = Str::random(10); // Genera una cadena aleatoria de 10 caracteres
                $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
                $file_name = $slug.'_'.substr(str_shuffle($permitted_chars), 0, 3).'.'.$request->file('imagen')->getClientOriginalExtension();

                $imageStorageThumb = Storage::putFileAs('configuracion/thumbs', $request->imagen, $file_name, [
                    'visibility' => 'public',
                ]);
                
                // IMAGEN NORMAL
                $manager = new ImageManager(new Driver());

                // IMAGEN THUMB
                $imgThumb = $manager->read(storage_path('app/public/' . $imageStorageThumb));
                $imgThumb->scale(null, 210, function($constraint){
                    $constraint->aspectRatio();
                }); // Redimenciona el ancho, alto, ajuste de aspecto (máximo)
                $imgThumb->save(storage_path('app/public/' . $imageStorageThumb), 90, 'jpg');
                
                $configuracion->imagen = $imageStorageThumb;
            }
            */

            $configuracion->save();


            session()->flash('swal', [
                'icon' => "success",
                'title' => "Operación correcta",
                'text' => "Configuración guardada correctamente.",
                'customClass' => [
                    'confirmButton' => 'text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800'  // Aquí puedes añadir las clases CSS que quieras
                ],
                'buttonsStyling' => false  // Deshabilitar el estilo predeterminado de SweetAlert2
            ]);

            return redirect()->route('admin.configuracion.index');
        } catch (\Exception $e) {
            session()->flash('swal', [
                'icon' => "error",
                'title' => "Operación fallida",
                'text' => 'Hubo un error durante el proceso, por favor intente más tarde.',
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

    public function destroy(Configuration $configuration)
    {
        //
    }
}
