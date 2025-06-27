<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pedidos;
use App\Models\Bicicleta;

class DashboardController extends Controller
{
    public function index()
    {
        try {
            // Obtener los 3 últimos pedidos con su sucursal y cantidad de bicicletas
            $pedidosRecientes = Pedidos::with(['sucursal'])
                ->withCount('bicicletas')
                ->orderByDesc('fecha_envio')
                ->take(3)
                ->get();

            // Obtener las últimas 3 bicicletas registradas con sus relaciones
            $bicicletas = Bicicleta::with(['modelo', 'color', 'tipoStock', 'pedido'])
                ->orderByDesc('created_at')
                ->take(3)
                ->get();

            return view('Mexico.inicio', [
                'pedidosRecientes' => $pedidosRecientes,
                'bicicletas' => $bicicletas,
            ]);
        } catch (\Exception $e) {
            Log::error('Error en DashboardController: ' . $e->getMessage());

            return view('Mexico.inicio', [
                'pedidosRecientes' => collect(),
                'bicicletas' => collect(),
            ]);
        }
    }
}
