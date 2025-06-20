<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Area;

class AreaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:usuarios');
    }

    // Ver lista de áreas
    public function ver()
    {
        $area = Area::orderBy('id_area', 'desc')->paginate(10);
        return view('area.ver', compact('area'));
    }

    // Mostrar formulario (si lo usas como modal o vista separada)
    public function crear()
    {
        return view('Area.crear');
    }

    // Guardar nueva área con ID incremental tipo AR001, AR002...
    public function store(Request $request)
    {
        $request->validate([
            'nombre_area' => 'required|string|max:100|unique:areas,nombre_area',
        ]);

        // Buscar el último ID
        $ultima = Area::orderBy('id_area', 'desc')->first();
        $nuevoId = $ultima
            ? 'AR' . str_pad((int)substr($ultima->id_area, 2) + 1, 3, '0', STR_PAD_LEFT)
            : 'AR001';

        Area::create([
            'id_area' => $nuevoId,
            'nombre_area' => $request->nombre_area,
        ]);

        return redirect()->route('area.ver')->with('success', '¡Área registrada correctamente!');
    }

    // Editar área
    public function editar($id)
    {
        $area = Area::findOrFail($id);
        return view('Area.editar', compact('area'));
    }

    // Actualizar área
    public function update(Request $request, $id)
    {
        $area = Area::findOrFail($id);

        $request->validate([
            'nombre_area' => 'required|string|max:100|unique:areas,nombre_area,' . $id . ',id_area',
        ]);

        $area->update([
            'nombre_area' => $request->nombre_area,
        ]);

        return redirect()->route('area.ver')->with('success', '¡Área actualizada correctamente!');
    }

    // Eliminar área
    public function eliminar($id)
    {
        $area = Area::findOrFail($id);
        $area->delete();

        return redirect()->route('area.ver')->with('success', '¡Área eliminada correctamente!');
    }
}
