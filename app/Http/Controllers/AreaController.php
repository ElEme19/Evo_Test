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
        $areas = Area::orderBy('id_area', 'desc')->paginate(10);
        return view('area.ver', compact('areas'));
    }

    // Mostrar formulario de creación
    public function crear()
    {
        return view('area.crear');
    }

    // Guardar nueva área
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre_area' => 'required|string|max:100|unique:area,nombre_area',
        ]);

        Area::create($validated);

        return redirect()->route('area.ver')->with('success', 'Área registrada correctamente');
    }

    // Mostrar formulario de edición
    public function editar($id)
    {
        $area = Area::findOrFail($id);
        return view('area.editar', compact('area'));
    }

    // Actualizar área
    public function update(Request $request, $id)
    {
        $area = Area::findOrFail($id);

        $validated = $request->validate([
            'nombre_area' => 'required|string|max:100|unique:area,nombre_area,' . $id . ',id_area',
        ]);

        $area->update($validated);

        return redirect()->route('area.ver')->with('success', 'Área actualizada correctamente');
    }

    // Eliminar área
    public function eliminar($id)
    {
        $area = Area::findOrFail($id);
        $area->delete();

        return redirect()->route('area.ver')->with('success', 'Área eliminada correctamente');
    }
}
