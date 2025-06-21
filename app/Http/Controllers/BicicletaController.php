<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Bicicleta;
use App\Models\modelos_bici;
use App\Models\ColorModelo;
use App\Models\Lote;
use App\Models\TipoStock;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;


class BicicletaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:usuarios');
    }


 public function buscarPorUltimos4(Request $request)
{
    $ult4 = $request->query('ult4');

    if (!$ult4 || strlen($ult4) !== 4) {
        return response()->json(['bicicleta' => null]);
    }

    try {
        $bici = Bicicleta::where(DB::raw('RIGHT(num_chasis, 4)'), $ult4)
                         ->with(['modelo', 'color'])
                         ->first();

        if (!$bici) {
            return response()->json(['bicicleta' => null]);
        }

        return response()->json([
            'bicicleta' => [
                'num_chasis' => $bici->num_chasis,
                'id_modelo' => $bici->id_modelo,
                'id_color' => $bici->id_color,
                'modelo' => [
                    'nombre_modelo' => optional($bici->modelo)->nombre_modelo
                ],
                'color' => [
                    'nombre_color' => optional($bici->color)->nombre_color
                ]
            ]
        ]);

    } catch (\Exception $e) {
        \Log::error('Error en buscarPorUltimos4(): ' . $e->getMessage());
        return response()->json(['bicicleta' => null], 500);
    }
}
    public $timestamps = false;

    // Mostrar formulario para crear bicicleta
    public function crear()
    {
        $modelos = modelos_bici::all();
        $colores = ColorModelo::all();
        $lotes = Lote::all();
        $tipos = TipoStock::all();

        return view('Bicicleta.crear', compact('modelos', 'colores', 'lotes', 'tipos'));
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
    $validated = $request->validate([
        'num_chasis' => 'required|string|exists:bicicleta,num_chasis',
        'id_color' => 'required|string|exists:color_modelo,id_colorM',
        'id_lote' => 'required|string|exists:lote,id_lote',
        'id_tipoStock' => 'required|string|exists:tipo_stock,id_tipoStock',
        'voltaje' => 'nullable|string|max:10',
        'error_iden_produccion' => 'nullable|string|max:255',
    ]);

    DB::beginTransaction();


    DB::beginTransaction();

    try {
        // 1. Actualización en base de datos (tu código actual)
        $updateData = [
            // ... (tus campos actuales)
            'codigo_barras' => $validated['num_chasis']
        ];
        
        DB::table('bicicleta')->where('num_chasis', $validated['num_chasis'])->update($updateData);

        // 2. Nuevo método de impresión (reemplazo de shell_exec)
        $resultado = $this->enviarImpresionLocal($validated['num_chasis']);

        DB::commit();

        return back()->with('success', '¡Bicicleta actualizada e impresa correctamente!');

    } catch (\Exception $e) {
        DB::rollBack();
        Log::error('Error en store:', ['error' => $e->getMessage()]);
        return back()->with('error', 'Error: ' . $e->getMessage())->withInput();
    }
}

// Nuevo método privado para manejar la impresión
private function enviarImpresionLocal(string $codigo): array
{
    $urlServicioLocal = 'http://localhost:9000/imprimir'; // Cambia por la IP del equipo con Python
    
    try {
        $response = Http::timeout(15) // 15 segundos de timeout
            ->retry(3, 500) // Reintenta 3 veces con 500ms de espera
            ->withHeaders(['Accept' => 'application/json'])
            ->post($urlServicioLocal, [
                'codigo' => $codigo,
                'api_key' => env('IMPRESORA_API_KEY') // Clave en tu .env
            ]);

        if ($response->failed()) {
            throw new \Exception("Error en servicio local: HTTP {$response->status()}");
        }

        return $response->json();

    } catch (\Illuminate\Http\Client\ConnectionException $e) {
        throw new \Exception("No se pudo conectar al servicio de impresión local");
    } catch (\Exception $e) {
        throw new \Exception("Error al imprimir: " . $e->getMessage());
    }
}

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

    return view('Bicicleta.vista', compact('bicicletas', 'modelos', 'colores', 'lotes'));
        }


// Busqueda por Num de Serie o Hachis

    public function buscarC(Request $request)
{
    $numChasis = $request->query('num_chasis');

    if (!$numChasis) {
        return response()->json([
            'success' => false,
            'message' => 'Debe ingresar un número de chasis',
            'bici' => null
        ]);
    }

    try {
        $bici = Bicicleta::where('num_chasis', $numChasis)
            ->with(['modelo', 'color'])
            ->first();

        if (!$bici) {
            return response()->json([
                'success' => false,
                'message' => 'Bicicleta no encontrada',
                'bici' => null
            ]);
        }

        return response()->json([
            'success' => true,
            'bici' => [
                'num_chasis' => $bici->num_chasis,
                'modelo' => $bici->modelo->nombre_modelo,
                'color' => $bici->color->nombre_color,
                // Datos adicionales consistentes con el otro método
                'id_modelo' => $bici->id_modelo,
                'id_color' => $bici->id_color
            ]
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Error en el servidor: '.$e->getMessage(),
            'bici' => null
        ], 500);
    }
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
