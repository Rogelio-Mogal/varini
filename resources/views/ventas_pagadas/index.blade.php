
@extends('layouts.app', ['breadcrumb' => [
    [
        'name' => 'Home',
        'url' => route('dashboard')
    ],
    [
        'name' => 'Ventas pagadas'
    ]
]])

@section('css')

@stop

@section('content')
<?php
    $fechaActual = date('Y-m-d');
?>

    <div class="shadow-md rounded-lg p-4 dark:bg-gray-800">
        <div class="grid grid-cols-1 lg:grid-cols-12 md:grid-cols-12 sm:grid-cols-12 gap-4">
            <div class="sm:col-span-12 lg:col-span-12 md:col-span-12">

                <div class="sm:col-span-12 lg:col-span-12 md:col-span-12">
                    <form id="filtroForm">
                        <div class="grid grid-cols-12 gap-3">
                            <!-- TIPO DE FILTRO -->
                            <div class="col-span-4">
                                <label class="block mb-2 text-sm font-medium text-gray-900">Tipo de filtro</label>
                                <div class="flex gap-6 items-center">
                                    <label class="flex items-center gap-2">
                                        <input type="radio" name="tipoFiltro" value="NINGUNO" id="radioNinguno" class="w-4 h-4" checked>
                                        <span>Ninguno</span>
                                    </label>
                                    <label class="flex items-center gap-2">
                                        <input type="radio" name="tipoFiltro" value="MES" id="radioMes" class="w-4 h-4">
                                        <span>Por mes</span>
                                    </label>
                                    <label class="flex items-center gap-2">
                                        <input type="radio" name="tipoFiltro" value="RANGO" id="radioRango" class="w-4 h-4">
                                        <span>Por rango de fechas</span>
                                    </label>
                                </div>
                            </div>
                            <!-- FILTRO POR MES -->
                            <div id="filtroMes" class="col-span-2 hidden">
                                <label class="block mb-2 text-sm font-medium text-gray-900">Mes</label>
                                <input type="month" id="mes" class="bg-gray-50 border border-gray-300 rounded-lg p-2.5 w-full"
                                value="{{ isset($mes) ? $mes : $now->format('Y-m') }}">
                            </div>
                            <!-- RANGO DE FECHAS -->
                            <div id="filtroRango" class="col-span-3 hidden grid grid-cols-8 gap-3">
                                <div class="col-span-4">
                                    <label class="block mb-2 text-sm font-medium text-gray-900">Fecha inicio</label>
                                    <input type="date" id="fechaInicio"
                                        class="bg-gray-50 border border-gray-300 rounded-lg p-2.5 w-full"
                                        value="{{ $fechaActual }}">
                                </div>
                                <div class="col-span-4">
                                    <label class="block mb-2 text-sm font-medium text-gray-900">Fecha fin</label>
                                    <input type="date" id="fechaFin"
                                        class="bg-gray-50 border border-gray-300 rounded-lg p-2.5 w-full"
                                        value="{{ $fechaActual }}">
                                </div>
                            </div>
                            <!-- BOTONES -->
                            <div class="col-span-3 flex gap-2 mt-0 items-end">
                                <button type="button" id="btnFiltrar" data-tooltip-target="tooltip-filtrar" data-tooltip-placement="bottom"
                                    class="text-white bg-pink-600 hover:bg-pink-700 px-3 py-2 rounded-lg">
                                    <svg class="w-5 h-7 text-gray-100 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-width="2" d="M18.796 4H5.204a1 1 0 0 0-.753 1.659l5.302 6.058a1 1 0 0 1 .247.659v4.874a.5.5 0 0 0 .2.4l3 2.25a.5.5 0 0 0 .8-.4v-7.124a1 1 0 0 1 .247-.659l5.302-6.059c.566-.646.106-1.658-.753-1.658Z"/>
                                    </svg>
                                    <span class="sr-only">Filtrar</span>
                                </button>
                                <div id="tooltip-filtrar"
                                    role="tooltip"
                                    class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip">
                                    Aplicar filtros
                                    <div class="tooltip-arrow" data-popper-arrow></div>
                                </div>
                                <button type="button" id="reloadTable" data-tooltip-target="tooltip-recargar" data-tooltip-placement="bottom"
                                    class="text-white bg-blue-500 hover:bg-blue-600 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-3 py-2 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                                    <svg class="w-5 h-7 text-gray-100 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.651 7.65a7.131 7.131 0 0 0-12.68 3.15M18.001 4v4h-4m-7.652 8.35a7.13 7.13 0 0 0 12.68-3.15M6 20v-4h4"/>
                                    </svg>
                                    <span class="sr-only">Recargar</span>
                                </button>
                                <div id="tooltip-recargar"
                                    role="tooltip"
                                    class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip">
                                    Recargar tabla
                                    <div class="tooltip-arrow" data-popper-arrow></div>
                                </div>
                                <button id="generarTicketSeleccionados" data-tooltip-target="tooltip-ticket" data-tooltip-placement="bottom"
                                        class="bg-green-600 text-white px-3 py-2 rounded hover:bg-green-700">
                                    <svg class="w-5 h-7 text-gray-100 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24"
                                        height="24" fill="none" viewBox="0 0 24 24">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 8h6m-6 4h6m-6 4h6M6 3v18l2-2 2 2 2-2 2 2 2-2 2 2V3l-2 2-2-2-2 2-2-2-2 2-2-2Z" />
                                    </svg>
                                    <span class="sr-only">Generar Ticket Seleccionados</span>
                                </button>
                                <div id="tooltip-ticket"
                                    role="tooltip"
                                    class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip">
                                    Generar ticket (seleccionados)
                                    <div class="tooltip-arrow" data-popper-arrow></div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <br>

                <table id="venta" class="table table-striped" style="width:100%">
                    <thead>
                        <tr>
                            <th></th>
                            <th>ID</th>
                            <th>Tipo</th>
                            <th>Pedido</th>
                            <th>Cliente</th>
                            <th>Fecha</th>
                            <th>Opciones</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

