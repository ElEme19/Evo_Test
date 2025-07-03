<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Bicicleta;
use App\Models\modelos_bici;
use App\Models\ColorModelo;
use App\Models\Lote;
use App\Models\TipoStock;
use App\Models\VoltajeModelo;   
use Mike42\Escpos\PrintConnectors\DummyPrintConnector;
use Mike42\Escpos\Printer;




class BicicletaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:usuarios');
    }

    /**
     * Muestra el formulario para crear bicicleta
     */
    public function crear()
    {
        $modelos = modelos_bici::all();
        $colores = ColorModelo::all();
        $lotes   = Lote::all();
        $tipos   = TipoStock::all();

        return view('Bicicleta.crear', compact('modelos','colores','lotes','tipos'));
    }

    /**
     * Guarda la bicicleta y dispara la impresión vía PrintNode.
     */
public function store(Request $request)
{
    $validated = $request->validate([
        'num_chasis'            => 'required|string|exists:bicicleta,num_chasis',
        'id_color'              => 'required|string|exists:color_modelo,id_colorM',
        'id_lote'               => 'required|string|exists:lote,id_lote',
        'id_tipoStock'          => 'required|string|exists:tipo_stock,id_tipoStock',
        'id_voltaje'               => 'nullable|string|max:10',
    ]);

    DB::beginTransaction();

    try {
        // Guardar bicicleta
        Bicicleta::where('num_chasis', $validated['num_chasis'])
            ->update([
                'id_color'              => $validated['id_color'],
                'id_lote'               => $validated['id_lote'],
                'id_tipoStock'          => $validated['id_tipoStock'],
                'codigo_barras'         => $validated['num_chasis'],
                'id_voltaje'               => $validated['id_voltaje'] ?? 'VOLT000',
                'error_iden_produccion' => $validated['error_iden_produccion'] ?? null,
                'updated_at'            => now(),
            ]);

        DB::commit();

    } catch (\Exception $e) {
        DB::rollBack();
        Log::error('❌ Error al guardar bicicleta:', [
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
        ]);
        return back()
            ->with('error', 'Error al guardar: ' . $e->getMessage())
            ->withInput();
    }

    // ⚠️ Intentamos imprimir *fuera* del try de la base
    try {
        $colorNombre = ColorModelo::where('id_colorM', $validated['id_color'])->value('nombre_color') ?? 'Error';

        $printResult = $this->enviarPrintNode($validated['num_chasis'], $colorNombre);

        return redirect()->route('Bicicleta.crear')
            ->with('success', 'Bicicleta guardada e impresa correctamente.')
            ->with('print_response', $printResult);

    } catch (\Exception $e) {
        Log::error('⚠️ Bicicleta guardada, pero error al imprimir:', [
            'codigo' => $validated['num_chasis'],
            'error'  => $e->getMessage()
        ]);

        return redirect()->route('Bicicleta.crear')
            ->with('success', ' Bicicleta guardada correctamente.')
            ->with('warning', '⚠️ Error al imprimir: ' . $e->getMessage());
    }
}




    /**
     * Envía impresión a PrintNode API
     */
