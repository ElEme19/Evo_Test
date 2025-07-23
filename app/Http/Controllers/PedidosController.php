<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pedidos;
use App\Models\Sucursal;
use App\Models\Bicicleta;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

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

    DB::transaction(function () use ($bicis, $request, &$nuevoId, &$pedido) {
        do {
            $nuevoId = Str::upper(Str::random(8));
        } while (Pedidos::where('id_pedido', $nuevoId)->exists());

        $pedido = Pedidos::create([
            'id_pedido'   => $nuevoId,
            'id_sucursal' => $request->id_sucursal,
            'fecha_envio' => now(),
            'status'      => 1,
        ]);

        // Generar token único solo si no existe (al crear siempre es null)
        if (!$pedido->qr_token) {
            $pedido->qr_token = Str::random(200); // cadena aleatoria 40 caracteres
            $pedido->save();
        }

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
        'cliente',
        'bicicletas.modelo',
        'bicicletas.color'
    ])->where('id_pedido', $id_pedido)->firstOrFail();

    if ($pedido->status == 2) {
        // Eliminar el token QR (ya no se usará)
        $pedido->qr_token = 'Ya usado';
        $pedido->save();

        $sucursal = $pedido->sucursal;
        $cliente = $pedido->sucursal->cliente;

        // Construir la información que irá en el QR
        $payload = "SUCURSAL:\n";
        $payload .= "Nombre: {$sucursal->nombre_sucursal}\n";
        $payload .= "Ubicación: {$sucursal->localizacion}\n";

        if ($cliente) {
            $payload .= "Cliente: {$cliente->nombre} {$cliente->apellido}\n";
            $telefono = preg_replace('/\D/', '', $cliente->telefono); 

            if (!Str::startsWith($telefono, '52')) {
                $telefono = '52' . ltrim($telefono, '0'); 
            }

            $whatsappLink = "https://wa.me/{$telefono}";

            $payload .= "WhatsApp: {$whatsappLink}\n";

        } else {
            $payload .= "Cliente: Sin valor\n";
            $payload .= "WhatsApp: Sin valor\n";
        }

        $qr_svg = base64_encode(QrCode::format('svg')->size(200)->generate($payload));
    } else {
        if (!$pedido->qr_token) {
            abort(500, 'Token QR no generado para este pedido.');
        }

        $qr_url = route('pedido.confirmarQR', ['token' => $pedido->qr_token]);
        $qr_svg = base64_encode(QrCode::format('svg')->size(200)->generate($qr_url));
    }

    $pdf = Pdf::loadView('pedido.pdf', compact('pedido', 'qr_svg'));
    return $pdf->stream("Pedido_{$id_pedido}.pdf");
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

    $bici = Bicicleta::where('num_chasis', $request->num_chasis)->firstOrFail();

    if ($bici->id_pedido && $bici->id_pedido != $id_pedido) {
        return back()->with('error', 'La bicicleta ya pertenece a otro pedido.');
    }

    // FORZAMOS la asignación con save()
    $bici->id_pedido = $id_pedido;
    $bici->save();

    return back()->with('success', 'Bicicleta añadida al pedido correctamente.');
}



public function eliminarBici($id_pedido, $biciId)
{
    $bicicleta = \App\Models\Bicicleta::where('num_chasis', $biciId)
        ->where('id_pedido', $id_pedido)
        ->firstOrFail();

    $bicicleta->id_pedido = null;
    $bicicleta->save();

    return back()->with('error', "Bicicleta {$biciId} eliminada del pedido.");
}




public function finalizar($id_pedido)
    {
        // Opcional: valida que exista
        $pedido = Pedidos::findOrFail($id_pedido);

        // Aquí podrías marcarlo como "finalizado" si tienes un campo estado:
        // $pedido->update(['estado' => 'finalizado']);

        // Redirige al formulario de creación con mensaje
        return redirect()
            ->route('pedido.ver')
            ->with('success', "Pedido {$id_pedido} guardado correctamente. Ahora puedes crear uno nuevo.");
    }



    public function confirmarQR($token)
{
    $pedido = Pedidos::where('qr_token', $token)->first();

    if (!$pedido) {
        return abort(404, 'Pedido no encontrado');
    }

    if ($pedido->status == 2) {
        // Ya usado, no permitir confirmar otra vez
        return abort(404, 'Este pedido ya fue confirmado y enviado');
    }

    // Cambiar status a 2 (saliente)
    $pedido->status = 2;
    $pedido->save();

    return view('pedido.confirmado', compact('pedido'));
}

}
