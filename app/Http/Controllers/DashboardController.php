<?php

namespace App\Http\Controllers;

use App\Models\Pedidos; // Cambiado a singular
use App\Models\Bicicleta;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        try {
            // Obtener pedidos recientes con manejo de errores
            $pedidosRecientes = Pedidos::with(['sucursal', 'bicicletas'])
                ->withCount('bicicletas')
                ->orderBy('created_at', 'desc')
                ->take(3)
                ->get();

            // Obtener bicicletas recientes con manejo de errores
            $bicicletas = Bicicleta::with(['modelo'])
                ->orderBy('created_at', 'desc')
                ->take(3)
                ->get();

            // Debug: Verificar datos
            // dd($pedidosRecientes, $bicicletas);

            return view('Mexico.inicio', [
                'pedidosRecientes' => $pedidosRecientes,
                'bicicletas' => $bicicletas
            ]);

        } catch (\Exception $e) {
            // Log del error
            \Log::error('Error en DashboardController: ' . $e->getMessage());
            
            // Retornar vista con colecciones vacías
            return view('Mexico.inicio', [
                'pedidosRecientes' => collect(),
                'bicicletas' => collect()
            ]);
        }
    }
}