<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pieza;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use App\Models\modelos_bici;


class PiezasBController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:usuarios');
    }



    public function ver()
    {
        $piezas = Pieza::all();
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
        'id_modelo' => 'required|string|max:45',
        'id_colorM' => 'required|string|max:45',
        'descripcion_general' => 'required|string',
        'foto_pieza' => 'nullable|image|max:2048',
    ]);

    $prefijo = strtoupper($request->id_modelo);

    // Generar ID aleatorio único
    $intentos = 0;
    do {
        $aleatorio = str_pad(mt_rand(0, 99999), 5, '0', STR_PAD_LEFT);  // ejemplo: 04839
        $nuevoIdPieza = $prefijo . $aleatorio;
        $existe = Pieza::where('id_pieza', $nuevoIdPieza)->exists();
        $intentos++;
    } while ($existe && $intentos < 10);

    if ($existe) {
        return back()->with('error', 'No se pudo generar un ID único para la pieza. Intente nuevamente.');
    }

    // Crear pieza
    $pieza = new Pieza();
    $pieza->id_pieza = $nuevoIdPieza;
    $pieza->id_modelo = $request->id_modelo;
    $pieza->id_colorM = $request->id_colorM;
    $pieza->descripcion_general = $request->descripcion_general;

    if ($request->hasFile('foto_pieza')) {
        $file = $request->file('foto_pieza');
        $filename = Str::slug($nuevoIdPieza) . '_' . time() . '.' . $file->getClientOriginalExtension();
        $file->storeAs('public/fotos_piezas', $filename);
        $pieza->foto_pieza = $filename;
    }

    $pieza->save();

    return redirect()->route('pieza.ver')->with('success', 'Pieza creada correctamente con ID: ' . $nuevoIdPieza);
}





    public function editar(Pieza $pieza)
    {
        return view('PiezasB.editar', compact('pieza'));
    }

    public function update(Request $request, Pieza $pieza)
    {
        $request->validate([
            'id_pieza' => 'required|string|max:45|unique:piezas,id_pieza,' . $pieza->id_pieza . ',id_pieza',
            'id_modelo' => 'required|string|max:45',
            'id_colorM' => 'required|string|max:45',
            'descripcion_general' => 'required|string',
            'foto_pieza' => 'nullable|image|max:2048',
        ]);

        $pieza->id_pieza = $request->id_pieza;
        $pieza->id_modelo = $request->id_modelo;
        $pieza->id_colorM = $request->id_colorM;
        $pieza->descripcion_general = $request->descripcion_general;

        if ($request->hasFile('foto_pieza')) {
            if ($pieza->foto_pieza) {
                Storage::delete('public/fotos_piezas/' . $pieza->foto_pieza);
            }
            $file = $request->file('foto_pieza');
            $filename = Str::slug($request->id_pieza).'_'.time().'.'.$file->getClientOriginalExtension();
            $file->storeAs('public/fotos_piezas', $filename);
            $pieza->foto_pieza = $filename;
        }

        $pieza->save();

        return redirect()->route('PiezasB.ver')->with('success', 'Pieza actualizada correctamente!');
    }

    
}
