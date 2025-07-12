<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Membresia;
use App\Models\modelos_bici;
use App\Models\ColorModelo;
use App\Models\Precio;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use Carbon\Carbon;
use Illuminate\Support\Str;


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
        'lineas'          => 'required',
        'membresia'       => 'required|string',
        'nombre_cliente'  => 'required|string|max:255',
        'telefono'        => 'required|string|max:20',
        'direccion'       => 'required|string|max:255',
        'metodo_entrega'  => 'required|string|max:50',
    ]);

    $lineasInput = json_decode($request->input('lineas'), true);
    if (!$lineasInput) {
        return redirect()->back()->with('error', 'Datos de cotización inválidos.');
    }

    $cliente = (object)[
        'nombre'    => $request->input('nombre_cliente'),
        'telefono'  => $request->input('telefono'),
        'direccion' => $request->input('direccion'),
    ];
    $asesor = Auth::user()->name;

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

    // ← Generar el código único
    $codigo = strtoupper(Str::random(15));

    $pdf = PDF::loadView('cotizacion.pdf', [
        'lineas'        => $lineas,
        'total'         => $total,
        'iva'           => $iva,
        'total_con_iva' => $total_con_iva,
        'membresia'     => $request->input('membresia'),
        'fecha'         => $fecha,
        'cliente'       => $cliente,
        'asesor'        => $asesor,
        'codigo'        => $codigo,
    ]);

    return $pdf->stream('cotizacion.pdf');
}

}
