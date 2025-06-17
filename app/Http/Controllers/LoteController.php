<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Lote;
use App\Models\TipoStock;

class LoteController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:usuarios');
    }

    public function crear()
    {
        return view('Lote.crear');
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_lote' => 'required|string|max:64|unique:lote,id_lote',
            'fecha_produccion' => 'required|date',
        ]);

        try {
            $lote = new Lote();
            $lote->id_lote = $request->id_lote;
            $lote->fecha_produccion = $request->fecha_produccion;
            

            if ($lote->save()) {
                return redirect()->back()->with('success', '¡Lote creado correctamente!');
            } else {
                return redirect()->back()->with('error', 'No se pudo crear el lote. Intenta nuevamente.');
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Ocurrió un error inesperado: ' . $e->getMessage());
        }
    }

    public function ver()
{
    $lotes = Lote::all(); 
    return view('Lote.vista', compact('lotes')); 
}




    public function update(Request $request, $id_lote)
    {
        $request->validate([
            'fecha_produccion' => 'required|date',
        ]);

        $updated = Lote::where('id_lote', $id_lote)
            ->update([
                'fecha_produccion' => $request->fecha_produccion,
            ]);

        if ($updated) {
            return redirect()->route('Lote.vista')->with('success', 'Lote actualizado correctamente!');
        } else {
            return redirect()->back()->with('error', 'No se pudo actualizar el lote.');
        }
    }

   
    
}
