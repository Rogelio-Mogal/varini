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

@stop

@php
    use Carbon\Carbon;
@endphp

@section('content')
    <div class="shadow-md rounded-lg p-4 dark:bg-gray-800">
        <div class="grid lg:grid-cols-12 md:grid-cols-12 sm:grid-cols-12 gap-2">
            @if ($pedido->activo == 0)
                <div class="sm:col-span-12 lg:col-span-12 md:col-span-12 flex justify-center items-center">
                    <span class="text-red-600 text-2xl font-bold">REGISTRO ELIMINADO</span>
                </div>
            @endif
            <div class="sm:col-span-12 lg:col-span-3 md:col-span-3">
                <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                    Cliente
                </label>
                <p class="mb-3 text-lg text-gray-900 md:text-xl dark:text-gray-800">
                    {{ $pedido->cliente->full_name }}
                </p>
            </div>
            <div class="sm:col-span-12 lg:col-span-3 md:col-span-3">
                <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                    Ubicación
                </label>
                <p class="mb-3 text-lg text-gray-900 md:text-xl dark:text-gray-800">
                    {{ $pedido->clasificacionUbicacion->nombre }}
                </p>
            </div>
            <div class="sm:col-span-12 lg:col-span-2 md:col-span-2">
                <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                    Piezas
                </label>
                <p class="mb-3 text-lg text-gray-900 md:text-xl dark:text-gray-800">
                    {{ $pedido->cantidad_piezas }}
                </p>
            </div>

            <div class="sm:col-span-12 lg:col-span-2 md:col-span-2">
                <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                    Fecha estimada de entrega
                </label>
                <p class="mb-3 text-lg text-gray-900 md:text-xl dark:text-gray-800">
                    {{ Carbon::parse($pedido->fecha_estimada_entrega)->format('d/m/Y') }}
                </p>
            </div>

            <div class="sm:col-span-12 lg:col-span-2 md:col-span-2">
                <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                    Archivo del Ponchado
                </label>

                @if ($archivoUrl)
                    {{--
                    <a href="{{ route('ponchados.descargarArchivo', $pedido->ponchado->id) }}"
                        class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">
                        Descargar archivo
                        <svg class="w-3 h-3 me-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M14.707 7.793a1 1 0 0 0-1.414 0L11 10.086V1.5a1 1 0 0 0-2 0v8.586L6.707 7.793a1 1 0 1 0-1.414 1.414l4 4a1 1 0 0 0 1.416 0l4-4a1 1 0 0 0-.002-1.414Z"/>
                        <path d="M18 12h-2.55l-2.975 2.975a3.5 3.5 0 0 1-4.95 0L4.55 12H2a2 2 0 0 0-2 2v4a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-4a2 2 0 0 0-2-2Zm-3 5a1 1 0 1 1 0-2 1 1 0 0 1 0 2Z"/>
                        </svg>
                    </a> --}}


                    <a href="{{ route('ponchados.descargarArchivo', $pedido->ponchado->id) }}"
                        class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-900 bg-white border border-gray-200 rounded-s-lg rounded-e-lg hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-2 focus:ring-blue-700 focus:text-blue-700 dark:bg-gray-800 dark:border-gray-700 dark:text-white dark:hover:text-white dark:hover:bg-gray-700 dark:focus:ring-blue-500 dark:focus:text-white">
                        <svg class="w-3 h-3 me-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                            viewBox="0 0 20 20">
                            <path
                                d="M14.707 7.793a1 1 0 0 0-1.414 0L11 10.086V1.5a1 1 0 0 0-2 0v8.586L6.707 7.793a1 1 0 1 0-1.414 1.414l4 4a1 1 0 0 0 1.416 0l4-4a1 1 0 0 0-.002-1.414Z" />
                            <path
                                d="M18 12h-2.55l-2.975 2.975a3.5 3.5 0 0 1-4.95 0L4.55 12H2a2 2 0 0 0-2 2v4a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-4a2 2 0 0 0-2-2Zm-3 5a1 1 0 1 1 0-2 1 1 0 0 1 0 2Z" />
                        </svg>
                        Descargar archivo
                    </a>
                @else
                    <p class="text-red-600 font-semibold text-sm">Archivo no encontrado</p>
                @endif
            </div>



            <div class="sm:col-span-12 lg:col-span-8 md:col-span-8">
                <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                    Nota
                </label>
                <p class="mb-3 text-lg text-gray-900 md:text-xl dark:text-gray-800">
                    {{ $pedido->nota }}
                </p>

            </div>

            <div class="sm:col-span-12 lg:col-span-4 md:col-span-4">
                <figure class="flex justify-center items-center w-full h-full">
                    <img class="object-cover object-center max-w-full max-h-full"
                        src="{{ $pedido->ponchado->imagen_1 ? asset('' . $pedido->ponchado->imagen_1) : '#' }}"
                        alt="" id="imgPreview1">
                </figure>
            </div>




            <div class="sm:col-span-12 lg:col-span-12 md:col-span-12">
                <div id="fondos_container" class="col-span-12 grid grid-cols-1 md:grid-cols-1 lg:grid-cols-1 gap-2">
                    @php $index = 0; @endphp
                    @foreach ($detalle as $fondoTela => $items)
                        <div
                            class="bg-white shadow-md rounded-xl p-3 border border-gray-200 fondo-item grid lg:grid-cols-12 md:grid-cols-12 sm:grid-cols-12 gap-2">
                            <div class="sm:col-span-12 lg:col-span-12 md:col-span-12">
                                <h4 class="text-3xl font-extrabold dark:text-white">Fondo de Tela: {{ $fondoTela }} </h4>
                            </div>
                            <div class="sm:col-span-12 lg:col-span-12 md:col-span-12">
                                <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                                    <table id="item_table_{{ $index }}"
                                        class="w-full text-sm text-left text-gray-900">

                                        <thead class="text-xs text-gray-900 uppercase bg-indigo-200">
                                            <tr>
                                                <th class="px-6 py-3 text-2xl font-extrabold dark:text-white">Color</th>
                                                <th class="px-6 py-3 text-2xl font-extrabold dark:text-white">Código</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($items as $fila)
                                                <tr
                                                    class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 border-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600">
                                                    <td class="px-3 py-2">
                                                        <h4 class="text-2xl font-extrabold dark:text-white">
                                                            {{ $fila->color }}
                                                        </h4>
                                                    </td>
                                                    <td class="px-3 py-2">
                                                        <h4 class="text-2xl font-extrabold dark:text-white">
                                                            {{ $fila->codigo }}
                                                        </h4>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        @php $index++; @endphp
                    @endforeach
                </div>
            </div>



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
        });
    </script>
@stop
