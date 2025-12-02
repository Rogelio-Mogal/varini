<x-validation-errors class="mb-4" />

<div class="grid grid-cols-12 gap-4">
    <!-- Panel principal (Ponchados) -->
    <div class="col-span-12 lg:col-span-9 space-y-2">
        <h3 class="font-bold text-purple-600 border-b pb-1 mb-3">Pedidos</h3>
        <div class="grid lg:grid-cols-12 md:grid-cols-12 sm:grid-cols-12 gap-2">

            <div class="sm:col-span-12 lg:col-span-2 md:col-span-2">
                <label for="btn-cliente" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                    Busqueda
                </label>
                <button data-modal-target="cliente-modal" data-modal-toggle="cliente-modal" id="btn-cliente"
                    class="block w-full text-white bg-purple-700 hover:bg-purple-800 focus:ring-4 focus:outline-none focus:ring-purple-300 font-medium rounded-lg text-sm  py-2.5 text-center dark:bg-purple-600 dark:hover:bg-purple-700 dark:focus:ring-purple-800"
                    type="button">
                    Cliente
                </button>
            </div>
            <input type="hidden" id="cliente_id" name="cliente_id"
                value="{{ old('cliente_id', $pedidoBase->cliente_id ?? 1) }}">

            <input type="hidden" id="precio_puntada" name="precio_puntada"
                value="{{ old('precio_puntada', $pedidoBase->precio_puntada) }}">
            <div class="sm:col-span-12 lg:col-span-8 md:col-span-8">
                <label for="nombre_cliente"
                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Cliente</label>
                <input type="text" id="nombre_cliente" name="nombre_cliente" required
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                    placeholder="Cliente"
                    value="{{ old('nombre_cliente', $pedidoBase->cliente?->full_name ?? 'CLIENTE PÚBLICO') }}"
                    readonly />
            </div>

            <div class="sm:col-span-12 lg:col-span-2 md:col-span-3 flex items-center mt-2">
                <input 
                    id="urgente" 
                    name="urgente" 
                    type="checkbox" 
                    value="2"
                    class="w-4 h-4 text-red-600 bg-gray-100 border-gray-300 rounded focus:ring-red-500 dark:focus:ring-red-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600"
                    {{ isset($pedidoBase) && $pedidoBase->activo == 2 ? 'checked' : '' }}
                >
                <label for="urgente" class="ml-2 text-sm font-medium text-red-600 dark:text-red-400">
                    🚨 Marcar como urgente
                </label>
            </div>


            <div class="sm:col-span-12 lg:col-span-6 md:col-span-6">
                <label for="referencia_cliente"
                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Folio</label>
                <input type="text" id="referencia_cliente" name="referencia_cliente" required
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                    placeholder="Folio"
                    value="{{ old('referencia_cliente', $pedidoBase->referencia_cliente ?? $folioGenerado) }}"
                    readonly />
            </div>
            <div class="sm:col-span-12 lg:col-span-4 md:col-span-4">
                <label for="fecha_estimada_entrega"
                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Fecha
                    estimada
                    de entrega</label>
                <input type="date" id="fecha_estimada_entrega" name="fecha_estimada_entrega" required
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                    value="{{ old('fecha_estimada_entrega', $pedidoBase->fecha_estimada_entrega) }}" />
            </div>
            <div class="sm:col-span-12 lg:col-span-2 md:col-span-2">
                <label for="btn-ponchado" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                    Busqueda
                </label>
                <button data-modal-target="ponchado-modal" data-modal-toggle="ponchado-modal"
                    data-target-table="item_table_0" id="btn-ponchado"
                    class="btn-ponchado block w-full text-white bg-purple-700 hover:bg-purple-800 focus:ring-4 focus:outline-none focus:ring-purple-300 font-medium rounded-lg text-sm  py-2.5 text-center dark:bg-purple-600 dark:hover:bg-purple-700 dark:focus:ring-purple-800"
                    type="button">
                    Ponchados
                </button>
            </div>

            <!-- ##### MODULO DE PONCHADOS  #########   -->

            <div class="sm:col-span-12 lg:col-span-12 md:col-span-12">
                <div class="col-span-12 grid grid-cols-1 md:grid-cols-1 lg:grid-cols-1 gap-6">
                    <div
                        class="bg-white shadow-md rounded-xl p-3 border border-gray-200 fondo-item grid lg:grid-cols-12 md:grid-cols-12 sm:grid-cols-12 gap-2">
                        <div class="sm:col-span-12 lg:col-span-12 md:col-span-12">
                            <div class="relative overflow-x-auto shadow-md sm:rounded-lg max-h-[400px] overflow-y-auto">
                                <table id="item_table_0"
                                    class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                                    <thead
                                        class="text-xs text-blue-700 uppercase bg-blue-50 dark:bg-blue-700 dark:text-blue-400">
                                        <tr>
                                            <th scope="col" class="px-6 py-3">Cant.</th>
                                            <th scope="col" class="px-6 py-3">Producto</th>
                                            <th scope="col" class="px-6 py-3">Prenda</th>
                                            <th scope="col" class="px-6 py-3">Ubicación</th>
                                            <th scope="col" class="px-6 py-3">P.U.</th>
                                            <th scope="col" class="px-6 py-3">Importe</th>
                                            <th scope="col" class="px-6 py-3">Opciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if ($ponchado)
                                            @foreach ($ponchado as $i => $detalle)
                                                <tr data-idponchadoServicio="{{ $detalle->ponchado_id }}"
                                                    data-idPrecio="{{ $detalle->id }}">
                                                    {{-- Cantidad --}}
                                                    <td>
                                                        <input type="number"
                                                            name="detalles[{{ $i }}][cantidad]"
                                                            value="{{ $detalle->cantidad_piezas }}" min="1"
                                                            class="cantVenta w-16 text-center border rounded" />
                                                    </td>

                                                    {{-- Producto --}}
                                                    <td>
                                                        {{ $detalle->ponchado->nombre ?? '—' }}
                                                        <input type="hidden"
                                                            name="detalles[{{ $i }}][ponchado_id]"
                                                            value="{{ $detalle->ponchado_id }}">
                                                        <input type="hidden"
                                                            name="detalles[{{ $i }}][tipo_item]"
                                                            value="PONCHADO">
                                                    </td>

                                                    {{-- Prenda --}}
                                                    <td>
                                                        <input type="text"
                                                            name="detalles[{{ $i }}][prenda]"
                                                            value="{{ $detalle->prenda }}"
                                                            class="border rounded w-full p-2.5">
                                                    </td>

                                                    {{-- Ubicación --}}
                                                    <td>
                                                        <select
                                                            name="detalles[{{ $i }}][clasificacion_ubicaciones_id]"
                                                            class="select2 w-full p-2.5 border rounded-md">
                                                            <option value="">-- UBICACIÓN --</option>
                                                            @foreach ($marcaValues as $ubicacion)
                                                                <option value="{{ $ubicacion->id }}"
                                                                    {{ $detalle->clasificacion_ubicaciones_id == $ubicacion->id ? 'selected' : '' }}>
                                                                    {{ ucfirst($ubicacion->nombre) }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </td>

                                                    {{-- Precio Unitario --}}
                                                    <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white pu"
                                                        data-precio="{{ $detalle->precio_unitario }}">
                                                        ${{ number_format($detalle->precio_unitario, 2) }}
                                                        <input type="hidden"
                                                            name="detalles[{{ $i }}][precio]"
                                                            value="{{ $detalle->precio_unitario }}">
                                                    </td>

                                                    {{-- Importe --}}
                                                    <td
                                                        class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white importe">
                                                        ${{ number_format($detalle->subtotal, 2) }}
                                                        <input type="hidden"
                                                            name="detalles[{{ $i }}][total]"
                                                            class="total_pp" value="{{ $detalle->subtotal }}">
                                                    </td>

                                                    {{-- Opciones --}}
                                                    <td class="space-x-2">

                                                        {{-- Botón eliminar --}}
                                                        <button type="button"
                                                            class="remove text-red-600 hover:underline">
                                                            Eliminar
                                                        </button>

                                                        {{-- SI el ponchado es el ID 1, botón para abrir modal --}}
                                                        @if ($detalle->ponchado_id == 1)
                                                            <button type="button"
                                                                class="btn-reemplazar-ponchado text-blue-600 hover:underline"
                                                                data-row-index="{{ $i }}"
                                                                data-target-table="item_table_0"
                                                                data-modal-target="ponchado-modal"
                                                                data-modal-toggle="ponchado-modal">
                                                                Ponchado
                                                            </button>
                                                        @endif

                                                    </td>

                                                </tr>
                                            @endforeach
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            <!-- ##### FIN MODULO DE PONCHADOS  #########   -->

            <div class="sm:col-span-12 lg:col-span-12 md:col-span-12">
                <label for="nota" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nota
                    adicional</label>
                <textarea id="nota" name="nota" rows="2"
                    class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                    placeholder="Nota adicional">{{ old('nota', $pedidoBase->nota) }}</textarea>
            </div>

            <!-- Modal -->
            @include('ponchados_pedidos._modal_precios')
            @include('clientes._modal_clientes')
        </div>
    </div>

    @php
        // Inicializamos los totales por método
        $formasPagoTotales = [
            'Efectivo' => 0,
            'TDD' => 0,
            'TDC' => 0,
            'Transferencia' => 0,
        ];

        // Recorremos todos los pedidos activos y sus formas de pago
        foreach ($ponchado as $pedido) {
            foreach ($pedido->formasPago->where('activo', 1) as $fp) {
                if (isset($formasPagoTotales[$fp->metodo])) {
                    $formasPagoTotales[$fp->metodo] += $fp->monto;
                }
            }
        }

        // Convertimos a array para la vista, opcionalmente con id/referencia
        $formasPago = [];
        foreach ($formasPagoTotales as $metodo => $monto) {
            $formasPago[] = [
                'metodo' => $metodo,
                'monto' => $monto,
                'referencia' => '', // si quieres manejar referencia, puedes sumar o tomar la primera
            ];
        }
    @endphp
    <!-- Panel lateral (formas de pago) -->
    <div class="col-span-12 lg:col-span-3 space-y-2">
        <!-- Paneles de forma de pago y total -->
        <div class="bg-white rounded-xl shadow p-4 space-y-6">

            <!-- Formas de pago -->
            <div>
                <h3 class="font-bold text-green-600 border-b pb-1 mb-3">Forma de pago</h3>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block mb-1 text-sm font-medium text-gray-700">Efectivo</label>
                        <input type="hidden" name="formas_pago[0][metodo]" value="Efectivo">
                        <input type="number" name="formas_pago[0][monto]" id="efectivo" step="any"
                            value="{{ collect($formasPago)->firstWhere('metodo', 'Efectivo')['monto'] ?? '' }}"
                            class="forma-pago w-full border border-gray-300 rounded px-2 py-1 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                            @if ($metodo == 'edit') readonly @endif>
                    </div>

                    <div>
                        <label>T. Débito</label>
                        <input type="hidden" name="formas_pago[1][metodo]" value="TDD">
                        <input type="number" name="formas_pago[1][monto]" id="debito" step="any"
                            value="{{ collect($formasPago)->firstWhere('metodo', 'TDD')['monto'] ?? '' }}"
                            class="forma-pago w-full border border-gray-300 rounded px-2 py-1 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                            @if ($metodo == 'edit') readonly @endif>
                    </div>

                    <div>
                        <label>T. Crédito</label>
                        <input type="hidden" name="formas_pago[2][metodo]" value="TDC">
                        <input type="number" name="formas_pago[2][monto]" id="credito" step="any"
                            value="{{ collect($formasPago)->firstWhere('metodo', 'TDC')['monto'] ?? '' }}"
                            class="forma-pago w-full border border-gray-300 rounded px-2 py-1 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                            @if ($metodo == 'edit') readonly @endif>
                    </div>

                    <div>
                        <label>Transferencia</label>
                        <input type="hidden" name="formas_pago[3][metodo]" value="Transferencia">
                        <input type="number" name="formas_pago[3][monto]" id="transferencia" step="any"
                            value="{{ collect($formasPago)->firstWhere('metodo', 'Transferencia')['monto'] ?? '' }}"
                            class="forma-pago w-full border border-gray-300 rounded px-2 py-1 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                            @if ($metodo == 'edit') readonly @endif>
                    </div>

                    <div id="monto_credito_container" class="hidden sm:col-span-12 lg:col-span-2">
                        <label class="block mb-1 text-sm font-medium text-gray-900 dark:text-white">Monto a
                            crédito</label>
                        <input type="number" name="monto_credito" id="monto_credito" step="any"
                            class="forma-pago bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5"
                            min="0" value="{{ $pedidoBase->monto_credito ?? '' }}"
                            @if ($metodo == 'edit') readonly @endif>
                    </div>
                </div>
            </div>

            <!-- Totales -->
            <div>
                <h3 class="font-bold text-blue-600 border-b pb-1 mb-3">Total</h3>
                <div class="space-y-3">
                    <div>
                        <label for="total" class="block mb-1 text-sm font-medium text-gray-700">Total</label>
                        <span id="total_mostrado" class="font-bold text-xl text-black-500 dark:text-black-400">$
                            0.0</span>
                        <input type="hidden" id="total_venta" name="total_venta">
                    </div>
                    <div class="sm:col-span-12 lg:col-span-3">
                        <label class="block text-xl font-medium text-gray-900">Faltante</label>
                        <span id="faltante_texto" class="text-red-600 font-bold">$0.00</span>
                        <input type="hidden" id="total_faltante" name="total_faltante">
                    </div>
                    <div class="sm:col-span-12 lg:col-span-3">
                        <label class="block text-xl font-medium text-gray-900">Cambio</label>
                        <span id="cambio_texto" class="text-green-600 font-bold">$0.00</span>
                        <input type="hidden" id="total_cambio" name="total_cambio">
                    </div>

                    <button
                        class="mt-4 w-full bg-orange-500 hover:bg-orange-600 text-white font-semibold py-2 rounded">
                        @if ($metodo2 == 'create')
                            Crear pedido
                        @elseif($metodo2 == 'edit')
                            Editar pedido
                        @endif
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

@section('js')
    <script>
        const ubicaciones = @json($marcaValues);

        const detalles = @json($detalle);

        // GENERO LA TABLA DINAMICA, HUBO ERRORES
        document.addEventListener('DOMContentLoaded', function() {
            let oldValues = @json(old());

            if (oldValues.fondos && Array.isArray(oldValues.fondos)) {
                const container = document.querySelector('#fondos_container');
                const original = container.querySelector('.fondo-item');
                const addBtn = container.querySelector('.tr_clone_add');

                // Limpiar contenedor y volver a insertar botón
                container.innerHTML = '';

                oldValues.fondos.forEach(function(bloque, index) {
                    let fondoTela = bloque.fondo_tela || '';
                    let colores = bloque.color || [];
                    let codigos = bloque.codigo || [];

                    // Clonar y preparar el bloque
                    let clone = original.cloneNode(true);
                    clone.querySelector('#fondo_tela').value = fondoTela;
                    clone.querySelector('#fondo_tela').setAttribute('name', `fondos[${index}][fondo_tela]`);
                    clone.querySelector('#fondo_tela').setAttribute('id', `fondos_${index}_fondo_tela`);

                    // Botón de búsqueda
                    const btnProduct = clone.querySelector('.btn-product');
                    btnProduct.setAttribute('data-index', index);
                    btnProduct.setAttribute('data-target-table', `item_table_${index}`);

                    // Tabla
                    const table = clone.querySelector('table');
                    table.setAttribute('id', `item_table_${index}`);
                    const tbody = table.querySelector('tbody');
                    tbody.innerHTML = '';

                    for (let i = 0; i < Math.max(colores.length, codigos.length); i++) {
                        let color = colores[i] || '';
                        let codigo = codigos[i] || '';

                        const row = document.createElement('tr');
                        row.innerHTML = `
                            <td class="px-6 py-3">
                                <input type="text" name="fondos[${index}][color][]" value="${color}"
                                    class="bg-white border border-gray-300 text-sm rounded-lg w-full p-2.5">
                            </td>
                            <td class="px-6 py-3">
                                <input type="text" name="fondos[${index}][codigo][]" value="${codigo}"
                                    class="bg-white border border-gray-300 text-sm rounded-lg w-full p-2.5">
                            </td>
                            <td class="px-6 py-3 text-center">
                                <button type="button" name="remove" class="remove remove-tr focus:outline-none text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm p-2.5 me-2 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-900">
                                    <svg class="w-6 h-6 text-white dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m15 9-6 6m0-6 6 6m6-3a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                                    </svg> 
                                    <span class="sr-only">Quitar</span>
                                </button>
                            </td>
                        `;
                        tbody.appendChild(row);
                    }

                    container.appendChild(clone);
                });

                // Agregar nuevamente el botón "Agregar Nuevo Fondo"
                container.appendChild(addBtn);
            }
        });

        // CLONA LAS FORMAS DE PAGO
        inicializarSelect2();
        //let indexFormaPago = 1;
        let indexFormaPago = {{ count($formasPago) - 1 }};

        function agregarFormaPago() {
            const container = document.getElementById('formasPagoContainer');

            //  Verifica que no haya más de 5 formas ya en el DOM
            const numActual = container.querySelectorAll('.formas-pago-group').length;
            if (numActual >= 5) {
                alert("Solo puedes agregar hasta 5 formas de pago.");
                return;
            }

            if (numActual + 1 >= 5) {
                document.querySelector('[data-btn-clone]').disabled = true;
            }

            const template = document.getElementById('tplFormaPago');
            const clone = document.importNode(template.content, true); // Clona el <template>
            indexFormaPago++;

            // Reemplaza __index__ por el nuevo índice
            clone.querySelectorAll('[name], [id]').forEach(el => {
                if (el.name) el.name = el.name.replace(/__index__/g, indexFormaPago);
                if (el.id) el.id = el.id.replace(/__index__/g, indexFormaPago);
            });

            // Añadir al DOM
            container.appendChild(clone);

            // Inicializa select2 SOLO en el nuevo select
            const newSelect = container.querySelector(`.formas-pago-group:last-child select.select2`);
            $(newSelect).select2({
                placeholder: "-- Seleccione --",
                allowClear: false,
                width: '100%'
            }).on('change', actualizarOpcionesFormasPago);

            actualizarOpcionesFormasPago();
        }

        // Actualiza las opciones de forma de pago
        function actualizarOpcionesFormasPago() {
            const selects = document.querySelectorAll('.formas-pago-group select[name^="formas_pago"]');
            const seleccionadas = Array.from(selects)
                .map(sel => sel.value)
                .filter(val => val !== '');

            selects.forEach(select => {
                const valorActual = select.value;

                select.querySelectorAll('option').forEach(opt => {
                    opt.disabled = seleccionadas.includes(opt.value) && opt.value !== valorActual;
                });
                $(select).trigger('change.select2');
            });
        }

        // Elimina el bloque de forma de pago
        function eliminarFormaPago(btn) {
            const group = btn.closest('.formas-pago-group');
            group.remove();

            // Llamar para actualizar las opciones disponibles tras la eliminación
            actualizarOpcionesFormasPago();

            // Reactivar el botón si hay menos de 5
            const btnAdd = document.querySelector('[data-btn-clone]');
            if (btnAdd) btnAdd.disabled = false;
        }

        // INICIALILZA EL SELECT FORMA DE PAGO
        function inicializarSelect2() {
            $('.select2').select2({
                placeholder: "-- Seleccione --",
                allowClear: false,
                width: '100%' // Asegura que se mantenga el ancho
            }).on('change', function() {
                actualizarOpcionesFormasPago();
            });

            // Llamar inmediatamente para reflejar el estado actual
            actualizarOpcionesFormasPago();
        }

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

            let tableS = null; //de ponchados

            recalcularTotalTabla('item_table_0');

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
            $('#formas_pago_metodo').select2({
                placeholder: "-- UBICACIÓN --",
                allowClear: false
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

            // APERTURA MODAL PONCHADOS, ASIGNA PONCHADO POR DEFINIR
            $(document).on('click', '.btn-reemplazar-ponchado', async function () {

                const $btn = $(this);
                $('.ponchado-modal').data('botonActivo', $btn);

                // Obtener la fila y los datos actuales
                let rowIndex = $btn.data('row-index');
                let $fila = $(`#item_table_0 tbody tr`).eq(rowIndex);

                let idponchadoActual = $fila.attr('data-idponchadoServicio');

                // Abrir modal
                $("#ponchado-modal").removeClass('hidden');

                // Asegurar que la tabla esté cargada
                await recargarOInicializarTabla();

                // Buscar el ponchado actual dentro del DataTable
                let row = tableS.rows().indexes().filter(function (idx) {
                    return tableS.row(idx).data().ponchado_id == idponchadoActual;
                });

                if (row.length) {
                    let data = tableS.row(row[0]).data();

                    // Llenar los campos del modal
                    $('#nombre_ponchado').val(data['nombre']);
                    $('#ponchado_id').val(data['ponchado_id']);
                    $('#puntadas').val(data['puntadas']);
                    $('#ancho').val(data['ancho']);
                    $('#largo').val(data['largo']);
                    $('#aro').val(data['aro']);
                }
            });

            // selecciona el ponchados del datatable/ modal
            let index = 0;
            $('#ponchados tbody').on('click', 'tr', function(e) {
                e.preventDefault();
                e.stopPropagation();

                if (tableS) {
                    var data = tableS.row(this).data();

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
                        $('.bg-gray-900\\/50, .dark\\:bg-gray-900\\/80')
                            .remove(); // Elimina el fondo oscuro del modal

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

            // MUESTRA EL MODAL DE LOS PONCHADOS
            $('#btn-ponchado').click(async function() {
                const $btn = $(this);
                $('.ponchado-modal').data('botonActivo', $btn);
                $("#ponchado-modal").removeClass('hidden');
                await recargarOInicializarTabla();
            });

            // selecciona el ponchado-pedido del datatable/ modal
            $('#ponchados tbody').on('click', 'tr', function(e) {
                e.preventDefault();
                e.stopPropagation();

                if (!tableS) {
                    console.error("La tabla no está inicializada correctamente.");
                    return;
                }

                var data = tableS.row(this).data();
                if (!data) {
                    console.error("No se pudo obtener los datos de la fila.");
                    return;
                }

                var idponchado = data['ponchado_id'];
                var idprecio = data['id'];
                var nombre = data['nombre'];
                var precio_publico = data['precio'];
                var nuevaCant = 1;

                // Obtener botón que abrió el modal
                const $botonActivoS = $('.ponchado-modal').data('botonActivo');
                const currentTargetTable = $botonActivoS?.attr('data-target-table');
                if (!currentTargetTable) {
                    alert("No se pudo determinar la tabla destino.");
                    return;
                }

                const $targetTable = $(`#${currentTargetTable}`);
                if ($targetTable.length === 0) {
                    alert("No se encontró la tabla destino.");
                    return;
                }

                // -------------------------------------------------------------
                // 🔥 AQUI INTEGRAS TU BLOQUE DE "REEMPLAZAR PONCHADO"
                // -------------------------------------------------------------
                if ($botonActivoS.hasClass('btn-reemplazar-ponchado')) {

                    let rowIndex = $botonActivoS.data('row-index');
                    let $fila = $(`#item_table_0 tbody tr`).eq(rowIndex);

                    // REEMPLAZAR idponchado y precio
                    $fila.attr('data-idponchadoServicio', idponchado);
                    $fila.attr('data-idPrecio', idprecio);

                    // --- actualizar nombre ---
                    $fila.find('td').eq(1).html(`
                        ${nombre}
                        <input type="hidden" name="detalles[${rowIndex}][ponchado_id]" value="${idponchado}">
                        <input type="hidden" name="detalles[${rowIndex}][tipo_item]" value="PONCHADO">
                    `);

                    // --- actualizar precio unitario ---
                    console.log('.pu: '+precio_publico);
                    //$fila.find('.pu').attr('data-precio', precio_publico);
                    //$fila.find('.pu').contents().filter(function () { return this.nodeType === 3; })
                    //    .first().replaceWith(
                    //        precio_publico.toLocaleString('es-MX', { style:'currency', currency:'MXN' })
                    //    );
                    //$fila.find('input[name$="[precio]"]').val(precio_publico);

                    // cantidad actual
                    //let cantidad = parseInt($fila.find('.cantVenta').val()) || 1;

                    // --- ACTUALIZAR PRECIO UNITARIO (BLOQUE NUEVO) ---
                    const $tdPU = $fila.find('td.pu');

                    $tdPU.attr('data-precio', precio_publico);

                    // Actualiza SOLO el texto
                    $tdPU.contents().filter(function() {
                        return this.nodeType === 3;
                    }).first().replaceWith(
                        precio_publico.toLocaleString('es-MX', { style:'currency', currency:'MXN' })
                    );

                    // Actualiza input hidden
                    $tdPU.find('input').val(precio_publico);

                    // cantidad actual
                    let cantidad = parseInt($fila.find('.cantVenta').val()) || 1;

                    // ================================
                    // 🔥 REPLICAR LÓGICA DEL AGREGADO:
                    // si el ponchado ya existe en otra fila → usar esa cantidad
                    // ================================
                    let $rowDuplicada = $(`#item_table_0 tbody tr[data-idPrecio="${idprecio}"]`)
                                        .not($fila);

                    if ($rowDuplicada.length > 0 && idponchado != 1) {
                        cantidad = parseInt($rowDuplicada.find('.cantVenta').val()) || 1;
                        $fila.find('.cantVenta').val(cantidad);
                    }

                    // --- recalcular total ---
                    let total = cantidad * precio_publico;

                    //$fila.find('.importe').contents().filter(function () {
                    //    return this.nodeType === 3;
                    //}).first().replaceWith(
                    //    total.toLocaleString('es-MX', { style:'currency', currency:'MXN' })
                    //);
                    //$fila.find('input[name$="[total]"]').val(total);

                    const $tdImp = $fila.find('td.importe');

                    $tdImp.contents().filter(function() {
                        return this.nodeType === 3;
                    }).first().replaceWith(
                        total.toLocaleString('es-MX', { style:'currency', currency:'MXN' })
                    );

                    // actualizar input hidden
                    $tdImp.find('input').val(total);

                    // cerrar modal + recalcular totales
                    cerrarModalPonchados();
                    recalcularTotalTabla('item_table_0');
                    return;
                }

                // -------------------------------------------------------------
                // 🔥 FIN DEL BLOQUE DE REEMPLAZO
                // -------------------------------------------------------------



                // Buscar si ya existe el ponchado en la tabla por data-idponchado
                //var $existingRow = $targetTable.find(`tbody tr[data-idponchadoServicio="${idponchado}"]`);
                var $existingRow = $targetTable.find(`tbody tr[data-idPrecio="${idprecio}"]`);
                //if ($existingRow.length > 0) { //NO Permitir duplicados si el ponchado es ID 1

                // Permitir duplicados si el ponchado es ID 1
                if (idponchado != 1 && $existingRow.length > 0) {
                    // Si existe, aumentar cantidad y recalcular importe
                    var $inputCant = $existingRow.find('.cantVenta');
                    var cantActual = parseInt($inputCant.val()) || 1;
                    //var nuevaCant = cantActual + 1;
                    $inputCant.val(nuevaCant);

                    const importe = (nuevaCant * precio_publico).toLocaleString('es-MX', {
                        style: 'currency',
                        currency: 'MXN'
                    });

                    //$existingRow.find('.importe').text(importe);

                    // Actualizar solo el texto visible del importe, sin borrar el input hidden
                    $existingRow.find('.importe').contents().filter(function() {
                        return this.nodeType === 3; // nodo de texto
                    }).first().replaceWith(importe);

                    // Actualizar el input hidden con el nuevo valor total
                    const totalOculto = nuevaCant;
                    $existingRow.find('input.total_pp').val(totalOculto.toFixed(2));

                } else {
                    // Si no existe, agregar fila nueva con cant=1
                    var html = '';

                    html +=
                        `<tr data-idponchadoServicio="${idponchado}" data-idPrecio="${idprecio}"  class="odd:bg-white odd:dark:bg-gray-500 even:bg-gray-50 even:dark:bg-gray-400 border-b border-gray-100 dark:border-gray-400">`;

                    // Cantidad
                    html += `<td>
                              <input type="number" name="detalles[${index}][cantidad]" min="1" value="${nuevaCant}" class="cantVenta w-16 text-center border rounded" />
                            </td>`;

                    // Producto (nombre mostrado)
                    html += `<td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                        ${nombre}
                        <input type="hidden" name="detalles[${index}][ponchado_id]" value="${idponchado}" />
                        <input type="hidden" name="detalles[${index}][tipo_item]" value="PONCHADO" />
                    </td>`;

                    // Prenda
                    html += `<td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                        <input type="text" name="detalles[${index}][prenda]"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                        placeholder="Prenda" />
                    </td>`;

                    // Ubicación con select2
                    html += `<td>
                        <select name="detalles[${index}][clasificacion_ubicaciones_id]" class="select2 block w-full p-2.5 border rounded-md">
                            <option value="">-- UBICACIÓN --</option>`;

                    ubicaciones.forEach(function(ubicacion) {
                        html +=
                            `<option value="${ubicacion.id}">${ubicacion.nombre.charAt(0).toUpperCase() + ubicacion.nombre.slice(1)}</option>`;
                    });

                    html += `</select></td>`;

                    // Precio Unitario
                    html += `<td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white pu" data-precio="${precio_publico}">
                        ${precio_publico.toLocaleString('es-MX', { style: 'currency', currency: 'MXN' })}
                        <input type="hidden" name="detalles[${index}][precio]" value="${precio_publico}" />
                    </td>`;

                    // Importe
                    const total = precio_publico;
                    html += `<td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white importe">
                        ${total.toLocaleString('es-MX', { style: 'currency', currency: 'MXN' })}
                        <input type="hidden" name="detalles[${index}][total]" value="${total}" />
                    </td>`;

                    // Botón eliminar
                    html += `<td class="px-6 py-4">
                        <button type="button" class="remove font-medium text-red-600 dark:text-red-400 hover:underline">Eliminar</button>
                    </td>`;

                    html += `</tr>`;

                    $targetTable.find('tbody').append(html);

                    // Re-inicializa select2 para los nuevos elementos
                    $targetTable.find('.select2').select2();

                    // INCREMENTAR el índice después de usarlo
                    index++;
                }

                // Ocultar modal y limpiar overlays
                cerrarModalPonchados();
                //$(".ponchado-modal").addClass('hidden');
                //$('.bg-gray-900\\/50, .dark\\:bg-gray-900\\/80').remove();

                // Recalcular el total de la tabla completa
                recalcularTotalTabla('item_table_0');
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
                        var cantidad_pieza = data['precio_puntada'];


                        $('#cliente_id').val(cliente_id);
                        $('#nombre_cliente').val(nombre_cliente);
                        //$('#precio_unitario').val(precio_puntada);
                        $('#precio_puntada').val(precio_puntada).attr('min', precio_puntada);

                        // Mostrar el modal (asegurarse de que esté visible)
                        if ($("#cliente-modal").hasClass('hidden')) {
                            $("#cliente-modal").removeClass('hidden');
                        }
                        $("#cliente-modal").addClass('hidden');
                        $('.bg-gray-900\\/50, .dark\\:bg-gray-900\\/80')
                            .remove(); // Elimina el fondo oscuro del modal

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
                recalcularTotalTabla('item_table_0');
            });

            // RECALCULA EL TOTAL POR CAMBIO DE PIEZAS
            // Detectar cambios en la cantidad
            $(document).on('input', '.cantVenta', function() {
                const $input = $(this);
                const $row = $input.closest('tr');

                const cantidad = parseFloat($input.val()) || 0;
                const precio = parseFloat($row.find('.pu').data('precio')) || 0;

                const nuevoImporte = cantidad * precio;

                // Actualizar solo el nodo de texto de la celda .importe (sin borrar el input hidden)
                $row.find('.importe').contents().filter(function() {
                    return this.nodeType === 3; // nodo de texto
                }).first().replaceWith(nuevoImporte.toLocaleString('es-MX', {
                    style: 'currency',
                    currency: 'MXN'
                }));

                // Actualizar el valor del input hidden con class="total_pp"
                $row.find('input.total_pp').val(nuevoImporte.toFixed(2));

                // Recalcular el total de la tabla completa
                recalcularTotalTabla('item_table_0');
            });

            // Esto lo colocas solo una vez al inicio
            $(document).on('click', '.remove', function() {
                $(this).closest('tr').remove();
                recalcularTotalTabla('item_table_0');
            });

            // cambio de forma de pago 
            $(document).on('input', '.forma-pago', function() {
                recalcularFaltanteCambio();
            });

            // MUESTRA EL MODAL DE LOS CLIENTES
            $('#btn-cliente').click(async function() {
                // Mostrar el modal primero si está oculto
                if ($("#cliente-modal").hasClass('hidden')) {
                    $("#cliente-modal").removeClass('hidden');
                }
                // Usa una función asíncrona para manejar la recarga o inicialización de DataTable
                await recargarTablaCliente();
            });


            // Evitar el envío del formulario al presionar Enter, excepto en textarea
            $(document).on('keypress', function(e) {
                if (e.which == 13 && e.target.tagName !== 'TEXTAREA') {
                    e.preventDefault();
                }
            });

            // Validación del formulario, también evita envio con el enter
            let formSubmitting = false;
            $('#formulario-pedidos').on('submit', function(e) {
                if (formSubmitting) {
                    e.preventDefault();
                    return false;
                }
                console.log('validacion');

                let totalVenta = parseFloat($('#total_venta').val()) || 0;

                let efectivo = parseFloat($('#efectivo').val()) || 0;
                let debito = parseFloat($('#debito').val()) || 0;
                let credito = parseFloat($('#credito').val()) || 0;
                let transferencia = parseFloat($('#transferencia').val()) || 0;

                let totalPagado = efectivo + debito + credito + transferencia;

                const tipoVenta = 'CONTADO';
                const clienteId = $('#cliente_id').val();
                const montoCredito = parseFloat($('#monto_credito').val()) || 0;

                let basePago = tipoVenta === 'CRÉDITO' ? totalVenta - montoCredito : totalVenta;
                let excedente = totalPagado - basePago;
                let cambio = 0;
                let faltante = 0;

                // 1. Cliente público no puede comprar a crédito
                if (clienteId == 1) {
                    e.preventDefault();
                    alert("No puedes seleccionar CLIENTE PÚBLICO para una venta a Crédito.");
                    return false;
                }

                // 2. Debe haber al menos un producto o ponchado
                if ($('#item_table_0 tbody tr').length === 0) {
                    e.preventDefault();
                    alert("Agrega al menos un ponchado antes de guardar la venta.");
                    return false;
                }

                // 3. Validar que todas las filas tengan ubicación seleccionada
                let ubicacionesInvalidas = false;

                $('#item_table_0 tbody tr').each(function() {
                    let select = $(this).find('select[name*="[clasificacion_ubicaciones_id]"]');
                    if (select.length && select.val() === '') {
                        ubicacionesInvalidas = true;
                        return false; // detener el each
                    }
                });

                if (ubicacionesInvalidas) {
                    e.preventDefault();
                    alert("Selecciona una ubicación en cada ponchado agregado.");
                    return false;
                }

                // 4. Cambio y faltante
                if (excedente > 0) {
                    if (efectivo >= excedente) {
                        cambio = excedente;
                    } else {
                        faltante = excedente - efectivo;
                    }
                } else if (excedente < 0) {
                    faltante = -excedente;
                }

                // 6. No se puede dar más cambio del efectivo recibido
                if (excedente > 0 && efectivo < excedente) {
                    e.preventDefault();
                    alert("El cambio no puede ser mayor al efectivo entregado.");
                    return false;
                }

                // Todo válido
                formSubmitting = true;
            });

            // Prevenir envío por Enter (solo en inputs tipo number o text)
            $('#formulario-pedidos').on('keypress', 'input', function(e) {
                if (e.which === 13) {
                    e.preventDefault();
                    return false;
                }
            });

            // Función para recargar o inicializar la tabla PONCHADOS
            async function recargarOInicializarTabla() {
                if ($.fn.DataTable.isDataTable('#ponchados')) {
                    // Recargar los datos sin redibujar la tabla
                    await tableS.ajax.reload(null, false);
                    tableS.ajax.reload(null, false);
                } else {
                    // Inicializar la tabla si aún no está inicializada
                    await ponchados();
                }
            }

            // OBTEMGO LOS PONCHADOS POR AJAX
            async function ponchados() {
                const postData = {
                    _token: $('input[name=_token]').val(),
                    origen: 'ponchados.precios',
                };

                if ($.fn.DataTable.isDataTable('#ponchados')) {
                    $('#ponchados').DataTable().clear().destroy();
                }

                // Inicializar DataTable
                tableS = $('#ponchados').DataTable({
                    "language": {
                        "url": "{{ asset('/json/i18n/es_es.json') }}"
                    },
                    responsive: true,
                    retrieve: true,
                    processing: true,
                    ajax: {
                        url: "{{ route('ponchados.precios.ajax') }}",
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
                            data: 'ponchado_id',
                            name: 'ponchado_id',
                            visible: false,
                            searchable: false
                        },
                        {
                            data: 'cliente',
                            name: 'cliente',
                        },
                        {
                            data: 'precio',
                            render: function(data, type, row) {
                                // Verificar si el dato es nulo, indefinido o vacío
                                if (data === null || data === undefined || data === '') {
                                    return '$0.00'; // Valor por defecto si no hay dato
                                }
                                // Formatear el número con separadores de miles y decimales
                                var formattedNumber = $.fn.dataTable.render.number(',', '.', 2)
                                    .display(data);
                                // Agregar el símbolo de pesos al valor formateado
                                return '$ ' + formattedNumber;
                            },
                            defaultContent: '$0.00'
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

            function cerrarModalPonchados() {
                const $modal = $(".ponchado-modal");
                $modal.addClass('hidden');
                $modal.removeData('botonActivo'); // limpiar referencia del botón activo
                $('.bg-gray-900\\/50, .dark\\:bg-gray-900\\/80')
                    .remove(); // limpiar backdrop si lo creaste manualmente
            }

            //Recalcula el faltante y cambio
            function recalcularFaltanteCambio() {
                const totalVenta = parseFloat($('#total_venta').val()) || 0;
                const tipoVenta = $('select[name="tipo_venta"]').val();
                const montoCredito = parseFloat($('#monto_credito').val()) || 0;

                // Si es venta a crédito, el cliente solo debe cubrir parte del total
                const basePago = tipoVenta === 'CRÉDITO' ? totalVenta - montoCredito : totalVenta;

                let efectivo = parseFloat($('#efectivo').val()) || 0;
                let debito = parseFloat($('#debito').val()) || 0;
                let credito = parseFloat($('#credito').val()) || 0;
                let transferencia = parseFloat($('#transferencia').val()) || 0;

                const totalPagado = efectivo + debito + credito + transferencia;

                let cambio = 0;
                let faltante = 0;

                if (totalPagado >= basePago) {
                    const excedente = totalPagado - basePago;

                    // Solo puede haber cambio si el efectivo alcanza para cubrir el excedente
                    if (efectivo >= excedente) {
                        cambio = excedente;
                    } else {
                        cambio = 0;
                        faltante = excedente - efectivo;
                    }
                } else {
                    faltante = basePago - totalPagado;
                }

                $('#faltante_texto').text(faltante.toLocaleString('es-MX', {
                    style: 'currency',
                    currency: 'MXN'
                }));
                $('#cambio_texto').text(cambio.toLocaleString('es-MX', {
                    style: 'currency',
                    currency: 'MXN'
                }));

                $('#total_faltante').val(faltante);
                $('#total_cambio').val(cambio);

            }

            // calcula el total de la venta
            function recalcularTotalTabla(targetTableId) {
                let total = 0;

                $(`#${targetTableId} tbody tr`).each(function() {
                    const $row = $(this);
                    //const precio = parseFloat($('#precio_puntada').val()) ||0; //parseFloat($row.find('.pu').data('precio')) || 0;
                    //11232025 const precio = parseFloat($row.find('.pu').data('precio')) || 0;
                    const $tdPU = $row.children('td.pu');
                    const precio = parseFloat($tdPU.attr('data-precio')) || 0;
                    const cantidad = parseFloat($row.find('.cantVenta').val()) || 0;
                    let subtotal = 0;

                    if ($row.attr('data-idponchadoServicio')) {
                        // Producto: se multiplica
                        subtotal = cantidad * precio;
                        total += cantidad * precio;
                    }
                    //else if ($row.attr('data-idponchadoServicio')) {
                    // Ponchado: el precio ya es el total (no se multiplica por cantidad)
                    //    subtotal = precio;
                    //    total += precio;
                    //}

                    // Actualizar el input hidden del total por producto/ponchado
                    //$row.find('input.total_pp').val(subtotal.toFixed(2));

                    $row.find('td.importe input').val(subtotal.toFixed(2));

                    // Actualizar PU
                    //11232025 $row.find('.pu').contents().filter(function() {
                    //11232025    return this.nodeType === 3; // texto
                    //11232025 }).first().replaceWith(precio.toLocaleString('es-MX', {
                    //11232025     style: 'currency',
                    //11232025     currency: 'MXN'
                    //11232025 }));

                    $tdPU.contents().filter(function() {
                        return this.nodeType === 3;
                    }).first().replaceWith(precio.toLocaleString('es-MX', {
                        style: 'currency',
                        currency: 'MXN'
                    }));

                    // Actualizar también el texto visible si quieres mantenerlo sincronizado
                    $row.find('.importe').contents().filter(function() {
                        return this.nodeType === 3; // texto
                    }).first().replaceWith(subtotal.toLocaleString('es-MX', {
                        style: 'currency',
                        currency: 'MXN'
                    }));
                });

                // Actualizar visualmente
                $('span[data-total]').text(total.toLocaleString('es-MX', {
                    style: 'currency',
                    currency: 'MXN'
                }));

                // Formateo a moneda
                const totalFormateado = total.toLocaleString('es-MX', {
                    style: 'currency',
                    currency: 'MXN'
                });

                // Actualizar input hidden
                $('#total_venta').val(total);
                $('#total_mostrado').text(totalFormateado);
                $('#total_venta').val(total.toFixed(2));

                recalcularFaltanteCambio();
            }

            $('.select2').select2({
                placeholder: "-- Seleccione --",
                allowClear: false,
                width: '100%'
            });

            actualizarOpcionesFormasPago(); // para actualizar si hay formas prellenadas

        });
    </script>
@stop
