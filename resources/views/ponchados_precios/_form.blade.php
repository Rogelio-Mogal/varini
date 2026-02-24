<x-validation-errors class="mb-4" />

<div class="grid grid-cols-12 gap-4">
    <div class="col-span-12 lg:col-span-12 space-y-2">
        <h3 class="font-bold text-purple-600 border-b pb-1 mb-3">Precios</h3>
        <div class="grid lg:grid-cols-12 md:grid-cols-12 sm:grid-cols-12 gap-2">
            <input type="hidden" name="detalles_json" id="detalles_json">

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
                value="{{ old('cliente_id', $cliente->id ?? 1) }}">


            <div class="sm:col-span-12 {{ $metodo == 'create' ? 'lg:col-span-8 md:col-span-8' : 'lg:col-span-10 md:col-span-10' }}">
                <label for="nombre_cliente"
                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Cliente</label>
                <input type="text" id="nombre_cliente" name="nombre_cliente" required
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                    placeholder="Nombre" value="{{ old('nombre_cliente', $cliente->full_name ?? 'CLIENTE PÚBLICO') }}" readonly />
            </div>

            @if($metodo == 'create')
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
            @endif

            <!-- ##### MODULO DE PONCHADOS  #########   -->
            @if($metodo == 'create')
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
                                                <th scope="col" class="px-6 py-3">Producto</th>
                                                <th scope="col" class="px-6 py-3">Precio</th>
                                                <th scope="col" class="px-6 py-3">Opciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                            @if($ponchado && !empty($ponchado->nombre))
                                                <tr data-idponchadoServicio="{{ $ponchado->id }}">
                                                    <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                                        {{ $ponchado->nombre }}
                                                        <input type="hidden"  class="ponchado_id" value="{{ $ponchado->id }}" />
                                                        <input type="hidden" class="ponchado_nombre" value="{{ $ponchado->nombre }}" />
                                                    </td>
                                                    <td class="px-6 py-4">
                                                        <input type="number" min="0" step="0.01"
                                                            class="precio_input bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg
                                                            focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                                                            placeholder="Precio" value="" />
                                                    </td>
                                                    <td class="px-6 py-4">
                                                        <button type="button" class="remove font-medium text-red-600 hover:underline">
                                                            Eliminar
                                                        </button>
                                                    </td>
                                                </tr>
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                @else
                    <div class="sm:col-span-12 lg:col-span-6 md:col-span-6">
                        <label for="ponchado_name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Producto</label>
                        <input type="text" id="ponchado_name" name="ponchado_name"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                            placeholder="Ingrese la dirección" value="{{ old('ponchado_name', $ponchado->ponchadoRelacionado->nombre) }}" readonly />
                            <input type="hidden" name="ponchado_id" value="{{ old('ponchado_id', $ponchado->ponchado_id) }}">
                    </div>
                    <div class="sm:col-span-12 lg:col-span-6 md:col-span-6">
                        <label for="precio" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Precio</label>
                        <input type="number" id="precio" name="precio" min="0" step="0.01"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                            placeholder="Precio" value="{{ old('precio', $ponchado->precio) }}" />
                    </div>

            @endif
            <!-- ##### FIN MODULO DE PONCHADOS  #########   -->

            <div class="sm:col-span-12 lg:col-span-12 md:col-span-12 mt-3">
                <button type="submit"
                    class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">
                    @if ($metodo == 'create')
                        CREAR PRECIO
                    @elseif($metodo == 'edit')
                        EDITAR PRECIO
                    @endif
                </button>
            </div>

            <!-- Modal -->
            @include('ponchados._modal_ponchados')
            @include('clientes._modal_clientes')
        </div>
    </div>


</div>

@section('js')
    <script>

        const detalles = @json($ponchado);
        const metodo = @json($metodo);

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

        $(document).ready(function() {
            $('form').on('submit', function(e) {

                let detallesArray = [];

                $('table[id^="item_table_"] tbody tr').each(function() {

                    let ponchado_id = $(this).find('.ponchado_id').val();
                    let precio = $(this).find('.precio_input').val();
                    let ponchado = $(this).find('.ponchado_nombre').val();

                    if (ponchado_id && precio && ponchado ) {
                        detallesArray.push({
                            ponchado_id: ponchado_id,
                            precio: precio,
                            ponchado: ponchado
                        });
                    }
                });

                if (detallesArray.length === 0) {
                    e.preventDefault();
                    alert("Debe agregar al menos un ponchado con precio.");
                    return;
                }

                $('#detalles_json').val(JSON.stringify(detallesArray));
            });

            let tableS = null; //de ponchados

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

                var idponchado = data['id'];
                var nombre = data['nombre'];
                var precio_publico = data.restante ? parseFloat(data.restante) : 0;
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

                // Buscar si ya existe el ponchado en la tabla por data-idponchado
                var $existingRow = $targetTable.find(`tbody tr[data-idponchadoServicio="${idponchado}"]`);
                if ($existingRow.length > 0) {
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
                        `<tr data-idponchadoServicio="${idponchado}" class="odd:bg-white odd:dark:bg-gray-500 even:bg-gray-50 even:dark:bg-gray-400 border-b border-gray-100 dark:border-gray-400">`;

                    // Producto (nombre mostrado)
                    html += `<td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                        ${nombre}
                        <input type="hidden" class="ponchado_id" value="${idponchado}" />
                        <input type="hidden" class="ponchado_nombre" value="${nombre}" />
                    </td>`;

                    // PRECIO
                    html += `<td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                       <input type="number" min="0" step="0.01"
                        class="precio_input bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                        placeholder="Precio" value="" />

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
            $('#formulario-precios').on('submit', function(e) {
                if (formSubmitting) {
                    e.preventDefault();
                    return false;
                }
                console.log('validacion');

                // 1. Debe haber al menos un precio
                if ($('#item_table_0 tbody tr').length === 0 && metodo == 'create') {
                    e.preventDefault();
                    alert("Agrega al menos un precio antes de guardar.");
                    return false;
                }else{

                }

                // Todo válido
                formSubmitting = true;
            });

            // Prevenir envío por Enter (solo en inputs tipo number o text)
            $('#formulario-precios').on('keypress', 'input', function(e) {
                if (e.which === 13) {
                    e.preventDefault();
                    return false;
                }
            });

            // Función para recargar o inicializar la tabla PONCHADOS
            //async function recargarOInicializarTabla() {
            //    if ($.fn.DataTable.isDataTable('#ponchados')) {
                    // Recargar los datos sin redibujar la tabla
            //        await tableS.ajax.reload(null, false);
            //        tableS.ajax.reload(null, false);
            //    } else {
                    // Inicializar la tabla si aún no está inicializada
            //        await ponchados();
            //    }
            //}

            async function recargarOInicializarTabla() {

                if (tableS) {
                    tableS.ajax.reload(null, false);
                    return;
                }

                await ponchados();
            }

            // OBTEMGO LOS PONCHADOS POR AJAX
            async function ponchados() {
                const postData = {
                    _token: $('input[name=_token]').val(),
                    origen: 'ponchados.index',
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

            function cerrarModalPonchados() {
                const $modal = $(".ponchado-modal");
                $modal.addClass('hidden');
                $modal.removeData('botonActivo'); // limpiar referencia del botón activo
                $('.bg-gray-900\\/50, .dark\\:bg-gray-900\\/80')
                    .remove(); // limpiar backdrop si lo creaste manualmente
            }



        });
    </script>
@stop
