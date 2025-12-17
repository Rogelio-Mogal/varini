  {{--
   <!--popper -->
    <script src="https://unpkg.com/@popperjs/core@2/dist/umd/popper.min.js"></script>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
--}}

    <!-- Flowbite -->
    <script src="{{ mix('node_modules/flowbite/dist/flowbite.min.js') }}"></script>


    <!--datatables -->
    <script src="{{ asset('datatable/js/datatables.min.js') }}"></script>
    <!-- RowGroup oficial -->
    <script src="{{ asset('datatable/js/dataTables.rowGroup.min.js') }}"></script>
    <!-- Responsive (si lo usas) -->
    <script src="{{ asset('datatable/js/dataTables.responsive.min.js') }}"></script>


    <!--select2 -->
    <script src="{{ asset('select2/select2.min.js') }}"></script>

    <!-- sweetAlert2 -->
    <script src="{{ asset('sweetalert2/js/sweetalert2@11.js') }}"></script>



    @livewireScripts

    <!-- Mensajes de alerta -->
    @if(session('swal'))
        <script>
            Swal.fire( {!! json_encode(session('swal')) !!} )
        </script>
    @endif

    @yield('js')


    <script>
        // APERTURA EL SUBMENU
        document.addEventListener('DOMContentLoaded', () => {
            const currentRoute = "{{ request()->route()->getName() }}"; // Obtiene el nombre de la ruta actual
            const routesToCheck = {
                'dropdown-cuenta': [
                    'admin.perfil.*',
                ],
                'dropdown-finanzas': [
                    'admin.forma.pago.*',
                    'admin.tipo.gasto.*',
                    'admin.gastos.*',
                    'admin.asignar.gasto.*',
                ],
                'dropdown-clientes': [
                    'admin.clientes.*',
                    'admin.creditos.*',
                ],
                'dropdown-ponchados': [
                    'admin.ponchados.*',
                    'admin.pedidos.ponchados.*',
                    'admin.precio.ponchado.*',
                ],
                'dropdown-productos': [
                    'admin.proveedores.*',
                    'admin.producto.caracteristica.*',
                    'admin.clasificacion.ubicacion.*',
                    'admin.producto.servicio.*',
                    'admin.precios.*',
                    'admin.compras.*',
                ],
                'dropdown-documentos': [
                    'admin.cotizacion.*',
                    'admin.ticket.alterno.*',
                    'admin.nota.venta.*',
                    'admin.nota.pc.venta.*',
                ]
            };

            // Función para verificar si la ruta actual coincide con alguna de las rutas especificadas con comodines
            function routeMatches(route, patterns) {
                return patterns.some(pattern => {
                    const regex = new RegExp('^' + pattern.replace(/\./g, '\\.').replace(/\*/g, '.*') + '$');
                    return regex.test(route);
                });
            }

            document.querySelectorAll('button[data-target]').forEach(button => {
                const targetMenu = button.getAttribute('data-target');

                if (routesToCheck[targetMenu] && routeMatches(currentRoute, routesToCheck[targetMenu])) {
                    const menu = document.getElementById(targetMenu);
                    if (menu) {
                        menu.classList.remove('hidden');
                        button.setAttribute('aria-expanded', 'true');
                        //button.querySelector('svg').classList.add('rotate-180'); // Rota la flecha hacia abajo
                    }
                }
            });
        });


        /*document.addEventListener('DOMContentLoaded', () => {
            const currentRoute = "{{ request()->route()->getName() }}"; // Obtiene el nombre de la ruta actual
            const routesToCheck = [
                'admin.proveedores.*',
                'admin.producto.caracteristica.*',
                'admin.producto.servicio.*',
                'admin.precios.*',
                'admin.compras.*',
            ];
            */

            // Función para verificar si la ruta actual coincide con alguna de las rutas especificadas con comodines
            //function routeMatches(route, patterns) {
            //    return patterns.some(pattern => {
            //        const regex = new RegExp('^' + pattern.replace(/\./g, '\\.').replace(/\*/g, '.*') + '$');
            //        return regex.test(route);
            //    });
           // }
            /*
            document.querySelectorAll('button[data-target]').forEach(button => {
                const targetId = button.getAttribute('data-target');
                const submenu = document.getElementById(targetId);

                // Abre el submenú si la ruta actual coincide con alguna de las rutas especificadas
                if (routeMatches(currentRoute, routesToCheck)) {
                    submenu.classList.remove('hidden');
                    button.setAttribute('aria-expanded', 'true');
                }

                button.addEventListener('click', () => {
                    const isExpanded = button.getAttribute('aria-expanded') === 'true';
                    submenu.classList.toggle('hidden', isExpanded);
                    button.setAttribute('aria-expanded', !isExpanded);
                });
            });
        });
        */
    </script>
    </body>

    </html>
