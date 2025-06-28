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
            'voltaje'               => 'nullable|string|max:10',
            'error_iden_produccion' => 'nullable|string|max:255',
        ]);

        DB::beginTransaction();

        try {
            // Actualizar registro
            Bicicleta::where('num_chasis', $validated['num_chasis'])
                ->update([
                    'id_color'               => $validated['id_color'],
                    'id_lote'                => $validated['id_lote'],
                    'id_tipoStock'           => $validated['id_tipoStock'],
                    'codigo_barras'          => $validated['num_chasis'],
                    'voltaje'                => $validated['voltaje'] ?? "Sin Vol",
                    'error_iden_produccion'  => $validated['error_iden_produccion'] ?? null,
                    'updated_at'             => now(),
                ]);

            // Enviar impresión
            $printResult = $this->enviarPrintNode($validated['num_chasis']);

            DB::commit();

            return redirect()->route('Bicicleta.crear')
                ->with('success', '¡Bicicleta guardada e impresa correctamente!')
                ->with('print_response', $printResult);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error en store BicicletaController:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return back()
                ->with('error', 'Error: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Envía impresión a PrintNode API
     */
private function enviarPrintNode(string $codigo): array
{
    // Crear conector de impresión
    $connector = new DummyPrintConnector();
    $printer = new Printer($connector);

    try {
        // 1. Construcción del contenido ESC/POS
        $printer->setJustification(Printer::JUSTIFY_CENTER);
        $printer->selectPrintMode(Printer::MODE_DOUBLE_HEIGHT | Printer::MODE_DOUBLE_WIDTH);
        $printer->text("Etiqueta QR\n");
        $printer->selectPrintMode(); // Modo normal
        $printer->feed(1);

        // QR y texto
        $printer->qrCode($codigo, Printer::QR_ECLEVEL_H, 8, Printer::QR_MODEL_2);
        $printer->feed(1);
        $printer->text("Código: " . $codigo . "\n");
        $printer->feed(3);
        $printer->cut();

        // 2. Cierre obligatorio del conector
        $printer->close(); // ✅ ¡IMPORTANTE!

        // 3. Obtener los datos en crudo
        $raw = $connector->getData();

        // 4. Enviar a PrintNode
        $client = new Client([
            'base_uri' => 'https://api.printnode.com/',
            'auth'     => [config('printnode.api_key'), ''],
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

        // 5. Procesar respuesta
        $body = (string) $response->getBody();

        // Log para depuración (opcional, útil en desarrollo)
        Log::debug('Respuesta cruda de PrintNode:', ['body' => $body]);

        $decoded = json_decode($body, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \Exception('JSON inválido: ' . json_last_error_msg() . ' | Cuerpo: ' . $body);
        }

        if (!is_array($decoded)) {
            throw new \Exception('Respuesta inesperada de PrintNode: ' . $body);
        }

        // 6. Log y retorno final
        Log::info('Impresión exitosa', ['codigo' => $codigo, 'response' => $decoded]);

        return [
            'status'    => 'success',
            'message'   => '🎉 ¡QR impreso con éxito!',
            'data'      => $decoded,
            'timestamp' => now()->toDateTimeString(),
        ];
    } catch (\Exception $e) {
        try {
            // Asegura el cierre si algo falló antes
            $printer->close();
        } catch (\Exception $inner) {
            // Silenciar cierre fallido
        }

        Log::error('Error al imprimir con PrintNode:', ['error' => $e->getMessage()]);
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

    if (! $numChasis) {
        return response()->json([
            'success' => false,
            'message' => 'Debe ingresar un número de chasis',
            'bici'    => null
        ]);
    }

    try {
        $bici = Bicicleta::where('num_chasis', $numChasis)
                    ->with(['modelo', 'color', 'tipoStock', 'pedido']) // incluimos relación
                    ->first();

        if (! $bici) {
            return response()->json([
                'success' => false,
                'message' => 'Bicicleta no encontrada',
                'bici'    => null
            ]);
        }

        // Agregamos el campo extra a la respuesta
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
        $bicicletas = Bicicleta::with(['modelo','color','lote','tipoStock'])
                        ->orderBy('updated_at','desc')->take(8)->get();
        $modelos  = $bicicletas->pluck('modelo')->filter()->unique('id')->values();
        $colores  = $bicicletas->pluck('color')->filter()->unique('id')->values();
        $lotes    = $bicicletas->pluck('lote')->filter()->unique('id')->values();
        return view('Bicicleta.vista', compact('bicicletas','modelos','colores','lotes'));
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