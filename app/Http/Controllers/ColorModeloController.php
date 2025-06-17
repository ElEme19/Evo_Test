<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ColorModelo;
use App\Models\modelos_bici;

class ColorModeloController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:usuarios');
    }


    public $timestamps = false;

    public function crear()
    {
        $modelos = modelos_bici::all();
    
        return view('ColorModelo.crear', compact('modelos'));
    }




   public function store(Request $request)
{
    $request->validate([
        'id_colorM' => 'required|string|max:64|unique:color_modelo,id_colorM', // check this line
        'id_modelo' => 'required|string|exists:modelos,id_modelo',
        'nombre_color' => 'required|string|max:32',
    ]);

    try {
        $colorModelo = new ColorModelo();
        $colorModelo->id_colorM = $request->id_colorM;
        $colorModelo->id_modelo = $request->id_modelo;
        $colorModelo->nombre_color = $request->nombre_color;

        if ($colorModelo->save()) {
            return redirect()->back()->with('success', '¡Color del modelo creado correctamente!');
        } else {
            return redirect()->back()->with('error', 'No se pudo crear el color del modelo. Intenta nuevamente.');
        }
    } catch (\Exception $e) {
        return redirect()->back()->with('error', 'Ocurrió un error inesperado: ' . $e->getMessage());
    }
}





    public function ver()
    {
        $colores = ColorModelo::with('modelo')->get();
        $modelos = modelos_bici::all();
    return view('ColorModelo.vista', compact('colores', 'modelos'));
    }



    public function buscar(Request $request)
    {
        $busqueda = $request->input('buscar');

        $colores = ColorModelo::where('nombre_color', 'like', '%' . $busqueda . '%')
                    ->orWhere('id_colorM', 'like', '%' . $busqueda . '%')
                    ->get();
        
    $modelos = modelos_bici::all(); 

    return view('colormodelo.ver', compact('colores', 'modelos'));
}





    public function update(Request $request, $id_colorM)
{
    $request->validate([
        'id_modelo' => 'required|string|exists:modelos,id_modelo',
        'nombre_color' => 'required|string|max:32',
    ]);

    $updated = ColorModelo::where('id_colorM', $id_colorM)
        ->update([
            'id_modelo' => $request->id_modelo,
            'nombre_color' => $request->nombre_color,
        ]);

    if ($updated) {
        return redirect()->route('Color.vista')->with('success', 'Color del modelo actualizado correctamente!');
        
    } else {
        return redirect()->back()->with('error', 'No se pudo actualizar el color del modelo.');
    }
}



    public function eliminar(ColorModelo $colormodelo)
    {
        $colormodelo->delete();
        return redirect()->route('colormodelo.vista')->with('success', 'Color del modelo eliminado correctamente!');
    }
}
