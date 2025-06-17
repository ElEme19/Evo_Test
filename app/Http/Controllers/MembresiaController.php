<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Membresia;

class MembresiaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:usuarios');
    }

    // Mostrar todas las membresías
    public function index()
    {
        $membresias = Membresia::all();
        return view('Membresia.index', compact('membresias'));
    }

    // Mostrar formulario de creación
    public function create()
{
    $ultima = Membresia::orderBy('id_membresia', 'desc')->first();
    $nextId = $ultima
        ? 'MEM' . str_pad((int)substr($ultima->id_membresia, 3) + 1, 3, '0', STR_PAD_LEFT)
        : 'MEM001';

    return view('Membresia.create', compact('nextId'));
}


    // Almacenar una nueva membresía
    public function store(Request $request)
{
    $request->validate([
        'descripcion_general' => 'required|string|max:255',
    ]);

    $ultima = Membresia::orderBy('id_membresia', 'desc')->first();
    $nuevoId = $ultima
        ? 'MEM' . str_pad((int)substr($ultima->id_membresia, 3) + 1, 3, '0', STR_PAD_LEFT)
        : 'MEM001';

    Membresia::create([
        'id_membresia' => $nuevoId,
        'descripcion_general' => $request->descripcion_general,
    ]);

    return redirect()->route('Membresia.create')->with('success', '¡Membresía creada correctamente!');
}


   public function actualizar(Request $request, $id)
{
    $request->validate([
        'descripcion_general' => 'required|string|max:255',
    ]);

    $membresia = Membresia::findOrFail($id);
    $membresia->descripcion_general = $request->input('descripcion_general');
    $membresia->save();

    return redirect()->route('Membresia.index')->with('success', '¡Membresía actualizada correctamente!');
}

    // Eliminar una membresía
    public function destroy($id)
    {
        $membresia = Membresia::findOrFail($id);
        $membresia->delete();

        return redirect()->route('membresia.index')->with('success', '¡Membresía eliminada correctamente!');
    }
}