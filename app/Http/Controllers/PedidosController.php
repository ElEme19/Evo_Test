<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pedidos;
use App\Models\Sucursal;
use App\Models\Bicicleta;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;

class PedidosController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:usuarios');
    }

    // Mostrar lista de pedidos con sucursal y bicicletas cargadas
    public function index()
    {
        $pedidos = Pedidos::with(['sucursal', 'bicicletas'])
                    ->orderBy('fecha_envio', 'desc')
                    ->paginate(10);
        return view('pedido.ver', compact('pedidos'));
    }

    // Formulario para crear nuevo pedido
    public function crear()
    {
        $sucursales = Sucursal::all();
        return view('pedido.crear', compact('sucursales'));
    }

    // Guardar pedido con bicicletas (recibido en JSON)
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

        DB::transaction(function () use ($bicis, $request, &$nuevoId) {
            // Obtener último pedido para generar ID incremental
            $ultimo = Pedidos::orderBy('id_pedido', 'desc')->first();
            $nuevoId = 'PED001';

            if ($ultimo && preg_match('/^PED(\d+)$/', $ultimo->id_pedido, $match)) {
                $numero = (int)$match[1] + 1;
                $nuevoId = 'PED' . str_pad($numero, 3, '0', STR_PAD_LEFT);
            }

            // Crear nuevo pedido
            $pedido = Pedidos::create([
                'id_pedido' => $nuevoId,
                'id_sucursal' => $request->id_sucursal,
                'num_chasis' => null,
                'fecha_envio' => now(),
            ]);

            // Asociar cada bicicleta al nuevo pedido
            foreach ($bicis as $bici) {
                Bicicleta::where('num_chasis', $bici['num_chasis'])->update([
                    'id_pedido' => $nuevoId,
                ]);
            }
        });

        // Redireccionar para descargar PDF
        return redirect()->route('pedido.pdf', $nuevoId);
    }

    // Generar PDF del pedido con sucursal, bicicletas, modelos y colores cargados
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

    public function buscar(Request $request)
{
    $termino = $request->input('q');

    $pedidos = Pedidos::with(['sucursal', 'bicicletas'])
        ->where('id_pedido', 'LIKE', "%$termino%")
        ->orWhereHas('sucursal', function ($query) use ($termino) {
            $query->where('nombre_sucursal', 'LIKE', "%$termino%");
        })
        ->orderBy('fecha_envio', 'desc')
        ->paginate(10);

    return view('pedido.ver', compact('pedidos'))->with('busqueda', $termino);
}
}
