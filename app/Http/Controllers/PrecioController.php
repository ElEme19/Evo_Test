<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Precio;
use App\Models\Membresia;
use App\Models\Modelo;
use App\Models\modelos_bici;

class PrecioController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:usuarios');
    }

    // Mostrar todos los precios
    public function index()
    {
        $precios = Precio::with(['membresia', 'modelo'])->get();
        $todosLosPrecios = Precio::all();
        $membresias = Membresia::all();
        $modelos = modelos_bici::all();
        return view('Precio.index', compact('precios', 'membresias', 'modelos'));
    }

    // Mostrar formulario de creación
    public function create()
    {
        $ultima = Precio::orderBy('id_precio', 'desc')->first();
        $nextId = $ultima
            ? 'PRE' . str_pad((int)substr($ultima->id_precio, 3) + 1, 3, '0', STR_PAD_LEFT)
            : 'PRE001';

        $membresias = Membresia::all();
        $modelos = modelos_bici::all();
        return view('Precio.create', compact('nextId', 'membresias', 'modelos'));
    }

    // Almacenar un nuevo precio
    public function store(Request $request)
    {
        $request->validate([
            'id_membresia' => 'required|exists:membresia,id_membresia',
            'id_modelo' => 'required|exists:modelos,id_modelo',
            'precio' => 'required|numeric|min:0',
        ]);

        $ultima = Precio::orderBy('id_precio', 'desc')->first();
        $nuevoId = $ultima
            ? 'PRE' . str_pad((int)substr($ultima->id_precio, 3) + 1, 3, '0', STR_PAD_LEFT)
            : 'PRE001';

        $precio = new Precio();
        $precio->id_precio = $nuevoId;
        $precio->id_membresia = $request->id_membresia;
        $precio->id_modelo = $request->id_modelo;
        $precio->precio = $request->precio;

        $precio->save();

        return redirect()->route('Precio.create')->with('success', '¡Precio registrado correctamente!');
    }

    // Actualizar precio
    public function update(Request $request, $id)
    {
        $request->validate([
            'id_membresia' => 'required|exists:membresia,id_membresia',
            'id_modelo' => 'required|exists:modelo,id_modelo',
            'precio' => 'required|numeric|min:0',
        ]);

        $precio = Precio::findOrFail($id);
        $precio->id_membresia = $request->id_membresia;
        $precio->id_modelo = $request->id_modelo;
        $precio->precio = $request->precio;

        $precio->save();

        return redirect()->route('Precio.index')->with('success', '¡Precio actualizado correctamente!');
    }

    // Eliminar precio
    public function destroy($id)
    {
        $precio = Precio::findOrFail($id);
        $precio->delete();

        return redirect()->route('Precios.index')->with('success', '¡Precio eliminado correctamente!');
    }
}
