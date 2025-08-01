<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class ListaModelosController extends Controller
{
    public function index()
    {
        // Obtenemos cada bici disponible (sin id_pedido) con su num_chasis
        $bicicletas = DB::table('bicicleta as b')
            ->join('modelos as m',      'b.id_modelo',  '=', 'm.id_modelo')
            ->join('color_modelo as cm', function($join) {
                $join->on('b.id_modelo', '=', 'cm.id_modelo')
                     ->on('b.id_color',  '=', 'cm.id_colorM');
            })
            ->leftJoin('voltaje as v',   'b.id_voltaje', '=', 'v.id_voltaje')
            ->whereNull('b.id_pedido')
            ->orWhere('b.id_pedido', '')
            ->select([
                'b.num_chasis',
                'm.nombre_modelo',
                'cm.nombre_color',
                DB::raw('COALESCE(v.tipo_voltaje, "Sin voltaje") as tipo_voltaje'),
            ])
            ->orderBy('m.nombre_modelo')
            ->orderBy('cm.nombre_color')
            ->orderBy('b.num_chasis')
            ->get();

       return view('Disponibles.listado', compact('bicicletas'));

    }



    public function refacciones()
{
    $piezas = DB::table('piezas as p')
        ->join('modelos as m', 'p.id_modelo', '=', 'm.id_modelo')
        ->where('p.cantidad', '>', 10)
        ->select([
            'p.id_pieza',
            'p.nombre_pieza',
            'p.descripcion_general',
            'p.color',
            'p.Unidad',
            'p.cantidad',
            'p.foto_pieza',
            'm.nombre_modelo'
        ])
        ->orderBy('m.nombre_modelo')
        ->orderBy('p.color')
        ->orderBy('p.nombre_pieza')
        ->get();

    return view('Disponibles.listadoP', compact('piezas'));
}



}
