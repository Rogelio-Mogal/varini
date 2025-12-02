<div id="edit-modal-status" style="display: none;"
    class="modal fixed inset-0 z-50 flex items-center justify-center p-4 overflow-x-hidden overflow-y-auto bg-gray-900 bg-opacity-50 custom-modal-bg dark:bg-gray-800">
    <div class="relative w-full max-w-lg bg-white rounded-lg shadow-lg dark:bg-gray-800">
        <form action="" method="POST" id="form-edit">
            @csrf
            @method('PUT')
            <input type="hidden" name="cliente_id" id="cliente_id" value="">
            <input type="hidden" name="pedido_ponchado_id" id="pedido_ponchado_id" value="">
            <div class="relative bg-white rounded-lg shadow dark:bg-gray-800">
                <div class="flex items-center justify-between p-2.5 md:p-5 border-b rounded-t dark:border-gray-600 dark:bg-gray-800">
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white">Cambio de estatus: <span id="nombre_pedido"></h3>
                    <button type="button"
                        class="close-modal text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white">
                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 14 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                        </svg>
                        <span class="sr-only">Cerrar modal</span>
                    </button>
                </div>
                <div class="p-4 md:p-5 space-y-4 dark:bg-gray-800">
                    <div class="grid  lg:grid-cols-12 gap-2">

                            <div class="sm:col-span-12 lg:col-span-6 md:col-span-6">
                                <label for="ponchado"
                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                    Ponchado</label>
                                <span id="ponchado" name="ponchado" class="self-center text-lg font-semibold whitespace-nowrap dark:text-white"></span>
                            </div>
                            <div class="sm:col-span-12 lg:col-span-6 md:col-span-6">
                                <label for="clasificacion"
                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                    Clasificación</label>
                                <span id="clasificacion" name="clasificacion" class="self-center text-lg font-semibold whitespace-nowrap dark:text-white"></span>
                            </div>
                            <div class="sm:col-span-12 lg:col-span-6 md:col-span-6">
                                <label for="piezas"
                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                    Piezas
                                </label>
                                <span id="piezas" name="piezas" class="self-center text-lg font-semibold whitespace-nowrap dark:text-white"></span>
                            </div>
                            <div class="sm:col-span-12 lg:col-span-6 md:col-span-6">
                                <label for="precio"
                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                    Precio
                                </label>
                                <span id="precio" name="precio" class="self-center text-lg font-semibold whitespace-nowrap dark:text-white"></span>
                            </div>

                            <div class="sm:col-span-12 lg:col-span-12 md:col-span-12">
                                <label for="estatus" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                    Estatus actual
                                </label>
                                <select id="estatus" name="estatus" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                    <option value="Diseño">Diseño</option>
                                    <option value="Programado para bordar">Programado para bordar</option>
                                    <option value="Bordando">Bordando</option>
                                    <option value="Finalizado">Finalizado</option>
                                    <option value="Entregado">Entregado</option>
                                    <option value="Eliminado">Eliminado</option>
                                </select>
                            </div>

                            <div class="sm:col-span-12 lg:col-span-12 md:col-span-12">
                                <label for="comentario" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                    Comentario (opcional)
                                </label>
                                <textarea id="comentario" name="comentario" rows="2" class="w-full rounded-md border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"></textarea>
                            </div>


                    </div>
                </div>
                <div class="flex items-center p-4 md:p-5 border-t border-gray-200 rounded-b dark:border-gray-600 dark:bg-gray-800">
                    <button type="submit"
                        class="btn-submit text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">Guardar</button>
                    <button type="button"
                        class="close-modal py-2.5 px-5 ms-3 text-sm font-medium text-gray-900 bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:ring-4 focus:ring-gray-100 dark:bg-gray-800">Cancelar</button>
                </div>
            </div>
        </form>
    </div>
</div>