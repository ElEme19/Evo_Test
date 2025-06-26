<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\modelos_bici;

class ModelosBController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:usuarios');
    }

   



public function ver()
{
    $modelos = modelos_bici::paginate(15);
    return view('Modelo.ver', compact('modelos'));
}




            public function editar($id_modelo)
            {
        $modelo = modelos_bici::findOrFail($id_modelo);
        return view('Modelo.editar', compact('modelo'));
    }



    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('Modelo.crear');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_modelo'     => 'required|string|max:64|unique:modelos,id_modelo',
            'nombre_modelo' => 'required|string|max:15',
            'foto_modelo'   => 'nullable|string|max:255',
        ]);

        $modelo = new modelos_bici();
        $modelo->id_modelo    = $validated['id_modelo'];
        $modelo->nombre_modelo= $validated['nombre_modelo'];

        if ($request->hasFile('foto_modelo')) {
            $path = $request->file('foto_modelo')->store('modelos', 'public');
            $modelo->foto_modelo = $path;
        }
        $modelo->save();

        return redirect()->route('Modelo.index')
                         ->with('success', 'Modelo creado correctamente.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id_modelo)
    {
        $modelo = modelos_bici::findOrFail($id_modelo);
        return view('Modelo.editar', compact('modelo'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id_modelo)
    {
        $modelo = modelos_bici::findOrFail($id_modelo);

        $validated = $request->validate([
            'nombre_modelo'=> 'required|string|max:15',
            'eliminar_foto'=> 'nullable|boolean',
            'foto_modelo'  => 'nullable|image|max:2048',
        ]);

        $modelo->nombre_modelo = $validated['nombre_modelo'];

        if ($request->filled('eliminar_foto') && $modelo->foto_modelo) {
            Storage::disk('public')->delete($modelo->foto_modelo);
            $modelo->foto_modelo = null;
        }

        if ($request->hasFile('foto_modelo')) {
            if ($modelo->foto_modelo) {
                Storage::disk('public')->delete($modelo->foto_modelo);
            }
            $path = $request->file('foto_modelo')->store('modelos', 'public');
            $modelo->foto_modelo = $path;
        }

        $modelo->save();

        return redirect()->route('Modelo.ver')
                         ->with('success', 'Modelo actualizado correctamente.');
    }


       public function mostrarImagen(string $path)
{
    if (! Storage::disk('local')->exists($path)) {
        abort(404);
    }

    $fullPath = storage_path('app/' . $path);
    return response()->file($fullPath);
}

    
}
