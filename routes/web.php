<?php

use App\Http\Controllers\ModelosBController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PiezasController;
use App\Http\Controllers\ColorModeloController;
use App\Http\Controllers\RegistroController;
use App\Http\Controllers\LoteController;
use App\Http\Controllers\TipoStockController;
use App\Http\Controllers\BicicletaController;
use App\Http\Controllers\EnvioController;
use App\Http\Controllers\SucursalController;
use App\Http\Controllers\ClientesController;
use App\Http\Controllers\MembresiaController;
use App\Http\Controllers\PrecioController;
use App\Http\Controllers\ProsesadorController;
use App\Http\Controllers\VoltajeController;
use App\Models\modelos_bici;
use App\Http\Controllers\AreaController;
use App\Http\Controllers\PedidosController;

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




Route::middleware(['auth:usuarios','check.user.type:0'])->group(function(){  // ==> Tener acceso dependiendo del usuario a las vistas (/piezas/crear)

// Laboratorio
Route::get('/piezas/crear', [PiezasController::class, 'crear']) -> name('piezas.crear');
Route::post('/piezas/store', [PiezasController::class, 'store']) -> name('piezas.store');
Route::get('/piezas/ver', [PiezasController::class, 'ver']) -> name('piezas.ver');
Route::put('/piezas/{piezas}', [PiezasController::class, 'update']) -> name('piezas.update');
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
Route::get('/Bicicleta/vista', [BicicletaController::class, 'ver'])->name('Bicicleta.ver');
/* Route::put('/Bicicleta/{bicicleta}', [BicicletaController::class, 'update'])->name('Bicicleta.update'); */
Route::get('/colores-por-modelo/{id_modelo}', [BicicletaController::class, 'coloresPorModelo'])->name('Bicicleta.ptoEmilioNoleMuevas');
Route::get('/voltaje-por-modelo/{id_modelo}', [BicicletaController::class, 'voltajePorModelo'])->name('Bicicleta.voltajeXmodelo');
Route::get('/Bicicleta/buscar-por-ultimos4', [BicicletaController::class, 'buscarPorUltimosSx'])->name('Bicicleta.buscarUltimos4');


// Busquedas

Route::get('/Busquedas/busChasis', [BicicletaController::class, 'buscarC'])->name('Busquedas.busChasis');
Route::get('/Busquedas/busMotor', [BicicletaController::class, 'buscarMotor'])->name('Busquedas.busMotor');
Route::get('/Busquedas/busModelo', [BicicletaController::class, 'buscarModelo'])->name('Busquedas.busModelo');
Route::get('/Busquedas/busStock', [BicicletaController::class, 'buscarPorStock']) ->name('Busquedas.busStock');

// Voltaje
Route::get('/voltaje-por-modelo/{id_modelo}', [VoltajeController::class, 'porModelo'])->name('voltaje.porModelo');

// Envios

Route::get('/Envio/crear', [EnvioController::class, 'crear'])->name('Envio.crear');
Route::post('/Envio/store', [EnvioController::class, 'store'])->name('Envio.store');

// Sucursal

Route::get('/Sucursal/crear', [SucursalController::class, 'crear'])->name('Sucursal.crear');
Route::post('/Sucursal/store', [SucursalController::class, 'store'])->name('Sucursal.store');
Route::get('/Sucursal/vista', [SucursalController::class, 'ver'])->name('Sucursal.ver');
Route::get('/sucursal/imagen/{path}', [SucursalController::class, 'mostrarImagen'])->where('path', '.*')->name('sucursal.imagen');

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


//Debatible
//Route::get('/Mexico/inicio', [DashboardController::class, 'index'])->name('Mexico.inicio');

});


// NO TOCAR!!!!!!

Route::get('/piezas/registrarse', [RegistroController::class, 'registrarse'])->name('registrarse');
Route::post('/piezas/registrar', [RegistroController::class, 'registrar'])->name('registrar');

Route::get('/Mexico/inicio', [PiezasController::class ,'inicio'])->name ('piezas.inicio');

// Rutas para login y logout  ==> Estas rutas quedan publicas

Route::get('login', [LoginController::class, 'verFormLogin']) -> name('login');
Route::post('login', [LoginController::class, 'login']) ;
Route::post('logout', [LoginController::class, 'logout']) -> name('logout');







