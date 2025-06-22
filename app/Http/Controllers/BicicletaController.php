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
use App\Jobs\EnviarTrabajoImpresion;

class BicicletaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:usuarios');
    }

    /**
     * Show the form for creating a new bicycle
     */
    public function crear()
    {
        $modelos = modelos_bici::all();
        $colores = ColorModelo::all();
        $lotes = Lote::all();
        $tipos = TipoStock::all();

        return view('Bicicleta.crear', compact('modelos', 'colores', 'lotes', 'tipos'));
    }

    /**
     * Get colors by model ID
     */
    public function coloresPorModelo($id_modelo)
    {
        try {
            $colores = ColorModelo::where('id_modelo', $id_modelo)
                       ->get(['id_colorM', 'nombre_color']);
            return response()->json($colores);
        } catch (\Exception $e) {
            Log::error('Error loading colors:', ['error' => $e->getMessage()]);
            return response()->json([], 500);
        }
    }

    /**
     * Store a newly created bicycle and trigger PrintNode printing
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'num_chasis' => 'required|string|unique:bicicleta,num_chasis',
            'id_color' => 'required|string|exists:color_modelo,id_colorM',
            'id_lote' => 'required|string|exists:lote,id_lote',
            'id_tipoStock' => 'required|string|exists:tipo_stock,id_tipoStock',
            'voltaje' => 'nullable|string|max:10',
            'error_iden_produccion' => 'nullable|string|max:255',
        ]);

        DB::beginTransaction();

        try {
            $bicicleta = Bicicleta::create([
                'num_chasis' => $validated['num_chasis'],
                'id_color' => $validated['id_color'],
                'id_lote' => $validated['id_lote'],
                'id_tipoStock' => $validated['id_tipoStock'],
                'codigo_barras' => $validated['num_chasis'],
                'voltaje' => $validated['voltaje'] ?? null,
                'error_iden_produccion' => $validated['error_iden_produccion'] ?? null,
            ]);

            $printResult = $this->enviarPrintNode($validated['num_chasis']);

            DB::commit();

            return redirect()->route('Bicicleta.crear')
                ->with('success', 'Bicycle saved and printed successfully!')
                ->with('print_response', $printResult);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error in BicicletaController store:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return back()
                ->with('error', 'Error: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Send print job to PrintNode API with QR code
     */
    private function enviarPrintNode(string $codigo): array
    {
        try {
            $client = new Client([
                'base_uri' => 'https://api.printnode.com/',
                'auth' => [config('printnode.api_key'), ''],
            ]);

            $zpl = <<<ZPL
^XA
^LH0,0
^FO20,20
^BQN,2,8
^FDQA,{$codigo}^FS
^FO20,120
^A0N,36,36
^FD{$codigo}^FS
^XZ
ZPL;

            $response = $client->post('printjobs', [
                'json' => [
                    'printerId' => config('printnode.printer_id'),
                    'title' => 'Bicycle ' . $codigo,
                    'contentType' => 'raw_base64',
                    'content' => base64_encode($zpl),
                    'source' => 'MyLaravelApp',
                ],
            ]);

            return json_decode((string)$response->getBody(), true);
        } catch (\Exception $e) {
            Log::error('Error printing with PrintNode:', ['error' => $e->getMessage()]);
            throw new \Exception('Print failed: ' . $e->getMessage());
        }
    }

    /**
     * Search by last 4 digits of chassis
     */
    public function buscarPorUltimos4(Request $request)
    {
        $ult4 = $request->query('ult4');
        if (!$ult4 || strlen($ult4) !== 4) {
            return response()->json(['bicicleta' => null]);
        }
        $bici = Bicicleta::where(DB::raw('RIGHT(num_chasis, 4)'), $ult4)
                   ->with(['modelo', 'color'])
                   ->first();
        return response()->json(['bicicleta' => $bici]);
    }

    /**
     * Search by complete chassis number
     */
    public function buscarC(Request $request)
    {
        $numChasis = $request->query('num_chasis');
        if (!$numChasis) {
            return response()->json([
                'success' => false,
                'message' => 'You must enter a chassis number',
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
                    'message' => 'Bicycle not found',
                    'bici' => null
                ]);
            }

            return response()->json([
                'success' => true,
                'bici' => [
                    'num_chasis' => $bici->num_chasis,
                    'modelo' => $bici->modelo->nombre_modelo,
                    'color' => $bici->color->nombre_color,
                    'id_modelo' => $bici->id_modelo,
                    'id_color' => $bici->id_color,
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Server error: ' . $e->getMessage(),
                'bici' => null
            ], 500);
        }
    }

    /**
     * Search by engine number
     */
    public function buscarMotor(Request $request)
    {
        $numMotor = $request->query('num_motor');
        if (!$numMotor) {
            return response()->json(['bici' => null]);
        }
        $bici = Bicicleta::where('num_motor', $numMotor)
                ->with(['modelo', 'color', 'tipoStock'])
                ->first();
        return response()->json(['bici' => $bici]);
    }

    /**
     * Search by model
     */
    public function buscarModelo(Request $request)
    {
        $idModelo = $request->query('modelo');
        if (!$idModelo) {
            $modelos = modelos_bici::select('id_modelo', 'nombre_modelo')
                       ->orderBy('nombre_modelo')
                       ->get();
            return response()->json(['modelos' => $modelos, 'bicis' => []]);
        }
        $bicis = Bicicleta::with(['modelo:id_modelo,nombre_modelo', 'color', 'tipoStock'])
                 ->where('id_modelo', $idModelo)
                 ->get();
        return response()->json(['modelos' => [], 'bicis' => $bicis]);
    }

    /**
     * Show view with latest bicycles
     */
    public function ver()
    {
        $bicicletas = Bicicleta::with(['modelo', 'color', 'lote', 'tipoStock'])
                        ->orderBy('updated_at', 'desc')
                        ->take(8)
                        ->get();
        
        $modelos = $bicicletas->pluck('modelo')->filter()->unique('id')->values();
        $colores = $bicicletas->pluck('color')->filter()->unique('id')->values();
        $lotes = $bicicletas->pluck('lote')->filter()->unique('id')->values();
        
        return view('Bicicleta.vista', compact('bicicletas', 'modelos', 'colores', 'lotes'));
    }

    /**
     * Dispatch print job to queue (optional)
     */
    protected function dispatchPrintJob(string $codigo, array $metadata = []): void
    {
        EnviarTrabajoImpresion::dispatch($codigo, $metadata)
            ->onQueue('printing')
            ->delay(now()->addSeconds(5));
    }
}