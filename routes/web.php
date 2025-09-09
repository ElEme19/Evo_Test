<?php

use App\Http\Controllers\ModelosBController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\PiezasController;
use App\Http\Controllers\ColorModeloController;
//use App\Http\Controllers\RegistroController;
use App\Http\Controllers\LoteController;
use App\Http\Controllers\TipoStockController;
use App\Http\Controllers\BicicletaController;
//use App\Http\Controllers\EnvioController;
use App\Http\Controllers\SucursalController;
use App\Http\Controllers\ClientesController;
use App\Http\Controllers\MembresiaController;
use App\Http\Controllers\PrecioController;
use App\Http\Controllers\ProsesadorController;
use App\Http\Controllers\VoltajeController;
use App\Http\Controllers\AreaController;
use App\Http\Controllers\PedidosController;
use App\Http\Controllers\CotizacionController;
use App\Http\Controllers\ListaModelosController;
use App\Http\Controllers\PiezasBController;
use App\Http\Controllers\PedidosPiezasController;
use App\Http\Controllers\AutorizacionController;

/*
|--------------------------------------------------------------------------
| Web Routess
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great! 
|
*/

Route::get('/',[PiezasController::class ,'inicio'])->name ('piezas.inicio');




Route::middleware(['auth:usuarios','check.user.type:0,1'])->group(function () {  // ==> Tener acceso dependiendo del usuario a las vistas (/piezas/crear)

// Laboratorio
//Route::get('/piezas/crear', [PiezasController::class, 'crear']) -> name('piezas.crear');
//Route::post('/piezas/store', [PiezasController::class, 'store']) -> name('piezas.store');
//Route::get('/piezas/ver', [PiezasController::class, 'ver']) -> name('piezas.ver');
//Route::put('/piezas/{piezas}', [PiezasController::class, 'update']) -> name('piezas.update');
Route::get('/hola', function () {  return view('Mexico.prueba');});


// Color-Modelo

Route::get('/ColorModelo/crear', [ColorModeloController::class, 'crear']) -> name('Color.crear');
Route::post('/ColorModelo/store', [ColorModeloController::class, 'store']) -> name('Color.store');
Route::get('/ColorModelo/vista', [ColorModeloController::class, 'ver']) -> name('Color.vista');
Route::put('/ColorModelo/{color}', [ColorModeloController::class, 'update'])->name('Color.update');
Route::get('/colores/search', [ColorModeloController::class, 'search'])->name('colores.search');


//Modelos
Route::get('/Modelo/ver', [ModelosBController::class, 'ver'])->name('Modelo.ver');
Route::get('/Modelo/crear', [ModelosBController::class, 'crear'])->name('Modelo.crear');
Route::post('/Modelo/store', [ModelosBController::class, 'store'])->name('Modelo.store');
Route::get('/Modelo/editar/{id_modelo}', [ModelosBController::class, 'editar'])->name('Modelo.editar');
Route::put('update/{id_modelo}', [ModelosBController::class, 'update'])->name('Modelo.update');
  //Route::delete('eliminar/{id_modelo}', [ModelosBController::class, 'eliminar'])->name('modelos.eliminar');
Route::get('/Modelos/imagen/{path}', [ModelosBController::class, 'mostrarImagen'])->where('path', '.*')->name('Modelo.imagen');

// Lote
Route::get('/Lote/crear', [LoteController::class, 'crear'])->name('Lote.crear');
Route::post('/Lote/store', [LoteController::class, 'store'])->name('Lote.store');
Route::get('/Lote/vista', [LoteController::class, 'ver'])->name('Lote.vista');
Route::put('/Lote/{lote}', [LoteController::class, 'update'])->name('Lote.update');


// Stock
Route::get('/Stock/crear', [TipoStockController::class, 'crear'])->name('Stock.crear');
Route::post('/Stock/store', [TipoStockController::class, 'store'])->name('Stock.store');
Route::get('/Stock/vista', [TipoStockController::class, 'ver'])->name('Stock.ver');
Route::put('/Stock/{tipostock}', [TipoStockController::class, 'update'])->name('Stock.update');

// Bicicleta

Route::get('/Bicicleta/buscarC', [BicicletaController::class, 'buscarC']);
Route::get('/Bicicleta/crear', [BicicletaController::class, 'crear'])->name('Bicicleta.crear');
Route::post('/Bicicleta/store', [BicicletaController::class, 'store'])->name('Bicicleta.store');
Route::get('/Bicicleta/guarda', [BicicletaController::class, 'guarda'])->name('Bicicleta.guarda');
Route::post('/Bicicleta/biciensistema', [BicicletaController::class, 'biciensistema'])->name('Bicicleta.biciensistema');
Route::get('/Bicicleta/vista', [BicicletaController::class, 'ver'])->name('Bicicleta.ver');
/* Route::put('/Bicicleta/{bicicleta}', [BicicletaController::class, 'update'])->name('Bicicleta.update'); */
Route::get('/colores-por-modelo/{id_modelo}', [BicicletaController::class, 'coloresPorModelo'])->name('Bicicleta.ptoEmilioNoleMuevas');
Route::get('/voltaje-por-modelo/{id_modelo}', [BicicletaController::class, 'voltajePorModelo'])->name('Bicicleta.voltajeXmodelo');
Route::get('/Bicicleta/buscar-por-ultimos4', [BicicletaController::class, 'buscarPorUltimosSx'])->name('Bicicleta.buscarUltimos4');

Route::get('/imprimirTodo', [BicicletaController::class, 'imprimirTodasBicicletas']);
Route::get('/imprimirTodo', [BicicletaController::class, 'viewImprimirTodo'])->name('Bicicleta.imprimirTodo');
Route::post('/imprimirTodo', [BicicletaController::class, 'imprimirBicicletasPorFecha'])->name('Bicicleta.imprimirTodo.post');



// Busquedas

Route::get('/Busquedas/busChasis', [BicicletaController::class, 'buscarC'])->name('Busquedas.busChasis');
Route::get('/Busquedas/busMotor', [BicicletaController::class, 'buscarMotor'])->name('Busquedas.busMotor');
Route::get('/Busquedas/busModelo', [BicicletaController::class, 'buscarModelo'])->name('Busquedas.busModelo');
Route::get('/Busquedas/busStock', [BicicletaController::class, 'buscarPorStock']) ->name('Busquedas.busStock');

// Voltaje
Route::get('/voltaje-por-modelo/{id_modelo}', [VoltajeController::class, 'porModelo'])->name('voltaje.porModelo');

// Envios

//Route::get('/Envio/crear', [EnvioController::class, 'crear'])->name('Envio.crear');
//Route::post('/Envio/store', [EnvioController::class, 'store'])->name('Envio.store');

// Sucursal

Route::get('/Sucursal/crear', [SucursalController::class, 'crear'])->name('Sucursal.crear');
Route::post('/Sucursal/store', [SucursalController::class, 'store'])->name('Sucursal.store');
Route::get('/Sucursal/vista', [SucursalController::class, 'ver'])->name('Sucursal.ver');
Route::get('/sucursal/imagen/{path}', [SucursalController::class, 'mostrarImagen'])->where('path', '.*')->name('sucursal.imagen');
Route::get('sucursal/buscar', [SucursalController::class, 'buscar'])->name('sucursal.buscar');


//Membresia
Route::get('/Membresia/index', [MembresiaController::class, 'index'])->name('Membresia.index');
Route::get('/Membresia', [MembresiaController::class, 'create'])->name('Membresia.create');
Route::put('/Membresia/{id}', [MembresiaController::class, 'actualizar'])->name('Membresia.actualizar');
Route::post('/Membresia', [MembresiaController::class, 'store'])->name('Membresia.store');

//Clientes
Route::get('/Clientes/index', [ClientesController::class, 'index'])->name('Clientes.index');
Route::get('/Clientes', [ClientesController::class, 'create'])->name('Clientes.create');
Route::put('/Clientes/{id}', [ClientesController::class, 'update'])->name('Clientes.update');
Route::post('/Clientes', [ClientesController::class, 'store'])->name('Clientes.store');
Route::get('/clientes/buscar', [ClientesController::class, 'buscar'])->name('Clientes.buscar');

//Precios
Route::get('/Precio/index', [PrecioController::class, 'index'])->name('Precio.index');
Route::get('/Precio', [PrecioController::class, 'create'])->name('Precio.create');
Route::put('/Precio/{id}', [PrecioController::class, 'update'])->name('Precio.update');
Route::post('/Precio', [PrecioController::class, 'store'])->name('Precio.store');
Route::get('/precios/buscar', [PrecioController::class, 'buscar'])->name('Precio.buscar');
Route::get('/pdf-precios', [PrecioController::class, 'generarPDF'])->name('Precio.pdf');


//Area
Route::get('/area/ver', [AreaController::class, 'ver'])->name('area.ver');         // Ver listado
Route::post('/area', [AreaController::class, 'store'])->name('area.store');        // Guardar nueva área
Route::put('/area/{id}', [AreaController::class, 'update'])->name('area.editar');  // Actualizar área
Route::delete('/area/{id}', [AreaController::class, 'eliminar'])->name('area.eliminar'); // Eliminar área

//Lector Excel para contenedores
Route::get('/Mexico/import',  [ProsesadorController::class, 'formulario'])->name('procesador.import');
Route::post('/Mexico/procesar', [ProsesadorController::class, 'procesarExcel'])->name('procesador.procesar');

//Pedidos
Route::get('/pedido/ver', [PedidosController::class, 'index'])->name('pedido.ver');
Route::get('/pedido/crear', [PedidosController::class, 'crear'])->name('pedido.crear');
Route::post('/pedido/store', [PedidosController::class, 'store'])->name('pedido.store');
Route::get('/pedido/pdf/{id_pedido}', [PedidosController::class, 'generarPDF'])->name('pedido.pdf');
Route::get('/pedido/buscar', [PedidosController::class, 'buscar'])->name('pedido.buscar');

Route::get('/pedido/{id}/editar', [PedidosController::class, 'editar'])->name('pedido.editar');
Route::put('/pedido/{id}/actualizar', [PedidosController::class, 'actualizar'])->name('pedido.actualizar');
Route::post('/pedido/{id}/agregar-bici', [PedidosController::class, 'agregarBici'])->name('pedido.bici.agregar');
Route::put('/pedido/{id}/eliminar-bici/{biciId}', [PedidosController::class, 'eliminarBici'])->name('pedido.bici.eliminar');
Route::post('/pedido/{id}/finalizar',[PedidosController::class, 'finalizar'])->name('pedido.finalizar');
Route::get('/pedidos/confirmar/{token}', [PedidosController::class, 'confirmarQR'])->name('pedido.confirmarQR');
Route::delete('/pedido/{id}/eliminar', [PedidosController::class, 'eliminar'])->name('pedido.eliminar');





//Pedidos Piezas
Route::get('/PedidosPiezas/ver', [PedidosPiezasController::class, 'ver'])->name('pedidos_piezas.ver');
Route::get('/PedidosPiezas/crear', [PedidosPiezasController::class, 'crear'])->name('pedidos_piezas.crear');
Route::post('/PedidosPiezas', [PedidosPiezasController::class, 'store'])->name('pedidos_piezas.store');
Route::get('/pieza/buscar', [PedidosPiezasController::class, 'buscarPieza'])->name('pieza.buscar');
Route::get('/pedidos_piezas/pdf/{id}', [PedidosPiezasController::class, 'generarPDF'])->name('pedidos_piezas.pdf');



Route::get('/PedidosPiezas/{id}/editar', [PedidosPiezasController::class, 'edit'])->name('pedidos_piezas.edit');
Route::put('/PedidosPiezas/{id}', [PedidosPiezasController::class, 'update'])->name('pedidos_piezas.update');
Route::delete('/PedidosPiezas/{id}', [PedidosPiezasController::class, 'destroy'])->name('pedidos_piezas.destroy');




//Cotizacion

Route::get('/Cotizacion/crear', [CotizacionController::class, 'index'])->name('cotizacion.create');
Route::get('/cotizacion/voltajes/{id_modelo}', [CotizacionController::class, 'voltajePorModelo'])->name('cotizacion.voltajes');
Route::get('/cotizacion/precio', [CotizacionController::class, 'precioParaCotizacion'])->name('cotizacion.precio');
Route::get('/cotizacion/colores/{id_modelo}', [CotizacionController::class, 'coloresPorModelo'])->name('cotizacion.colores');
Route::post('/cotizacion/pdf', [CotizacionController::class, 'generarPDF'])->name('cotizacion.pdf');
Route::post('/cotizacion/distancia', [CotizacionController::class, 'distancia'])->name('cotizacion.distancia');


//Lista de Disponibles

Route::get('/Disponibles/listado', [ListaModelosController::class, 'index'])->name('Listado.modelos');
Route::get('/Disponibles/listadoRefacciones', [ListaModelosController::class, 'refacciones'])->name('Listado.refacciones');


//Piezas

Route::get('pieza', [PiezasBController::class, 'ver'])->name('pieza.ver');
Route::get('pieza/crear', [PiezasBController::class, 'crear'])->name('pieza.crear');
Route::post('pieza/store', [PiezasBController::class, 'store'])->name('pieza.store');
Route::put('/pieza/{pieza}', [PiezasBController::class, 'update'])->name('pieza.update');
Route::get('pieza/imagen/{path}', [PiezasBController::class, 'mostrarImagen'])->where('path', '.*')->name('pieza.imagen');


//Youtube
Route::get('/imprimir-qr-youtube', [BicicletaController::class, 'vistaImprimirQR'])->name('bicicleta.vistaImprimirQR');
Route::post('/imprimir-qr-youtube', [BicicletaController::class, 'imprimirQRConPrintNode'])->name('bicicleta.imprimirQR');
Route::get('/bicicleta/prueba-modelo', [BicicletaController::class, 'vistaPruebaModelo'])
    ->name('Bicicleta.pruebaModelo');
//Nmms JUAN
Route::get('/autorizacion/responder/{token}/{accion}', [AutorizacionController::class, 'responder'])
     ->name('autorizacion.responder');

});


// NO TOCAR!!!!!!

//Route::get('/piezas/registrarse', [RegistroController::class, 'registrarse'])->name('registrarse');
//Route::post('/piezas/registrar', [RegistroController::class, 'registrar'])->name('registrar');

Route::get('/Mexico/inicio', [PiezasController::class ,'inicio'])->name ('piezas.inicio');

// Rutas para login y logout  ==> Estas rutas quedan publicas

Route::get('login', [LoginController::class, 'verFormLogin']) -> name('login');
Route::post('login', [LoginController::class, 'login']) ;
Route::post('logout', [LoginController::class, 'logout']) -> name('logout');