@endsection

@section('js')
    <script>
        $(document).ready(function() {
            let tblVentas;
            cargarVentas();

            function cargarVentas() {

                if ($.fn.DataTable.isDataTable('#venta')) {
                    $('#venta').DataTable().clear().destroy();
                }

                // ORDENAR CANTIDADES CON FORMATO "$1,234.56"
                $.extend($.fn.dataTable.ext.type.order, {
                    "currency-mx-pre": function (data) {
                        if (!data) return 0;

                        // Elimina $, comas y espacios
                        return parseFloat(
                            data.replace('$', '').replace(/,/g, '').trim()
                        );
                    }
                });

                tblVentas = $('#venta').DataTable({
                    processing: true,
                    serverSide: false, // cambiar a true si quieres paginación del lado del servidor
                    responsive: true,
                    order: [[4, 'asc'], [5, 'asc']], // ORDENA por cliente y fecha
                    rowGroup: {
                        dataSrc: 'cliente',
                        startRender: function (rows, group) {

                            // IDs de filas visibles del grupo
                            let rowsNodes = rows.nodes();

                            // Checkbox del encabezado
                            let checkbox = `
                                <input type="checkbox"
                                    class="group-check"
                                    data-cliente="${group}"
                                    style="margin-right:8px">
                            `;

                            return `
                                <div class="flex items-center gap-2">
                                    ${checkbox}
                                    <span class="font-semibold text-gray-800">
                                        ${group} (${rows.count()} ventas)
                                    </span>
                                </div>
                            `;
                        }
                    },
                    columnDefs: [
                        { orderable: false, targets: 0 } // desactiva ordenamiento en la columna de checkboxes
                    ],
                    ajax: {
                        url: "{{ route('ventas.pagadas.index.ajax') }}",
                        type: "POST",
                        data: function (d) {
                            return $.extend(d, {
                                _token: $('meta[name="csrf-token"]').attr('content'),
                                origen: "ventas.pagadas",
                            });
                        }
                    },
                    columns: [
                        {
                            data: null,
                            orderable: false,
                            render: function (data, type, row) {

                                if (row.tipo === 'venta') {
                                    return `
                                        <input type="checkbox"
                                            class="pedido-check"
                                            name="${row.id}"
                                            value="${row.id}"
                                            data-cliente-nombre="${row.cliente}">
                                    `;
                                }

                                return '';
                            }
                        },
                        { data: 'id' },
                        {
                            data: 'tipo',
                            render: function (data) {
                                return data === 'pedido' ? 'PEDIDO' : 'VENTA';
                            }
                        },
                        { data: 'referencia_cliente' },
                        { data: 'cliente' },
                        {
                            data: 'fecha',
                            render: function (data) {
                                if (!data) return '';

                                const fecha = new Date(data + 'T00:00:00');
                                return fecha.toLocaleDateString('es-MX');
                            }
                        },
                        {
                            data: 'acciones',
                            orderable: false,
                            searchable: false
                        }
                    ],
                    language: { url: "{{ asset('/json/i18n/es_es.json') }}" }
                });

                //  Re-inicializa Flowbite cada vez que DataTables repinta
                tblVentas.on('draw', function () {
                    if (typeof window.initFlowbite === "function") {
                        window.initFlowbite();
                    }
                });
            }

            // Botón de recargar
            $("#reloadTable").on("click", function() {
                $// Dejar seleccionado NINGUNO
                $("#radioNinguno").prop("checked", true);

                // Ocultar ambos filtros
                $("#filtroMes").addClass("hidden");
                $("#filtroRango").addClass("hidden");

                // Limpiar valores
                $("#mes").val("");
                $("#fechaInicio").val("");
                $("#fechaFin").val("");

                cargarVentas();
            });

            // Mostrar u ocultar filtros según selección
            $("input[name='tipoFiltro']").on("change", function () {

                let tipo = $(this).val();

                if (tipo === "MES") {
                    $("#filtroMes").removeClass("hidden");
                    $("#filtroRango").addClass("hidden");
                } else if (tipo === "RANGO") {
                    $("#filtroRango").removeClass("hidden");
                    $("#filtroMes").addClass("hidden");
                }else if (tipo === "NINGUNO") {
                    $("#filtroMes").addClass("hidden");
                    $("#filtroRango").addClass("hidden");
                }
            });

            // FILTRAR (envío AJAX al DataTable)
            $("#btnFiltrar_NO").on("click", function () {

                let tipo = $("input[name='tipoFiltro']:checked").val();

                let postData = {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    origen: "ventas.pagadas",
                };

                if (tipo === "MES") {
                    postData.mes_hidden = "MES";
                    postData.mes = $("#mes").val();
                }

                if (tipo === "RANGO") {
                    postData.rango = "RANGO";
                    postData.fechaInicio = $("#fechaInicio").val();
                    postData.fechaFin = $("#fechaFin").val();
                }

                if (tipo === "NINGUNO") {
                    postData.filtro = "NINGUNO";
                }

                // Ahora SÍ enviamos los datos correctamente al DataTable
                tblVentas.ajax.reload(null, false);
                tblVentas.ajax.params = postData;

                tblVentas.settings()[0].ajax.data = function(d){
                    return $.extend(d, postData);
                };

                tblVentas.ajax.reload();
            });

            $("#btnFiltrar").on("click", function () {

                let tipoFiltro = $("input[name='tipoFiltro']:checked").val();

                tblVentas.settings()[0].ajax.data = function (d) {

                    d._token = $('meta[name="csrf-token"]').attr('content');
                    d.origen = "ventas.pagadas";
                    d.tipoFiltro = tipoFiltro;

                    if (tipoFiltro === "MES") {
                        d.mes = $("#mes").val();
                    }

                    if (tipoFiltro === "RANGO") {
                        d.fechaInicio = $("#fechaInicio").val();
                        d.fechaFin = $("#fechaFin").val();
                    }

                    // NINGUNO no envía fechas
                    return d;
                };

                tblVentas.ajax.reload();
            });


            // seleccionar / deseleccionar pedidos por cliente (rowGroup)
            $('#venta').on('change', '.group-check', function () {

                let cliente = $(this).data('cliente');
                let checked = this.checked;

                // Selecciona SOLO los pedidos de ese cliente
                $('.pedido-check').each(function () {
                    if ($(this).data('cliente-nombre') === cliente) {
                        $(this).prop('checked', checked);
                    }
                });
            });

            // Cuando se desmarca un solo pedido, el checkbox del grupo debe actualizarse.
            $('#venta').on('change', '.pedido-check', function () {

                let cliente = $(this).data('cliente-nombre');

                let total = $('.pedido-check[data-cliente-nombre="' + cliente + '"]').length;
                let checked = $('.pedido-check[data-cliente-nombre="' + cliente + '"]:checked').length;

                let groupCheckbox = $('.group-check[data-cliente="' + cliente + '"]');

                if (checked === total) {
                    groupCheckbox.prop('checked', true);
                } else {
                    groupCheckbox.prop('checked', false);
                }
            });


            // pasar seleccionados a venta
            $('#pasarSeleccionados').on('click', function() {
                /*
                let seleccionados = $('.pedido-check:checked')
                    .map(function() { return $(this).val(); }).get();

                if (seleccionados.length === 0) {
                    alert('Selecciona al menos un pedido');
                    return;
                }

                // Enviar por GET al create
                let url = "{{ route('admin.ventas.create') }}?referencia_cliente=" + seleccionados.join(',');

                window.location.href = url;
                */
               let seleccionados = $('.pedido-check:checked');

                if (seleccionados.length === 0) {
                    alert('Selecciona al menos un pedido');
                    return;
                }

                // Obtener nombre del cliente del primer pedido
                let clienteBase = seleccionados.first().data('cliente-nombre');

                // Validar que todos los seleccionados tengan el MISMO cliente
                let mezcla = false;

                seleccionados.each(function() {
                    if ($(this).data('cliente-nombre') !== clienteBase) {
                        mezcla = true;
                        return false; // detener loop
                    }
                });

                if (mezcla) {
                    alert('Solo puedes seleccionar pedidos del mismo cliente.');
                    return;
                }

                // Construir lista de referencias_cliente ya existentes en tu código
                let valores = seleccionados.map(function() {
                    return $(this).val();
                }).get();

                // Enviar por GET al create
                let url = "{{ route('admin.ventas.create') }}?referencia_cliente=" + valores.join(',');
                window.location.href = url;
            });

            // generar ticket seleccionados
            $('#generarTicketSeleccionados').on('click', function() {
                let seleccionados = [];

                // recolectar pedidos seleccionados
                $('.pedido-check:checked').each(function() {
                    seleccionados.push($(this).val());
                });

                // si también quieres ventas seleccionadas
                $('.venta-check:checked').each(function() {
                    seleccionados.push($(this).val());
                });

                if (seleccionados.length === 0) {
                    alert('Selecciona al menos un pedido o venta para generar el ticket.');
                    return;
                }
                console.log(seleccionados.join(','));

                // construir la URL con las referencias seleccionadas
                let urlBase = "{{ route('ticket.misto') }}"; // /ticket/mixto
                //let url = urlBase + '?referencias=' + encodeURIComponent(seleccionados.join(','));
                let url = urlBase + '?referencias=' + seleccionados.join(',');

                window.open(url, '_blank'); // abrir ticket en nueva pestaña
            });



            // Manejar el clic en la opción "Eliminar"
            $('#venta').on('click', '.delete-item', function(e) {
                e.preventDefault();
                var id = $(this).data('id');

                // Utilizar SweetAlert2 para mostrar un mensaje de confirmación
                Swal.fire({
                    title: '¿Estás seguro?',
                    text: 'No podrás revertir esto',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Sí, eliminarlo'
                }).then((result) => {
                    if (result.value) {
                        console.log(id);
                        // Solicitud AJAX para eliminar el elemento
                        $.ajax({
                            url: "{{ route('admin.ventas.destroy', ':id') }}"
                                .replace(':id', id),
                            type: 'POST',
                            data: {
                                "_token": "{{ csrf_token() }}",
                                "_method": "DELETE"
                            },
                            success: function(data) {
                                setTimeout(function() {
                                    location.reload();
                                }, 1000);
                            },
                            error: function(xhr, status, error) {
                                console.error(xhr.responseText);
                            }
                        });
                    }
                });
            });

            // Manejar el clic en la opción "Avtivar"
            $('#venta').on('click', '.activa-item', function(e) {
                e.preventDefault();
                var id = $(this).data('id');

                // Utilizar SweetAlert2 para mostrar un mensaje de confirmación
                Swal.fire({
                    title: 'El proveedor está deshabilitada',
                    text: '¿Está seguro de activar al proveedor?',
                    icon: 'info',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Sí, activar'
                }).then((result) => {
                    if (result.value) {
                        console.log(id);
                        // Solicitud AJAX para eliminar el elemento
                        $.ajax({
                            url: "{{ route('admin.ventas.update', ':id') }}"
                                .replace(':id', id),
                            type: 'PUT',
                            data: {
                                "_token": "{{ csrf_token() }}",
                                "_method": "PUT",
                                "activa" : 1
                            },
                            success: function(data) {
                                setTimeout(function() {
                                    location.reload();
                                }, 1000);
                            },
                            error: function(xhr, status, error) {
                                //console.error(xhr.responseText);
                                setTimeout(function() {
                                    location.reload();
                                }, 1000);
                            }
                        });
                    }
                });
            });
        });
    </script>
    @if (Session::has('id'))
        <script type="text/javascript">
            var id = {{ session('id') }};
            setTimeout(function() {
                window.open("{{ url('/ticket-venta') }}/" + id, '_blank');
            }, 200);
            <?php Session::forget('id'); ?>
        </script>
    @endif
@stop
