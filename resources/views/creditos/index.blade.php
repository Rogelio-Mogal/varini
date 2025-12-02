@extends('layouts.app', [
    'breadcrumb' => [
        [
            'name' => 'Home',
            'url' => route('dashboard'),
        ],
        [
            'name' => 'Créditos',
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

</style>
@stop

@section('content')

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
                        rocesando
                    </div>
                </div>
            </div>

            <table id="creditos" class="table table-striped " style="width:100%">
                <thead>
                    <tr>
                        <th>cliente ID</th>
                        <th>Cliente</th>
                        <th>Crédito</th>
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
        // Inicializar DataTable
        let table = creditos();

        // RECARGAR TABLA
        $('#reloadTable').on('click', function() {
            $('#loadingOverlay').removeClass('hidden'); // Mostrar overlay
            table.ajax.reload(function() {
                $('#loadingOverlay').addClass('hidden'); // Ocultar overlay después de recargar
            });
        });
    });

    function creditos() {
        const postData = {
            _token: $('input[name=_token]').val(),
            origen: 'creditos.index',
        };
        var editUrl = "{{ route('admin.abonos.show', ':id') }}";

        // Inicializar DataTable
        return $('#creditos').DataTable({
            "language": {
                "url": "{{ asset('/json/i18n/es_es.json') }}"
            },
            responsive: true,
            retrieve: true,
            processing: true,
            ajax: {
                url: "{{ route('creditos.index.ajax') }}",
                type: "POST",
                'data': function(d) {
                    d._token = postData._token;
                    d.origen = postData.origen;
                }
            },
            'columns': [{
                    data: 'cliente_id',
                    name: 'cliente_id',
                    visible: false,
                    searchable: false
                },
                {
                    data: 'full_name',
                    name: 'full_name'
                },
                {
                    data: 'total_credito',
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
                    data: 'cliente_id',
                    render: function(data, type, row) {
                        var editLink = editUrl.replace(':id', data);

                        return `
                            <a href="${editLink}"
                                data-id="${data}"
                                data-popover-target="editar-${data}" data-popover-placement="left"
                                class="text-white mb-1 bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm p-2.5 text-center inline-flex items-center me-0 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                                
                                <svg class="w-5 h-5 aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                     stroke-width="2" 
                                     d="M8 17.345a4.76 4.76 0 0 0 2.558 1.618c2.274.589 4.512-.446 4.999-2.31.487-1.866-1.273-3.9-3.546-4.49-2.273-.59-4.034-2.623-3.547-4.488.486-1.865 2.724-2.899 4.998-2.31.982.236 1.87.793 2.538 1.592m-3.879 12.171V21m0-18v2.2"/>
                                </svg>

                                <span class="sr-only">Abonar</span>
                            </a>
                            <div id="editar-${data}" role="tooltip"
                                class="absolute z-10 invisible inline-block w-54 text-sm font-light text-gray-500 transition-opacity duration-300 bg-white border border-gray-200 rounded-lg shadow-sm opacity-0 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-400">
                                <div class="p-2 space-y-2">
                                    <h6 class="font-semibold mb-0 text-gray-900 dark:text-black">Abonar</h6>
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
