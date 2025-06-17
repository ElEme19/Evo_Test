<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Bicicleta;
use App\Models\modelos_bici;
use App\Models\ColorModelo;
use App\Models\Lote;
use App\Models\TipoStock;

class BicicletaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:usuarios');
    }

    public $timestamps = false;

    // Mostrar formulario para crear bicicleta
    public function crear()
    {
        $modelos = modelos_bici::all();
        $colores = ColorModelo::all();
        $lotes = Lote::all();
        $tipos = TipoStock::all();

        return view('bicicleta.crear', compact('modelos', 'colores', 'lotes', 'tipos'));
    }


    // Obtener colores según modelo (para uso con JS/AJAX)
    public function coloresPorModelo($id_modelo)
    {
    try {
        $colores = ColorModelo::where('id_modelo', $id_modelo)->get();

        return response()->json($colores);
    } catch (\Exception $e) {
        return response()->json(['error' => 'No se pudieron cargar los colores.'], 500);
    }
    }

    
    // Guardar bicicleta
    public function store(Request $request)
    {
         //dd($request->all());
        $request->validate([
            'num_chasis' => 'required|string|max:64|unique:bicicleta,num_chasis',
            'id_modelo' => 'required|string|exists:modelos,id_modelo',
            'id_color' => 'required|string|exists:color_modelo,id_colorM',
            'id_lote' => 'required|string|exists:lote,id_lote',
            'id_tipoStock' => 'required|string|exists:tipo_stock,id_tipoStock',
            'voltaje' => 'nullable|string|max:10',
            'num_motor' => 'nullable|string|max:64',
            'error_iden_produccion' => 'nullable|string|max:255',
            'descripcion_general' => 'nullable|string|max:255',
        ]);

        try {
            $bicicleta = new Bicicleta();
            $bicicleta->num_chasis = $request->num_chasis;
            $bicicleta->id_modelo = $request->id_modelo;
            $bicicleta->id_color = $request->id_color;
            $bicicleta->id_lote = $request->id_lote;
            $bicicleta->id_tipoStock = $request->id_tipoStock;
            $bicicleta->voltaje = $request->voltaje;
            $bicicleta->num_motor = $request->num_motor;
            $bicicleta->error_iden_produccion = $request->error_iden_produccion;


            if ($bicicleta->save()) {
                return redirect()->back()->with('success', '¡Bicicleta creada correctamente!');
            } else {
                return redirect()->back()->with('error', 'No se pudo crear la bicicleta. Intenta nuevamente.');
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error inesperado: ' );
        }
    }

    // Ver bicicletas

    public function ver()
    {
        // Obtener solo las últimas 5 bicicletas (ordenadas por id descendente)
    $bicicletas = bicicleta::with(['modelo', 'color', 'lote', 'tipoStock'])
                    ->orderBy('updated_at', 'desc')
                    ->take(8)
                    ->get();

    $modelos = $bicicletas->pluck('modelo')->filter()->unique('id')->values();
    $colores = $bicicletas->pluck('color')->filter()->unique('id')->values();
    $lotes   = $bicicletas->pluck('lote')->filter()->unique('id')->values();

     //dd($bicicletas); // Ver toda la colección con relaciones

    return view('bicicleta.vista', compact('bicicletas', 'modelos', 'colores', 'lotes'));
        }


// Busqueda por Num de Serie o Hachis

    public function buscarC(Request $request)
    {
        $numChasis = $request->query('num_chasis');

        if (!$numChasis) {
            return response()->json(['bici' => null]);
        }

        $bici = bicicleta::where('num_chasis', $numChasis)
            ->with(['modelo', 'color', 'tipoStock'])
            ->first();

        // Retornar datos en JSON para que el JS los reciba
        return response()->json(['bici' => $bici]);
}


// Busqueda por Num de Motor

   public function buscarMotor(Request $request)
{
    $numMotor = $request->query('num_motor');

    if (!$numMotor) {
        return response()->json(['bici' => null]);
    }

    $bici = Bicicleta::where('num_motor', $numMotor)
        ->with(['modelo', 'color', 'tipoStock'])
        ->first();
        
        //dd($bici);
    return response()->json(['bici' => $bici]);
}


// Busqueda por Modelo

        public function buscarModelo(Request $request)
        {
            $idModelo = $request->query('modelo');

            // Si no envían un modelo, devolvemos sólo la lista de modelos
            if (! $idModelo) {
                // Seleccionamos sólo los campos que necesitamos y usamos get()
                $modelos = modelos_bici::select('id_modelo', 'nombre_modelo')
                                    ->orderBy('nombre_modelo')
                                    ->get();

                return response()->json([
                    'modelos' => $modelos,
                    'bicis'   => [],
                ]);
            }

            // Si envían un modelo, devolvemos las bicis filtradas
            $bicis = Bicicleta::with(['modelo:id_modelo,nombre_modelo', 
                                    'color:id_colorM,nombre_color', 
                                    'tipoStock:id_tipoStock,nombre_stock'])
                ->where('id_modelo', $idModelo)
                ->get();  // usamos get() para traer todas las coincidencias

            return response()->json([
                'modelos' => [],
                'bicis'   => $bicis,
            ]);
        }


// Busqueda por Stock

public function buscarPorStock(Request $request)
{
    $idStock = $request->query('stock');
    if (! $idStock) {
        $stocks = TipoStock::all(['id_tipoStock','nombre_stock']);
        return response()->json(['stocks' => $stocks, 'bicis' => []]);
    }
    $bicis = Bicicleta::with(['modelo','color','tipoStock'])
             ->where('id_tipoStock', $idStock)
             ->paginate(6); 
    return response()->json(['stocks'=>[], 'bicis'=>$bicis]);
}





//    public function update(Request $request, $num_chasis)
// {
//     $request->validate([
//     'id_tipoStock' => 'required|string|exists:tipo_stock,id_tipoStock',
//     'voltaje' => 'nullable|string|max:10',
//     'num_motor' => 'nullable|string|max:64',
//     'error_iden_produccion' => 'nullable|string|max:255',
// ]);

//     $updated = Bicicleta::where('num_chasis', $num_chasis)
//     ->update([
//         'id_tipoStock' => $request->id_tipoStock,
//         'voltaje' => $request->voltaje,
//         'num_motor' => $request->num_motor,
//         'error_iden_produccion' => $request->error_iden_produccion,
//     ]);


//     if ($updated) {
//         return redirect()->route('Bicicleta.ver')->with('success', 'Bicicleta actualizada correctamente!');
//     } else {
//         return redirect()->back()->with('error', 'No se pudo actualizar la bicicleta.');
//     }
// }



}
