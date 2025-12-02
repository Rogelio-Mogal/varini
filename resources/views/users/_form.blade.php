<x-validation-errors class="mb-4" />
<input type="hidden" name="activa" id="activa" value="0">
<div class="grid lg:grid-cols-12 md:grid-cols-12 sm:grid-cols-12 gap-2">
    <div class="sm:col-span-12 lg:col-span-6 md:col-span-6">
        <label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nombre</label>
        <input type="text" id="name" name="name"
            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
            placeholder="Ingrese el nombre" value="{{ old('name', $user->name) }}" />
    </div>
    <div class="sm:col-span-12 lg:col-span-6 md:col-span-6">
        <label for="last_name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Apellidos</label>
        <input type="text" id="last_name" name="last_name"
            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
            placeholder="Ingrese los apellidos" value="{{ old('last_name', $user->last_name) }}" />
    </div>
    <div class="sm:col-span-12 lg:col-span-8 md:col-span-8">
        <label for="email" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Correo</label>
        <input type="email" id="email" name="email"
            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
            placeholder="Ingrese el correo" value="{{ old('email', $user->email) }}" />
    </div>
    <div class="sm:col-span-12 lg:col-span-4 md:col-span-4">
        <label for="printer_size" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Ticket</label>
        <div class="input-group">
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="printer_size" id="inlineRadio1" value="58" 
                    {{ ($user->printer_size == '58') ? 'checked' : '' }}
                >
                <label class="text-sm font-medium text-gray-800 dark:text-gray-200" for="inlineRadio1">58mm</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="printer_size" id="inlineRadio2" value="80"
                    {{ (isset($user) && $user->printer_size == '80') || !isset($user->printer_size) || $user->printer_size == '' ? 'checked' : '' }}
                >
                <label class="text-sm font-medium text-gray-800 dark:text-gray-200" for="inlineRadio2">80mm</label>
            </div>
        </div>
    </div>


    <div class="sm:col-span-12 lg:col-span-6 md:col-span-6">
        <label for="password" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Contraseña</label>
    <input type="password" id="password" name="password"
        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" />
    </div>
    <div class="sm:col-span-12 lg:col-span-6 md:col-span-6">
        <label for="password_confirmation" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Confirmar
            contraseña</label>
        <input type="password" id="password_confirmation" name="password_confirmation"
            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" />
    </div>
    <div class="sm:col-span-12 lg:col-span-12 md:col-span-12">
        <ul>
            @foreach ($roles as $item)
                <li>
                    <label class="text-sm font-medium text-gray-800 dark:text-gray-200">
                        <x-checkbox name="roles[]" value="{{ $item->id }}" :checked="in_array($item->id, old('roles', $user->roles->pluck('id')->toArray()))" />
                        {{ $item->name }}
                    </label>
                </li>
            @endforeach
        </ul>
    </div>
    
    <div class="sm:col-span-12 lg:col-span-12 md:col-span-12">
        <button type="submit"
        class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">
        @if ($metodo == 'create')
            CREAR USUARIO
        @elseif($metodo == 'edit')
            EDITAR USUARIO
        @endif
    </button>
    </div>
</div>

@section('js')
    <script>
        $(document).ready(function() {
            // Evitar el envío del formulario al presionar Enter
            $(document).on('keypress', function(e) {
                if (e.which == 13) {
                    e.preventDefault();
                }
            });

            // Variable para evitar envíos múltiples
            var formSubmitting = false;

            // Manejar el envío del formulario
            $('form').on('submit', function(e) {
                if (formSubmitting) {
                    // Si ya se está enviando, prevenir el envío
                    e.preventDefault();
                } else {
                    // Si no, marcar como enviando
                    formSubmitting = true;
                }
            });
        });
    </script>
@stop
