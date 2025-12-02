@extends('layouts.app', [
    'breadcrumb' => [
        [
            'name' => 'Home',
            'url' => route('dashboard'),
        ],
        [
            'name' => 'Abonos',
            'url' => route('admin.creditos.index'),
        ],
        [
            'name' => $creditos[0]['full_name'],
        ],
    ],
])

@section('css')

@stop

@php
    use Carbon\Carbon;
@endphp

@section('content')
    <div class="shadow-md rounded-lg p-3 dark:bg-gray-800">
        @php
            // Si solo muestras un cliente, basta con el primero.
            $deudaTotal = $creditos[0]['deuda_credito']; // 1 516.00 en tu ejemplo
            $clienteId = $creditos[0]['cliente_id'];
        @endphp

        <form id="formAbono" action="{{ route('admin.abonos.store') }}" method="POST">
            @csrf
            <div class="grid grid-cols-12 gap-4">
                <!-- Panel formas de pago -->
                <div class="col-span-12 lg:col-span-6 h-full">
                    <!-- Paneles de forma de pago y total -->
                    <div class="bg-white rounded-xl shadow p-4 space-y-1 h-full flex flex-col">
                        <!-- Formas de pago -->
                        <div>
                            <h3 class="font-bold text-green-600 border-b pb-1 mb-3">Forma de pago</h3>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block mb-1 text-sm font-medium text-gray-700">Efectivo</label>
                                    <input type="hidden" name="formas_pago[0][metodo]" value="Efectivo">
                                    <input type="number" name="formas_pago[0][monto]" id="efectivo" step="any"
                                        class="forma-pago w-full border border-gray-300 rounded px-2 py-1 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                                </div>
                                <div>
                                    <label>T. Débito</label>
                                    <input type="hidden" name="formas_pago[1][metodo]" value="TDD">
                                    <input type="number" name="formas_pago[1][monto]" id="debito" step="any"
                                        class="forma-pago w-full border border-gray-300 rounded px-2 py-1 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                                </div>
                                <div>
                                    <label>T. Crédito</label>
                                    <input type="hidden" name="formas_pago[2][metodo]" value="TDC">
                                    <input type="number" name="formas_pago[2][monto]" id="credito" step="any"
                                        class="forma-pago w-full border border-gray-300 rounded px-2 py-1 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                                </div>
                                <div>
                                    <label>Transferencia</label>
                                    <input type="hidden" name="formas_pago[3][metodo]" value="Transferencia">
                                    <input type="number" name="formas_pago[3][monto]" id="transferencia" step="any"
                                        class="forma-pago w-full border border-gray-300 rounded px-2 py-1 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                                </div>
                                <div id="monto_credito_container" class="hidden sm:col-span-12 lg:col-span-2">
                                    <label class="block mb-1 text-sm font-medium text-gray-900 dark:text-white">Monto a
                                        crédito</label>
                                    <input type="number" name="monto_credito" id="monto_credito" step="any"
                                        class="forma-pago bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5"
                                        min="0">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Panel abonar -->
                <div class="col-span-12 lg:col-span-6 h-full">
                    <!-- Paneles de forma de pago y total -->
                    <div class="bg-white rounded-xl shadow p-4 space-y-1 h-full flex flex-col">
                        <!-- Formas de pago -->
                        <div>
                            <h3 class="font-bold text-blue-600 border-b pb-1 mb-3">Abono</h3>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <div
                                        class="flex items-center ps-4 border border-gray-200 rounded-sm dark:border-gray-700">
                                        <input checked id="tipo_monto" type="radio" value="monto" name="tipo_abono"
                                            class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                        <label for="tipo_monto"
                                            class="w-full py-3 ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Por
                                            monto específico</label>
                                    </div>
                                </div>

                                <div>
                                    <div
                                        class="flex items-center ps-4 border border-gray-200 rounded-sm dark:border-gray-700">
                                        <input id="tipo_venta" type="radio" value="venta" name="tipo_abono"
                                            class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                        <label for="tipo_venta"
                                            class="w-full py-3 ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Seleccionar
                                            ventas</label>
                                    </div>
                                </div>

                                <div>
                                    <label class="block mb-1 text-sm font-medium text-gray-700">Total abono</label>
                                    <input type="text" readonly value="0.00" name="total_formas_pago"
                                        id="total_formas_pago"
                                        class="w-full border border-gray-300 rounded px-2 py-1 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                                </div>


                                <div>
                                    <label>Referencia</label>
                                    <input type="number" name="referencia" id="referencia" step="any"
                                        class="w-full border border-gray-300 rounded px-2 py-1 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                                </div>

                                <div class="sm:col-span-12 lg:col-span-2 flex justify-center items-center">
                                    <button type="submit" id="btnAbonar"
                                        class="inline-flex items-center px-6 py-2 text-sm font-medium
                                        text-white bg-green-600 rounded-lg hover:bg-green-700
                                        focus:ring-4 focus:ring-green-300 dark:focus:ring-green-800">
                                        Abonar
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Panel Creditos -->
                <div class="col-span-12 lg:col-span-12 h-full">
                    <div class="bg-white rounded-xl shadow p-4 space-y-0 h-full flex flex-col">
                        <div class="sm:col-span-12 lg:col-span-12 md:col-span-12">
                            <h4 class="text-xl font-bold dark:text-white text-center">VENTAS A CRÉDITO</h4>
                        </div>
                        <div class="sm:col-span-12 lg:col-span-12 md:col-span-12">
                            @foreach ($creditos as $cliente)
                                <h4>
                                    {{ $cliente['full_name'] }} — Total crédito:
                                    ${{ number_format($cliente['total_credito'], 2) }} —
                                    Abonos ${{ number_format($cliente['total_abonado'], 2) }} —
                                    Deuda ${{ number_format($cliente['deuda_credito'], 2) }}
                                </h4>

                                <table id="cotizacion_detalles" class="table table-striped" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th></th>
                                            <th>ID</th>
                                            <th>Folio</th>
                                            <th>Fecha</th>
                                            <th>Total</th>
                                            <th>Saldo</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($cliente['ventas_credito'] as $detalle)
                                            <tr>
                                                <td>
                                                    <input type="checkbox"
                                                        class="check-venta w-4 h-4 text-green-600 bg-gray-100 border-gray-300 rounded-sm focus:ring-green-500 dark:focus:ring-green-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600"
                                                        data-saldo="{{ $detalle['saldo_actual'] }}"
                                                        value="{{ $detalle['venta_id'] }}" name="ventas_seleccionadas[]"
                                                        disabled>
                                                </td>
                                                <td>{{ $detalle['venta_id'] }}</td>
                                                <td>{{ $detalle['folio'] }}</td>
                                                <td>{{ $detalle['fecha'] }}</td>
                                                <td>{{ '$' . number_format($detalle['monto_credito'], 2, '.', ',') }}</td>
                                                <td>{{ '$' . number_format($detalle['saldo_actual'], 2, '.', ',') }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            @endforeach
                        </div>
                    </div>
                </div>




            </div>
            <input type="hidden" id="cliente_id" name="cliente_id" value="{{ $clienteId }}">
        </form>
        <br />
        <div class="grid grid-cols-12 gap-4">
            <!-- Panel Abonos -->
            <div class="col-span-12 lg:col-span-12 h-full">
                <div class="bg-white rounded-xl shadow p-4 space-y-0 h-full flex flex-col">
                    <div class="sm:col-span-12 lg:col-span-12 md:col-span-12">
                        <div class="sm:col-span-12 lg:col-span-12 md:col-span-12">
                            <h4 class="text-xl font-bold dark:text-white text-center">ABONOS</h4>
                        </div>

                        <table id="tabla-abonos" class="table table-bordered table-striped w-full">
                            <thead>
                                <tr>
                                    <th></th> <!-- columna para expandir -->
                                    <th>Folio</th>
                                    <th>Fecha</th>
                                    <th>Total Abonado</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($creditos as $cliente)
                                    @foreach ($cliente['historial_abonos'] as $abono)
                                        <tr data-detalles='@json($abono['detalles'])'>
                                            <td class="text-center"><button class="btn-detalle">+</button></td>
                                            <td>{{ $abono['folio_abono'] }}</td>
                                            <td>{{ \Carbon\Carbon::parse($abono['fecha'])->format('d/m/Y H:i') }}</td>
                                            <td>${{ number_format($abono['total_abonado'], 2) }}</td>
                                        </tr>
                                    @endforeach
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <input type="hidden" id="deuda_total" name="deuda_total" value="{{ $deudaTotal }}">
        <span id="total_seleccionado" class="hidden">0.00</span>
    </div>

@endsection

@section('js')
    <script>
        function actualizarTotalFormasPago() {
            // Suma todos los inputs de monto dentro de tus bloques de formas de pago
            const montos = document.querySelectorAll('[name^="formas_pago"][name$="[monto]"]');
            const total = Array.from(montos)
                .map(i => parseFloat(i.value) || 0)
                .reduce((a, b) => a + b, 0);

            document.getElementById('total_formas_pago').value = total.toFixed(2);
        }

        /* Ejemplo: vuelve a calcular cada que cambie un monto */
        document.addEventListener('input', e => {
            if (e.target.matches('[name^="formas_pago"][name$="[monto]"]')) {
                actualizarTotalFormasPago();
            }
        });

        // CLONA LAS FORMAS DE PAGO
        inicializarSelect2();
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

        // === VALIDACIÓN AL ENVIAR EL FORMULARIO =========================
        document.getElementById('formAbono').addEventListener('submit', function(e) {
            const tipoAbono = document.querySelector('input[name="tipo_abono"]:checked').value;
            const totalAbono = parseFloat(document.getElementById('total_formas_pago').value) || 0;
            const deuda = parseFloat(document.getElementById('deuda_total').value) || 0;

            // 1. Debe existir al menos una forma de pago con monto > 0
            const montos = document.querySelectorAll('[name^="formas_pago"][name$="[monto]"]');
            const hayFormaPago = Array.from(montos).some(i => (parseFloat(i.value) || 0) > 0);
            if (!hayFormaPago) {
                alert('Debes ingresar al menos una forma de pago con monto mayor a 0.');
                e.preventDefault(); // Detiene el envío
                return;
            }

            // 2. Si es "por monto específico"
            if (tipoAbono === 'monto') {

                if (totalAbono <= 0) {
                    alert('El Total abono debe ser mayor a 0.');
                    e.preventDefault();
                    return;
                }
                if (totalAbono > deuda) {
                    alert('El Total abono no puede ser mayor a la deuda ($' + deuda.toFixed(2) + ').');
                    e.preventDefault();
                    return;
                }

            } else { // 3. Si es "seleccionar ventas"

                const totalSeleccionado = parseFloat(document.getElementById('total_seleccionado').textContent) ||
                0;

                if (totalSeleccionado <= 0) {
                    alert('Debes seleccionar al menos una venta para abonar.');
                    e.preventDefault();
                    return;
                }
                if (totalAbono <= 0) {
                    alert('El monto a abonar debe ser mayor a 0.');
                    e.preventDefault();
                    return;
                }
                if (totalAbono > totalSeleccionado) {
                    alert('El abono no puede ser mayor a la suma de los saldos seleccionados ($' +
                        totalSeleccionado.toFixed(2) + ').');
                    e.preventDefault();
                    return;
                }
            }
        });

        $(document).ready(function() {
            var rolesTable = new DataTable('#cotizacion_detalles', {
                responsive: true,
                "language": {
                    "url": "{{ asset('/json/i18n/es_es.json') }}"
                },
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

            $('.select2').select2({
                placeholder: "-- Seleccione --",
                allowClear: false,
                width: '100%'
            });

            actualizarOpcionesFormasPago(); // para actualizar si hay formas prellenadas

            //TABLA ABONOS
            const table = $('#tabla-abonos').DataTable({
                responsive: true
            });

            // Manejar click en botón "+"
            $('#tabla-abonos tbody').on('click', 'button.btn-detalle', function() {
                const tr = $(this).closest('tr');
                const row = table.row(tr);

                if (row.child.isShown()) {
                    // Ocultar detalles
                    row.child.hide();
                    $(this).text('+');
                } else {
                    // Mostrar detalles con función
                    const detalles = tr.data('detalles');
                    row.child(formatearDetalles(detalles)).show();
                    $(this).text('−');
                }
            });

            // Función que genera el HTML para la fila expandida
            function formatearDetalles(detalles) {
                let html = '<table class="w-full table table-sm table-striped">';
                html +=
                    '<thead><tr><th>Venta</th><th>Abonado</th><th>Antes</th><th>Después</th></tr></thead><tbody>';

                detalles.forEach(det => {
                    html += `<tr>
                        <td>${det.folio_venta ?? '—'}</td>
                        <td>$${parseFloat(det.abonado).toFixed(2)}</td>
                        <td>$${parseFloat(det.monto_antes).toFixed(2)}</td>
                        <td>$${parseFloat(det.saldo_despues).toFixed(2)}</td>
                    </tr>`;
                });

                html += '</tbody></table>';
                return html;
            }


        });

        // para los checbox de la tabla
        document.addEventListener('DOMContentLoaded', function() {
            const tipoAbonoRadios = document.querySelectorAll('input[name="tipo_abono"]');
            const checkboxes = document.querySelectorAll('.check-venta');
            const totalSpan = document.getElementById('total_seleccionado');

            function updateCheckboxState() {
                const tipo = document.querySelector('input[name="tipo_abono"]:checked').value;
                checkboxes.forEach(cb => {
                    cb.disabled = tipo !== 'venta';
                    if (tipo !== 'venta') {
                        cb.checked = false;
                    }
                });
                calcularTotalSeleccionado();
            }

            function calcularTotalSeleccionado() {
                let total = 0;
                checkboxes.forEach(cb => {
                    if (cb.checked) {
                        total += parseFloat(cb.dataset.saldo || 0);
                    }
                });
                totalSpan.textContent = total.toFixed(2);
            }

            tipoAbonoRadios.forEach(rb => {
                rb.addEventListener('change', updateCheckboxState);
            });

            checkboxes.forEach(cb => {
                cb.addEventListener('change', calcularTotalSeleccionado);
            });
        });
    </script>
@stop
