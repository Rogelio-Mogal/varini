@extends('layouts.app', [
    'breadcrumb' => [
        [
            'name' => 'Home',
            'url' => route('dashboard'),
        ],
        [
            'name' => 'Precios ponchados',
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
    <a href="{{ route('admin.precio.ponchado.create') }}"
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

            <!-- Mensaje de carga sobre la tabla -->
            <div id="loadingOverlay" class="absolute inset-0 flex items-center justify-center z-50 hidden">
                {{--
                <div class="relative flex items-center">
                    <!-- Contenedor para el texto de carga -->
                    <div class="text-white text-lg font-bold p-4 bg-gray-900 rounded flex items-center">
                        <svg aria-hidden="true"
                            class="w-8 h-8 text-gray-200 animate-spin dark:text-gray-600 fill-blue-600"
                            viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z"
                                fill="currentColor" />
                            <path
                                d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z"
                                fill="currentFill" />
                        </svg>
                        Procesando
                    </div>
                </div>
                --}}
            </div>

            <table id="ponchados_precios" class="table table-striped " style="width:100%">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Imagen</th>
                        <th>Nombre</th>
                        <th>Cliente</th>
                        <th>Precio</th>
                        <th>Opciones</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
            @include('ponchados_pedidos._modal_edita_pedido')
            @include('ponchados_pedidos._modal_edita_estatus_pedidos')
        </div>
    </div>
</div>


@endsection

@section('js')
<script>
    $(document).ready(function() {
        // Inicializar DataTable
        var table = ponchados();
        let tblPedido;

        // RECARGAR TABLA
        $('#reloadTable').on('click', function() {
            $('#loadingOverlay').removeClass('hidden'); // Mostrar overlay
            table.ajax.reload(function() {
                $('#loadingOverlay').addClass('hidden'); // Ocultar overlay después de recargar
            });
        });
        // Manejar el clic en la opción "Eliminar"
        $('#ponchados_precios').on('click', '.delete-item', function(e) {
            e.preventDefault();
            var id = $(this).data('id');

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
                    //console.log(id);
                    // Solicitud AJAX para eliminar el elemento
                    $.ajax({
                        url: "{{ route('admin.precio.ponchado.destroy', ':id') }}"
                            .replace(':id', id),
                        type: 'POST',
                        data: {
                            "_token": "{{ csrf_token() }}",
                            "_method": "DELETE"
                        },
                        success: function(data) {
                            //console.log('data: '+data);
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

        /*
        // Manejar el clic en el botón para mostrar el modal
        $('#ponchados_precios tbody').on('click', '.open-modal', function() {
            var id = $(this).data('id');
            showModal(id);
        });

        // Función para mostrar el modal
        function showModal(id) {
            var modal = $('#edit-modal-status');
            var updateUrl = "{{ route('admin.precio.ponchado.update', ':id') }}".replace(':id', id);
            modal.find('form').attr('action', updateUrl);
            modal.show();
            precios(id)
        }

        // Manejar el clic en el botón para cerrar el modal
        $('.close-modal').on('click', function() {
            $(this).closest('.modal').hide();
        });

        // Listener for details control
        table.on('responsive-display', function(e, datatable, row, showHide, update) {
            var rowData = row.data();
            if (showHide) {
                var id = rowData[0];
                $('.open-modal', row.node()).on('click', function() {
                    showModal(id);
                });
            }
        });
        */

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
            var showUrl = "{{ route('admin.precio.ponchado.show', ':id') }}";
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
        if ($.fn.DataTable.isDataTable('#ponchados_precios')) {
            $('#ponchados_precios').DataTable().destroy();
            $('#ponchados_precios').empty(); // 🔥 importante
        }

        const postData = {
            _token: $('input[name=_token]').val(),
            origen: 'ponchados.precios',
        };
        var editUrl = "{{ route('admin.precio.ponchado.edit', ':id') }}";
        var showUrl = "{{ route('admin.precio.ponchado.show', ':id') }}";

        // Inicializar DataTable
        return $('#ponchados_precios').DataTable({
            "language": {
                "url": "{{ asset('/json/i18n/es_es.json') }}"
            },
            responsive: true,
            //retrieve: true,
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
                        //console.log('data: '+data);
                        return `
                        <img class="h-auto max-w-20 sm:max-w-20 md:max-w-40 lg:max-w-40 object-cover object-center" src="${data}" alt="Imagen">`;
                    }
                },
                {
                    data: 'nombre',
                    name: 'nombre'
                },
                {
                    data: 'cliente',
                    name: 'cliente'
                },

                {
                    data: 'precio',
                    render: function(data, type, row) {
                        // Verificar si el dato es nulo, indefinido o vacío
                        if (data === null || data === undefined || data === '') {
                            return '$0.00';  // Valor por defecto si no hay dato
                        }
                        // Formatear el número con separadores de miles y decimales
                        var formattedNumber = $.fn.dataTable.render.number(',', '.', 2).display(data);
                        // Agregar el símbolo de pesos al valor formateado
                        return '$ ' + formattedNumber;
                    },
                    defaultContent: '$0.00'
                },


                {
                    data: 'id',
                    render: function(data, type, row) {
                        var editLink = editUrl.replace(':id', data);
                        var sowLink = showUrl.replace(':id', data);
                        var isActive = row.activo;


                            return `
                                <a href="${editLink}"
                                    data-id="${data}"
                                    data-popover-target="editar-${data}" data-popover-placement="left"
                                    class="open-modal edit-item text-white mb-1 bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm p-2.5 text-center inline-flex items-center me-0 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                                    <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                        width="24" height="24" fill="none" viewBox="0 0 24 24">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M10.779 17.779 4.36 19.918 6.5 13.5m4.279 4.279 8.364-8.643a3.027 3.027 0 0 0-2.14-5.165 3.03 3.03 0 0 0-2.14.886L6.5 13.5m4.279 4.279L6.499 13.5m2.14 2.14 6.213-6.504M12.75 7.04 17 11.28" />
                                    </svg>
                                    <span class="sr-only">Editar-</span>
                                </a>
                                <div id="editar-${data}" role="tooltip"
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

                    }
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
@stop
