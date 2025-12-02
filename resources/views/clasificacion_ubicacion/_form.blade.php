<x-validation-errors class="mb-4" />
<div class="grid lg:grid-cols-12 md:grid-cols-12 sm:grid-cols-12 gap-2">
    <div class="sm:col-span-12 lg:col-span-6 md:col-span-6">
        <label for="nombre" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nombre</label>
        <input type="text" id="nombre" name="nombre"
            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
            placeholder="Ingrese el nombre" value="{{ old('nombre', $caracteristica->nombre) }}" />
    </div>
    <div class="sm:col-span-12 lg:col-span-3 md:col-span-3">
        <label for="tipo" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Tipo</label>
        <div class="input-group">
            <select id="tipo" name="tipo" style="height: 400px !important;"
                class="select2 block w-full mt-1 p-2.5 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                @foreach ($tipoValues as $value)
                    <option value="{{ $value }}"
                        {{ old('tipo', isset($caracteristica) ? $caracteristica->tipo : '') == $value ? 'selected' : '' }}>
                        {{ ucfirst($value) }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="sm:col-span-12 lg:col-span-3 md:col-span-3">
        <label for="tipo" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Botón</label>
        <button type="submit"
            class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">
            @if ($metodo == 'create')
                CREAR
            @elseif($metodo == 'edit')
                EDITAR
            @endif
        </button>
    </div>
</div>

@section('js')
    <script>
        $(document).ready(function() {
            $('#tipo').select2({
                selectOnClose: true
            });

            // Ajusta la altura del select2
            $('.select2-selection--single').css({
                'height': '2.5rem', // Ajusta la altura según sea necesario
                'display': 'flex',
                'align-items': 'center'
            });

            $('.select2-selection__rendered').css({
                'line-height': '2.5rem', // Asegúrate de que coincida con la altura del input
                'padding-left': '0.5rem', // Ajusta el padding según sea necesario
                'color': '#374151' // Asegúrate de que coincida con el texto del input
            });

            $('.select2-selection__arrow').css({
                'height': '2.5rem', // Ajusta la altura según sea necesario
                'top': '50%',
                'transform': 'translateY(-50%)'
            });

            // ACTIVA LA BUSQUEDA
            $(document).on('select2:open', () => {
                let allFound = document.querySelectorAll('.select2-container--open .select2-search__field');
                $(this).one('mouseup keyup', () => {
                    setTimeout(() => {
                        allFound[allFound.length - 1].focus();
                    }, 0);
                });
            });

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
