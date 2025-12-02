<div id="edit-modal" style="display: none;"
    class="modal fixed inset-0 z-50 flex items-center justify-center p-4 overflow-x-hidden overflow-y-auto bg-gray-900 bg-opacity-50 custom-modal-bg dark:bg-gray-800">
    <div class="relative w-full max-w-lg bg-white rounded-lg shadow-lg dark:bg-gray-800">
        <form action="" method="POST" id="form-edit">
            @csrf
            @method('PUT')
            <input type="hidden" name="tipo_precio" id="tipo_precio" value="">
            <div class="relative bg-white rounded-lg shadow dark:bg-gray-800">
                <div class="flex items-center justify-between p-2.5 md:p-5 border-b rounded-t dark:border-gray-600 dark:bg-gray-800">
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white">Editar Registro: <span id="nombre_pedido"></h3>
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

                            <div class="col-span-12">
                                <label for="ponchado"
                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                    Ponchado</label>
                                <input type="text" id="ponchado" name="ponchado"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                    placeholder="Poncahdo" value="" required />
                            </div>
                            <div class="col-span-12">
                                <label for="piezas"
                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                    Piezas
                                </label>
                                <input type="text" id="piezas" name="piezas"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                    placeholder="% Medio mayoreo" value="" required />
                            </div>
                            <div class="col-span-12">
                                <label for="precio"
                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                    Precio
                                </label>
                                <input type="text" id="precio" name="precio"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                    placeholder="% Mayoreo" value="" required />
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