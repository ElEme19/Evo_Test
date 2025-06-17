<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\modelos_bici; // nombre correcto del modelo

class ModelosBController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:usuarios');
    }

    

    public function crear()
    {
        return view('modelos.crear');
    }





    public function store(Request $request)
    {
        $request->validate([
            'id_modelo' => 'required|string|max:64|unique:modelos,id_modelo',
            'nombre_modelo' => 'required|string|max:15',
            'descripcion' => 'required|string|max:64',
            'foto_modelo' => 'nullable|image|max:2048',
        ]);

        $modelo = new modelos_bici();
        $modelo->id_modelo = $request->id_modelo;
        $modelo->nombre_modelo = $request->nombre_modelo;
        $modelo->descripcion = $request->descripcion;

        if ($request->hasFile('foto_modelo')) {
            $modelo->foto_modelo = file_get_contents($request->file('foto_modelo')->getRealPath());
        }

        $modelo->save();

        return redirect()->route('modelos.ver')->with('success', 'Modelo creado correctamente!');
    }





    public function ver(Request $request)
    {
        $q = $request->input('q');

        if ($q) {
            $modelos = modelos_bici::where('nombre_modelo', 'LIKE', "%$q%")->get();
        } else {
            $modelos = modelos_bici::all();
        }

        return view('modelos.ver', compact('modelos', 'q'));
    }

    public function editar($id_modelo)
    {
        $modelo = modelos_bici::findOrFail($id_modelo);
        return view('modelos.editar', compact('modelo'));
    }




    public function update(Request $request, $id_modelo)
    {
        $modelo = modelos_bici::findOrFail($id_modelo);

        $request->validate([
            'nombre_modelo' => 'required|string|max:15',
            'descripcion' => 'required|string|max:64',
            'foto_modelo' => 'nullable|image|max:2048',
        ]);

        $modelo->nombre_modelo = $request->nombre_modelo;
        $modelo->descripcion = $request->descripcion;

        if ($request->hasFile('foto_modelo')) {
            $modelo->foto_modelo = file_get_contents($request->file('foto_modelo')->getRealPath());
        }

        $modelo->save();

        return redirect()->route('modelos.ver')->with('success', 'Modelo actualizado correctamente!');
    }






    public function eliminar($id_modelo)
    {
        $modelo = modelos_bici::findOrFail($id_modelo);
        $modelo->delete();

        return redirect()->route('modelos.ver')->with('success', 'Modelo eliminado correctamente!');
    }
}
