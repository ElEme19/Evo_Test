<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Membresia;
use App\Models\modelos_bici;
use App\Models\ColorModelo;
use App\Models\Precio;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Barryvdh\DomPDF\Facade\Pdf;

class CotizacionController extends Controller
{
    public function __construct()
    {
        // Protege todas las rutas con autenticación
        $this->middleware('auth:usuarios');
    }

    /**
     * Mostrar formulario de cotización
     */
    public function index()
    {
        // Carga datos para los selects
        $membresias = Membresia::all();
        $modelos    = modelos_bici::all();
        // Voltajes vacíos al inicio; se cargarán según el modelo
        $voltajes   = collect();

        return view('Cotizacion.crear', compact('membresias', 'modelos', 'voltajes'));
    }

    /**
     * Devuelve los voltajes disponibles para un modelo dado
     * Ruta sugerida: GET /cotizacion/voltajes/{id_modelo}
     */
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

    /**
     * Devuelve el precio para una combinación de membresía, modelo y voltaje
     * Ruta sugerida: GET /cotizacion/precio
     */
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

public function generarPDF(Request $request)
    {
        // Validamos que existan los datos necesarios
        if (!$request->has('lineas')) {
            return redirect()->back()->with('error', 'No se recibieron líneas de cotización.');
        }

        // Decodificamos las líneas desde el input oculto
        $lineasInput = json_decode($request->lineas);

        if (!$lineasInput || !is_array($lineasInput)) {
            return redirect()->back()->with('error', 'Datos de cotización inválidos.');
        }

        // Formateamos las líneas para el PDF
        $lineas = collect($lineasInput)->map(function ($item) {
            $subtotal = $item->precio * $item->cantidad;

            return (object)[
                'modelo'   => $item->modelo,
                'color'    => $item->color,
                'voltaje'  => $item->voltaje,
                'precio'   => $item->precio,
                'cantidad' => $item->cantidad,
                'subtotal' => $subtotal,
            ];
        });

        // Total general y total de bicis
        $total = $lineas->sum('subtotal');
        $total_bicis = $lineas->sum('cantidad');

        // Renderizamos el PDF con la vista
        $pdf = PDF::loadView('cotizacion.pdf', [
            'lineas' => $lineas,
            'total' => $total,
            'total_bicis' => $total_bicis,
            'membresia' => $request->membresia ?? 'No especificada',
        ]);

        return $pdf->stream('cotizacion.pdf');
    }
}
