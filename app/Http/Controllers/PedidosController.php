<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pedidos;
use App\Models\Sucursal;
use App\Models\Bicicleta;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PedidosController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:usuarios');
    }

    // Mostrar lista de pedidos con sucursal y bicicletas cargadas
    public function index()
{
    $pedidos = Pedidos::with([
        'sucursal',
        'bicicletas.modelo',
        'bicicletas.color'
    ])
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

  
public function store(Request $request)
{
    $request->validate([
        'id_sucursal' => 'required|exists:sucursales,id_sucursal',
        'bicis_json'  => 'required|json',
    ]);

    $bicis = json_decode($request->bicis_json, true);
    if (empty($bicis)) {
        return back()->with('error', 'No se han agregado bicicletas al pedido.');
    }

    DB::transaction(function () use ($bicis, $request, &$nuevoId) {
        // Generar un ID aleatorio único de 8 caracteres
        do {
            $nuevoId = Str::upper(Str::random(8));  // p.ej. "A1B2C3D4"
        } while (Pedidos::where('id_pedido', $nuevoId)->exists());

        // Crear nuevo pedido
        $pedido = Pedidos::create([
            'id_pedido'   => $nuevoId,
            'id_sucursal' => $request->id_sucursal,
            'num_chasis'  => null,
            'fecha_envio' => now(),
        ]);

        // Asociar cada bicicleta al nuevo pedido
        foreach ($bicis as $bici) {
            Bicicleta::where('num_chasis', $bici['num_chasis'])
                     ->update(['id_pedido' => $nuevoId]);
        }
    });

    return redirect()
        ->route('pedido.ver')
        ->with([
            'success' => "Pedido {$nuevoId} creado correctamente.",
            'pdf_url' => route('pedido.pdf', $nuevoId),
        ]);
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
       return $pdf
        ->stream("Pedido_{$id_pedido}.pdf")
        ;
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

//   ==> Editar el pedido

public function editar($id_pedido)
{
    $pedido = Pedidos::with([
        'bicicletas.modelo',
        'bicicletas.color',
        'bicicletas.voltaje',
        'sucursal'
    ])->where('id_pedido', $id_pedido)->firstOrFail();

    return view('pedido.editar', compact('pedido'));
}


public function agregarBici(Request $request, $id_pedido)
{
    $request->validate([
        'num_chasis' => 'required|exists:bicicleta,num_chasis',
    ]);

    $bicicleta = Bicicleta::where('num_chasis', $request->num_chasis)->first();

    // Verifica que no esté ya asignada a otro pedido
    if ($bicicleta->id_pedido && $bicicleta->id_pedido !== $id_pedido) {
        return back()->with('error', 'La bicicleta ya pertenece a otro pedido.');
    }

    $bicicleta->update(['id_pedido' => $id_pedido]);

    return back()->with('success', 'Bicicleta añadida al pedido correctamente.');
}


public function eliminarBici($id_pedido, $num_chasis)
{
    // Busca la bici en ese pedido
    $bicicleta = Bicicleta::where('num_chasis', $num_chasis)
                          ->where('id_pedido', $id_pedido)
                          ->firstOrFail();

    // Desasocia del pedido
    $bicicleta->update(['id_pedido' => null]);

    return back()->with('success', "Bicicleta {$num_chasis} eliminada del pedido.");
}


public function finalizar($id_pedido)
{
    $pedido = Pedidos::with('bicicletas')->where('id_pedido', $id_pedido)->firstOrFail();

    if ($pedido->bicicletas->isEmpty()) {
        return back()->with('error', 'El pedido no puede finalizarse sin bicicletas.');
    }

    // Si tienes un campo `estado`, podrías hacer:
    // $pedido->update(['estado' => 'finalizado']);

    return redirect()
        ->route('pedido.ver')
        ->with('success', "Pedido {$id_pedido} finalizado correctamente.");
}


}
