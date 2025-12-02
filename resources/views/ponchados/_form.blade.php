<x-validation-errors class="mb-4" />

<div class="grid lg:grid-cols-12 md:grid-cols-12 sm:grid-cols-12 gap-2">
    <div class="sm:col-span-12 lg:col-span-7 md:col-span-12">
        <label for="nombre" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nombre</label>
        <input type="text" id="nombre" name="nombre" required
            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
            placeholder="Nombre" value="{{ old('nombre', $ponchado->nombre) }}" />
    </div>
    <div class="sm:col-span-12 lg:col-span-5 md:col-span-5">
        <label for="clasificacion_ubicaciones_id" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Clasificación</label>
        <div class="input-group">
            <select id="clasificacion_ubicaciones_id" name="clasificacion_ubicaciones_id" style="height: 400px !important;"
                class="select2 block w-full mt-1 p-2.5 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                <option value="" disabled @if ($metodo == 'create') selected @endif>-- CLASIFICACIÓN --</option>
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
    <div class="sm:col-span-12 lg:col-span-3 md:col-span-3">
        <label for="puntadas" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
            Puntadas
        </label>
        <input type="number" min="1" step="1" id="puntadas" name="puntadas"
            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
            placeholder="Puntadas"
            value="{{ old('puntadas', $ponchado->puntadas) }}" />
    </div>
    <div class="sm:col-span-12 lg:col-span-3 md:col-span-3">
        <label for="ancho" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
            Alto
        </label>
        <input type="text" id="ancho" name="ancho"
            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
            placeholder="Alto"
            value="{{ old('ancho', $ponchado->ancho) }}" />
    </div>
    <div class="sm:col-span-12 lg:col-span-3 md:col-span-3">
        <label for="largo" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
            Largo
        </label>
        <input type="text" id="largo" name="largo"
            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
            placeholder="Largo"
            value="{{ old('largo', $ponchado->largo) }}" />
    </div>
    <div class="sm:col-span-12 lg:col-span-3 md:col-span-3">
        <label for="aro" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
            Aro
        </label>
        <input type="text" id="aro" name="aro"
            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
            placeholder="Aro"
            value="{{ old('aro', $ponchado->aro) }}" />
    </div>
    <div class="sm:col-span-12 lg:col-span-12 md:col-span-12">
        <label for="nota" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nota adicional</label>
        <textarea id="nota" name="nota" rows="2"
            class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" 
            placeholder="Nota adicional">{{ old('nota', $ponchado->nota) }}</textarea>
    </div>

    <!-- ##### MODULO DE COLORES  #########   -->
    <div class="sm:col-span-12 lg:col-span-12 md:col-span-12">
        @php
            $fondosOld = old('fondos', $fondos ?? [['fondo_tela' => '', 'color' => [], 'codigo' => [], 'otro' => []]]);
        @endphp
        <div id="fondos_container" class="col-span-12 grid grid-cols-1 md:grid-cols-1 lg:grid-cols-1 gap-6">
            @foreach ($fondosOld as $fondoIndex => $fondo)
                <!-- Fondo individual -->
                <div
                    class="bg-white shadow-md rounded-xl p-3 border border-gray-200 fondo-item grid lg:grid-cols-12 md:grid-cols-12 sm:grid-cols-12 gap-2">
                    <div class="sm:col-span-12 lg:col-span-10 md:col-span-10">

                        <label for="fondo_tela" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                            Fondo de Tela
                            <button type="button" class="text-red-600 hover:text-red-800 trash">
                                <svg class="w-6 h-6 text-red-800 dark:text-white" aria-hidden="true"
                                    xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                                    viewBox="0 0 24 24">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M9 5v14m-6-8h6m-6 4h6m4.506-1.494L15.012 12m0 0 1.506-1.506M15.012 12l1.506 1.506M15.012 12l-1.506-1.506M20 19H4a1 1 0 0 1-1-1V6a1 1 0 0 1 1-1h16a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1Z" />
                                </svg>
                            </button>
                        </label>
                        <input type="text" id="fondo_tela" name="fondos[{{ $fondoIndex }}][fondo_tela]"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                            placeholder="Fondo de tela" value="{{ $fondo['fondo_tela'] }}" />
                    </div>

                    <div class="sm:col-span-12 lg:col-span-2 md:col-span-2">
                        <label for="cantidad_hilos" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                            Cantidad de Hilos
                        </label>
                        <div class="flex gap-1">
                            <input type="number" min="1" id="cantidad_hilos_0"
                                class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2.5"
                                placeholder="0">
                            <button type="button" data-target-table="item_table_0" data-index="0"
                                class="btn-generar-hilos text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-3 py-2">
                                Ok
                            </button>
                        </div>
                    </div>

                    <div class="sm:col-span-12 lg:col-span-12 md:col-span-12">
                        <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                            <table id="item_table_0"
                                class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                                <thead
                                    class="text-xs text-gray-900 uppercase bg-indigo-200 dark:bg-gray-700 dark:text-gray-400 ">
                                    <tr>
                                        <th scope="col" class="px-6 py-3">Color</th>
                                        <th scope="col" class="px-6 py-3">Código</th>
                                        <th scope="col" class="px-6 py-3">Otro</th>
                                        <th scope="col" class="px-6 py-3">Opciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($fondo['color'] as $i => $color)
                                        <tr>
                                            <td class="px-6 py-3">
                                                <input type="text" name="fondos[{{ $fondoIndex }}][color][]" value="{{ $color }}"
                                                    class="bg-gray-50 border border-gray-300 rounded-lg p-2.5 w-full">
                                            </td>
                                            <td class="px-6 py-3">
                                                <input type="text" name="fondos[{{ $fondoIndex }}][codigo][]" value="{{ $fondo['codigo'][$i] ?? '' }}"
                                                    class="bg-gray-50 border border-gray-300 rounded-lg p-2.5 w-full">
                                            </td>
                                            <td class="px-6 py-3">
                                                <input type="text" name="fondos[{{ $fondoIndex }}][otro][]" value="{{ $fondo['otro'][$i] ?? '' }}"
                                                    class="bg-gray-50 border border-gray-300 rounded-lg p-2.5 w-full">
                                            </td>
                                            <td class="px-6 py-3">
                                                <button type="button" class="text-red-600 hover:text-red-800 eliminar-fila">Eliminar</button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endforeach
            <!-- Botón para agregar nuevo fondo -->
            <div class="col-span-full flex justify-center lg:col-span-1">
                <button type="button"
                    class="tr_clone_add text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5">
                    Agregar Nuevo Fondo
                </button>
            </div>
        </div>
    </div>
    <!-- ##### FIN MODULO DE COLORES  #########   -->

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
                    Archivo
                @elseif($metodo == 'edit')
                    Actualizar archivo
                @endif
            </span>
            <input type="file" name="archivo" id="archivo" class="hidden" accept=".dst, .DST">
        </label>
    
        {{-- Mostrar nombre del archivo seleccionado --}}
        <div id="archivoNombre" class="text-sm text-gray-700 mt-2">
            @if ($metodo == 'edit' && $ponchado->archivo)
                Archivo actual: <strong>{{ basename($ponchado->archivo) }}</strong>
            @endif
        </div>
    </div>
    
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
        const detalles = @json($detalle);
        
        //GENERA TABLAS DE COLORES-PONCHADOS (POR VALIDACIÓN)


        // GENERA LA CANTIDAD DE HILOS:
        document.addEventListener('DOMContentLoaded', function () {
            let fondoIndex = 0; // control del índice

            // Botón para agregar nuevo fondo
            /*document.querySelector('.tr_clone_add').addEventListener('click', function () {
                fondoIndex++;

                const primerFondo = document.querySelector('.fondo-item'); // primer bloque
                const nuevoFondo = primerFondo.cloneNode(true);

                // Limpiar valores
                nuevoFondo.querySelectorAll('input').forEach(input => {
                    input.value = '';
                });

                // Actualizar nombres e IDs dentro del clon
                nuevoFondo.querySelectorAll('[name]').forEach(el => {
                    el.name = el.name.replace(/\[\d+\]/, `[${fondoIndex}]`);
                });

                nuevoFondo.querySelectorAll('[id]').forEach(el => {
                    if (el.id.includes('cantidad_hilos_')) {
                        el.id = `cantidad_hilos_${fondoIndex}`;
                    }
                    if (el.id.includes('item_table_')) {
                        el.id = `item_table_${fondoIndex}`;
                    }
                });

                // Actualizar data attributes
                nuevoFondo.querySelectorAll('[data-index]').forEach(el => {
                    el.setAttribute('data-index', fondoIndex);
                });
                nuevoFondo.querySelectorAll('[data-target-table]').forEach(el => {
                    el.setAttribute('data-target-table', `item_table_${fondoIndex}`);
                });

                // Vaciar tbody de hilos
                nuevoFondo.querySelector('tbody').innerHTML = '';

                // Insertar el clon antes del botón
                const container = document.getElementById('fondos_container');
                container.insertBefore(nuevoFondo, container.lastElementChild);
            });*/

            document.querySelector('.tr_clone_add').addEventListener('click', function () {
                const container = document.getElementById('fondos_container');

                // Obtener el último bloque antes del botón
                const ultimoFondo = container.querySelectorAll('.fondo-item');
                const fondoOriginal = ultimoFondo[ultimoFondo.length - 1];

                // Incrementar índice global
                fondoIndex++;

                // Clonar el último bloque
                const nuevoFondo = fondoOriginal.cloneNode(true);

                // Guardar valores de hilos actuales
                const valoresHilos = [];
                nuevoFondo.querySelectorAll('tbody tr').forEach(tr => {
                    const color = tr.querySelector('input[name*="[color]"]')?.value || '';
                    const codigo = tr.querySelector('input[name*="[codigo]"]')?.value || '';
                    const otro = tr.querySelector('input[name*="[otro]"]')?.value || '';
                    valoresHilos.push({ color, codigo, otro });
                });
                // Limpiar todos los inputs excepto los hilos
                nuevoFondo.querySelectorAll('input').forEach(input => {
                    if (!input.name.includes('[color]') && !input.name.includes('[codigo]') && !input.name.includes('[otro]')) {
                        input.value = '';
                    }
                });

                // Actualizar nombres con el nuevo índice
                nuevoFondo.querySelectorAll('[name]').forEach(el => {
                    el.name = el.name.replace(/\[\d+\]/, `[${fondoIndex}]`);
                });

                // Actualizar IDs
                nuevoFondo.querySelectorAll('[id]').forEach(el => {
                    if (el.id.includes('cantidad_hilos_')) el.id = `cantidad_hilos_${fondoIndex}`;
                    if (el.id.includes('item_table_')) el.id = `item_table_${fondoIndex}`;
                });

                // Actualizar data attributes
                nuevoFondo.querySelectorAll('[data-index]').forEach(el => el.setAttribute('data-index', fondoIndex));
                nuevoFondo.querySelectorAll('[data-target-table]').forEach(el => el.setAttribute('data-target-table', `item_table_${fondoIndex}`));

                // Insertar antes del botón
                container.insertBefore(nuevoFondo, container.lastElementChild);

                // Reasignar valores de hilos
                const tbodyNuevo = nuevoFondo.querySelector('tbody');
                tbodyNuevo.innerHTML = ''; // Limpiar filas existentes clonadas
                valoresHilos.forEach(v => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td class="px-6 py-3">
                            <input type="text" name="fondos[${fondoIndex}][color][]" value="${v.color}" class="bg-gray-50 border border-gray-300 rounded-lg p-2.5 w-full">
                        </td>
                        <td class="px-6 py-3">
                            <input type="text" name="fondos[${fondoIndex}][codigo][]" value="${v.codigo}" class="bg-gray-50 border border-gray-300 rounded-lg p-2.5 w-full">
                        </td>
                        <td class="px-6 py-3">
                            <input type="text" name="fondos[${fondoIndex}][otro][]" value="${v.otro}" class="bg-gray-50 border border-gray-300 rounded-lg p-2.5 w-full">
                        </td>
                        <td class="px-6 py-3">
                            <button type="button" class="text-red-600 hover:text-red-800 eliminar-fila">Eliminar</button>
                        </td>
                    `;
                    tbodyNuevo.appendChild(row);
                });

                /*
                // Limpiar valores de inputs
                //nuevoFondo.querySelectorAll('input').forEach(input => {
                //    input.value = '';
                //});

                // Actualizar nombres con el nuevo índice
                nuevoFondo.querySelectorAll('[name]').forEach(el => {
                    el.name = el.name.replace(/\[\d+\]/, `[${fondoIndex}]`);
                });

                // Actualizar IDs
                nuevoFondo.querySelectorAll('[id]').forEach(el => {
                    if (el.id.includes('cantidad_hilos_')) {
                        el.id = `cantidad_hilos_${fondoIndex}`;
                    }
                    if (el.id.includes('item_table_')) {
                        el.id = `item_table_${fondoIndex}`;
                    }
                });

                // Actualizar data attributes
                nuevoFondo.querySelectorAll('[data-index]').forEach(el => {
                    el.setAttribute('data-index', fondoIndex);
                });
                nuevoFondo.querySelectorAll('[data-target-table]').forEach(el => {
                    el.setAttribute('data-target-table', `item_table_${fondoIndex}`);
                });

                // Limpiar filas de la tabla
                //nuevoFondo.querySelector('tbody').innerHTML = '';

                // Insertar antes del botón
                container.insertBefore(nuevoFondo, container.lastElementChild);
                */
            });

            // Delegación para generar hilos
            document.addEventListener('click', function (e) {
                if (e.target.classList.contains('btn-generar-hilos')) {
                    const index = e.target.getAttribute('data-index');
                    const tableId = e.target.getAttribute('data-target-table');
                    const cantidadInput = document.getElementById(`cantidad_hilos_${index}`);
                    const cantidad = parseInt(cantidadInput.value) || 0;

                    if (cantidad > 0) {
                        const tbody = document.querySelector(`#${tableId} tbody`);

                        for (let i = 0; i < cantidad; i++) {
                            const row = document.createElement('tr');
                            row.innerHTML = `
                                <td class="px-6 py-3">
                                    <input type="text" name="fondos[${index}][color][]" 
                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg p-2.5 w-full" 
                                        placeholder="Color">
                                </td>
                                <td class="px-6 py-3">
                                    <input type="text" name="fondos[${index}][codigo][]" 
                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg p-2.5 w-full" 
                                        placeholder="Código">
                                </td>
                                <td class="px-6 py-3">
                                    <input type="text" name="fondos[${index}][otro][]" 
                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg p-2.5 w-full" 
                                        placeholder="Otro">
                                </td>
                                <td class="px-6 py-3">
                                    <button type="button" class="text-red-600 hover:text-red-800 eliminar-fila">Eliminar</button>
                                </td>
                            `;
                            tbody.appendChild(row);
                        }
                        cantidadInput.value = '';
                    } else {
                        alert('Por favor ingrese un número válido.');
                    }
                }

                // Eliminar fila individual
                if (e.target.classList.contains('eliminar-fila')) {
                    e.target.closest('tr').remove();
                }

                // Eliminar fondo (excepto el primero)
                if (e.target.closest('.trash')) {
                    const fondoCard = e.target.closest('.fondo-item');
                    const fondos = document.querySelectorAll('.fondo-item');

                    if (fondoCard.isSameNode(fondos[0])) {
                        alert('No puedes eliminar el primer fondo.');
                        return;
                    }
                    fondoCard.remove();
                }
            });
        });


        /*
        document.addEventListener('click', function (e) {
            if (e.target.classList.contains('btn-generar-hilos')) {
                const index = e.target.getAttribute('data-index');
                const tableId = e.target.getAttribute('data-target-table');
                const cantidadInput = document.getElementById(`cantidad_hilos_${index}`);
                const cantidad = parseInt(cantidadInput.value) || 0;

                if (cantidad > 0) {
                    const tbody = document.querySelector(`#${tableId} tbody`);

                    for (let i = 0; i < cantidad; i++) {
                        const row = document.createElement('tr');
                        row.innerHTML = `
                            <td class="px-6 py-3">
                                <input type="text" name="fondos[${index}][color][]" 
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg p-2.5 w-full" 
                                    placeholder="Color">
                            </td>
                            <td class="px-6 py-3">
                                <input type="text" name="fondos[${index}][codigo][]" 
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg p-2.5 w-full" 
                                    placeholder="Código">
                            </td>
                            <td class="px-6 py-3">
                                <input type="text" name="fondos[${index}][OTRO][]" 
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg p-2.5 w-full" 
                                    placeholder="oTRO">
                            </td>
                            <td class="px-6 py-3">
                                <button type="button" class="text-red-600 hover:text-red-800 eliminar-fila">Eliminar</button>
                            </td>
                        `;
                        tbody.appendChild(row);
                    }

                    cantidadInput.value = ''; // Limpiar input después de generar
                } else {
                    alert('Por favor ingrese un número válido.');
                }
            }

            // Eliminar fila individual
            if (e.target.classList.contains('eliminar-fila')) {
                e.target.closest('tr').remove();
            }
        });
        */

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

        //MUESTRA EL NOMBRE DEL ARCHIVO
        document.getElementById('archivo').addEventListener('change', function (event) {
            const fileName = event.target.files[0]?.name || 'Ningún archivo seleccionado';
            document.getElementById('archivoNombre').innerText = 'Archivo seleccionado: ' + fileName;
        });

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

            $('#clasificacion_ubicaciones_id').select2({
                placeholder: "-- CLASIFICACIÓN --",
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

            /*
            // Esto lo colocas solo una vez al inicio
            $(document).on('click', '.remove-tr', function() {
                $(this).closest('tr').remove();
            });
            */

            // PARA LOS COLORES DE LOS HILOS




            /*
            // Eliminar fila de hilo (mínimo una)
            $(document).on('click', '.remove-hilo', function() {
                const $row = $(this).closest('tr');
                const $tbody = $row.closest('tbody');
                const $dataRows = $tbody.find('tr').not(':last'); // todas menos el botón "Agregar"

                if ($dataRows.length > 1) {
                    $row.remove();
                }
            });

            // Evitar eliminar el primer fondo
            $(document).on('click', '.trash', function() {
                const $fondoCard = $(this).closest('.fondo-item');
                const $fondos = $('#fondos_container').find('.fondo-item');

                if ($fondoCard.is($fondos.first())) {
                    alert('No puedes eliminar el primer fondo.');
                    return;
                }

                $fondoCard.remove();
            });
            */


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
        });
    </script>
@stop
