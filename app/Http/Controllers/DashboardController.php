<?php

namespace App\Http\Controllers;

use App\Models\Pedidos;
use App\Models\Bicicleta;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Obtener los últimos 3 pedidos con sus relaciones
        $pedidosRecientes = Pedidos::with(['sucursal', 'bicicletas'])
            ->withCount('bicicletas')
            ->orderBy('fecha', 'desc')
            ->take(3)
            ->get();

        // Obtener las últimas 3 bicicletas registradas
        $bicicletas = Bicicleta::with(['modelo', 'ultimoPedido'])
            ->orderBy('created_at', 'desc')
            ->take(3)
            ->get();

        return view('dashboard', compact('pedidosRecientes', 'bicicletas'));
    }
}