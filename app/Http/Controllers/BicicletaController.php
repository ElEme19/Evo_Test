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
     * Muestra el formulario para crear bicicleta
     */
    public function crear()
    {
        $modelos = modelos_bici::all();
        $colores = [];
        $lotes   = Lote::all();
        $tipos   = TipoStock::all();

        return view('Bicicleta.crear', compact('modelos','colores','lotes','tipos'));
    }

    /**
     * Carga colores según modelo (AJAX)
     */
    public function coloresPorModelo($id_modelo)
    {
        try {
            $colores = ColorModelo::where('id_modelo', $id_modelo)
                       ->get(['id_colorM', 'nombre_color']);
            return response()->json($colores);
        } catch (\Exception $e) {
            Log::error('Error al cargar colores:', ['error' => $e->getMessage()]);
            return response()->json([], 500);
        }
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
            // 1. Actualizar registro
            Bicicleta::where('num_chasis', $validated['num_chasis'])
                ->update([
                    'id_color'              => $validated['id_color'],
                    'id_lote'               => $validated['id_lote'],
                    'id_tipoStock'          => $validated['id_tipoStock'],
                    'codigo_barras'         => $validated['num_chasis'],
                    'voltaje'               => $validated['voltaje'] ?? null,
                    'error_iden_produccion' => $validated['error_iden_produccion'] ?? null,
                    'updated_at'            => now(),
                ]);

            // 2. Enviar impresión
            $printResult = $this->enviarPrintNode($validated['num_chasis']);

            DB::commit();

            return redirect()->route('Bicicleta.crear')
                ->with('success', '¡Bicicleta guardada e impresa correctamente!')
                ->with('print_response', $printResult);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error en store BicicletaController:', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return back()
                ->with('error', 'Error: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Envía impresión a PrintNode API
     * Siempre retorna un array para evitar errores de tipo
     */
    private function enviarPrintNode(string $codigo): array
    {
        // Leer directamente de env
        $apiKey    = env('PRINTNODE_API_KEY');
        $printerId = env('PRINTNODE_PRINTER_ID');

        if (! $apiKey || ! $printerId) {
            Log::error('PrintNode: falta API_KEY o PRINTER_ID en .env');
            return ['error' => 'Configuración de PrintNode inválida'];
        }

        try {
            $client = new Client([
                'base_uri' => 'https://api.printnode.com/',
                'auth'     => [$apiKey, ''],
            ]);

            $raw  = "Código: {$codigo}\n";
            $raw .= "\x1dV\x00"; // GS V 0 -> cortar

            $response = $client->post('printjobs', [
                'json' => [
                    'printerId'   => $printerId,
                    'title'       => 'Bicicleta ' . $codigo,
                    'contentType' => 'raw_base64',
                    'content'     => base64_encode($raw),
                    'source'      => 'MiAppLaravel',
                ],
            ]);

            return json_decode((string) $response->getBody(), true) ?? ['status' => 'unknown'];

        } catch (\GuzzleHttp\Exception\ClientException $e) {
            $body = (string) $e->getResponse()->getBody();
            Log::error('PrintNode ClientException:', ['body' => $body]);
            return ['error' => 'PrintNode error: ' . $body];
        } catch (\Exception $e) {
            Log::error('Error al imprimir con PrintNode:', ['error' => $e->getMessage()]);
            return ['error' => 'Falló la impresión: ' . $e->getMessage()];
        }
    }

    /**
     * Búsqueda por últimos 4 dígitos de chasis
     */
    public function buscarPorUltimos4(Request $request)
    {
        $ult4 = $request->query('ult4');
        if (! $ult4 || strlen($ult4) !== 4) {
            return response()->json(['bicicleta' => null]);
        }
        $bici = Bicicleta::where(DB::raw('RIGHT(num_chasis, 4)'), $ult4)
                   ->with(['modelo', 'color'])
                   ->first();
        return response()->json(['bicicleta' => $bici]);
    }

    /**
     * Búsqueda por número de chasis completo
     */
    public function buscarC(Request $request)
    {
        $numChasis = $request->query('num_chasis');
        if (! $numChasis) {
            return response()->json(['success' => false, 'message' => 'Debe ingresar un número de chasis', 'bici' => null]);
        }
        try {
            $bici = Bicicleta::where('num_chasis', $numChasis)
                        ->with(['modelo', 'color'])
                        ->first();
            if (! $bici) {
                return response()->json(['success' => false, 'message' => 'Bicicleta no encontrada', 'bici' => null]);
            }
            return response()->json(['success' => true, 'bici' => [
                'num_chasis' => $bici->num_chasis,
                'modelo'     => $bici->modelo->nombre_modelo,
                'color'      => $bici->color->nombre_color,
                'id_modelo'  => $bici->id_modelo,
                'id_color'   => $bici->id_color,
            ]]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error en el servidor:' . $e->getMessage(), 'bici' => null], 500);
        }
    }

    /**
     * Búsqueda por número de motor
     */
    public function buscarMotor(Request $request)
    {
        $numMotor = $request->query('num_motor');
        if (! $numMotor) {
            return response()->json(['bici' => null]);
        }
        $bici = Bicicleta::where('num_motor', $numMotor)
                ->with(['modelo', 'color', 'tipoStock'])
                ->first();
        return response()->json(['bici' => $bici]);
    }

    /**
     * Búsqueda por modelo
     */
    public function buscarModelo(Request $request)
    {
        $idModelo = $request->query('modelo');
        if (! $idModelo) {
            $modelos = modelos_bici::select('id_modelo', 'nombre_modelo')
                        ->orderBy('nombre_modelo')->get();
            return response()->json(['modelos' => $modelos, 'bicis' => []]);
        }
        $bicis = Bicicleta::with(['modelo:id_modelo,nombre_modelo', 'color', 'tipoStock'])
                 ->where('id_modelo', $idModelo)->get();
        return response()->json(['modelos' => [], 'bicis' => $bicis]);
    }

    /**
     * Muestra la vista con las últimas bicicletas
     */
    public function ver()
    {
        $bicicletas = Bicicleta::with(['modelo', 'color', 'lote', 'tipoStock'])
                        ->orderBy('updated_at', 'desc')
                        ->take(8)
                        ->get();
        $modelos = $bicicletas->pluck('modelo')->filter()->unique('id')->values();
        $colores = $bicicletas->pluck('color')->filter()->unique('id')->values();
        $lotes   = $bicicletas->pluck('lote')->filter()->unique('id')->values();
        return view('Bicicleta.vista', compact('bicicletas', 'modelos', 'colores', 'lotes'));
    }

    /**
     * Dispatch a print job to queue (opcional)
     */
    protected function dispatchPrintJob(string $codigo, array $metadata = []): void
    {
        EnviarTrabajoImpresion::dispatch($codigo, $metadata)
            ->onQueue('impresiones')
            ->delay(now()->addSeconds(5));
    }
}