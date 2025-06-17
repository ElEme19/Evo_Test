<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Piezas;

class PiezasBController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:usuarios');
    }

    public function crear()
    {
        return view('piezas.crear');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre_pieza' => 'required|string|max:45',
            'descripcion_pieza' => 'required|string',
        ]);

        $pieza = new Piezas();
        $pieza->nombre_pieza = $request->nombre_pieza;
        $pieza->descripcion_pieza = $request->descripcion_pieza;
        $pieza->save();

        return redirect()->route('piezas.ver')->with('success', 'Pieza creada correctamente!');
    }

    public function ver()
    {
        $piezas = Piezas::all();
        return view('piezas.ver', compact('piezas'));
    }

    public function editar(Piezas $pieza)
    {
        return view('piezas.editar', compact('pieza'));
    }

    public function update(Request $request, Piezas $pieza)
    {
        $request->validate([
            'nombre_pieza' => 'required|string|max:45',
            'descripcion_pieza' => 'required|string',
        ]);

        $pieza->nombre_pieza = $request->nombre_pieza;
        $pieza->descripcion_pieza = $request->descripcion_pieza;
        $pieza->save();

        return redirect()->route('piezas.ver')->with('success', 'Pieza actualizada correctamente!');
    }

    public function eliminar(Piezas $pieza)
    {
        $pieza->delete();
        return redirect()->route('piezas.ver')->with('success', 'Pieza eliminada correctamente!');
    }
}
