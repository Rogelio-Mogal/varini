<?php

namespace App\Http\Controllers;

use App\Models\ClasificacionUbicaciones;
use Illuminate\Http\Request;

class ClasificacionubicacionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
        //$this->middleware(['can:Gestión de roles']);
    }

    public function index()
    {
        $caracteristica = ClasificacionUbicaciones::where('activo', 1)->get();
        return view('clasificacion_ubicacion.index', compact('caracteristica'));
    }

    public function create()
    {
        $caracteristica = new ClasificacionUbicaciones();
        $tipoValues = ['CLASIFICACIÓN','UBICACIÓN'];
        $metodo = 'create';
        return view('clasificacion_ubicacion.create', compact('metodo','caracteristica','tipoValues'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nombre' => 'required|string|max:255',
            'tipo' => 'required|in:CLASIFICACIÓN,UBICACIÓN',
        ]);


        // Verificar la unicidad de la combinación nombre y tipo
        if (ClasificacionUbicaciones::where('nombre', $validatedData['nombre'])->where('tipo', $validatedData['tipo'])->exists()) {
            return redirect()->back()->withErrors(['nombre' => 'El nombre ya existe para este tipo.'])->withInput();
        }

        try {
            $caracteristica = new ClasificacionUbicaciones();
            $caracteristica->nombre = $request->nombre;
            $caracteristica->tipo = $request->tipo;
            $caracteristica->wci = auth()->user()->id;
            $caracteristica->save();

            session()->flash('swal', [
                'icon' => "success",
                'title' => "Operación correcta",
                'text' => "La característica se creó correctamente.",
                'customClass' => [
                    'confirmButton' => 'text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800'  // Aquí puedes añadir las clases CSS que quieras
                ],
                'buttonsStyling' => false  // Deshabilitar el estilo predeterminado de SweetAlert2
            ]);

            return redirect()->route('admin.clasificacion.ubicacion.index');
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
                ->withInput($request->all()) // Aquí solo pasas los valores del formulario
                ->with('status', 'Hubo un error al ingresar los datos, por favor intente de nuevo.')
                ->withErrors(['error' => $e->getMessage()]); // Aquí pasas el mensaje de error
        }
    }

    public function show(ClasificacionUbicaciones $clasificacionUbicaciones)
    {
        //
    }

    public function edit($id)
    {

        $metodo = 'edit';
        $caracteristica = ClasificacionUbicaciones::find($id);
        $tipoValues = ['CLASIFICACIÓN', 'UBICACIÓN'];

        return view('clasificacion_ubicacion.edit', compact('caracteristica','metodo','tipoValues'));

    }

    public function update(Request $request, $id)
    {
        $caracteristica = ClasificacionUbicaciones::findorfail($id);
        
        $validatedData = $request->validate([
            'nombre' => 'required|string|max:255',
            'tipo' => 'required|in:CLASIFICACIÓN,UBICACIÓN',
        ]);

        // Si el tipo es 'marca', el campo 'imagen' es requerido
        if ($request->input('tipo') === 'MARCA' && !$request->hasFile('imagen')) {
            return redirect()->back()->withErrors(['imagen' => 'La imagen es obligatoria cuando el tipo es marca.'])->withInput();
        }

        // Verificar la unicidad de la combinación nombre y tipo
         if (ClasificacionUbicaciones::where('nombre', $validatedData['nombre'])->where('tipo', $validatedData['tipo'])->where('id', '!=', $caracteristica->id)->exists()) {
            return redirect()->back()->withErrors(['nombre' => 'El nombre ya existe para este tipo.'])->withInput();
        }

        try {
            $caracteristica->nombre = $request->nombre;
            $caracteristica->tipo = $request->tipo;
            $caracteristica->wci = auth()->user()->id;
            $caracteristica->save();

            session()->flash('swal', [
                'icon' => "success",
                'title' => "Operación correcta",
                'text' => "La característica se actualizó correctamente.",
                'customClass' => [
                    'confirmButton' => 'text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800'  // Aquí puedes añadir las clases CSS que quieras
                ],
                'buttonsStyling' => false  // Deshabilitar el estilo predeterminado de SweetAlert2
            ]);

            return redirect()->route('admin.clasificacion.ubicacion.index');
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
                ->withInput($request->all()) // Aquí solo pasas los valores del formulario
                ->with('status', 'Hubo un error al ingresar los datos, por favor intente de nuevo.')
                ->withErrors(['error' => $e->getMessage()]); // Aquí pasas el mensaje de error
        }
    }

    public function destroy($id)
    {
        try {

            $caracteristica = ClasificacionUbicaciones::findorfail($id);
            $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

            $caracteristica->update([
                'nombre' => $caracteristica->nombre.'-'.substr(str_shuffle($permitted_chars), 0, 5),
                'activo' => 0
            ]);


            session()->flash('swal', [
                'icon' => "success",
                'title' => "Operación correcta",
                'text' => "La característica se eliminó correctamente.",
                'customClass' => [
                    'confirmButton' => 'text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800'  // Aquí puedes añadir las clases CSS que quieras
                ],
                'buttonsStyling' => false  // Deshabilitar el estilo predeterminado de SweetAlert2
            ]);
    
            //return redirect()->route('admin.users.index');
        } catch (\Exception $e) {
            $query = $e->getMessage();
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
}
