<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Personal;
use App\Models\Area; // En caso de que existan áreas relacionadas

class PersonalController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:usuarios');
    }

    // Mostrar formulario de creación
    public function crear()
    {
        $areas = Area::all();
        return view('personal.crear', compact('areas'));
    }

    // Guardar nuevo personal
    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_area'       => 'required|exists:area,id_area',
            'nombre'        => 'required|string|max:100',
            'apellido'      => 'required|string|max:100',
            'telefono'      => 'nullable|string|max:20',
            'foto_personal' => 'nullable|image|max:2048',
            'direccion'     => 'nullable|string|max:255',
            'antiguedad'    => 'nullable|integer|min:0',
            'salario'       => 'nullable|numeric|min:0',
        ]);

        if ($request->hasFile('foto_personal')) {
            $path = $request->file('foto_personal')->store('fotos_personal', 'public');
            $validated['foto_personal'] = $path;
        }

        Personal::create($validated);

        return redirect()->route('personal.ver')->with('success', '¡Personal registrado correctamente!');
    }

    // Ver todos los registros de personal
    public function ver()
    {
        $personal = Personal::with('area')->orderBy('id_personal', 'desc')->paginate(10);
        return view('personal.ver', compact('personal'));
    }

    // Buscar por nombre o apellido
    public function buscar(Request $request)
    {
        $query = $request->query('q');

        $resultados = Personal::where('nombre', 'like', '%' . $query . '%')
                        ->orWhere('apellido', 'like', '%' . $query . '%')
                        ->get();

        return response()->json(['personal' => $resultados]);
    }

    // Mostrar formulario de edición
    public function editar($id)
    {
        $persona = Personal::findOrFail($id);
        $areas = Area::all();
        return view('personal.editar', compact('persona', 'areas'));
    }

    // Actualizar un registro
    public function update(Request $request, $id)
    {
        $persona = Personal::findOrFail($id);

        $validated = $request->validate([
            'id_area'       => 'required|exists:area,id_area',
            'nombre'        => 'required|string|max:100',
            'apellido'      => 'required|string|max:100',
            'telefono'      => 'nullable|string|max:20',
            'foto_personal' => 'nullable|image|max:2048',
            'direccion'     => 'nullable|string|max:255',
            'antiguedad'    => 'nullable|integer|min:0',
            'salario'       => 'nullable|numeric|min:0',
        ]);

        if ($request->hasFile('foto_personal')) {
            $path = $request->file('foto_personal')->store('fotos_personal', 'public');
            $validated['foto_personal'] = $path;
        }

        $persona->update($validated);

        return redirect()->route('personal.ver')->with('success', '¡Datos actualizados correctamente!');
    }

    // Eliminar un registro
    public function eliminar($id)
    {
        $persona = Personal::findOrFail($id);
        $persona->delete();

        return redirect()->route('personal.ver')->with('success', 'Registro eliminado correctamente.');
    }
}
