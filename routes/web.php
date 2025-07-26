<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
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
use App\Models\modelos_bici;


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

Route::get('/', function () {
    return view('welcome');
});


Route::middleware(['auth:usuarios','check.user.type:0'])->group(function(){  // ==> Tener acceso dependiendo del usuario a las vistas (/piezas/crear)


});


// NO TOCAR!!!!!!

Route::get('registrarse', [RegistroController::class, 'registrarse'])->name('registrarse');
Route::post('/registrar', [RegistroController::class, 'registrar'])->name('registrar');

Route::get('/Mexico/inicio', [PiezasController::class ,'inicio'])->name ('piezas.inicio');

// Rutas para login y logout  ==> Estas rutas quedan publicas

Route::get('login', [LoginController::class, 'verFormLogin']) -> name('login');
Route::post('login', [LoginController::class, 'login']) ;
Route::post('logout', [LoginController::class, 'logout']) -> name('logout');







