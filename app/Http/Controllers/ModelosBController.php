<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\modelos_bici;
use Illuminate\Support\Str;

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
    public function crear()
    {
        return view('Modelo.crear');
    }

    /**
     * Store a newly created resource in storage.
     */
   public function store(Request $request)
{
    // Validación
    $request->validate([
        'id_modelo'     => 'required|string|max:64|unique:modelos,id_modelo',
        'nombre_modelo' => 'required|string|max:15',
        'foto_modelo'   => 'nullable|image|max:2048',
    ]);

    try {
        $modelo = new modelos_bici();
        $modelo->id_modelo     = $request->id_modelo;
        $modelo->nombre_modelo = $request->nombre_modelo;

        if ($request->hasFile('foto_modelo')) {
            $file     = $request->file('foto_modelo');
            $filename = time()
                      . '_'
                      . Str::slug($request->id_modelo)
                      . '.'
                      . $file->getClientOriginalExtension();
            // Almacenamos en storage/app/modelos
            $path = $file->storeAs('modelos', $filename);
            $modelo->foto_modelo = $path; // Ej: "modelos/1234_mimodelo.jpg"
        }

        $modelo->save();

        return redirect()
            ->route('Modelo.ver')
            ->with('success', 'Modelo creado correctamente.');

    } catch (\Exception $e) {
        return back()
            ->with('error', 'Error al crear el modelo: ' . $e->getMessage());
    }
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

    // Validación
    $request->validate([
        'nombre_modelo'=> 'required|string|max:15',
        'eliminar_foto'=> 'nullable|boolean',
        'foto_modelo'  => 'nullable|image|max:2048',
    ]);

    $modelo->nombre_modelo = $request->nombre_modelo;

    // Eliminar foto previa si se solicitó
    if ($request->filled('eliminar_foto') && $modelo->foto_modelo) {
        Storage::delete($modelo->foto_modelo);
        $modelo->foto_modelo = null;
    }

    // Guardar nueva foto
    if ($request->hasFile('foto_modelo')) {
        // Si había foto previa, la borramos
        if ($modelo->foto_modelo) {
            Storage::delete($modelo->foto_modelo);
        }

        $file     = $request->file('foto_modelo');
        $filename = time()
                  . '_'
                  . Str::slug($modelo->id_modelo)
                  . '.'
                  . $file->getClientOriginalExtension();

        // Almacena en storage/app/modelos
        $path = $file->storeAs('modelos', $filename);
        $modelo->foto_modelo = $path; // ej. "modelos/1234_mimodelo.jpg"
    }

    $modelo->save();

    return redirect()
        ->route('Modelo.ver')
        ->with('success', 'Modelo actualizado correctamente.');
}



 public function mostrarImagen(string $path)
    {
        // Acceder a storage/app/
        $fullPath = storage_path('app/' . ltrim($path, '/'));
        if (!file_exists($fullPath)) {
            abort(404);
        }
        return response()->file($fullPath);
    }


    
}
