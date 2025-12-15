<?php

use App\Http\Controllers\AbonosController;
use App\Http\Controllers\AsignarGastosController;
use App\Http\Controllers\ClasificacionubicacionController;
use App\Http\Controllers\ClientesController;
use App\Http\Controllers\ComprasController;
use App\Http\Controllers\ConfiguracionController;
use App\Http\Controllers\CotizacionesController;
use App\Http\Controllers\CotizacionesDetallesController;
use App\Http\Controllers\CreditosController;
use App\Http\Controllers\EstatusPedidosController;
use App\Http\Controllers\FinanzasController;
use App\Http\Controllers\FormaPagoController;
use App\Http\Controllers\GastosController;
use App\Http\Controllers\InventarioController;
use App\Http\Controllers\NotaVentaController;
use App\Http\Controllers\NotaVentaPcController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\PonchadosController;
use App\Http\Controllers\PonchadosPedidosController;
use App\Http\Controllers\PrecioPonchadoController;
use App\Http\Controllers\PreciosController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserProfileController;
use App\Http\Controllers\ProductoCaracteristicasController;
use App\Http\Controllers\ProductosController;
use App\Http\Controllers\ProveedoresController;
use App\Http\Controllers\TicketAlternoController;
use App\Http\Controllers\TipoGastoController;
use App\Http\Controllers\VentaController;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\CheckUserActive;


Route::get('/', function () {
    return view('welcome');
});

Route::get('/prueba', function () {
    session()->flash('swal', [
        'icon' => "error",
        'title' => "Oops...",
        'text' => "Something went wrong!",
        'footer' => '<a href="#">Why do I have this issue?</a>'
    ]);
    return view('boostrap.componente');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
    CheckUserActive::class
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});

Route::post('/update-menu-color', [UserProfileController::class, 'updateMenuColor'])->middleware('auth');
Route::post('/update-theme', [UserProfileController::class, 'updateTheme'])->middleware('auth');

Route::get('/compara-precios', [PreciosController::class, 'comparaRangosPrecios'])
    ->name('compara.precios');

Route::get('busca/factura/{id}', [ComprasController::class, 'busca_factura_duplicada'])
	->name('busca.factura.duplicada');

Route::get('busca/num/serie/{id}', [ComprasController::class, 'numero_serie_duplicado'])
	->name('busca.num.serie.duplicado');

Route::post('busca/producto/servicio/codbarra', [ProductosController::class, 'busca_codbarra_productos_compra'])
	->name('busca.producto.servicio.codbarra');

/*Route::get('productos/compra', [ComprasController::class, 'productos_compra'])/
	->name('producto.compra');*/

Route::post('productos/index/ajax', [ProductosController::class, 'productos_index_ajax'])
	->name('productos.index.ajax');

Route::get('/obtener-precios', [PreciosController::class, 'obtenerPrecios'])
		->name('obtener.precios');

Route::post('clientes/index/ajax', [ClientesController::class, 'clientes_index_ajax'])
	->name('clientes.index.ajax');

Route::post('ponchados/index/ajax', [PonchadosController::class, 'ponchados_index_ajax'])
	->name('ponchados.index.ajax');

Route::resource('/roles', RoleController::class)->names('admin.roles')
    ->except(['show']);
Route::resource('/permissions', PermissionController::class)->names('admin.permissions')
    ->except(['show']);
Route::resource('/users', UserController::class)->names('admin.users')
    ->except(['show']);
Route::resource('/perfil', UserProfileController::class)
    ->except(['index', 'create', 'store', 'show', 'destroy'])
    ->parameters(['perfil' => 'perfil'])
    ->names('admin.perfil');
Route::resource('/producto-caracteristica', ProductoCaracteristicasController::class)->names('admin.producto.caracteristica')
    ->except(['show']);
Route::resource('/proveedores', ProveedoresController::class)->names('admin.proveedores')
    ->except(['show']);
Route::resource('/producto-servicio', ProductosController::class)->names('admin.producto.servicio');
Route::resource('/precios', PreciosController::class)->names('admin.precios');
Route::resource('/compras', ComprasController::class)->names('admin.compras');
Route::resource('/inventario', InventarioController::class)->names('admin.inventario')
    ->except(['create', 'store', 'show', 'destroy']);

Route::resource('/clientes', ClientesController::class)->names('admin.clientes')
->except('show');

Route::resource('/forma-pago', FormaPagoController::class)->names('admin.forma.pago')
->except('show');

Route::resource('/tipo-gasto', TipoGastoController::class)->names('admin.tipo.gasto')
->except('show');

Route::resource('/gastos', GastosController::class)->names('admin.gastos')
->except('show');

Route::resource('/asignar-gasto', AsignarGastosController::class)->names('admin.asignar.gasto')
->except(['show','edit']);

Route::post('creditos/index/ajax', [CreditosController::class, 'creditos_index_ajax'])
	->name('creditos.index.ajax');

Route::get('/ponchados/descargar-archivo/{id}', [PonchadosPedidosController::class, 'descargarArchivo'])
    ->name('ponchados.descargarArchivo');

Route::get('ticket-venta/{id}', [VentaController::class, 'ticket'])
	->name('ticket.venta');
Route::get('/ticket/mixto', [VentaController::class, 'ticketMixto'])->name('ticket.misto');

Route::post('ponchados-precios/index/ajax', [PrecioPonchadoController::class, 'ponchados_precios_ajax'])
	->name('ponchados.precios.ajax');

Route::get('ticket-ponchado/{id}', [PonchadosPedidosController::class, 'ticketPedido'])
		->name('ticket.ponchado');

Route::post('pedidos-ponchados/{id}/update-cantidad', 
    [PonchadosPedidosController::class, 'updateCantidad']
)->name('ponchados.updateCantidad');

Route::resource('cotizaciones', CotizacionesController::class)->names('admin.cotizacion');
Route::resource('cotizacion/detalles', CotizacionesDetallesController::class)->names('admin.cotizacion.detalles');
Route::resource('ticket-alterno', TicketAlternoController::class)->names('admin.ticket.alterno');
Route::resource('nota-venta', NotaVentaController::class)->names('admin.nota.venta');
Route::resource('venta-pc-nota', NotaVentaPcController::class)->names('admin.nota.pc.venta');
Route::resource('clasificacion-ubicacion', ClasificacionubicacionController::class)->names('admin.clasificacion.ubicacion');
Route::resource('ponchados', PonchadosController::class)->names('admin.ponchados');
Route::resource('pedidos-ponchados', PonchadosPedidosController::class)->names('admin.pedidos.ponchados');
Route::resource('ventas', VentaController::class)->names('admin.ventas');
Route::resource('creditos', CreditosController::class)->names('admin.creditos');
Route::resource('abonos', AbonosController::class)->names('admin.abonos');
Route::resource('estatus-pedidos', EstatusPedidosController::class)->names('admin.estatus.pedidos');
Route::resource('configuracion', ConfiguracionController::class)->names('admin.configuracion');
Route::resource('precio-ponchado', PrecioPonchadoController::class)->names('admin.precio.ponchado');

