<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pedidos;
use App\Models\Sucursal;
use App\Models\Bicicleta;
use Barryvdh\DomPDF\Facade\Pdf;

class PedidosController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:usuarios');
    }

    // Mostrar lista de pedidos
    public function index()
    {
        $pedidos = Pedidos::with('sucursal')->orderBy('fecha_envio', 'desc')->paginate(10);
        return view('pedido.ver', compact('pedidos'));
    }

    // Formulario para crear nuevo pedido
    public function crear()
{
    $sucursales = Sucursal::all(); // Esto es correcto
    return view('pedido.crear', compact('sucursales')); // <- CAMBIADO
}

    public function buscarPorUltimos4(Request $request)
{
    $ult4 = $request->query('ult4');

    if (!$ult4 || strlen($ult4) !== 4) {
        return response()->json(['status' => 'error', 'message' => 'Faltan dígitos']);
    }

    $bicicleta = Bicicleta::with(['modelo', 'color'])
        ->where('num_chasis', 'like', '%' . $ult4)
        ->first();

    if ($bicicleta) {
        return response()->json(['status' => 'ok', 'bicicleta' => $bicicleta]);
    } else {
        return response()->json(['status' => 'not_found']);
    }
}

    // Guardar pedido con bicicletas (JSON)
    public function store(Request $request)
    {
        $request->validate([
            'id_sucursal' => 'required|exists:sucursales,id_sucursal',
            'bicis_json' => 'required|json',
        ]);

        $bicis = json_decode($request->bicis_json, true);

        if (empty($bicis)) {
            return back()->with('error', 'No se han agregado bicicletas al pedido.');
        }

        // Generar ID incremental
        $ultimo = Pedidos::orderBy('id_pedido', 'desc')->first();
        $nuevoId = $ultimo
            ? 'PED' . str_pad((int)substr($ultimo->id_pedido, 3) + 1, 3, '0', STR_PAD_LEFT)
            : 'PED001';

        foreach ($bicis as $bici) {
            Pedidos::create([
                'id_pedido' => $nuevoId,
                'id_sucursal' => $request->id_sucursal,
                'num_chasis' => $bici['num_chasis'],
                'fecha_envio' => now(),
            ]);
        }

        return redirect()->route('pedido.pdf', $nuevoId);
    }

    // Generar PDF del pedido
    public function generarPDF($id_pedido)
    {
        $pedidos = Pedidos::with(['sucursal', 'bicicleta'])->where('id_pedido', $id_pedido)->get();
        $pdf = Pdf::loadView('pedido.pdf', compact('pedidos'));
        return $pdf->download("Pedido_{$id_pedido}.pdf");
    }
}
