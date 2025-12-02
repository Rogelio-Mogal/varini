<x-validation-errors class="mb-4" />

<div class="grid lg:grid-cols-12 md:grid-cols-12 sm:grid-cols-12 gap-2">
    <div class="sm:col-span-12 lg:col-span-10 md:col-span-12">
        <label for="nombre" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nombre</label>
        <input type="text" id="nombre" name="nombre" required
            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
            placeholder="Producto / Servicio" value="{{ old('nombre', $productoServicio->nombre) }}" />
    </div>
    <div class="sm:col-span-12 lg:col-span-2 md:col-span-12">
        <label for="tipo" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Tipo</label>
        <div class="input-group">
            <select id="tipo" name="tipo" style="height: 400px !important;"
                class="select2 block w-full mt-1 p-2.5 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                @foreach ($tipoValues as $value)
                    <option value="{{ $value }}"
                        {{ old('tipo', isset($productoServicio) ? $productoServicio->tipo : '') == $value ? 'selected' : '' }}>
                        {{ ucfirst($value) }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="sm:col-span-12 lg:col-span-3 md:col-span-3">
        <label for="color" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Color</label>
        <input type="text" id="color" name="color"
            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
            placeholder="Color" value="{{ old('color', $productoServicio->color) }}" />
    </div>
    <div class="sm:col-span-12 lg:col-span-3 md:col-span-3">
        <label for="codigo_barra" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
            Código de barra
        </label>
        <input type="text" id="codigo_barra" name="codigo_barra"  required
            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
            placeholder="Ingrese el código de barras"
            value="{{ old('codigo_barra', $productoServicio->codigo_barra) }}" />
    </div>
    
    <div class="sm:col-span-12 lg:col-span-3 md:col-span-3">
        <label for="marca" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Marca</label>
        <div class="input-group">
            <select id="marca" name="marca" style="height: 400px !important;"
                class="select2 block w-full mt-1 p-2.5 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                <option value="" disabled @if ($metodo == 'create') selected @endif>-- MARCA --</option>
                @foreach ($marcaValues as $value)
                    <option value="{{ $value->id }}"
                        {{ old('marca', isset($productoServicio) ? $productoServicio->marca : '') == $value->id ? 'selected' : '' }}>
                        {{ ucfirst($value->nombre) }}
                    </option>
                @endforeach
            </select>
            <p id="marcaError" class="mt-2 text-xs text-red-600 dark:text-red-400 hidden"><span class="font-medium">Seleccione una marca.</span></p> 
        </div>
    </div>

    <div class="sm:col-span-12 lg:col-span-3 md:col-span-13">
        <label for="precio" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
            Precio servicio
        </label>
        <input type="number" min="0" step="0.01" id="precio" name="precio"
            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
            placeholder="Precio servicio"
            value="{{ old('precio', $productoServicio->precio) }}" />
    </div>



    @if ($metodo == 'create')
        <div class="sm:col-span-12 lg:col-span-12 md:col-span-12 flex justify-center items-center h-full mt-1">
            <label for="btn" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">&nbsp;</label>
            <button type="button" id="btn" class="focus:outline-none text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">
                Cargar inventario inicial
            </button>
            <input type="hidden" id="menuVisible" name="menuVisible" value="{{ old('menuVisible', $productoServicio->menuVisible ?? 0) }}">
        </div>
        <div class="sm:col-span-12 lg:col-span-4 md:col-span-12 menu" style="display:none">
            <label for="cantidad" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                Cantidad
            </label>
            <input type="number" min="1" step="1" id="cantidad" name="cantidad"
                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                placeholder="Cantidad"
                value="{{ old('cantidad', $productoServicio->cantidad) }}" />
        </div>
        <div class="sm:col-span-12 lg:col-span-4 md:col-span-12 menu" style="display:none">
            <label for="precio_costo" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                Precio costo
            </label>
            <input type="number" min="0" step="0.01" id="precio_costo" name="precio_costo"
                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                placeholder="Precio costo"
                value="{{ old('precio_costo', $productoServicio->precio_costo) }}" />
        </div>
        <div class="sm:col-span-12 lg:col-span-4 md:col-span-12 menu" style="display:none">
            <label for="precio_publico" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                Precio venta
            </label>
            <input type="number" min="0" step="0.01" id="precio_publico" name="precio_publico"
                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                placeholder="Precio venta"
                value="{{ old('precio_publico', $productoServicio->precio_publico) }}" />
        </div>
    @endif
    <div class="sm:col-span-12 lg:col-span-4 md:col-span-12">
        <label
            class="text-gray-900 bg-white border border-gray-300 focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-100 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-gray-800 dark:text-black dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:border-gray-600 dark:focus:ring-gray-700 cursor-pointer">
            <span>
                @if ($metodo == 'create')
                    Imagen
                @elseif($metodo == 'edit')
                    Actualizar imagen
                @endif
            </span>
            <input type="file" accept="image/*" name="imagen_1" id="imagen_1" class="hidden"
                onchange="previewImage(event, '#imgPreview1')">
        </label>
        <div id="fileError" class="text-red-600 hidden">Por favor, seleccione una imagen.</div>
        <figure class="flex justify-center items-center w-full h-full">
            <img class="object-cover object-center max-w-full max-h-full"
                src="{{ $metodo == 'edit' && $productoServicio->imagen_1 ? asset('' . $productoServicio->imagen_1) : '#' }}"
                alt="" id="imgPreview1">
        </figure>
    </div>
    <br/>
    <div class="sm:col-span-12 lg:col-span-12 md:col-span-12">
        <label for="descripcion" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Descripción del producto</label>
        <textarea id="descripcion" name="descripcion" rows="2"
            class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" 
            placeholder="Descripción del producto">{{ old('descripcion', $productoServicio->descripcion) }}</textarea>
    </div>
    <div class="sm:col-span-12 lg:col-span-12 md:col-span-12">
        <button type="submit"
            class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">
            @if ($metodo == 'create')
                CREAR PRODUCTO/SERVICIO
            @elseif($metodo == 'edit')
                EDITAR PRODUCTO/SERVICIO
            @endif
        </button>
    </div>
</div>

@section('js')
    <script>
        // PREVISUALIZACION DE IMAGEN
        function previewImage(event, querySelector, btnSelector) {
            const input = event.target; //Recuperamos el input que desencadeno la acción
            $imgPreview = document.querySelector(querySelector); //Recuperamos la etiqueta img donde cargaremos la imagen
            const $removeButton = document.querySelector(btnSelector); //Recuperamos el botón de eliminar
            if (!input.files.length) return // Verificamos si existe una imagen seleccionada
            file = input.files[0]; //Recuperamos el archivo subido
            objectURL = URL.createObjectURL(file); //Creamos la url
            $imgPreview.src = objectURL; //Modificamos el atributo src de la etiqueta img
            $removeButton.classList.remove('hidden'); // Mostramos el botón de quitar
        }

        // FUNCION PARA REMOVER IMAGEN
        function removeImage(inputSelector, imgSelector, btnSelector) {
            const input = document.querySelector(inputSelector);
            const $imgPreview = document.querySelector(imgSelector);
            const $removeButton = document.querySelector(btnSelector);
            input.value = ''; // Limpiamos el valor del input de archivo
            $imgPreview.src = '#'; // Restablecemos la src de la vista previa
            $removeButton.classList.add('hidden'); // Ocultamos el botón de quitar
        }

        $(document).ready(function() {
            // ACTIVA LA BUSQUEDA
            $(document).on('select2:open', () => {
                let allFound = document.querySelectorAll('.select2-container--open .select2-search__field');
                $(this).one('mouseup keyup', () => {
                    setTimeout(() => {
                        allFound[allFound.length - 1].focus();
                    }, 0);
                });
            });

            $('#tipo').select2({
                //selectOnClose: true
            });
            $('#marca').select2({
                placeholder: "-- MARCA --",
                allowClear: true
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


            $('#increment-button2').on('click', function() {
                let currentValue = parseInt($('#cantidad').val());
                if (!isNaN(currentValue) && currentValue < 999) {
                    $('#cantidad').val(currentValue - 1);
                }
            });

            $('#decrement-button2').on('click', function() {
                let currentValue = parseInt($('#cantidad').val());
                if (!isNaN(currentValue) && currentValue > 1) {
                    $('#cantidad').val(currentValue + 1);
                }
            });

            // MUESTRA INVENTARIO INICIAL
            $("#btn").click(function() {
                var $menu = $('.menu');
                if ($menu.is(':visible')) {
                    $menu.hide();
                    $("#menuVisible").val(0);
                    $("#cantidad").val('');
                    $("#precio_costo").val('');
                    $("#precio_publico").val('');
                    $("#precio_medio_mayoreo").val('');
                    $("#precio_mayoreo").val('');
                    // Quito los required
                    $("#cantidad").removeAttr('required');
                    $("#precio_costo").removeAttr('required');
                    $("#precio_publico").removeAttr('required');
                    $("#precio_medio_mayoreo").removeAttr('required');
                    $("#precio_mayoreo").removeAttr('required');
                } else {
                    $menu.show();
                    $("#menuVisible").val(1);
                    $("#cantidad").val(1);
                    // Agrego required
                    $("#cantidad").attr('required', 'required');
                    $("#precio_costo").attr('required', 'required');
                    $("#precio_publico").attr('required', 'required');
                    $("#precio_medio_mayoreo").attr('required', 'required');
                    $("#precio_mayoreo").attr('required', 'required');
                }
            });

            // VALIDACION DEL CAMPO FILA (IMAGEN 1)
            $('form').on('submit', function(e) {
                //e.preventDefault();
                var fileInput = $('#imagen_1');
                var fileError = $('#fileError');
                var marcaError = $('#marcaError');
                var familiaError = $('#familiaError');
                var isValid = true;

                /*if (fileInput.get(0).files.length === 0) {
                    e.preventDefault(); // Detener el envío del formulario
                    fileError.removeClass('hidden');
                    fileInput.focus(); // Dar foco al input para que se pueda seleccionar un archivo
                    isValid = false;
                } else {
                    fileError.addClass('hidden');
                }*/

                
                // validacion de select MARCA
                if ($('#marca').val() === null || $('#marca').val() === "") {
                    marcaError.removeClass('hidden');
                    isValid = false;
                } else {
                    marcaError.addClass('hidden');
                }

                if (!isValid) {
                    e.preventDefault();  // Prevent form submission if validation fails
                }

            });

            // Evitar el envío del formulario al presionar Enter, excepto en textarea
            $(document).on('keypress', function(e) {
                if (e.which == 13 && e.target.tagName !== 'TEXTAREA') {
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

            //HABILITA CAMPO PRECIO EN SERVICIOS
            const $tipoSelect = $('#tipo');
            const $precioInput = $('#precio');

            function togglePrecioInput() {
                const tipo = $tipoSelect.val();
                if (tipo === 'SERVICIO') {
                    $precioInput.prop('disabled', false).prop('required', true);
                } else {
                    $precioInput.prop('disabled', true).prop('required', false).val('');
                }
            }

            // Ejecutar al cargar la página
            togglePrecioInput();

            // Ejecutar cuando cambia el valor del select
            $tipoSelect.on('change', togglePrecioInput);
        });
    </script>
@stop
