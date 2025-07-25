<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Membresia;
use App\Models\modelos_bici;
use App\Models\VoltajeModeloD;
use App\Models\ColorModelo;
use App\Models\Precio;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;
use SimpleSoftwareIO\QrCode\Facades\QrCode;


class CotizacionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:usuarios');
    }

   
    public function index()
    {
        $membresias = Membresia::all();
        $modelos    = modelos_bici::all();
        $voltajes   = collect(); // se cargarán según el modelo seleccionado

        return view('Cotizacion.crear', compact('membresias', 'modelos', 'voltajes'));
    }

    
    public function voltajePorModelo($id_modelo)
    {
        try {
            $voltajes = DB::table('voltaje_modelo')
                ->join('voltaje', 'voltaje_modelo.id_voltaje', '=', 'voltaje.id_voltaje')
                ->where('voltaje_modelo.id_modelo', $id_modelo)
                ->select('voltaje.id_voltaje', 'voltaje.tipo_voltaje')
                ->get();

            return response()->json($voltajes);
        } catch (\Exception $e) {
            Log::error("Error en voltajePorModelo: " . $e->getMessage());
            return response()->json([], 500);
        }
    }

    
    public function coloresPorModelo($id_modelo)
    {
        try {
            $colores = ColorModelo::where('id_modelo', $id_modelo)
                ->get(['id_colorM', 'nombre_color']);

            return response()->json($colores);
        } catch (\Exception $e) {
            Log::error('Error al cargar colores: ' . $e->getMessage());
            return response()->json([], 500);
        }
    }

    public function precioParaCotizacion(Request $request)
    {
        $data = $request->validate([
            'id_membresia' => 'required|exists:membresia,id_membresia',
            'id_modelo'    => 'required|exists:modelos,id_modelo',
            'id_voltaje'   => 'required|exists:voltaje,id_voltaje',
        ]);

        $precio = Precio::where('id_membresia', $data['id_membresia'])
                        ->where('id_modelo',    $data['id_modelo'])
                        ->where('id_voltaje',   $data['id_voltaje'])
                        ->value('precio');

        if (is_null($precio)) {
            return response()->json([
                'success' => false,
                'message' => 'No se encontró precio para esa combinación.'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'precio'  => $precio
        ]);
    }


   public function generarPDF(Request $request)
{
    $request->validate([
        'lineas'             => 'required',
        'membresia'          => 'required|string',
        'nombre_cliente'     => 'required|string|max:255',
        'telefono'           => 'required|string|max:20',
        'direccion'          => 'nullable|string|max:255',
        'metodo_entrega'     => 'required|string|max:50',
        'direccion_destino'  => 'nullable|string|max:255',
    ]);

    $lineasInput = json_decode($request->input('lineas'), true);
    if (!$lineasInput) {
        return redirect()->back()->with('error', 'Datos de cotización inválidos.');
    }

    // Dirección final a usar
    $direccionDestino = $request->input('direccion_destino') 
                       ?? $request->input('direccion');

    $cliente = (object)[
        'nombre'    => $request->input('nombre_cliente'),
        'telefono'  => $request->input('telefono'),
        'direccion' => $direccionDestino,
    ];

    $asesor = Auth::user()->nombre_user ?? 'N/A';

    $metodoEntrega = $request->input('metodo_entrega');

    $lineas = collect($lineasInput)->map(function ($item) use ($metodoEntrega) {
        $subtotal = $item['precio'] * $item['cantidad'];
        return (object)[
            'membresia'      => $item['membresia'],
            'modelo'         => $item['modelo'],
            'color'          => $item['color'],
            'voltaje'        => $item['voltaje'],
            'precio'         => $item['precio'],
            'cantidad'       => $item['cantidad'],
            'subtotal'       => $subtotal,
            'metodo_entrega' => $metodoEntrega,
        ];
    });

    $total = $lineas->sum('subtotal');
    $iva = round($total * 0.08, 2);
    $total_con_iva = $total + $iva;

    Carbon::setLocale('es');
    $fecha = Carbon::now()->translatedFormat('d \d\e F \d\e Y');

    $codigo = strtoupper(Str::random(15));

   // Construir texto para el QR con toda la info
$payload = "fk1 #$codigo\n";
$payload .= "kd52: $fecha\n";
$payload .= "pa98: {$cliente->nombre}\n";
$payload .= "tq71: {$cliente->telefono}\n";
$payload .= "a9sp: {$cliente->direccion}\n";
$payload .= "em89: $asesor\n";
$payload .= "pxj8: {$request->input('membresia')}\n";
$payload .= "5skf:\n";

foreach ($lineas as $i => $linea) {
    // Buscar el modelo por nombre
   $modelo = modelos_bici::where('nombre_modelo', trim($linea->modelo))->first();
    $idModeloReal = $modelo?->id_modelo ?? 'Error';  // Usando el operador null-safe

    $idColor = ColorModelo::where('id_modelo', $idModeloReal)->where('nombre_color', trim($linea->color))->value('id_colorM'); 

    $voltaje = VoltajeModeloD::where('tipo_voltaje', trim($linea->voltaje))->value('id_voltaje');

    if (!$modelo) {
        logger("⚠️ Modelo no encontrado: " . $linea->modelo);
        $modelo = $linea->modelo;
        $color = $linea->color;
        $voltaje = $linea->voltaje;
    }

    $payload .= ($i + 1) . ". {$idModeloReal},{$idColor},{$voltaje}, quantity: {$linea->cantidad}\n";
}


$payload .= "lab7: " . number_format($total, 2) . "\n";
$payload .= "lap8: " . number_format($total_con_iva, 2) . "\n";

// Generar QR SVG
$qr_svg_raw = QrCode::format('svg')->size(200)->generate($payload);

// Codificar a base64 para insertarlo en <img>
$qr_base64 = base64_encode($qr_svg_raw);

$pdf = PDF::loadView('cotizacion.pdf', [
    'lineas'           => $lineas,
    'total'            => $total,
    'iva'              => $iva,
    'total_con_iva'    => $total_con_iva,
    'membresia'        => $request->input('membresia'),
    'fecha'            => $fecha,
    'cliente'          => $cliente,
    'asesor'           => $asesor,
    'codigo'           => $codigo,
    'qr_base64'        => $qr_base64,
]);

return $pdf->stream('cotizacion.pdf');

}




public function distancia(Request $request)
{
    $request->validate([
        'lat'     => 'required|numeric',
        'lng'     => 'required|numeric',
        'almacen' => 'required|string',
    ]);

    Log::info('Distancia recibida coords:', $request->only('lat','lng', 'almacen'));

    // Coordenadas del origen (lng, lat)
    $almacenes = [
        'fabricaixta'        => [-98.920218, 19.309912], // Fabrica Ixtapaluca
        'oficinascentrales' => [-99.169043, 19.429392], // Oficinas
        'cdmx'              => [-99.1332, 19.4326],      // Default
    ];

    $almacenKey = strtolower($request->input('almacen'));

    if (!isset($almacenes[$almacenKey])) {
        return response()->json([
            'error' => 'Almacén no reconocido.',
        ], 422);
    }

    $origen = $almacenes[$almacenKey];
    $destino = [(float)$request->input('lng'), (float)$request->input('lat')];

    $km = null;
    $direccionFormateada = null;
    $codigo_postal = null;

    try {
        // Distancia entre origen y destino
        $resp = Http::withHeaders([
            'Authorization' => config('services.openrouteservice.key'),
            'Accept'        => 'application/json',
        ])->post('https://api.openrouteservice.org/v2/directions/driving-car', [
            'coordinates' => [$origen, $destino],
        ]);

        $data = $resp->json();
        Log::info('Respuesta ORS raw (directions):', $data);

        if (isset($data['features'][0]['properties']['segments'][0]['distance'])) {
            $metros = $data['features'][0]['properties']['segments'][0]['distance'];
        } elseif (isset($data['routes'][0]['summary']['distance'])) {
            $metros = $data['routes'][0]['summary']['distance'];
        } else {
            $metros = null;
        }

        $km = is_numeric($metros) ? round($metros / 1000, 1) : null;
        Log::info("Distancia final (km): {$km}");

        // Geocodificación inversa
        $geoResp = Http::withHeaders([
            'Authorization' => config('services.openrouteservice.key'),
            'Accept'        => 'application/json',
        ])->get('https://api.openrouteservice.org/geocode/reverse', [
            'point.lat' => $request->input('lat'),
            'point.lon' => $request->input('lng'),
        ]);

        $geoData = $geoResp->json();
        Log::info('Respuesta ORS raw (reverse geocode):', $geoData);

        if (!empty($geoData['features'][0]['properties'])) {
            $prop = $geoData['features'][0]['properties'];

            $componentes = [];

            if (!empty($prop['house_number']))  $componentes[] = $prop['house_number'];
            if (!empty($prop['street']))        $componentes[] = $prop['street'];
            else                                 $componentes[] = 'SN';
            if (!empty($prop['suburb']))        $componentes[] = $prop['suburb'];
            if (!empty($prop['neighbourhood'])) $componentes[] = $prop['neighbourhood'];
            if (!empty($prop['locality']))      $componentes[] = $prop['locality'];
            if (!empty($prop['region']))        $componentes[] = $prop['region'];
            if (!empty($prop['postcode'])) {
                $componentes[] = 'CP ' . $prop['postcode'];
                $codigo_postal = $prop['postcode'];
            }
            if (!empty($prop['country']))       $componentes[] = $prop['country'];

            $direccionFormateada = implode(', ', $componentes);
        }

    } catch (\Throwable $e) {
        Log::error('Error al calcular distancia/dirección ORS: '.$e->getMessage());
    }

    return response()->json([
        'km'            => $km,
        'direccion'     => $direccionFormateada,
        'codigo_postal' => $codigo_postal,
    ]);
}





public function generarQR(Request $request)
{
    // Validación base
    $request->validate([
        'nombre'     => 'required|string|max:255',
        'asesor'     => 'required|string|max:255',
        'fecha'      => 'required|date',
        'direccion'  => 'nullable|string',
        'almacen'    => 'required|string',
        'modelos'    => 'required|array', // Ejemplo: [['modelo' => 'Cielo', 'color' => 'Rojo', 'cantidad' => 2, 'precio' => 14000]]
        'total'      => 'required|numeric',
    ]);

    // Preparar líneas
    $lineas = [];
    $lineas[] = "Cotización de Cliente";
    $lineas[] = "Nombre: {$request->nombre}";
    $lineas[] = "Asesor: {$request->asesor}";
    $lineas[] = "Fecha: " . date('d/m/Y', strtotime($request->fecha));
    $lineas[] = "Almacén: " . ucfirst($request->almacen);

    if ($request->direccion) {
        $lineas[] = "Dirección: " . $request->direccion;
    }

    $lineas[] = "\nModelos:";
    foreach ($request->modelos as $i => $modelo) {
       $lineas[] = ($i + 1) . ". {$modelo['modelo']} - {$modelo['color']} | {$modelo['cantidad']} pzas x {$modelo['precio']}";

    }

    $lineas[] = "\nTOTAL: $" . number_format($request->total, 2);


    $contenidoQR = implode("\n", $lineas);

    // Generar QR
    $qr = \SimpleSoftwareIO\QrCode\Facades\QrCode::format('png')->size(300)->generate($contenidoQR);
    $qr_base64 = base64_encode($qr);

    return response()->json([
        'qr_base64' => $qr_base64,
        'contenido' => $contenidoQR,
    ]);
}


}