private function enviarPrintNode(string $codigo, $color): array
{
    try {
        $connector = new DummyPrintConnector();
        $printer = new Printer($connector);

        // NO usar initialize(), para evitar resetear la cola
        $printer->setJustification(Printer::JUSTIFY_CENTER);
        $printer->setLineSpacing(30); // valor pequeño pero no cero, para evitar problemas

       
        // QR con corrección alta y tamaño moderado
        $printer->qrCode($codigo, Printer::QR_ECLEVEL_H, 7, Printer::QR_MODEL_2);

        $printer->feed(1);

        $printer->text("No. Serie: $codigo\nColor: $color\n");

        $printer->feed(2); // algo de espacio para corte

        $printer->cut();

        $raw = $connector->getData();

        // Enviar a PrintNode
        $client = new Client([
            'base_uri' => 'https://api.printnode.com/account',
            'auth'     => [config('printnode.api_key'), ''],
            'timeout'  => 10,
        ]);

        $response = $client->post('printjobs', [
            'json' => [
                'printerId'   => config('printnode.printer_id'),
                'title'       => 'QR ' . $codigo,
                'contentType' => 'raw_base64',
                'content'     => base64_encode($raw),
                'source'      => 'MiAppLaravel',
            ],
        ]);

        $body = (string) $response->getBody();
        $decoded = json_decode($body, true);
        if (!is_array($decoded)) {
            throw new \Exception('Respuesta inesperada de PrintNode: ' . $body);
        }

        return [
            'status' => 'success',
            'message' => '🎉 ¡QR impreso con éxito!',
            'data' => $decoded,
            'timestamp' => now()->toDateTimeString()
        ];
    } catch (\Exception $e) {
        Log::error('Error al imprimir con PrintNode:', [
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);
        throw new \Exception('⚠️ Error en impresión: ' . $e->getMessage());
    }
}





     public function coloresPorModelo($id_modelo)
{
    try {
        $colores = ColorModelo::where('id_modelo', $id_modelo)
            ->get(['id_colorM', 'nombre_color']);

        return response()->json($colores);
    } catch (\Exception $e) {
        Log::error('Error al cargar colores:', ['error' => $e->getMessage()]); // ==>  Revisar, la problemas el /Log
        return response()->json([], 500);
    }
}




   public function voltajePorModelo($id_modelo)
{
    try {
        $voltajes = VoltajeModelo::where('id_modelo', $id_modelo)
            ->join('voltaje', 'voltaje_modelo.id_voltaje', '=', 'voltaje.id_voltaje')
            ->get([
                'voltaje_modelo.id_mVoltaje',
                'voltaje_modelo.id_voltaje',
                'voltaje.tipo_voltaje' // si tenés esta columna
            ]);

        return response()->json($voltajes);
    } catch (\Exception $e) {
        Log::error('Error al cargar Voltajes:', ['error' => $e->getMessage()]);
        return response()->json([], 500);
    }
}



    /**
     * Búsqueda por últimos 4 dígitos de chasis
     */
    public function buscarPorUltimos4(Request $request)
{
    $ult4 = $request->query('ult4');

    if (! $ult4 || strlen($ult4) !== 4) {
        return response()->json([
            'success' => false,
            'message' => 'Debe ingresar los últimos 4 caracteres del número de chasis',
            'bici'    => null
        ]);
    }

    try {
        $bici = Bicicleta::where(DB::raw('RIGHT(num_chasis, 4)'), $ult4)
                   ->with(['modelo', 'color', 'tipoStock', 'pedido']) // agrego tipoStock y pedido
                   ->first();

        if (! $bici) {
            return response()->json([
                'success' => false,
                'message' => 'Bicicleta no encontrada',
                'bici'    => null
            ]);
        }

        // agrego el campo pedido_asociado
        $biciData = $bici->toArray();
        $biciData['pedido_asociado'] = $bici->pedido ? true : false;

        return response()->json([
            'success' => true,
            'bici'    => $biciData
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Error en el servidor: ' . $e->getMessage(),
            'bici'    => null
        ], 500);
    }
}


    /**
     * Búsqueda por número de chasis completo
     */
    public function buscarC(Request $request)
{
    $numChasis = $request->query('num_chasis');

    if (!$numChasis) {
        return response()->json([
            'success' => false,
            'message' => 'Debe ingresar el número completo de chasis',
            'bici'    => null
        ]);
    }

    try {
        // Cargar bici con sus relaciones
        $bici = Bicicleta::with(['modelo', 'color', 'tipoStock', 'pedido', 'voltaje'])
                    ->where('num_chasis', $numChasis)
                    ->first();

        if (!$bici) {
            return response()->json([
                'success' => false,
                'message' => 'Bicicleta no encontrada',
                'bici'    => null
            ]);
        }

        // Convertir a array y añadir campo adicional
        $biciData = $bici->toArray();
        $biciData['pedido_asociado'] = !empty($bici->pedido);

        return response()->json([
            'success' => true,
            'bici'    => $biciData
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Error en el servidor: ' . $e->getMessage(),
            'bici'    => null
        ], 500);
    }
}




    /**
     * Búsqueda por número de motor
     */
    public function buscarMotor(Request $request)
    {
        $numMotor = $request->query('num_motor');
        if (! $numMotor) {
            return response()->json(['bici'=>null]);
        }
        $bici = Bicicleta::where('num_motor', $numMotor)
                ->with(['modelo','color','tipoStock'])
                ->first();
        return response()->json(['bici'=>$bici]);
    }

    
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

    /**
     * Búsqueda por modelo
     */
    public function buscarModelo(Request $request)
    {
        $idModelo = $request->query('modelo');
        if (! $idModelo) {
            $modelos = modelos_bici::select('id_modelo','nombre_modelo')->orderBy('nombre_modelo')->get();
            return response()->json(['modelos'=>$modelos,'bicis'=>[]]);
        }
        $bicis = Bicicleta::with(['modelo:id_modelo,nombre_modelo','color','tipoStock'])
                 ->where('id_modelo', $idModelo)->get();
        return response()->json(['modelos'=>[],'bicis'=>$bicis]);
    }

    /**
     * Muestra la vista con las últimas bicicletas
     */
    public function ver()
{
    $bicicletas = Bicicleta::with(['modelo','color','lote','tipoStock', 'voltaje'])
                    ->orderBy('updated_at','desc')->take(8)->get();

    $modelos  = $bicicletas->pluck('modelo')->filter()->unique('id')->values();
    $colores  = $bicicletas->pluck('color')->filter()->unique('id')->values();
    $lotes    = $bicicletas->pluck('lote')->filter()->unique('id')->values();
    $voltajes = $bicicletas->pluck('voltaje')->filter()->unique('id_voltaje')->values();

    return view('Bicicleta.vista', compact('bicicletas','modelos','colores','lotes','voltajes'));
}


     public function buscarPorUltimosSX(Request $request)
{
    $ult4 = $request->query('ult4');

    if (! $ult4 || strlen($ult4) !== 4) {
        return response()->json([
            'success' => false,
            'message' => 'Debe ingresar los últimos 4 caracteres del número de chasis',
            'bici'    => null
        ]);
    }

    try {
        // Solo traemos lo esencial: num_chasis completo, id_modelo, id_color, id_tipoStock, id_lote
        $bici = Bicicleta::where(DB::raw('RIGHT(num_chasis, 4)'), $ult4)
                   ->select('num_chasis', 'id_modelo', 'id_color', 'id_tipoStock', 'id_lote')
                   ->first();

        if (! $bici) {
            return response()->json([
                'success' => false,
                'message' => 'Bicicleta no encontrada',
                'bici'    => null
            ]);
        }

        return response()->json([
            'success' => true,
            'bici'    => $bici
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Error en el servidor: ' . $e->getMessage(),
            'bici'    => null
        ], 500);
    }
}



}