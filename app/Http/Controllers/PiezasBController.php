<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Pieza;
use App\Models\modelos_bici;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class PiezasBController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:usuarios');
    }

    public function ver()
    {
        $piezas = Pieza::with('modelo')->get();
        return view('PiezasB.ver', compact('piezas'));
    }


    

    public function crear()
    {
        $modelos = modelos_bici::all();
        return view('PiezasB.crear', compact('modelos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_modelo'           => 'required|string|max:65',
            'nombre_pieza'        => 'required|string|max:255',
            'color'               => 'nullable|string|max:100',
            'descripcion_general' => 'required|string',
            'foto_pieza'          => 'nullable|image|max:2048',
        ]);

        try {
            // Valor de color predeterminado
            $color = $request->filled('color') ? $request->color : 'No aplica';

            // Prefijo (EVO_SOL -> ESOL)
            $parts = explode('_', strtoupper($request->id_modelo));
            $prefijo = count($parts) >= 2
                ? substr($parts[0], 0, 1) . $parts[1]
                : strtoupper(substr($request->id_modelo, 0, 4));

            // Generar ID único
            $id = null;
            $intentos = 0;
            do {
                $aleatorio = strtoupper(Str::random(5));
                $id = $prefijo . '_' . $aleatorio;
                $existe = Pieza::where('id_pieza', $id)->exists();
                $intentos++;
            } while ($existe && $intentos < 10);

            if ($existe) {
                return back()->with('error', 'No se pudo generar un ID único para la pieza. Intente nuevamente.');
            }

            // Crear Pieza
            $pieza = new Pieza([
                'id_pieza'            => $id,
                'id_modelo'           => $request->id_modelo,
                'nombre_pieza'        => $request->nombre_pieza,
                'color'               => $color,
                'descripcion_general' => $request->descripcion_general,
            ]);

            // Guardar imagen en storage/app/fotos_piezas
            if ($request->hasFile('foto_pieza')) {
                $file = $request->file('foto_pieza');
                $filename = time() . '_' . Str::slug($id) . '.' . $file->getClientOriginalExtension();
                // Almacena en storage/app/fotos_piezas
                $path = $file->storeAs('fotos_piezas', $filename);
                // Guardar ruta relativa en BD
                $pieza->foto_pieza = $path;
            }

            $pieza->save();

            return redirect()->route('pieza.crear')
                             ->with('success', 'Pieza creada correctamente con ID: ' . $id);

        } catch (\Exception $e) {
            return back()->with('error', 'Error al crear la pieza: ' . $e->getMessage());
        }
    }



    

    public function editar(Pieza $pieza)
    {
        $modelos = modelos_bici::all();
        return view('PiezasB.editar', compact('pieza', 'modelos'));
    }

    public function update(Request $request, Pieza $pieza)
    {
        $request->validate([
            'id_pieza'             => 'required|string|max:45|unique:piezas,id_pieza,' . $pieza->id_pieza . ',id_pieza',
            'id_modelo'            => 'required|string|max:65',
            'nombre_pieza'         => 'required|string|max:255',
            'color'                => 'nullable|string|max:100',
            'descripcion_general'  => 'required|string',
            'foto_pieza'           => 'nullable|image|max:2048',
        ]);

        try {
            // Actualizar campos
            $pieza->id_pieza            = $request->id_pieza;
            $pieza->id_modelo           = $request->id_modelo;
            $pieza->nombre_pieza        = $request->nombre_pieza;
            $pieza->color               = $request->filled('color') ? $request->color : 'No aplica';
            $pieza->descripcion_general = $request->descripcion_general;

            // Reemplazar imagen si se envía nueva
            if ($request->hasFile('foto_pieza')) {
                // Eliminar antigua si existe
                if ($pieza->foto_pieza && Storage::exists($pieza->foto_pieza)) {
                    Storage::delete($pieza->foto_pieza);
                }
                $file = $request->file('foto_pieza');
                $filename = time() . '_' . Str::slug($request->id_pieza) . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs('fotos_piezas', $filename);
                $pieza->foto_pieza = $path;
            }

            $pieza->save();

            return redirect()->route('pieza.crear')
                             ->with('success', 'Pieza actualizada correctamente!');
        } catch (\Exception $e) {
            return back()->with('error', 'Error al actualizar la pieza: ' . $e->getMessage());
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
