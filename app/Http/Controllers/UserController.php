<?php

namespace App\Http\Controllers;

use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
        //$this->middleware(['can:Gestión de roles']);
    }
    
    public function index()
    {
        $users = User::all();
        return view('users.index', compact('users'));
    }

    public function create()
    {
        $user = new User();
        $roles = Role::all();
        $metodo = 'create';
        return view('users.create', compact('roles','metodo','user'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'printer_size' => 'required|integer',
            'email' => 'required|string|email|max:255|unique:users',
            //'es_reparador' => 'required|boolean',
            //'es_externo' => 'required|boolean',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Validación personalizada para full_name
        $fullName = $request->name . ' ' . $request->last_name;
        if (User::where('full_name', $fullName)->exists()) {
            return back()->withErrors(['full_name' => 'El usuario ya se encuentra registrado.'])->withInput();
        }

        try{
            $user = new User();
            $user->name = $request->name;
            $user->last_name = $request->last_name;
            $user->full_name = $fullName;
            $user->printer_size = $request->printer_size;
            $user->email = $request->email;
            //$user->es_reparador = $request->es_reparador;
            //$user->es_externo = $request->es_externo;
            $user->password = bcrypt($request->password);
            $user->save();

            $user->roles()->sync($request->roles);

            session()->flash('swal', [
                'icon' => "success",
                'title' => "Operación correcta",
                'text' => "El usuario se creó correctamente.",
                'customClass' => [
                    'confirmButton' => 'text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800'  // Aquí puedes añadir las clases CSS que quieras
                ],
                'buttonsStyling' => false  // Deshabilitar el estilo predeterminado de SweetAlert2
            ]);

            return redirect()->route('admin.users.index');
        } catch (\Exception $e) {
            \DB::rollback();
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

    public function show(User $user)
    {
        //
    }

    public function edit(User $user)
    {
        if($user->activo == 1){
            $metodo = 'edit';
            $roles = Role::all();
            return view('users.edit', compact('user','metodo','roles'));
        }else{
            return redirect()->route('admin.users.index');
        }   
    }

    public function update(Request $request, User $user)
    {
        if ($request->activa == 0){

            $request->validate([
                'name' => 'required|string|max:255',
                'email' => "required|string|email|max:255|unique:users,email,{$user->id}",
                'printer_size' => 'required|integer',
                //'es_reparador' => 'required|boolean',
                //'es_externo' => 'required|boolean',
                'password' => 'nullable|string|min:8|confirmed',
            ]);

            // Validación personalizada para full_name
            $fullName = $request->name . ' ' . $request->last_name;
            if (User::where('full_name', $fullName)->where('id', '!=', $user->id)->exists()) {
                return back()->withErrors(['full_name' => 'El usuario ya se encuentra registrado.'])->withInput();
            }

            try{
                $user->name = $request->name;
                $user->last_name = $request->last_name;
                $user->full_name = $fullName;
                $user->printer_size = $request->printer_size;
                $user->email = $request->email;
                //$user->es_reparador = $request->es_reparador;
                //$user->es_externo = $request->es_externo;
                
                // Solo actualizar la contraseña si se proporciona una nueva
                if ($request->has('password') && $request->password != '') {
                    $user->password = bcrypt($request->password);
                }

                $user->save();

                $user->roles()->sync($request->roles);

                session()->flash('swal', [
                    'icon' => "success",
                    'title' => "Operación correcta",
                    'text' => "El usuario se actualizó correctamente.",
                    'customClass' => [
                        'confirmButton' => 'text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800'  // Aquí puedes añadir las clases CSS que quieras
                    ],
                    'buttonsStyling' => false  // Deshabilitar el estilo predeterminado de SweetAlert2
                ]);

                return redirect()->route('admin.users.index');
            } catch (\Exception $e) {
                \DB::rollback();
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

        if ($request->activa == 1){
            // Remueve los últimos 5 caracteres de 'full_name' y 'email'
            $full_name = substr($user->full_name, 0, -6);
            $email = substr($user->email, 0, -6);

            // Verifica si 'full_name' y 'email' son únicos
            $isFullNameUnique = !User::where('full_name', $full_name)->where('id', '!=', $user->id)->exists();
            $isEmailUnique = !User::where('email', $email)->where('id', '!=', $user->id)->exists();

            if (!$isFullNameUnique || !$isEmailUnique) {
                // Almacena el mensaje de error en la sesión y redirige de vuelta
                session()->flash('swal', [
                    'icon' => "error",
                    'title' => "Error en la operación",
                    'text' => "El nombre completo o el correo electrónico ya existen. Por favor, elija otro.",
                    'customClass' => [
                        'confirmButton' => 'text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-red-600 dark:hover:bg-red-700 focus:outline-none dark:focus:ring-red-800'  // Aquí puedes añadir las clases CSS que quieras
                    ],
                    'buttonsStyling' => false  // Deshabilitar el estilo predeterminado de SweetAlert2
                ]);

                return redirect()->back();
            }

            try{
                // Actualiza los campos necesarios
                $user->update([
                    'full_name' => $full_name,
                    'email' => $email,
                    'activo' => 1
                ]);

                session()->flash('swal', [
                    'icon' => "success",
                    'title' => "Operación correcta",
                    'text' => "El usuario se activo correctamente.",
                    'customClass' => [
                        'confirmButton' => 'text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800'  // Aquí puedes añadir las clases CSS que quieras
                    ],
                    'buttonsStyling' => false  // Deshabilitar el estilo predeterminado de SweetAlert2
                ]);
                return redirect()->back();
            } catch (\Exception $e) {
                \DB::rollback();
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
    }

    public function destroy($id)//User $user)
    {
        try {

            /*$debe = 0;

            $montoDeuda = Ventas::select(
            'ventas.Id_Cliente',
            DB::raw(' (SUM(ventas.Total) - SUM(ventas.MontoPagado)) as debe ' ) )
            ->where('ventas.TipoVenta', 'Crédito')
            ->where('ventas.EstatusVenta','=','Activo')
            ->where('ventas.Id_Cliente', '=', $id)
            ->whereRaw('ventas.MontoPagado < ventas.Total')
            ->groupBy('ventas.Id_Cliente')
            ->get();

            foreach ($montoDeuda as $row) {
                $debe = $row->debe;
            }

            if($debe > 0){
                return redirect()->back()->with('fail','Error elimina cliente');
            }else if($debe <= 0  ){
                $cliente = Clientes::findorfail($id);*/

                $user = User::findorfail($id);
                $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

                $user->update([
                    'full_name' => $user->full_name.'-'.substr(str_shuffle($permitted_chars), 0, 5),
                    'email' => $user->email.'-'.substr(str_shuffle($permitted_chars), 0, 5),
                    'activo' => 0
                ]);
            //}

            session()->flash('swal', [
                'icon' => "success",
                'title' => "Operación correcta",
                'text' => "El usuario se eliminó correctamente.",
                'customClass' => [
                    'confirmButton' => 'text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800'  // Aquí puedes añadir las clases CSS que quieras
                ],
                'buttonsStyling' => false  // Deshabilitar el estilo predeterminado de SweetAlert2
            ]);
    
            //return redirect()->route('admin.users.index');
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
                ->with('status', 'Hubo un error al ingresar los datos, por favor intente de nuevo.')
                ->withErrors(['error' => $e->getMessage()]); // Aquí pasas el mensaje de error
        }
    }
}
