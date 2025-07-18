<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Sucursal;
  use App\Models\Cliente; // <--- Asegúrate de importar el modelo
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class SucursalController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:usuarios');
    }

 

public function crear()
{
    $clientes = Cliente::all(); // Obtener todos los clientes
    return view('Sucursal.crear', compact('clientes'));
}


    public function store(Request $request)
{
    $request->validate([
        'id_cliente'       => 'required|exists:clientes,id_cliente', // Validamos que exista en la tabla `clientes`
        'nombre_sucursal'  => 'required|string|max:64',
        'localizacion'     => 'nullable|string|max:64',
        'foto_fachada'     => 'nullable|image|max:2048',
    ]);

    try {
        $sucursal = new Sucursal();
        $sucursal->id_sucursal     = strtoupper('SUC-' . Str::random(10));
        $sucursal->id_cliente      = $request->id_cliente; // <- NUEVO
        $sucursal->nombre_sucursal = $request->nombre_sucursal;
        $sucursal->localizacion    = $request->localizacion;

        if ($request->hasFile('foto_fachada')) {
            $file = $request->file('foto_fachada');
            $subpath = $file->store('fachadas');
            $sucursal->foto_fachada = $subpath;
        }

        $sucursal->save();

        return redirect()->back()->with('success', '¡Sucursal creada correctamente con ID: ' . $sucursal->id_sucursal . '!');
    } catch (\Exception $e) {
        return redirect()->back()->with('error', 'Ocurrió un error al crear la sucursal: ' . $e->getMessage());
    }
}


    

   public function ver()
{
    $sucursales = Sucursal::with('cliente')->get(); 
    return view('Sucursal.vista', compact('sucursales'));
}




   public function update(Request $request, $id_sucursal)
{
    $request->validate([
        'id_cliente'       => 'required|exists:clientes,id_cliente',
        'nombre_sucursal'  => 'required|string|max:64',
        'localizacion'     => 'nullable|string|max:64',
        'foto_fachada'     => 'nullable|image|max:2048',
    ]);

    try {
        $sucursal = Sucursal::findOrFail($id_sucursal);
        $sucursal->id_cliente      = $request->id_cliente;
        $sucursal->nombre_sucursal = $request->nombre_sucursal;
        $sucursal->localizacion    = $request->localizacion;

        if ($request->hasFile('foto_fachada')) {
            if ($sucursal->foto_fachada && Storage::disk('public')->exists($sucursal->foto_fachada)) {
                Storage::disk('public')->delete($sucursal->foto_fachada);
            }

            $file = $request->file('foto_fachada');
            $subpath = $file->store('fachadas');
            $sucursal->foto_fachada = $subpath;
        }

        $sucursal->save();

        return redirect()->route('sucursal.vista')->with('success', '¡Sucursal actualizada correctamente!');
    } catch (\Exception $e) {
        return redirect()->back()->with('error', 'Error al actualizar: ' . $e->getMessage());
    }
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
