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
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Auth\LoginController;
use App\Models\Autorizacion;
use Illuminate\Support\Facades\Mail;
use App\Mail\SolicitudAutorizacion;
use PhpOffice\PhpSpreadsheet\IOFactory;



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

public function procesarExcel(Request $request)
{
    set_time_limit(0);

    $request->validate([
        'archivo' => 'required|file|mimes:xls,xlsx',
    ]);

    $archivo     = $request->file('archivo');
    $spreadsheet = IOFactory::load($archivo->getRealPath());
    $hoja        = $spreadsheet->getActiveSheet();

    $registros   = [];
    $orden_excel = 1;

    foreach ($hoja->getRowIterator() as $fila) {
        $celdas = $fila->getCellIterator();
        $celdas->setIterateOnlyExistingCells(false);
        $valores = [];

        foreach ($celdas as $celda) {
            $valores[] = trim((string) $celda->getValue());
        }

        // Primera parte: columnas 1 y 2 (índices 0 y 1)
        if (isset($valores[1]) && str_starts_with($valores[1], 'H')) {
            $registros[] = [
                'num_chasis'  => $valores[1],
                'num_motor'   => $valores[2] ?? null,
                'orden_excel' => $orden_excel++,
            ];
        } elseif (isset($valores[2]) && str_starts_with($valores[2], 'H')) {
            $registros[] = [
                'num_chasis'  => $valores[2],
                'num_motor'   => $valores[1] ?? null,
                'orden_excel' => $orden_excel++,
            ];
        }

        // Segunda parte: columnas 5 y 6 (índices 4 y 5)
        if (isset($valores[5]) && str_starts_with($valores[5], 'H')) {
            $registros[] = [
                'num_chasis'  => $valores[5],
                'num_motor'   => $valores[6] ?? null,
                'orden_excel' => $orden_excel++,
            ];
        } elseif (isset($valores[6]) && str_starts_with($valores[6], 'H')) {
            $registros[] = [
                'num_chasis'  => $valores[6],
                'num_motor'   => $valores[5] ?? null,
                'orden_excel' => $orden_excel++,
            ];
        }
    }

    if (empty($registros)) {
        return back()->withErrors(['No se encontraron registros válidos en el Excel.']);
    }

    // Configuración de PrintNode
    $apiKey    = config('printnode.api_key');
    $printerId = config('printnode.printer_id');

    $resultados = [];

    foreach ($registros as $registro) {
        $chasis = $registro['num_chasis'];
        $motor  = $registro['num_motor'];

        try {
            // Enviar ambos valores como un solo QR
            $resultado = $this->enviarPrintNode($chasis, $motor, $apiKey, $printerId);

            $resultados[] = [
                'num_chasis' => $chasis,
                'num_motor'  => $motor,
                'status'     => 'success',
                'message'    => $resultado['message'],
                'data'       => $resultado['data'] ?? null,
            ];
        } catch (\Exception $e) {
            $resultados[] = [
                'num_chasis' => $chasis,
                'num_motor'  => $motor,
                'status'     => 'error',
                'message'    => $e->getMessage(),
            ];
        }
    }

    return response()->json([
        'status'     => 'completed',
        'total'      => count($resultados),
        'resultados' => $resultados,
        'timestamp'  => now()->toDateTimeString(),
    ]);
}

   
public function store(Request $request)
{
    // =====================
    // VALIDACIÓN
    // =====================
    $validated = $request->validate([
        'num_chasis' => 'required|string|size:17',
        'num_motor'  => 'nullable|string|max:20',
        'id_modelo'  => 'nullable|integer',
    ]);

    // Forzar mayúsculas
    $num_chasis = strtoupper($validated['num_chasis']);
    $num_motor  = !empty($validated['num_motor']) ? strtoupper($validated['num_motor']) : 'SIN MOTOR';

    // =====================
    // IMPRESIÓN
    // =====================
    try {
        // Obtener nombre del modelo (si se envió id_modelo)
        $modeloNombre = modelos_bici::where('id_modelo', $validated['id_modelo'] ?? null)
            ->value('nombre_modelo') ?? 'Modelo desconocido';

        // Obtener datos del usuario autenticado
        $user = Auth::guard('usuarios')->user();
        $apiKey = match ($user->user_tipo) {
            '0' => env('PRINTNODE_API_KEY'),
            '1' => env('PRINTNODE_API_KEY_2'),
            default => env('PRINTNODE_API_KEY'),
        };
        $printerId = match ($user->user_tipo) {
            '0' => env('PRINTNODE_PRINTER_ID'),
            '1' => env('PRINTNODE_PRINTER_ID_2'),
            default => env('PRINTNODE_PRINTER_ID'),
        };

        // 🔹 Llamada directa a impresión
        $printResult = $this->enviarPrintNode(
            $num_chasis,
            $num_motor,
            $modeloNombre,
            $apiKey,
          (int) $printerId
        );

        return redirect()->route('Bicicleta.crear')
            ->with('success', '✅ Impresión realizada correctamente.')
            ->with('print_response', $printResult);

    } catch (\Exception $e) {
        Log::error('⚠️ Error al imprimir etiqueta ZPL:', [
            'codigo' => $num_chasis,
            'error'  => $e->getMessage()
        ]);

        return redirect()->route('Bicicleta.crear')
            ->with('warning', '⚠️ No se pudo imprimir: ' . $e->getMessage());
    }
}





