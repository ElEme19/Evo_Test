<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TipoStock;

class TipoStockController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:usuarios');
    }

    public function crear()
{
    $ultimo = TipoStock::orderBy('id_tipoStock', 'desc')->first();
    $nextId = $ultimo
        ? 'STK' . str_pad((int)substr($ultimo->id_tipoStock, 3) + 1, 3, '0', STR_PAD_LEFT)
        : 'STK001';

    return view('Stock.crear', compact('nextId'));
}



public function store(Request $request)
{
    $request->validate([
        'nombre_stock' => 'required|string|max:32',
    ]);

    try {
        // Generar el ID automáticamente
        $ultimo = TipoStock::orderBy('id_tipoStock', 'desc')->first();
        if ($ultimo) {
            $numero = (int) substr($ultimo->id_tipoStock, 3);
            $nuevoId = 'STK' . str_pad($numero + 1, 3, '0', STR_PAD_LEFT);
        } else {
            $nuevoId = 'STK001';
        }

        // Crear el nuevo tipo de stock
        TipoStock::create([
            'id_tipoStock' => $nuevoId,
            'nombre_stock' => $request->nombre_stock,
        ]);

        return redirect()->route('Stock.crear')->with('success', '¡Tipo de stock creado correctamente!');
    } catch (\Exception $e) {
        return redirect()->route('Stock.crear')->with('error', 'Ocurrió un error: ' . $e->getMessage());
    }
}




    public function ver()
    {
        $tipos = TipoStock::paginate(7);
        return view('Stock.vista', compact('tipos'));
    }



    
    public function buscar(Request $request)
    {
        $busqueda = $request->input('buscar');

        $tipos = TipoStock::where('id_tipoStock', 'like', "%$busqueda%")
                    ->orWhere('nombre_stock', 'like', "%$busqueda%")
                    ->get();

        return view('Stock.vista', compact('tipos'));
    }




    public function update(Request $request, $id_tipoStock)
    {
        $request->validate([
            'nombre_stock' => 'required|string|max:32',
        ]);

        $updated = TipoStock::where('id_tipoStock', $id_tipoStock)
            ->update([
                'nombre_stock' => $request->nombre_stock,
            ]);

        if ($updated) {
            return redirect()->route('Stock.ver')->with('success', 'Tipo de stock actualizado correctamente!');
        } else {
            return redirect()->back()->with('error', 'No se pudo actualizar el tipo de stock.');
        }
    }

}
