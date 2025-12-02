<x-validation-errors class="mb-4" />

<div class="grid lg:grid-cols-12 md:grid-cols-12 sm:grid-cols-12 gap-2">
    <div class="sm:col-span-12 lg:col-span-6 md:col-span-6">
        <label for="razon_social" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Razón social</label>
        <input type="text" id="razon_social" name="razon_social" required
            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
            placeholder="Razón social" value="{{ old('razon_social', $configuracion->razon_social) }}" />
    </div>

    <div class="sm:col-span-12 lg:col-span-6 md:col-span-6">
        <label for="direccion" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
            Dirección
        </label>
        <input type="text" id="direccion" name="direccion"
            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
            placeholder="Dirección"
            value="{{ old('direccion', $configuracion->direccion) }}" />
    </div>

    <div class="sm:col-span-12 lg:col-span-2 md:col-span-2">
        <label for="rfc" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
            RFC
        </label>
        <input type="text" id="rfc" name="rfc"
            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
            placeholder="RFC"
            value="{{ old('rfc', $configuracion->rfc) }}" />
    </div>

    <div class="sm:col-span-12 lg:col-span-3 md:col-span-3">
        <label for="correo" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
            Correo electrónico
        </label>
        <input type="text" id="correo" name="correo"
            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
            placeholder="Correo electrónico"
            value="{{ old('correo', $configuracion->correo) }}" />
    </div>

    <div class="sm:col-span-12 lg:col-span-3 md:col-span-3">
        <label for="telefonos" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
            Teléfonos
        </label>
        <input type="text" id="telefonos" name="telefonos"
            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
            placeholder="Teléfonos"
            value="{{ old('telefonos', $configuracion->telefonos) }}" />
    </div>

    <div class="sm:col-span-12 lg:col-span-3 md:col-span-3">
        <label for="telefonos" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
            Campos impresos del Ticket
        </label>
        
        <div class="input-group mb-3">
            <select class="form-control js-example-basic-multiple OpcPrinter" id="OpcPrinter" name="OpcPrinter[]" required="true" multiple="multiple">
                <option value="Razón social">Razón social</option>
                <option value="Dirección">Dirección</option>
                <option value="RFC">RFC</option>
                <option value="Correo electrónico">Correo electrónico</option>
                <option value="Teléfonos">Teléfonos</option>
                <option value="Horario de atención">Horario de atención</option>
                <option value="Leyenda inferior">Leyenda inferior</option>
                <option value="Imágen">Imágen</option>
            </select>
            <input type="hidden" name="opc" id="opc" value="{{ old('opc', $configuracion->campos_ticket ?? '') }}">
        </div>
    </div>

    <div class="sm:col-span-12 lg:col-span-6 md:col-span-6">
        <label for="horario" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Horario de atención</label>
        <textarea id="horario" name="horario" rows="2"
            class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" 
            placeholder="Horario de atención">{{ old('horario', $configuracion->horario) }}</textarea>
    </div>

    <div class="sm:col-span-12 lg:col-span-6 md:col-span-6">
        <label for="leyenda_inferior" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Leyenda inferiro</label>
        <textarea id="leyenda_inferior" name="leyenda_inferior" rows="2"
            class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" 
            placeholder="Leyenda inferiro">{{ old('leyenda_inferior', $configuracion->leyenda_inferior) }}</textarea>
    </div>
   

    <div class="sm:col-span-12 lg:col-span-8 md:col-span-12">
        <label
            for="imagen"
            class="text-gray-900 bg-white border border-gray-300 focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-100 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-gray-800 dark:text-black dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:border-gray-600 dark:focus:ring-gray-700 cursor-pointer">
            <span>
                @if ($metodo == 'create')
                    Imagen
                @elseif($metodo == 'edit')
                    Actualizar imagen
                @endif
            </span>
            <input type="file" accept="image/*" name="imagen" id="imagen" class="hidden"
                onchange="previewImage(event, '#imgPreview1')">
        </label>
        <div id="fileError" class="text-red-600 hidden">Por favor, seleccione una imagen.</div>
        <figure class="flex justify-center items-center w-full h-full">
            <img class="object-cover object-center max-w-full max-h-full"
                src="{{ $metodo == 'edit' && $configuracion->imagen ? asset('' . $configuracion->imagen) : '#' }}"
                alt="" id="imgPreview1">
        </figure>
    </div>

    
        
    <div class="sm:col-span-12 lg:col-span-12 md:col-span-12 mt-3">
        <button type="submit"
            class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">
            @if ($metodo == 'create')
                GUARDAR
            @elseif($metodo == 'edit')
                GUARDAR
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

            $(".OpcPrinter").select2({
                tags: true,
                tokenSeparators: [',', ' ']
            });

            // OBTENGO OPCIONES_TICKET PARA EDITAR
            // Intenta leer el JSON desde el campo hidden
            let oldOpcionesRaw = $('#opc').val();

            if (oldOpcionesRaw) {
                try {
                    let oldOpciones = JSON.parse(oldOpcionesRaw);
                    let seleccionados = [];

                    // Recorre las claves del JSON
                    for (let key in oldOpciones) {
                        if (parseInt(oldOpciones[key]) === 1) {
                            seleccionados.push(key);
                        }
                    }

                    // Selecciona los valores en el select
                    $('#OpcPrinter').val(seleccionados).trigger('change');

                } catch (e) {
                    console.error("JSON inválido en campo opc:", e);
                }
            }

            // Escucha cambios para regenerar el JSON
            $('#OpcPrinter').on('change', function () {
                let campos = {
                    "Razón social": 0,
                    "Dirección": 0,
                    "RFC": 0,
                    "Correo electrónico": 0,
                    "Teléfonos": 0,
                    "Horario de atención": 0,
                    "Leyenda inferior": 0,
                    "Imágen": 0
                };

                $('#OpcPrinter :selected').each(function () {
                    campos[this.value] = 1;
                });

                $('#opc').val(JSON.stringify(campos));
            });

            // OPCIONES TICKET CHANGE
            $('select[name="OpcPrinter[]"]').on('change', function() {
                var razonSocial = 0; var direccion = 0; 
                var rfc = 0;var email = 0; 
                var tel = 0;var horario = 0; 
                var leyenda = 0;var imagen = 0; 
                var tel1 = 0; var tel2 = 0;
                var text1 = 0; var text2 = 0;
                
                $(".OpcPrinter :selected").each(function() {
                    
                switch(this.value) {
                    case 'Razón social':
                        razonSocial =1;
                        break;
                    case 'Dirección':
                        direccion =1;
                        break;
                    case 'RFC':
                        rfc =1;
                        break;
                    case 'Correo electrónico':
                        email =1;
                        break;
                    case 'Teléfonos':
                        tel =1;
                        break;
                    case 'Horario de atención':
                        horario =1;
                        break;
                    case 'Leyenda inferior':
                        leyenda =1;
                        break;
                    case 'Imágen':
                        imagen =1;
                        break;
                    }
                });
                var json = '{ "Razón social": "'+razonSocial+'", "Dirección": "'+direccion+
                    '", "RFC": "'+rfc+
                    '", "Correo electrónico": "'+email+
                    '", "Teléfonos": "'+tel+
                    '", "Horario de atención": "'+horario+
                    '", "Leyenda inferior": "'+leyenda+
                    '", "Imágen": "'+imagen+'" }';
                $("#opc").val(json);
            });

            // VALIDACION DEL CAMPO FILA (IMAGEN 1)
            $('form').on('submit', function(e) {
                //e.preventDefault();
                var fileInput = $('#imagen');
                var fileError = $('#fileError');
                var marcaError = $('#clasificacion_ubicaciones_idError');
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
                if ($('#clasificacion_ubicaciones_id').val() === null || $('#clasificacion_ubicaciones_id').val() === "") {
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
        });
    </script>
@stop
