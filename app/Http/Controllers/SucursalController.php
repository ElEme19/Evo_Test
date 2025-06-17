<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Sucursal;
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
        return view('Sucursal.crear');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre_sucursal' => 'required|string|max:64',
            'localizacion'    => 'nullable|string|max:64',
            'foto_fachada'    => 'nullable|image|max:2048', // permite hasta 2 MB
        ]);

        try {
          
            $sucursal = new Sucursal();
            $sucursal->id_sucursal     = strtoupper('SUC-' . Str::random(10));
            $sucursal->nombre_sucursal = $request->nombre_sucursal;
            $sucursal->localizacion    = $request->localizacion;

            
            if ($request->hasFile('foto_fachada')) {
                $file      = $request->file('foto_fachada');
                $subpath = $file->store('fachadas');
                $sucursal->foto_fachada = $subpath;
            }
            $sucursal->save();

            return redirect()->back()->with('success', '¡Sucursal creada correctamente con ID: ' . $sucursal->id_sucursal . '!');
        } catch (\Exception $e) {
            return redirect() ->back()->with('error', 'Ocurrió un error al crear la sucursal: ' . $e->getMessage());
        }
    }

    

    public function ver()
    {
        $sucursales = Sucursal::all();
        return view('Sucursal.vista', compact('sucursales'));
    }

    public function update(Request $request, $id_sucursal)
    {
        $request->validate([
            'nombre_sucursal' => 'required|string|max:64',
            'localizacion'    => 'nullable|string|max:64',
            'foto_fachada'    => 'nullable|image|max:2048',
        ]);

        try {
            $sucursal = Sucursal::findOrFail($id_sucursal);
            $sucursal->nombre_sucursal = $request->nombre_sucursal;
            $sucursal->localizacion    = $request->localizacion;

            if ($request->hasFile('foto_fachada')) {

                if ($sucursal->foto_fachada && Storage::disk('public')->exists($sucursal->foto_fachada)) {
                    Storage::disk('public')->delete($sucursal->foto_fachada);
                }

               
                $file    = $request->file('foto_fachada');
                $subpath = $file->store('fachadas');
                $sucursal->foto_fachada = $subpath;
            }

            $sucursal->save();

            return redirect()
                ->route('sucursal.vista')
                ->with('success', '¡Sucursal actualizada correctamente!');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Error al actualizar: ' . $e->getMessage());
        }
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
