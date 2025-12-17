@extends('layouts.app', [
    'breadcrumb' => [
        [
            'name' => 'Home',
            'url' => route('dashboard'),
        ],
        [
            'name' => 'Pedidos ponchados',
        ],
    ],
])

@section('css')
<style>
    .tooltip-content {
        transition: opacity 0.3s ease-in-out;
    }

    .invisible {
        display: none;
    }

    .visible {
        display: block;
        opacity: 1;
    }

    .opacity-0 {
        opacity: 0;
    }

    .tooltip-content {
        transition: opacity 0.3s ease-in-out;
        visibility: hidden; /* Esto es lo que asegura que esté oculto */
        opacity: 0;
    }

    .tooltip-visible {
        visibility: visible; /* Esto lo hará visible */
        opacity: 1;
    }

    .modal {
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .modal-content {
        background: white;
        padding: 20px;
        border-radius: 8px;
        max-width: 600px;
        width: 100%;
    }
    .custom-modal-bg {
        background-color: rgba(146, 151, 162, 0.688); /* Aplicar bg-gray-100 con opacidad 50% */
    }
    /* Estilos para modo oscuro */
    .dark .custom-modal-bg {
        background-color: rgba(137, 143, 153, 0.688); /* Color oscuro con opacidad */
    }

</style>
@stop

@section('content')
@section('action')
    <a href="{{ route('admin.pedidos.ponchados.create') }}"
        class="text-white bg-blue-700 hover:bg-blue-800 focus:outline-none focus:ring-4 focus:ring-blue-300 font-medium rounded-full text-sm px-5 py-2.5 text-center me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Nuevo</a>
@endsection
<div class="shadow-md rounded-lg p-4 dark:bg-gray-800">
    <div class="hola grid grid-cols-1 lg:grid-cols-12 md:grid-cols-12 sm:grid-cols-12 gap-4">
        <div class="sm:col-span-12 lg:col-span-12 md:col-span-12">
            <div class="mb-4">
                <button id="reloadTable"
                    class="text-white bg-blue-500 hover:bg-blue-600 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-4 py-2.5 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                    Recargar Tabla
                </button>
            </div>

            <table id="ponchados_pedidos" class="table table-striped " style="width:100%">
                <thead>
                    <tr>
                        <th></th>
                        <th>ID</th>
                        <th>Pedido</th>
                        <th>Cliente</th>
                        <th>Fecha estimada</th>
                        <th>Estatus</th>
                        <th>Activo</th>
                        <th>Opciones</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>

            @include('ponchados_pedidos._modal_edita_estatus_pedidos')
        </div>
    </div>
</div>


@endsection

@section('js')
<script>
    var table;

    $(document).ready(function() {
        // Inicializar DataTable
        ponchados();
        let tblPedido;

        // RECARGAR TABLA
        $('#reloadTable').on('click', function() {
            $('#loadingOverlay').removeClass('hidden'); // Mostrar overlay
            table.ajax.reload(function() {
                $('#loadingOverlay').addClass('hidden'); // Ocultar overlay después de recargar
            });
        });
        // Manejar el clic en la opción "Eliminar"
        $('#ponchados_pedidos').on('click', '.delete-item', function(e) {
            e.preventDefault();
            var id = $(this).data('id');
            console.log('id: '+id);

            // Utilizar SweetAlert2 para mostrar un mensaje de confirmación
            Swal.fire({
                title: '¿Estás seguro?',
                text: 'No podrás revertir esto',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Sí, eliminarlo',
                cancelButtonText: 'Cancelar',
                buttonsStyling: false, // <- Desactiva los estilos por defecto
                customClass: {
                    confirmButton: 'text-white bg-red-600 hover:bg-red-700 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2',
                    cancelButton: 'text-gray-900 bg-white border border-gray-300 hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-gray-200 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2'
                }
            }).then((result) => {
                if (result.value) {
                    console.log(id);
                    // Solicitud AJAX para eliminar el elemento
                    $.ajax({
                        url: "{{ route('admin.pedidos.ponchados.destroy', ':id') }}"
                            .replace(':id', id),
                        type: 'POST',
                        data: {
                            "_token": "{{ csrf_token() }}",
                            "_method": "DELETE"
                        },
                        success: function(data) {
                            console.log('data: '+data);
                            Swal.fire({
                                icon: data.swal.icon,
                                title: data.swal.title,
                                text: data.swal.text,
                                customClass: data.swal.customClass,
                                buttonsStyling: data.swal.buttonsStyling
                            }).then(() => {
                                // Después de que el usuario cierre el SweetAlert, recarga la página
                                location.reload();
                            });
                        },
                        error: function(xhr, status, error) {
                            if (xhr.status === 400) {
                                var swalData = xhr.responseJSON.swal;
                                Swal.fire({
                                    icon: swalData.icon,
                                    title: swalData.title,
                                    text: swalData.text,
                                    customClass: swalData.customClass,
                                    buttonsStyling: swalData.buttonsStyling
                                });
                            } else {
                                console.error(xhr.responseText);
                            }
                        }
                    });
                }
            });
        });

        // FUNCION PARA TRAER EL MODAL Y CAMBIAR ESTATUS
        function cambiaEstatus(id) {
            const origen = 'show.edit';
            const ajaxData = {
                "_token": "{{ csrf_token() }}",
                id: id,
                origen: 'show.edit',
            };
            var showUrl = "{{ route('admin.pedidos.ponchados.show', ':id') }}";
            var showLink = showUrl.replace(':id', id);
            // Agregamos los parámetros como query string
            showLink += '?origen=' + encodeURIComponent(origen);

            $.ajax({
                url: showLink,
                type: "GET",
                dataType: 'json',
                data: ajaxData,
                success: function(response) {
                    if (response.data) {

                        // Genearar el modal de edición
                        let modal = $('#edit-modal-status'); // + id);

                        // Cambiar valores del formulario en el modal
                        var updateUrl = "{{ route('admin.estatus.pedidos.update', ':id') }}".replace(':id',
                            id);

                        if (response.data.estatus) {
                             //modal.find('#estatus').val(response.data.estatus);
                                modal.find('#estatus')
                                .val(response.data.estatus)
                                .trigger('change');

                                console.log('response.data.estatus: '+response.data.estatus);

                                // --- Deshabilitar select si Entregado o Eliminado ---
                                if (response.data.estatus === 'Entregado' || response.data.estatus === 'Eliminado') {

                                    modal.find('#estatus').prop('disabled', true);

                                    // Opcional: deshabilitar botón Guardar
                                    modal.find('.btn-submit')
                                        .prop('disabled', true)
                                        .addClass('opacity-50 cursor-not-allowed');

                                } else {

                                    modal.find('#estatus').prop('disabled', false);

                                    modal.find('.btn-submit')
                                        .prop('disabled', false)
                                        .removeClass('opacity-50 cursor-not-allowed');
                                }
                        } else {
                            modal.find('#estatus').val('Diseño'); // Valor por defecto
                        }
                        modal.find('form').attr('action', updateUrl);
                        modal.find('#cliente_id').val(response.data.cliente_id);
                        modal.find('#pedido_ponchado_id').val(response.data.id);

                        modal.find('#nombre_pedido').text(response.data.referencia_cliente);
                        modal.find('#ponchado').text(response.data.ponchado.nombre);
                        modal.find('#piezas').text(response.data.cantidad_piezas);
                        modal.find('#precio').text(response.data.precio_unitario);
                        modal.find('#clasificacion').text(response.data.clasificacion_ubicacion.nombre);

                        // Mostrar el modal
                        modal.removeClass('hidden');
                    } else {
                        // No hay datos, manejar el caso donde no se encuentra el registro
                        console.log('No se encontro el Id')
                    }
                },
                error: function(response) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Advertencia',
                        text: 'Hubo un error durante el proceso, por favor intente de nuevo.',
                        showCancelButton: false,
                        confirmButtonText: 'OK',
                        customClass: {
                            confirmButton: 'text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800'
                        },
                        buttonsStyling: false // Deshabilitar el estilo predeterminado de SweetAlert2
                    });
                },
            });
        }

        //intercepto el submit del modal
        $(document).on('submit', '#form-edit', function(e) {
            e.preventDefault(); // evitar submit normal

            let $form = $(this);
            let url = $form.attr('action'); // acción dinámica
            let formData = $form.serialize();

            $.ajax({
                url: url,
                type: 'POST',
                data: formData,
                dataType: 'json',
                success: function(response) {
                    // Cerrar modal
                    $('#edit-modal-status').fadeOut();

                    Swal.fire({
                        icon: 'success',
                        title: 'Éxito',
                        text: response.message || 'Estatus actualizado correctamente'
                    }).then(() => {
                        // Recargar DataTable con overlay
                        $('#loadingOverlay').removeClass('hidden');
                        table.ajax.reload(function() {
                            $('#loadingOverlay').addClass('hidden');
                        });
                    });

                    // Actualizar la fila en la tabla (si usas DataTables)
                    if(response.data && response.data.id) {
                        let row = table.rows().nodes().to$().find(`tr[data-id="${response.data.id}"]`);
                        if(row.length) {
                            row.find('td:nth-child(6)').text(response.data.estatus); // columna estatus
                        }
                    }
                },
                error: function(xhr) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: xhr.responseJSON?.message || 'Ocurrió un error, intente de nuevo'
                    });
                }
            });
        });

        // Abrir modal al hacer clic en el botón
        $(document).on('click', '.open-modal', function(e) {
            e.preventDefault();

            let pedidoId = $(this).data('id');

            // si guardaste info adicional en atributos data-xxx
            let ponchado      = $(this).data('ponchado');
            let clasificacion = $(this).data('clasificacion');
            let piezas        = $(this).data('piezas');
            let precio        = $(this).data('precio');
            let estatus       = $(this).data('estatus');
            let clienteId     = $(this).data('cliente');

            console.log({
                ponchado, clasificacion, piezas, precio, estatus, clienteId
            });

            // llenar los campos ocultos
            $('#pedido_ponchado_id').val(pedidoId);
            $('#cliente_id').val(clienteId);

            // mostrar info
            $('#ponchado').html(ponchado);
            $('#clasificacion').text(clasificacion);
            $('#piezas').text(piezas);
            $('#precio').text(precio);
            $('#estatus').val(estatus);

            // actualizar la acción del form
            let updateUrl = "{{ route('admin.pedidos.ponchados.update', ':id') }}";
            $('#form-edit').attr('action', updateUrl.replace(':id', pedidoId));

            // abrir modal
            $('#edit-modal-status').fadeIn();

            cambiaEstatus(pedidoId);
        });

        // cerrar modal
        $(document).on('click', '.close-modal', function() {
            $('#edit-modal-status').fadeOut();
        });

        // detalles
        $('#ponchados_pedidos tbody').on('click', 'td.dt-control', function () {
            var tr = $(this).closest('tr');
            var row = table.row(tr);

            if (row.child.isShown()) {
                row.child.hide();
                $tr.removeClass('shown');
            } else {
                var data = row.data();
                var detalles = row.data().detalles;
                var html = `
                <div class="p-3 border-b bg-gray-50 text-sm text-gray-700 flex flex-wrap gap-6">
                    <span><strong>Pedido:</strong> ${data.referencia_cliente ?? ''}</span>
                    <span><strong>Fecha estimada:</strong> ${data.fecha_estimada_entrega ?? ''}</span>
                    <span><strong>Estatus:</strong> ${data.estatus ?? ''}</span>
                </div>

                <table class="table table-sm table-bordered">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Ponchado</th>
                            <th>Prenda</th>
                            <th>Clasificación</th>
                            <th>Cantidad</th>
                            <th>Estatus</th>
                            <th>Cambio estatus</th>
                        </tr>
                    </thead>
                    <tbody>`;
                detalles.forEach(function(d) {

                    let botonEstatus = '';

                    if (d.estatus !== 'Finalizado') {
                        let $a = $(`<a href="#"
                                data-popover-target="estatus-${d.id}"
                                data-popover-placement="left"
                                data-id="${d.id}"
                                data-cliente="${d.cliente_id}"
                                data-ponchado="${d.ponchado}"
                                data-clasificacion="${d.clasificacion ?? ''}"
                                data-piezas="${d.cantidad_piezas}"
                                data-precio="${d.precio ?? ''}"
                                data-estatus="${d.estatus}"
                                class="open-modal edit-item text-white mb-1 bg-blue-700 hover:bg-blue-800
                                    focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg
                                    text-sm p-2.5 text-center inline-flex items-center me-0
                                    dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                                <svg class="w-5 h-5 text-white-800 dark:text-white" aria-hidden="true"
                                    xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                    fill="none" viewBox="0 0 24 24">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M8.5 11.5 11 14l4-4m6 2a9 9 0 1 1-18 0
                                            9 9 0 0 1 18 0Z"/>
                                </svg>
                                <span class="sr-only">Estatus</span></a>`);
                        $a.attr('data-id', d.id);
                        $a.attr('data-cliente', d.cliente_id);
                        $a.attr('data-ponchado', d.ponchado);
                        $a.attr('data-clasificacion', d.clasificacion ?? '');
                        $a.attr('data-piezas', d.cantidad_piezas);
                        $a.attr('data-precio', d.precio ?? '');
                        $a.attr('data-estatus', d.estatus);

                        // Convertir a HTML para ponerlo en la celda
                        botonEstatus = $a.prop('outerHTML');
                    }

                    html += `<tr>
                        <td>${d.id}</td>
                        <td>${d.ponchado}</td>
                        <td>${d.prenda}</td>
                        <td>${d.clasificacion ?? ''}</td>
                        <td>${d.cantidad_piezas}</td>
                        <td>${d.estatus}</td>
                        <td>${botonEstatus}</td>
                    </tr>`;
                });
                html += `</tbody></table>`;

                row.child(html).show();
                tr.addClass('shown');
            }
        });

        // FUNCION MONEDA
        function formatCurrency(amount) {
            return amount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, "$&,");
        }

        // FUNCION PARA TRAER EL PRECIO Y GENERAR EL MODAL
        function precios(id) {
            const origen = 'show.edit';
            const ajaxData = {
                "_token": "{{ csrf_token() }}",
                id: id,
                origen: 'show.edit',
            };
            var showUrl = "{{ route('admin.pedidos.ponchados.show', ':id') }}";
            var showLink = showUrl.replace(':id', id);
            // Agregamos los parámetros como query string
            showLink += '?origen=' + encodeURIComponent(origen);

            $.ajax({
                url: showLink,
                type: "GET",
                dataType: 'json',
                data: ajaxData,
                success: function(response) {
                    if (response.data) {

                        // Genearar el modal de edición
                        let modal = $('#edit-modal-status'); // + id);

                        // Cambiar valores del formulario en el modal
                        var updateUrl = "{{ route('admin.estatus.pedidos.update', ':id') }}".replace(':id',
                            id);

                        if (response.data.estatus) {
                            modal.find('#estatus').val(response.data.estatus);
                        } else {
                            modal.find('#estatus').val('Diseño'); // Valor por defecto
                        }
                        modal.find('form').attr('action', updateUrl);
                        modal.find('#cliente_id').val(response.data.cliente_id);
                        modal.find('#pedido_ponchado_id').val(response.data.id);

                        modal.find('#nombre_pedido').text(response.data.referencia_cliente);
                        modal.find('#ponchado').text(response.data.ponchado.nombre);
                        modal.find('#piezas').text(response.data.cantidad_piezas);
                        modal.find('#precio').text(response.data.precio_unitario);
                        modal.find('#clasificacion').text(response.data.clasificacion_ubicacion.nombre);

                        // Mostrar el modal
                        modal.removeClass('hidden');
                    } else {
                        // No hay datos, manejar el caso donde no se encuentra el registro
                        console.log('No se encontro el Id')
                    }
                },
                error: function(response) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Advertencia',
                        text: 'Hubo un error durante el proceso, por favor intente de nuevo.',
                        showCancelButton: false,
                        confirmButtonText: 'OK',
                        customClass: {
                            confirmButton: 'text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800'
                        },
                        buttonsStyling: false // Deshabilitar el estilo predeterminado de SweetAlert2
                    });
                },
            });
        }

    });

    function ponchados() {
        const postData = {
            _token: $('input[name=_token]').val(),
            origen: 'ponchados.pedidos',
        };
        var editUrl = "{{ route('admin.pedidos.ponchados.edit', ':id') }}";
        var showUrl = "{{ route('admin.pedidos.ponchados.show', ':id') }}";

        // Inicializar DataTable
        table =  $('#ponchados_pedidos').DataTable({
            "language": {
                "url": "{{ asset('/json/i18n/es_es.json') }}"
            },
            responsive: {
                details: false
            },
            retrieve: true,
            processing: true,
            columnDefs: [
                {
                    targets: 0,
                    className: 'dt-control',
                    orderable: false,
                    responsivePriority: 1,
                    visible: true // 👈 evita que se oculte en responsive
                },
                {
                    targets: 0,
                    visible: true,
                    responsivePriority: -1 // 👈 hace que nunca se oculte
                }
            ],
            ajax: {
                url: "{{ route('ponchados.index.ajax') }}",
                type: "POST",
                'data': function(d) {
                    d._token = postData._token;
                    d.origen = postData.origen;
                }
            },
            'columns': [
                {
                    className: 'dt-control',
                    orderable: false,
                    data: null,
                    defaultContent: '', // DataTables insertará el ícono
                    responsivePriority: 1,
                },
                {
                    data: 'id',
                    name: 'id',
                    visible: true,
                    searchable: false
                },
                { data: "cliente", title: "Cliente" },
                { data: "referencia_cliente", title: "Pedido" },
                {
                    data: "fecha_estimada_entrega",
                    title: "Fecha estimada",
                    render: function(data) {
                        if (!data) return '';
                        const fecha = new Date(data);
                        return fecha.toLocaleDateString('es-MX');
                    }
                },
                { data: "estatus", title: "estatus" },
                {
                    data: 'activo',
                    render: function(data, type, row) {
                        if (data == 0) {
                            return '<span class="bg-red-100 text-red-800 text-sm font-medium me-2 px-2.5 py-0.5 rounded dark:bg-red-900 dark:text-red-300">Eliminado</span>';
                        } else if (data == 1) {
                            return '<span class="bg-green-100 text-green-800 text-sm font-medium me-2 px-2.5 py-0.5 rounded dark:bg-green-900 dark:text-green-300">Activo</span>';
                        }else if (data == 2) {
                            return '<span class="bg-red-100 text-red-800 text-sm font-medium me-2 px-2.5 py-0.5 rounded dark:bg-red-900 dark:text-red-300">URGENTE</span>';
                        }
                        return '';
                    }
                },
                {
                    data: 'id',
                    render: function(data, type, row) {
                        var editLink = editUrl.replace(':id', data);
                        var sowLink = showUrl.replace(':id', data);
                        var isActive = row.activo;
                        console.log('isActive: '+isActive);

                        if (isActive == 1 || isActive == 2) {
                            return `
                                <a href="${sowLink}" target="_blank"
                                    data-popover-target="detalle-${data}" data-popover-placement="left"
                                    data-id="${data}"
                                    data-popover-target="detalles${data}" data-popover-placement="bottom"
                                    class="text-white mb-1 bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm p-2.5 text-center inline-flex items-center me-0 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                                    <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                        width="24" height="24" fill="none" viewBox="0 0 24 24">
                                        <path stroke="currentColor" stroke-width="2"
                                            d="M21 12c0 1.2-4.03 6-9 6s-9-4.8-9-6c0-1.2 4.03-6 9-6s9 4.8 9 6Z" />
                                        <path stroke="currentColor" stroke-width="2"
                                            d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                    </svg>
                                    <span class="sr-only">Detalles</span>
                                </a>
                                <div id="detalle-${data}" role="tooltip"
                                    class="absolute z-10 invisible inline-block w-54 text-sm font-light text-gray-500 transition-opacity duration-300 bg-white border border-gray-200 rounded-lg shadow-sm opacity-0 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-400">
                                    <div class="p-2 space-y-2">
                                        <h6 class="font-semibold mb-0 text-gray-900 dark:text-black">Detalles</h6>
                                    </div>
                                </div>

                                <a href="${editLink}"
                                    data-id="${data}"
                                    data-popover-target="editar${data}" data-popover-placement="bottom"
                                    class="edit-item text-white mb-1 bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm p-2.5 text-center inline-flex items-center me-0 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                                    <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                        width="24" height="24" fill="none" viewBox="0 0 24 24">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M10.779 17.779 4.36 19.918 6.5 13.5m4.279 4.279 8.364-8.643a3.027 3.027 0 0 0-2.14-5.165 3.03 3.03 0 0 0-2.14.886L6.5 13.5m4.279 4.279L6.499 13.5m2.14 2.14 6.213-6.504M12.75 7.04 17 11.28" />
                                    </svg>
                                    <span class="sr-only">Editar</span>
                                </a>
                                <div id="editar${data}" role="tooltip"
                                    class="absolute z-10 invisible inline-block w-54 text-sm font-light text-gray-500 transition-opacity duration-300 bg-white border border-gray-200 rounded-lg shadow-sm opacity-0 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-400">
                                    <div class="p-2 space-y-2">
                                        <h6 class="font-semibold mb-0 text-gray-900 dark:text-black">Editar</h6>
                                    </div>
                                </div>

                                <a href="#"
                                    data-popover-target="eliminar-${data}" data-popover-placement="left"
                                    data-id="${data}"
                                    class="delete-item mb-1 text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm p-2.5 text-center inline-flex items-center me-0 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                                    <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                        width="24" height="24" fill="none" viewBox="0 0 24 24">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                            stroke-width="2" d="m15 9-6 6m0-6 6 6m6-3a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                    </svg>
                                    <span class="sr-only">Eliminar</span>
                                </a>
                                <div id="eliminar-${data}" role="tooltip"
                                    class="absolute z-10 invisible inline-block w-54 text-sm font-light text-gray-500 transition-opacity duration-300 bg-white border border-gray-200 rounded-lg shadow-sm opacity-0 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-400">
                                    <div class="p-2 space-y-2">
                                        <h6 class="font-semibold mb-0 text-gray-900 dark:text-black">Eliminar</h6>
                                    </div>
                                </div>
                            `;
                        } else {
                            // COLOCAR EL BOTÓN PARA ACTIVAR EL PRODUCTO
                            return `

                            `;
                        }
                    }
                }
            ],
            order: [[3, 'asc']], // la columna 10 es la de 'fecha' según tu código
            columnDefs: [
                {
                    targets: 3, // índice de la columna fecha
                    type: 'date'
                }
            ],
            drawCallback: function(settings) {
                // Inicializar popovers para los elementos
                $('[data-popover-target]').each(function() {
                    const triggerEl = $(this);
                    const tooltipId = `#${triggerEl.attr('data-popover-target')}`;
                    const tooltipEl = $(tooltipId);

                    // Asegúrate de que la clase tooltip-content esté presente
                    tooltipEl.addClass('tooltip-content');

                    // Mostrar el tooltip al pasar el cursor
                    triggerEl.hover(
                        function() {
                            tooltipEl.removeClass('invisible opacity-0').addClass('visible opacity-100');
                        },
                        function() {
                            tooltipEl.removeClass('visible opacity-100').addClass('invisible opacity-0');
                        }
                    );
                });
            }
        });
    }
</script>

@if (Session::has('id'))
    <script type="text/javascript">
        var id = {!! json_encode(session('id')) !!};
        setTimeout(function() {
            window.open("{{ url('/ticket-ponchado') }}/" + id, '_blank');
        }, 200);
        <?php Session::forget('id'); ?>
    </script>
@endif


@stop