public function imprimirBicicletasPorFecha(Request $request)
{
    set_time_limit(0);

    $apiKey    = config('printnode.api_key');
    $printerId = config('printnode.printer_id');

    // Parámetros desde la vista
    $fecha   = $request->input('fecha');        // YYYY-MM-DD
    $idModelo = $request->input('id_modelo');   // ID del modelo

    // Query con filtros
    $query = Bicicleta::with('modelo')
        ->whereDate('created_at', $fecha);

    if ($idModelo) {
        $query->where('id_modelo', $idModelo);
    }

    $bicicletas = $query->orderBy('orden_excel')->get();

    $resultados = [];

    foreach ($bicicletas as $bici) {
        $codigo = trim($bici->num_chasis);

        try {
            $resultado = $this->enviarPrintNode($codigo, null, $apiKey, $printerId);

            $resultados[] = [
                'num_chasis' => $codigo,
                'modelo'     => $bici->modelo->nombre_modelo ?? 'Sin modelo',
                'status'     => 'success',
                'message'    => $resultado['message'],
                'data'       => $resultado['data'] ?? null,
            ];
        } catch (\Exception $e) {
            $resultados[] = [
                'num_chasis' => $codigo,
                'modelo'     => $bici->modelo->nombre_modelo ?? 'Sin modelo',
                'status'     => 'error',
                'message'    => $e->getMessage(),
            ];
        }
    }

    return response()->json([
        'status'     => 'completed',
        'fecha'      => $fecha,
        'id_modelo'  => $idModelo,
        'total'      => count($resultados),
        'resultados' => $resultados,
        'timestamp'  => now()->toDateTimeString(),
    ]);
}


public function viewImprimirTodo()
{
    $modelos = modelos_bici::orderBy('nombre_modelo')->get();
    return view('Bicicleta.imprimirTodo', compact('modelos'));
}




// Mostrar vista con botón para imprimir
public function vistaImprimirQR()
{
    return view('Bicicleta.imprimirQR'); // crea la vista resources/views/Bicicleta/imprimirQR.blade.php
}


public function enviarPrintNode(string $num_chasis, string $num_motor,  string $apiKey, int $printerId): array
{


    $todo = $num_chasis . '|' . $num_motor;

    try {
        $zpl = <<<EOT
^XA
^PW320
^LL200

^FO95,25
^BQN,2,5,H
^FD{$todo}


^FS

^FO60,180
^A0N,8,8
^FB320,1,0,C,0
^FD{$num_chasis}
^FS


^FO80,210
^A0N,8,8
^FB320,1,0,C,0
^FD{$num_motor}
^FS



^XZ
EOT;

        $client = new \GuzzleHttp\Client([
            'base_uri' => 'https://api.printnode.com/',
            'auth'     => [$apiKey, ''],
            'timeout'  => 10,
        ]);

        $response = $client->post('printjobs', [
            'json' => [
                'printerId'   => $printerId,
                'title'       => "Etiqueta QR {$num_chasis}",
                'contentType' => 'raw_base64',
                'content'     => base64_encode($zpl),
                'source'      => 'MiAppLaravel',
            ],
        ]);

        return [
            'status'  => 'success',
            'message' => '🎉 Etiqueta QR impresa correctamente.',
            'data'    => json_decode((string) $response->getBody(), true),
        ];

    } catch (\Exception $e) {
        \Log::error('Error al imprimir etiqueta ZPL:', ['error' => $e->getMessage()]);
        throw new \Exception('Error al imprimir: ' . $e->getMessage());
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
    public function buscarPorUltimos10(Request $request)
{
    $ult10 = $request->query('ult10');

    if (! $ult10 || strlen($ult10) !== 10) {
        return response()->json([
            'success' => false,
            'message' => 'Debe ingresar los últimos 10 caracteres del número de chasis',
            'bici'    => null
        ]);
    }

    try {
        $bici = Bicicleta::where(DB::raw('RIGHT(num_chasis, 10)'), $ult10)
                   ->with(['modelo', 'color', 'tipoStock', 'pedido'])
                   ->first();

        if (! $bici) {
            return response()->json([
                'success' => false,
                'message' => 'Bicicleta no encontrada',
                'bici'    => null
            ]);
        }

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
    $ult10 = $request->query('ultimos10');

    if (! $ult10 || strlen($ult10) !== 10) {
        return response()->json([
            'success' => false,
            'message' => 'Debe ingresar los últimos 10 caracteres del número de chasis',
            'bici'    => null
        ]);
    }

    try {
        $bici = Bicicleta::where(DB::raw('RIGHT(num_chasis, 10)'), $ult10)
            ->select('num_chasis', 'id_modelo', 'id_color', 'id_tipoStock', 'id_lote')
            ->with('modelo') // Para traer nombre_modelo
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