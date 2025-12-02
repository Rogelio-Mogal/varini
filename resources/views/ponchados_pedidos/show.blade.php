@extends('layouts.app', [
    'breadcrumb' => [
        [
            'name' => 'Home',
            'url' => route('dashboard'),
        ],
        [
            'name' => 'Pedidos / Ponchados',
            'url' => route('admin.pedidos.ponchados.index'),
        ],
        [
            'name' => $pedido->referencia_cliente,
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

@php
    use Carbon\Carbon;
@endphp

@section('content')
    <div class="shadow-md rounded-lg p-4 dark:bg-gray-800">
        <div id="accordion-open" data-accordion="open">
            @foreach ($pedidos as $pedido)
                @if ($pedido->activo == 0)
                    <div class="col-span-full flex justify-center items-center">
                        <span class="text-red-600 text-2xl font-bold">REGISTRO ELIMINADO</span>
                    </div>
                @endif

                <h2 id="accordion-open-heading-{{ $pedido->id }}">
                    <button type="button"
                        class="flex items-center justify-between w-full p-3 font-medium rtl:text-right text-gray-500 border border-b-0 border-gray-200 rounded-t-xl focus:ring-4 focus:ring-gray-200 dark:focus:ring-gray-800 dark:border-gray-700 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800 gap-3"
                        data-accordion-target="#accordion-open-body-{{ $pedido->id }}" aria-expanded="false" aria-controls="accordion-open-body-{{ $pedido->id }}">
                        <span class="flex items-center">
                            Ponchado: {{ $pedido->ponchado->nombre ?? 'Sin nombre' }} |
                            Fecha estimada de entrega: {{ \Carbon\Carbon::parse($pedido->fecha_estimada_entrega)->format('d/m/Y') }} |
                            Estatus: {{$pedido->estatus}}

                        </span>
                        <svg data-accordion-icon class="w-3 h-3 rotate-180 shrink-0" aria-hidden="false"
                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 5 5 1 1 5" />
                        </svg>
                    </button>
                </h2>
                <div id="accordion-open-body-{{ $pedido->id }}" class="hidden" aria-labelledby="accordion-open-heading-{{ $pedido->id }}">
                    <div class="p-3 border border-b-0 border-gray-200 dark:border-gray-700 dark:bg-gray-900 text-2xl">
                        <div class="grid lg:grid-cols-12 md:grid-cols-12 sm:grid-cols-12 gap-1">
                            {{-- Columna derecha (9) --}}
                            <div class="sm:col-span-12 md:col-span-8 lg:col-span-8 grid grid-cols-12 gap-0">
                                <div class="sm:col-span-12 lg:col-span-6 md:col-span-6">
                                    <p><strong>Cliente:</strong> {{ $pedido->cliente->full_name }}</p>
                                </div>

                                <div class="sm:col-span-12 lg:col-span-6 md:col-span-6">
                                    <p><strong>Ponchado:</strong> {{ $pedido->ponchado->nombre ?? 'Sin nombre' }}</p>
                                </div>

                                <div class="sm:col-span-12 lg:col-span-4 md:col-span-4">
                                    <p><strong>Prenda:</strong> {{ $pedido->prenda }}</p>
                                </div>

                                <div class="sm:col-span-12 lg:col-span-4 md:col-span-4">
                                    <p><strong>Ubicación:</strong> {{ $pedido->clasificacionUbicacion->nombre }}</p>
                                </div>

                                <div class="sm:col-span-12 lg:col-span-4 md:col-span-4">
                                    <p><strong>Piezas:</strong> {{ $pedido->cantidad_piezas }}</p>
                                </div>

                                <div class="sm:col-span-12 lg:col-span-3 md:col-span-3">
                                    <p><strong>Puntadas:</strong> {{ number_format($pedido->ponchado->puntadas, 2, '.', ',') }} </p>
                                </div>

                                <div class="sm:col-span-12 lg:col-span-3 md:col-span-3">
                                    <p><strong>Alto:</strong> {{ $pedido->ponchado->ancho }} </p>
                                </div>
                                <div class="sm:col-span-12 lg:col-span-3 md:col-span-3">
                                    <p><strong>Largo:</strong> {{ $pedido->ponchado->largo }} </p>
                                </div>
                                <div class="sm:col-span-12 lg:col-span-3 md:col-span-3">
                                    <p><strong>Aro:</strong> {{ $pedido->ponchado->aro }} </p>
                                </div>

                                <div class="sm:col-span-12 lg:col-span-12 md:col-span-12">
                                    <p><strong>Nota poncahdo:</strong> {{ $pedido->ponchado->nota }}</p>
                                </div>
                                <div class="sm:col-span-12 lg:col-span-12 md:col-span-12">
                                    <p><strong>Nota pedido:</strong> {{ $pedido->nota }}</p>
                                </div>
                            </div>
                            {{-- Columna izquierda (3) --}}
                            <div class="sm:col-span-12 md:col-span-4 lg:col-span-4 flex flex-col items-center gap-2">
                                <img src="{{ $pedido->ponchado->imagen_1 }}"
                                    alt="Imagen ponchado"
                                    class="w-full max-w-xs h-auto object-contain border rounded shadow">

                                @if ($pedido->archivoUrl)
                                    <a href="{{ route('ponchados.descargarArchivo', $pedido->ponchado->id) }}"
                                        class="px-3 py-1 text-sm bg-blue-600 text-white rounded hover:bg-blue-700">
                                        Descargar
                                    </a>
                                @else
                                    <span class="text-red-600 text-sm">Archivo no disponible</span>
                                @endif

                                {{-- Botón Estatus solo si no está Finalizado --}}
                                @if ($pedido->estatus !== 'Finalizado')
                                    <a href="#"
                                        data-popover-target="estatus-{{$pedido->id}}" data-popover-placement="left"
                                        data-id="{{$pedido->id}}"
                                        data-popover-target="detalles{{$pedido->id}}" data-popover-placement="bottom"
                                        class="open-modal edit-item text-white mb-1 bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm p-2.5 text-center inline-flex items-center me-0 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                                        <svg class="w-5 h-5 text-white-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.5 11.5 11 14l4-4m6 2a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                                        </svg>

                                        <span class="sr-only">Estatus</span>
                                    </a>
                                @endif
                                <div id="estatus-{{$pedido->id}}" role="tooltip"
                                    class="absolute z-10 invisible inline-block w-54 text-sm font-light text-gray-500 transition-opacity duration-300 bg-white border border-gray-200 rounded-lg shadow-sm opacity-0 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-400">
                                    <div class="p-2 space-y-2">
                                        <h6 class="font-semibold mb-0 text-gray-900 dark:text-black">Cambio de estatus</h6>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Tabla de detalles -->
                        <div id="accordion-fondos-{{ $pedido->id }}" data-accordion="open" class="mt-4">
                            @foreach ($pedido->ponchado->detalles->groupBy('color_tela') as $fondoTela => $items)
                                <h2 id="accordion-fondos-heading-{{ $pedido->id }}-{{ Str::slug($fondoTela) }}">
                                    <button type="button"
                                        class="flex items-center justify-between w-full p-2 font-medium text-gray-600 border border-b-0 border-gray-200 rounded-t-lg focus:ring-2 focus:ring-indigo-300 dark:focus:ring-gray-700 dark:border-gray-700 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800"
                                        data-accordion-target="#accordion-fondos-body-{{ $pedido->id }}-{{ Str::slug($fondoTela) }}"
                                        aria-expanded="false"
                                        aria-controls="accordion-fondos-body-{{ $pedido->id }}-{{ Str::slug($fondoTela) }}">
                                        <span>Fondo de Tela: {{ $fondoTela }}</span>
                                        <svg data-accordion-icon class="w-3 h-3 shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none"
                                            viewBox="0 0 10 6" aria-hidden="true">
                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 5 5 1 1 5" />
                                        </svg>
                                    </button>
                                </h2>
                                <div id="accordion-fondos-body-{{ $pedido->id }}-{{ Str::slug($fondoTela) }}"
                                    class="hidden"
                                    aria-labelledby="accordion-fondos-heading-{{ $pedido->id }}-{{ Str::slug($fondoTela) }}">
                                    <div class="p-3 border border-b-0 border-gray-200 dark:border-gray-700">
                                        <table class="w-full border border-gray-300 my-2 text-2xl">
                                            <thead class="bg-indigo-200">
                                                <tr>
                                                    <th class="px-3 py-2">#</th>
                                                    <th class="px-3 py-2">Color</th>
                                                    <th class="px-3 py-2">Código</th>
                                                    <th class="px-3 py-2">Otro</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @php $num = 1; @endphp
                                                @foreach ($items as $fila)
                                                    <tr class="border-t">
                                                        <td class="px-3 py-2 text-center font-bold text-xl">{{ $num++ }}</td>
                                                        <td class="px-3 py-2">{{ $fila->color }}</td>
                                                        <td class="px-3 py-2">{{ $fila->codigo }}</td>
                                                        <td class="px-3 py-2">{{ $fila->otro }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endforeach
            @include('ponchados_pedidos._modal_edita_estatus_pedidos')
        </div>
    </div>
@endsection

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
            var rolesTable = new DataTable('#compras_detalles', {
                responsive: true,
                "language": {
                    "url": "{{ asset('/json/i18n/es_es.json') }}"
                },
            });

            
            // ✅ Listener para botones con clase .open-modal
            $(document).on('click', '.open-modal', function (e) {
                e.preventDefault();

                let id = $(this).data('id'); // id del ponchado
                showModal(id); // reutilizamos tu función existente
            });

            // ✅ Función para mostrar modal (ya la tenías, solo la dejamos limpia)
            function showModal(id) {
                var modal = $('#edit-modal-status');
                var updateUrl = "{{ route('admin.pedidos.ponchados.update', ':id') }}".replace(':id', id);
                modal.find('form').attr('action', updateUrl);
                modal.show();
                cambiaEstatus(id);
            }

            // ✅ Listener para cerrar modal
            $('.close-modal').on('click', function () {
                $(this).closest('.modal').hide();
            });

            // ✅ DataTable responsive event (mantengo el tuyo)
            rolesTable.on('responsive-display', function (e, datatable, row, showHide, update) {
                var rowData = row.data();
                if (showHide) {
                    var id = rowData[0];
                    $('.open-modal', row.node()).on('click', function () {
                        showModal(id);
                    });
                }
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
        });
    </script>
@stop
