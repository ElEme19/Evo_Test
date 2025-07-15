<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


    class ListaModelosController extends Controller
{
    public function index()
{
    $resultados = DB::table('bicicleta as b')
        ->join('modelos as m', 'b.id_modelo', '=', 'm.id_modelo')
        ->join('color_modelo as cm', function($join) {
            $join->on('b.id_modelo', '=', 'cm.id_modelo')
                 ->on('b.id_color', '=', 'cm.id_colorM');
        })
        ->join('voltaje as v', 'b.id_voltaje', '=', 'v.id_voltaje') // Agregamos voltaje
        ->where(function($query) {
            $query->whereNull('b.id_pedido')
                  ->orWhere('b.id_pedido', '');
        })
        ->selectRaw('m.nombre_modelo, cm.nombre_color, v.tipo_voltaje, COUNT(*) AS total_disponibles')
        ->groupBy('m.nombre_modelo', 'cm.nombre_color', 'v.tipo_voltaje')
        ->get();

    return view('Disponibles.listado', compact('resultados'));
}

}