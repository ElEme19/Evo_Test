<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PedidosPiezas;
use App\Models\Pedidos;
use App\Models\Pieza;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf;
use SimpleSoftwareIO\QrCode\Facades\QrCode;


class PedidosPiezasController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:usuarios');
    }

    // Mostrar lista de piezas por pedido (con paginación)
    public function ver()
{
    $pedidosPiezas = PedidosPiezas::with(['pedido.sucursal', 'pieza.modelo'])
        ->orderBy('id_control', 'desc')
        ->paginate(10);

    return view('PedidosPiezas.ver', compact('pedidosPiezas'));
}


public function generarPDF($id_pedido)
{
    $pedido = Pedidos::with(['sucursal', 'piezas.pieza.modelo'])->findOrFail($id_pedido);

    $pdf = Pdf::loadView('PedidosPiezas.pdf', compact('pedido'));
    return $pdf->stream("pedido_piezas_{$pedido->id_pedido}.pdf");
}




    // Formulario para crear un nuevo pedido_pieza
    public function crear()
    {
        $pedidos = Pedidos::all();
        $piezas = Pieza::with('modelo')->get();

        return view('PedidosPiezas.crear', compact('pedidos', 'piezas'));
    }







    // Guardar pieza en pedido (similar a store de pedidos)
public function store(Request $request)
{
    $request->validate([
        'id_pedido' => 'required|string|exists:pedidos,id_pedido',
        'piezas_json' => 'required|json',
    ]);

    $piezas = json_decode($request->piezas_json, true);
    if (empty($piezas)) {
        return back()->with('error', 'No se han agregado piezas al pedido.');
    }

    try {
        DB::transaction(function () use ($piezas, $request) {
            foreach ($piezas as $piezaData) {
                $pieza = Pieza::findOrFail($piezaData['id_pieza']);
                $cantidadSolicitada = intval($piezaData['cantidad'] ?? 1);

                // Verificar stock disponible
                if ($pieza->cantidad < $cantidadSolicitada) {
                    throw new \Exception("Stock insuficiente para la pieza {$pieza->nombre_pieza} ({$pieza->id_pieza}). Disponibles: {$pieza->cantidad}, solicitados: {$cantidadSolicitada}.");
                }

                // Generar ID único para el control
                do {
                    $id_control = strtoupper(Str::random(10));
                } while (PedidosPiezas::where('id_control', $id_control)->exists());

                // Crear el registro del pedido de pieza
                PedidosPiezas::create([
                    'id_control' => $id_control,
                    'id_pedido' => $request->id_pedido,
                    'id_pieza' => $pieza->id_pieza,
                    'cantidad' => $cantidadSolicitada,
                ]);

                // Descontar del stock
                $pieza->cantidad -= $cantidadSolicitada;
                $pieza->save();
            }
        });

        return redirect()
            ->route('pedidos_piezas.ver')
            ->with('success', "Piezas agregadas correctamente al pedido {$request->id_pedido}.");

    } catch (\Exception $e) {
        return back()->with('error', 'Error al procesar el pedido: ' . $e->getMessage());
    }
}






public function buscarPieza(Request $request)
{
    try {
        $term = $request->query('term');
        if (!$term) {
            return response()->json([
                'success' => false,
                'message' => 'No se recibió término de búsqueda.'
            ], 400);
        }

        $pieza = Pieza::with('modelo')
            ->where('id_pieza', 'LIKE', "%{$term}%")
            ->orWhere('nombre_pieza', 'LIKE', "%{$term}%")
            ->first();

        if (!$pieza) {
            return response()->json([
                'success' => false,
                'message' => 'No se encontró ninguna pieza con ese código o nombre.'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'pieza' => [
                'id_pieza' => $pieza->id_pieza,
                'nombre_pieza' => $pieza->nombre_pieza,
                'modelo' => $pieza->modelo ? $pieza->modelo->nombre_modelo : 'N/D',
                'color' => $pieza->color ?? 'N/D',
            ]
        ]);


    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Error interno: ' . $e->getMessage()
        ], 500);
    }
}








    // Editar: Formulario para editar piezas de un pedido específico
    public function editar($id_pedido)
    {
        $pedido = Pedidos::with(['pedidos_piezas.pieza.modelo'])->where('id_pedido', $id_pedido)->firstOrFail();
        $piezas = Pieza::with('modelo')->get();

        return view('pedidos_piezas.editar', compact('pedido', 'piezas'));
    }






    // Agregar una pieza al pedido (similar a agregarBici)
    public function agregarPieza(Request $request, $id_pedido)
    {
        $request->validate([
            'id_pieza' => 'required|string|exists:piezas,id_pieza',
        ]);

        $existe = PedidosPiezas::where('id_pedido', $id_pedido)
                    ->where('id_pieza', $request->id_pieza)
                    ->exists();

        if ($existe) {
            return back()->with('error', 'La pieza ya está agregada a este pedido.');
        }

        PedidosPiezas::create([
            'id_control' => Str::upper(Str::random(10)),
            'id_pedido' => $id_pedido,
            'id_pieza' => $request->id_pieza,
            'cantidad' => 1,
        ]);

        return back()->with('success', 'Pieza añadida al pedido correctamente.');
    }

    // Eliminar una pieza del pedido
    public function eliminarPieza($id_pedido, $id_control)
    {
        $pieza = PedidosPiezas::where('id_control', $id_control)
                ->where('id_pedido', $id_pedido)
                ->firstOrFail();

        $pieza->delete();

        return back()->with('success', 'Pieza eliminada del pedido correctamente.');
    }

    // Finalizar pedido piezas (opcional)
    public function finalizar($id_pedido)
    {
        $pedido = Pedidos::findOrFail($id_pedido);

        // Aquí podrías actualizar estado o hacer alguna acción

        return redirect()
            ->route('pedidos_piezas.ver')
            ->with('success', "Pedido piezas {$id_pedido} finalizado correctamente.");
    }


    
}
