<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pedidos;
use App\Models\Sucursal;
use App\Models\Bicicleta;
use App\Models\modelos_bici;
use App\Models\ColorModelo;
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

    // Generar ID incremental del pedido
    $ultimo = Pedidos::orderBy('id_pedido', 'desc')->first();
    $nuevoId = 'PED001';

    if ($ultimo && preg_match('/^PED(\d+)$/', $ultimo->id_pedido, $match)) {
        $numero = (int)$match[1] + 1;
        $nuevoId = 'PED' . str_pad($numero, 3, '0', STR_PAD_LEFT);
    }

    // Crear un solo pedido
    $pedido = Pedidos::create([
        'id_pedido' => $nuevoId,
        'id_sucursal' => $request->id_sucursal,
        'num_chasis' => null, // opcional: ya no lo necesitas aquí
        'fecha_envio' => now(),
    ]);

    // Actualizar cada bicicleta con el ID del pedido
    foreach ($bicis as $bici) {
        Bicicleta::where('num_chasis', $bici['num_chasis'])->update([
            'id_pedido' => $nuevoId
        ]);
    }

    return redirect()->route('pedido.pdf', $nuevoId);
}

    // Generar PDF del pedido
    public function generarPDF($id_pedido)
{
    $pedido = Pedidos::with([
        'sucursal',
        'bicicletas.modelo',
        'bicicletas.color',
    ])->where('id_pedido', $id_pedido)->firstOrFail();

    $pdf = Pdf::loadView('pedido.pdf', compact('pedido'));
    return $pdf->download("Pedido_{$id_pedido}.pdf");
}


}
