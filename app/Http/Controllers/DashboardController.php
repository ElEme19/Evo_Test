<?php

namespace App\Http\Controllers;

use App\Models\Pedidos; // Asegúrate que el modelo se llame Pedido (singular)
use App\Models\Bicicleta;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        try {
            // Obtener últimos 3 pedidos con sus relaciones
            $pedidosRecientes = Pedidos::with(['sucursal', 'bicicletas'])
                ->withCount('bicicletas')
                ->orderBy('created_at', 'desc')
                ->take(3)
                ->get();

            // Obtener últimas 3 bicicletas con sus relaciones
            $bicicletas = Bicicleta::with(['modelo', 'ultimoPedido'])
                ->orderBy('created_at', 'desc')
                ->take(3)
                ->get();

            return view('Mexico.inicio', [
                'pedidosRecientes' => $pedidosRecientes,
                'bicicletas' => $bicicletas
            ]);

        } catch (\Exception $e) {
            Log::error('Error en DashboardController: ' . $e->getMessage());
            
            return view('Mexico.inicio', [
                'pedidosRecientes' => collect(),
                'bicicletas' => collect()
            ]);
        }
    }
}