<x-validation-errors class="mb-4" />

<div class="grid lg:grid-cols-12 md:grid-cols-12 sm:grid-cols-12 gap-2">
    <div class="sm:col-span-12 lg:col-span-2 md:col-span-2">
        <label for="btn-cliente" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
            Busqueda
        </label>
        <button data-modal-target="cliente-modal" data-modal-toggle="cliente-modal" id="btn-cliente"
            class="block w-full text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm  py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
            type="button">
            Cliente
        </button>
    </div>
    <input type="hidden" id="cliente_id" name="cliente_id" value="{{ old('cliente_id', $ponchado->cliente_id) }}">
    <input type="hidden" id="precio_puntada" name="precio_puntada" value="{{ old('precio_puntada', $ponchado->precio_puntada) }}">
    <div class="sm:col-span-12 lg:col-span-10 md:col-span-10">
        <label for="nombre_cliente" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Cliente</label>
        <input type="text" id="nombre_cliente" name="nombre_cliente" required
            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
            placeholder="Nombre" value="{{ old('nombre_cliente', $ponchado->nombre_cliente) }}"
            readonly />
    </div>

    <div class="sm:col-span-12 lg:col-span-6 md:col-span-7">
        <label for="referencia_cliente" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nombre</label>
        <input type="text" id="referencia_cliente" name="referencia_cliente" required
            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
            placeholder="Nombre" value="{{ old('referencia_cliente', $ponchado->referencia_cliente) }}" />
    </div>
    <div class="sm:col-span-12 lg:col-span-4 md:col-span-5">
        <label for="clasificacion_ubicaciones_id" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Ubicación</label>
        <div class="input-group">
            <select id="clasificacion_ubicaciones_id" name="clasificacion_ubicaciones_id" style="height: 400px !important;"
                class="select2 block w-full mt-1 p-2.5 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                <option value="" disabled @if ($metodo == 'create') selected @endif>-- UBICACIÓN --</option>
                @foreach ($marcaValues as $value)
                    <option value="{{ $value->id }}"
                        {{ old('clasificacion_ubicaciones_id', isset($ponchado) ? $ponchado->clasificacion_ubicaciones_id : '') == $value->id ? 'selected' : '' }}>
                        {{ ucfirst($value->nombre) }}
                    </option>
                @endforeach
            </select>
            <p id="clasificacion_ubicaciones_idError" class="mt-2 text-xs text-red-600 dark:text-red-400 hidden"><span class="font-medium">Seleccione una clasificación.</span></p> 
        </div>
    </div>
    <div class="sm:col-span-12 lg:col-span-2 md:col-span-2">
        <label for="btn-ponchado" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
            Busqueda
        </label>
        <button data-modal-target="ponchado-modal" data-modal-toggle="ponchado-modal" id="btn-ponchado"
            class="block w-full text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm  py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
            type="button">
            Ponchado
        </button>
    </div>

    <input type="hidden" id="ponchado_id" name="ponchado_id" value="{{ old('ponchado_id', $ponchado->ponchado_id) }}">
    <input type="hidden" id="producto_id" name="producto_id" value="2">
    
    
    <div class="sm:col-span-12 lg:col-span-4 md:col-span-4">
        <label for="nombre_ponchado" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Ponchado</label>
        <input type="text" id="nombre_ponchado" name="nombre_ponchado" required
            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
            placeholder="Nombre" value="{{ old('nombre_ponchado', $ponchado->nombre_ponchado) }}"
            readonly />
    </div>
    <div class="sm:col-span-12 lg:col-span-2 md:col-span-2">
        <label for="puntadas" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
            Puntadas
        </label>
        <input type="number" min="1" step="1" id="puntadas" name="puntadas"
            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
            placeholder="Puntadas"
            value="{{ old('puntadas', $ponchado->puntadas) }}" 
            readonly/>
    </div>
    <div class="sm:col-span-12 lg:col-span-2 md:col-span-2">
        <label for="ancho" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
            Ancho
        </label>
        <input type="number" min="1" step="1" id="ancho" name="ancho"
            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
            placeholder="Ancho"
            value="{{ old('ancho', $ponchado->ancho) }}" 
            readonly/>
    </div>
    <div class="sm:col-span-12 lg:col-span-2 md:col-span-2">
        <label for="largo" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
            Largo
        </label>
        <input type="number" min="1" step="1" id="largo" name="largo"
            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
            placeholder="Largo"
            value="{{ old('largo', $ponchado->largo) }}" 
            readonly/>
    </div>
    <div class="sm:col-span-12 lg:col-span-2 md:col-span-2">
        <label for="aro" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
            Aro
        </label>
        <input type="number" min="1" step="1" id="aro" name="aro"
            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
            placeholder="Aro"
            value="{{ old('aro', $ponchado->aro) }}" 
            readonly/>
    </div>
    
    <div class="sm:col-span-12 lg:col-span-3 md:col-span-3">
        <label for="cantidad_piezas" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Cantidad</label>
        <input type="number" id="cantidad_piezas" name="cantidad_piezas" min="1" step="1"
            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
            placeholder="Cantidad"  value="{{ old('cantidad_piezas', $ponchado->cantidad_piezas) }}"  />
    </div>
    <div class="sm:col-span-12 lg:col-span-3 md:col-span-3">
        <label for="precio_unitario" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Precio</label>
        <input type="number" id="precio_unitario" name="precio_unitario" min="0.01" step="any"
            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
            placeholder="Precio"  value="{{ old('precio_unitario', $ponchado->precio_unitario) }}"  />
    </div>
    <div class="sm:col-span-12 lg:col-span-3 md:col-span-3">
        <label for="subtotal" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Total</label>
        <input type="number" id="subtotal" name="subtotal" min="0.01" step="any"
            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
            placeholder="Total"  value="{{ old('subtotal', $ponchado->subtotal) }}"  />
    </div>
    <div class="sm:col-span-12 lg:col-span-3 md:col-span-3">
        <label for="fecha_estimada_entrega" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Fecha estimada
            de entrega</label>
        <input type="date" id="fecha_estimada_entrega" name="fecha_estimada_entrega" required
            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
            value="{{ old('fecha_estimada_entrega', $ponchado->fecha_estimada_entrega) }}" />
    </div>


    <!-- ##### MODULO DE COLORES  #########   -->
        <div class="sm:col-span-12 lg:col-span-12 md:col-span-12">

            <div id="fondos_container" class="col-span-12 grid grid-cols-1 md:grid-cols-1 lg:grid-cols-1 gap-6">
                <!-- Fondo individual -->
                <div class="bg-white shadow-md rounded-xl p-3 border border-gray-200 fondo-item">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold">Fondo de Tela</h3>
                        <button type="button" class="text-red-600 hover:text-red-800 trash">
                            <svg class="w-6 h-6 text-red-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5v14m-6-8h6m-6 4h6m4.506-1.494L15.012 12m0 0 1.506-1.506M15.012 12l1.506 1.506M15.012 12l-1.506-1.506M20 19H4a1 1 0 0 1-1-1V6a1 1 0 0 1 1-1h16a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1Z"/>
                              </svg>                              
                        </button>
                    </div>
                    <div class="sm:col-span-12 lg:col-span-3 md:col-span-3">
                        <label for="fondo[]" class="block mb-2 text-sm font-medium text-gray-900">Color de Fondo</label>
                        <input type="text" name="fondo[]" class="form-input fondo w-full rounded-lg border-gray-300" required>
                    </div>
                    <div class="sm:col-span-12 lg:col-span-3 md:col-span-3">
                        <label for="fondo[]" class="block mb-2 text-sm font-medium text-gray-900">Color de Fondo</label>
                        <input type="text" name="fondo[]" class="form-input fondo w-full rounded-lg border-gray-300" required>
                    </div>
            
                    <div class="overflow-x-auto max-w-full">
                        <table class="table-auto w-full text-sm text-left text-gray-500">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-100">
                                <tr>
                                    <th class="px-4 py-2">Color</th>
                                    <th class="px-4 py-2">Tono</th>
                                    <th class="px-4 py-2">Código</th>
                                    <th class="px-4 py-2 text-center">
                                        Quitar
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="hilo-body">
                                <tr>
                                    <td class="px-4 py-2"><input type="text" name="color[]" class="form-input w-full"></td>
                                    <td class="px-4 py-2"><input type="text" name="tono[]" class="form-input w-full"></td>
                                    <td class="px-4 py-2"><input type="text" name="codigo[]" class="form-input w-full"></td>
                                    <td class="px-4 py-2 text-center">
                                        <button type="button" class="text-red-600 hover:text-red-800 remove-hilo">
                                            <svg class="w-6 h-6 text-red-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7.757 12h8.486M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                                            </svg>
                                        </button>
                                    </td>
                                </tr>
                                <!-- FILA ESPECIAL PARA AGREGAR NUEVOS HILOS -->
                                <tr>
                                    <td colspan="4" class="text-center py-2">
                                        <button type="button" class="text-green-600 hover:text-green-800 add-hilo">
                                            <svg class="w-6 h-6 text-green-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 7.757v8.486M7.757 12h8.486M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                                            </svg>                                              
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            
                <!-- Botón para agregar nuevo fondo -->
                <div class="col-span-full flex justify-center lg:col-span-1">
                    <button type="button" class="tr_clone_add text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5">
                        Agregar Nuevo Fondo
                    </button>
                </div>
            </div>
        </div>
    <!-- ##### FIN MODULO DE COLORES  #########   -->
    


    <div class="sm:col-span-12 lg:col-span-12 md:col-span-12">
        <label for="nota" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nota adicional</label>
        <textarea id="nota" name="nota" rows="2"
            class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" 
            placeholder="Nota adicional">{{ old('nota', $ponchado->nota) }}</textarea>
    </div>

    <div class="sm:col-span-12 lg:col-span-8 md:col-span-12">
        <label
            for="imagen_1"
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
                src="{{ $metodo == 'edit' && $ponchado->imagen_1 ? asset('' . $ponchado->imagen_1) : '#' }}"
                alt="" id="imgPreview1">
        </figure>
    </div>

    <div class="sm:col-span-12 lg:col-span-3 md:col-span-12">
        {{-- Botón para archivo adicional (ej. .emb, .zip, .pdf) --}}
        <label
            class="text-gray-900 bg-white border border-gray-300 focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-100 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-gray-800 dark:text-black dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:border-gray-600 dark:focus:ring-gray-700 cursor-pointer">
            <span>
                @if ($metodo == 'create')
                    Archivo del diseño
                @elseif($metodo == 'edit')
                    Actualizar archivo del diseño
                @endif
            </span>
            <input type="file" name="archivo" id="archivo" class="hidden" accept=".zip,.rar,.emb,.pdf">
        </label>
    
        {{-- Mostrar nombre del archivo seleccionado --}}
        <div id="archivoNombre" class="text-sm text-gray-700 mt-2">
            @if ($metodo == 'edit' && $ponchado->archivo)
                Archivo actual: <strong>{{ basename($ponchado->archivo) }}</strong>
            @endif
        </div>
    </div>

     <!-- Modal -->
     @include('ponchados._modal_ponchados')
     @include('clientes._modal_clientes')
    
    
    <div class="sm:col-span-12 lg:col-span-12 md:col-span-12 mt-3">
        <button type="submit"
            class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">
            @if ($metodo == 'create')
                CREAR PONCHADO
            @elseif($metodo == 'edit')
                EDITAR PONCHADO
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
            $('#clasificacion_ubicaciones_id').select2({
                placeholder: "-- UBICACIÓN --",
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

            // PARA LOS COLORES DE LOS HILOS
            // Agregar nuevo fondo
            $('.tr_clone_add').on('click', function () {
                const $container = $('#fondos_container');
                const $firstFondo = $container.find('.fondo-item').first();

                if ($firstFondo.length === 0) {
                    alert('No hay ningún fondo para clonar.');
                    return;
                }

                const $clone = $firstFondo.clone();

                // Limpiar inputs dentro del fondo clonado
                $clone.find('input').val('');

                // Solo dejar una fila de datos (sin la de agregar)
                const $tbody = $clone.find('tbody');
                $tbody.find('tr').not(':first, :last').remove(); // deja la primera (data) y la última (add)

                $container.find('.tr_clone_add').parent().before($clone);
            });



            // Agregar fila de hilo (dinámico)
            $(document).on('click', '.add-hilo', function () {
                const $tbody = $(this).closest('tbody');
                const $rows = $tbody.find('tr');
                const $dataRow = $rows.first().clone(); // primera fila con inputs

                $dataRow.find('input').val('');
                $dataRow.insertBefore($rows.last()); // insertar antes del botón "Agregar"
            });

            // Eliminar fila de hilo (mínimo una)
            $(document).on('click', '.remove-hilo', function () {
                const $row = $(this).closest('tr');
                const $tbody = $row.closest('tbody');
                const $dataRows = $tbody.find('tr').not(':last'); // todas menos el botón "Agregar"

                if ($dataRows.length > 1) {
                    $row.remove();
                }
            });

            // Evitar eliminar el primer fondo
            $(document).on('click', '.trash', function () {
                const $fondoCard = $(this).closest('.fondo-item');
                const $fondos = $('#fondos_container').find('.fondo-item');

                if ($fondoCard.is($fondos.first())) {
                    alert('No puedes eliminar el primer fondo.');
                    return;
                }

                $fondoCard.remove();
            });


            // selecciona el producto del datatable/ modal
            $('#ponchados tbody').on('click', 'tr', function(e) {
                e.preventDefault();
                e.stopPropagation();

                if (table) {
                    var data = table.row(this).data();

                    if (data) {
                        var nombre_ponchado = data['nombre'];
                        var idpPonchado = data['id'];
                        var puntadas = data['puntadas'];
                        var ancho = data['ancho'];
                        var largo = data['largo'];
                        var aro = data['aro'];


                        $('#nombre_ponchado').val(nombre_ponchado);
                        $('#ponchado_id').val(idpPonchado);
                        $('#puntadas').val(puntadas);
                        $('#ancho').val(ancho);
                        $('#largo').val(largo);
                        $('#aro').val(aro);

                        // Mostrar el modal (asegurarse de que esté visible)
                        if ($("#ponchado-modal").hasClass('hidden')) {
                            $("#ponchado-modal").removeClass('hidden');
                        }
                        $("#ponchado-modal").addClass('hidden');
                        $('.bg-gray-900\\/50, .dark\\:bg-gray-900\\/80').remove(); // Elimina el fondo oscuro del modal

                         // Simular un segundo clic después de mostrar el modal
                        setTimeout(function() {
                            // Forzar el clic en el botón de mostrar modal si es necesario
                            $('#btn-ponchado').trigger('click');
                        }, 100); // Ajusta el retraso según sea necesario

                    } else {
                        console.error("No se pudo obtener los datos de la fila.");
                    }
                } else {
                    console.error("La tabla no está inicializada correctamente.");
                } 
            });

            // MUESTRA EL MODAL DE LOS PRODUCTOS
            $('#btn-ponchado').click(async  function() {
                // Mostrar el modal primero si está oculto
                if ($("#ponchado-modal").hasClass('hidden')) {
                    $("#ponchado-modal").removeClass('hidden');
                }
                // Usa una función asíncrona para manejar la recarga o inicialización de DataTable
                await recargarOInicializarTabla();
            });

            // selecciona el cliente del datatable/ modal
            $('#clientes tbody').on('click', 'tr', function(e) {
                e.preventDefault();
                e.stopPropagation();

                if (table) {
                    var data = table.row(this).data();

                    if (data) {
 
                        var cliente_id = data['id'];
                        var nombre_cliente = data['full_name'];
                        var precio_puntada = data['precio_puntada'];


                        $('#cliente_id').val(cliente_id);
                        $('#nombre_cliente').val(nombre_cliente);
                        $('#precio_puntada').val(precio_puntada);

                        // Mostrar el modal (asegurarse de que esté visible)
                        if ($("#cliente-modal").hasClass('hidden')) {
                            $("#cliente-modal").removeClass('hidden');
                        }
                        $("#cliente-modal").addClass('hidden');
                        $('.bg-gray-900\\/50, .dark\\:bg-gray-900\\/80').remove(); // Elimina el fondo oscuro del modal

                         // Simular un segundo clic después de mostrar el modal
                        setTimeout(function() {
                            // Forzar el clic en el botón de mostrar modal si es necesario
                            $('#btn-cliente').trigger('click');
                        }, 100); // Ajusta el retraso según sea necesario

                    } else {
                        console.error("No se pudo obtener los datos de la fila.");
                    }
                } else {
                    console.error("La tabla no está inicializada correctamente.");
                } 
            });

            // MUESTRA EL MODAL DE LOS CLIENTES
            $('#btn-cliente').click(async  function() {
                // Mostrar el modal primero si está oculto
                if ($("#cliente-modal").hasClass('hidden')) {
                    $("#cliente-modal").removeClass('hidden');
                }
                // Usa una función asíncrona para manejar la recarga o inicialización de DataTable
                await recargarTablaCliente();
            });


            // CAMBIA LOS VALORES SI SE ACTOIVA EL CAMPO SIN IVA
            $(document).on('click', '#iva', function() {
                suma();
            });


            // VALIDACION DEL CAMPO FILA (IMAGEN 1)
            $('form').on('submit', function(e) {
                //e.preventDefault();
                var fileInput = $('#imagen_1');
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

            // Función para recargar o inicializar la tabla PONCHADOS
            async function recargarOInicializarTabla() {
                if ($.fn.DataTable.isDataTable('#ponchados')) {
                    // Recargar los datos sin redibujar la tabla
                    await table.ajax.reload(null, false);
                    table.ajax.reload(null, false);
                } else {
                    // Inicializar la tabla si aún no está inicializada
                    await ponchados();
                }
            }
 
            // OBTEMGO LOS PONCHADOS POR AJAX
            async function ponchados() {
                const postData = {
                    _token: $('input[name=_token]').val(),
                    origen: 'ponchados.pedidos',
                };

                if ($.fn.DataTable.isDataTable('#ponchados')) {
                    $('#ponchados').DataTable().clear().destroy();
                }

                // Inicializar DataTable
                table = $('#ponchados').DataTable({
                    "language": {
                        "url": "{{ asset('/json/i18n/es_es.json') }}"
                    },
                    responsive: true,
                    retrieve: true,
                    processing: true,
                    ajax: {
                        url: "{{ route('ponchados.index.ajax') }}",
                        type: "POST",
                        'data': function(d) {
                            d._token = postData._token;
                            d.origen = postData.origen;
                        }
                    },
                    'columns': [{
                            data: 'id',
                            name: 'id',
                            visible: false,
                            searchable: false
                        },
                        {
                            data: 'img_thumb',
                            render: function(data, type, row) {
                                return '<img class="h-auto max-w-20 sm:max-w-20 md:max-w-40 lg:max-w-40 object-cover object-center" src="' +
                                    data + '" alt="">';
                            }
                        },
                        {
                            data: 'nombre',
                            name: 'nombre'
                        },
                        {
                            data: 'puntadas',
                            name: 'puntadas',
                            defaultContent: '0'
                        },
                        {
                            data: 'ancho',
                            name: 'ancho',
                            defaultContent: '0',
                        },
                        {
                            data: 'largo',
                            name: 'largo',
                            defaultContent: '0',
                        },
                        {
                            data: 'aro',
                            name: 'aro',
                            defaultContent: '0',
                        }
                    ],
                });
            }

            // Función para recargar o inicializar la tabla PONCHADOS
            async function recargarTablaCliente() {
                if ($.fn.DataTable.isDataTable('#clientes')) {
                    // Recargar los datos sin redibujar la tabla
                    await table.ajax.reload(null, false);
                    table.ajax.reload(null, false);
                } else {
                    // Inicializar la tabla si aún no está inicializada
                    await clientes();
                }
            }
 
            // OBTEMGO LOS CLIENTES POR AJAX
            async function clientes() {
                const postData = {
                    _token: $('input[name=_token]').val(),
                    origen: 'clientes.pedidos',
                };

                if ($.fn.DataTable.isDataTable('#clientes')) {
                    $('#clientes').DataTable().clear().destroy();
                }

                // Inicializar DataTable
                table = $('#clientes').DataTable({
                    "language": {
                        "url": "{{ asset('/json/i18n/es_es.json') }}"
                    },
                    responsive: true,
                    retrieve: true,
                    processing: true,
                    ajax: {
                        url: "{{ route('clientes.index.ajax') }}",
                        type: "POST",
                        'data': function(d) {
                            d._token = postData._token;
                            d.origen = postData.origen;
                        }
                    },
                    'columns': [{
                            data: 'id',
                            name: 'id',
                            visible: false,
                            searchable: false
                        },
                        {
                            data: 'full_name',
                            name: 'full_name'
                        },
                        {
                            data: 'precio_puntada',
                            name: 'precio_puntada',
                            defaultContent: '0'
                        }
                    ],
                });
            }


        });
    </script>
@stop
