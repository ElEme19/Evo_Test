<?php

namespace App\Http\Controllers;

use App\Models\Pedidos;
use App\Models\Bicicleta;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
{
    // Obtener pedidos recientes
    $pedidosRecientes = Pedidos::with(['sucursal', 'bicicletas'])
        ->withCount('bicicletas')
        ->orderBy('created_at', 'desc')
        ->take(3)
        ->get();

    // Obtener bicicletas recientes
    $bicicletas = Bicicleta::with(['modelo'])
        ->orderBy('created_at', 'desc')
        ->take(3)
        ->get();

    return view('Mexico.inicio', compact('pedidosRecientes', 'bicicletas'));
}
}