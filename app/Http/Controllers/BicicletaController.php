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


    public function guarda()
    {
        $modelos = modelos_bici::all();
        $colores = ColorModelo::all();
        $lotes   = Lote::all();
        $tipos   = TipoStock::all();

        return view('Bicicleta.guarda', compact('modelos','colores','lotes','tipos'));
    }
    /**
     * Guarda la bicicleta y dispara la impresión vía PrintNode.
     */  
public function store(Request $request)
{
    $validated = $request->validate([
        'num_chasis' => 'required|string|size:17',
        'id_modelo'  => 'nullable|string|exists:modelos,id_modelo',
    ]);
    // Forzar mayúsculas
    $validated['num_chasis'] = strtoupper($validated['num_chasis']);

    DB::beginTransaction();

    try {
        Bicicleta::create([
            'num_chasis' => $validated['num_chasis'],
            'id_modelo'  => $validated['id_modelo'],
            'created_at' => now(),
            'updated_at' => now(),
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

    try {
        $modeloNombre = modelos_bici::where('id_modelo', $validated['id_modelo'])->value('nombre_modelo') ?? 'Modelo desconocido';

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

        $printResult = $this->enviarPrintNode($validated['num_chasis'], $modeloNombre, $apiKey, (int) $printerId);

        return redirect()->route('Bicicleta.crear')
            ->with('success', 'Bicicleta guardada e impresa correctamente.')
            ->with('print_response', $printResult);

    } catch (\Exception $e) {
        Log::error('⚠️ Bicicleta guardada, pero error al imprimir:', [
            'codigo' => $validated['num_chasis'],
            'error'  => $e->getMessage()
        ]);

        return redirect()->route('Bicicleta.crear')
            ->with('success', 'Bicicleta guardada correctamente.')
            ->with('warning', '⚠️ Error al imprimir: ' . $e->getMessage());
    }
}

public function biciensistema(Request $request)
{
    $validated = $request->validate([
        'num_chasis'    => 'required|string|exists:bicicleta,num_chasis',
        'id_color'      => 'required|string|exists:color_modelo,id_colorM',
        'id_lote'       => 'required|string|exists:lote,id_lote',
        'id_tipoStock'  => 'required|string|exists:tipo_stock,id_tipoStock',
        'id_voltaje'    => 'nullable|string|max:10',
    ]);

    $validated['num_chasis'] = strtoupper($validated['num_chasis']);
    $user = Auth::guard('usuarios')->user();

    DB::beginTransaction();
    try {
        // Actualizar bicicleta
        Bicicleta::where('num_chasis', $validated['num_chasis'])
            ->update([
                'id_color'      => $validated['id_color'],
                'id_lote'       => $validated['id_lote'],
                'id_tipoStock'  => $validated['id_tipoStock'],
                'codigo_barras' => null,
                'id_voltaje'    => $validated['id_voltaje'] ?? 'VOLT000',
                'updated_at'    => now(),
            ]);
        DB::commit();
    } catch (\Exception $e) {
        DB::rollBack();
        Log::error('❌ Error al guardar bicicleta:', [
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
        ]);
        return back()->with('error', 'Error al guardar: ' . $e->getMessage())
                     ->withInput();
    }

    // Usuario tipo 0: imprime directo
    if ($user->user_tipo == '0') {
        try {
            $bici = Bicicleta::where('num_chasis', $validated['num_chasis'])->first();
            $apiKey = env('PRINTNODE_API_KEY');
            $printerId = env('PRINTNODE_PRINTER_ID');

            $this->enviarPrintNode(
                $bici->num_chasis,
                $bici->color->nombre_color ?? 'Color desconocido',
                $apiKey,
                (int)$printerId
            );

            return redirect()->route('Bicicleta.guarda')
                             ->with('success', 'Bicicleta guardada e impresa correctamente.');
        } catch (\Exception $e) {
            Log::error('⚠️ Error al imprimir bicicleta tipo 0:', ['error' => $e->getMessage()]);
            return redirect()->route('Bicicleta.guarda')
                             ->with('success', 'Bicicleta guardada correctamente.')
                             ->with('warning', '⚠️ Error al imprimir: ' . $e->getMessage());
        }
    }

    // Usuario tipo 1: crea autorización
    if ($user->user_tipo == '1') {
    try {
        $token = bin2hex(random_bytes(16));

        // Crear autorización
        Autorizacion::create([
            'num_chasis' => $validated['num_chasis'],
            'estado'     => 'pendiente',
            'token'      => $token,
            'usuario_solicita'=> 1,
        ]);

        // -------------------------------
        // DEPURACIÓN DE ENVÍO DE CORREO
        // -------------------------------
        try {
            Mail::to('emi2.0carmona@gmail.com')
    ->send(new \App\Mail\SolicitudAutorizacion(
        $validated['num_chasis'],
        $token,
        $user->name ?? $user->usuario ?? 'Usuario desconocido'
    ));


            Log::info('✅ Correo enviado correctamente para: ' . $validated['num_chasis']);
        } catch (\Exception $mailEx) {
            Log::error('❌ Error enviando correo: ' . $mailEx->getMessage(), [
                'num_chasis' => $validated['num_chasis'],
                'token'      => $token,
            ]);
            return redirect()->route('Bicicleta.guarda')
                             ->with('warning', 'No se pudo enviar el correo de autorización. Revisa logs.');
        }

        return redirect()->route('Bicicleta.guarda')
                         ->with('success', 'Solicitud de autorización enviada. Esperando aprobación.');

    } catch (\Exception $e) {
        Log::error('⚠️ Error al crear autorización:', [
            'codigo' => $validated['num_chasis'],
            'error'  => $e->getMessage()
        ]);

        return redirect()->route('Bicicleta.guarda')
                         ->with('success', 'Bicicleta guardada correctamente.')
                         ->with('warning', '⚠️ Error al crear autorización: ' . $e->getMessage());
    }
}}

// Función auxiliar
private function biciYaImprimida($num_chasis)
{
    return Bicicleta::where('num_chasis', $num_chasis)
                    ->whereNotNull('codigo_barras')
                    ->exists();
}

public function procesarAutorizacion(Request $request, $token, $accion)
{
    $autorizacion = Autorizacion::where('token', $token)->first();

    if (!$autorizacion) {
        return response("❌ Token no válido o expirado.");
    }

    if ($autorizacion->estado !== 'pendiente') {
        return response("⚠️ Esta solicitud ya fue procesada.");
    }

    if (!in_array($accion, ['approve', 'reject'])) {
        return response("Acción no válida.");
    }

    $autorizacion->estado = $accion === 'approve' ? 'aprobado' : 'rechazado';
    $autorizacion->save();

    if ($accion === 'approve') {
        $bici = Bicicleta::where('num_chasis', $autorizacion->num_chasis)->first();
        if ($bici) {
            try {
                $this->enviarPrintNode(
                    $bici->num_chasis,
                    $bici->color->nombre_color ?? 'Color desconocido',
                    env('PRINTNODE_API_KEY'),
                    (int)env('PRINTNODE_PRINTER_ID')
                );
            } catch (\Exception $e) {
                Log::error('Error al imprimir tras aprobación:', ['error' => $e->getMessage()]);
            }
        }
    }

    return response(
        $accion === 'approve'
            ? "✅ Solicitud aprobada y bicicleta impresa."
            : "❌ Solicitud rechazada. No se imprimirá la bicicleta."
    );
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

// Imprimir QR vía PrintNode
public function imprimirQRConPrintNode(Request $request)
{
    try {
        $url = "https://drive.google.com/file/d/1XYzvw8GR8IG3fLetzSYGTTBlS0w1zsQ-/view?usp=drive_link";
        $texto = "JOSHUA ESTUVO AQUI";

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

        // Generar ZPL
        $zpl = <<<EOT
^XA
^PW320
^LL200

^FO80,25
^BQN,2,3.8,H
^FD{$url}
^FS

^FO68,209
^A0N,8,5
^FB320,1,0,C,0
^FD{$texto}
^FS

^XZ
EOT;

        $client = new Client([
            'base_uri' => 'https://api.printnode.com/account',
            'auth'     => [$apiKey, ''],
            'timeout'  => 10,
        ]);

        $response = $client->post('printjobs', [
            'json' => [
                'printerId'   => $printerId,
                'title'       => 'QR YouTube',
                'contentType' => 'raw_base64',
                'content'     => base64_encode($zpl),
                'source'      => 'MiAppLaravel',
            ],
        ]);

        $body = (string) $response->getBody();
        $decoded = json_decode($body, true);

        return redirect()->back()->with('success', 'QR de YouTube impreso correctamente.')
                                 ->with('print_response', $decoded);

    } catch (\Exception $e) {
        Log::error('Error al imprimir QR de YouTube:', [
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
        ]);

        return redirect()->back()->with('error', 'Error al imprimir QR: ' . $e->getMessage());
    }
}


public function enviarPrintNode(string $code, $color, string $apiKey, int $printerId): array
{
    try {
        $zpl = <<<EOT
^XA
^PW320
^LL200

^FO75,25
^BQN,2,7,H


^FD{$code}

^FS

^FO60,209

^A0N,8,8
^FB320,1,0,C,0
^FD{$code}

^FS

^XZ


EOT;

        $client = new Client([
            'base_uri' => 'https://api.printnode.com/account',
            'auth'     => [$apiKey, ''],
            'timeout'  => 10,
        ]);

        $response = $client->post('printjobs', [
            'json' => [
                'printerId'   => $printerId,
                'title'       => 'Etiqueta QR ' . $code,
                'contentType' => 'raw_base64',
                'content'     => base64_encode($zpl),
                'source'      => 'MiAppLaravel',
            ],
        ]);

        $body = (string) $response->getBody();
        $decoded = json_decode($body, true);

        if (!is_array($decoded) && !is_numeric($decoded)) {
            throw new \Exception('Respuesta inesperada de PrintNode: ' . $body);
        }

        return [
            'status'    => 'success',
            'message'   => '🎉 ¡Etiqueta ZPL impresa con éxito!',
            'data'      => $decoded,
            'timestamp' => now()->toDateTimeString(),
        ];
    } catch (\Exception $e) {
        Log::error('Error al imprimir con PrintNode:', [
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
        ]);

        throw new \Exception('⚠️ Error en impresión ZPL: ' . $e->getMessage());
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