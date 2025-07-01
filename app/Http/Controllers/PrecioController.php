<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Precio;
use App\Models\Membresia;
use App\Models\VoltajeModeloD;
use App\Models\VoltajeModelo;
use Illuminate\Support\Facades\DB;
use App\Models\modelos_bici;
use Illuminate\Support\Facades\Log;

class PrecioController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:usuarios');
    }

    // Mostrar todos los precios
    public function index()
{

    $precios = Precio::with(['membresia', 'modelo', 'voltaje' ])
                     ->orderBy('id_precio', 'asc')
                     ->paginate(15);

    $membresias = Membresia::all();
    $modelos    = modelos_bici::all();
    $voltaje    = VoltajeModeloD::all();

    return view('Precio.index', compact('precios', 'membresias', 'modelos', 'voltaje'));
}


    // Mostrar formulario de creación
   public function create()
{
    $ultima = Precio::orderBy('id_precio', 'desc')->first();
    $nextId = $ultima
        ? 'PRE' . str_pad((int)substr($ultima->id_precio, 3) + 1, 3, '0', STR_PAD_LEFT)
        : 'PRE001';

    $membresias = Membresia::all();
    $modelos    = modelos_bici::all();

    return view('Precio.create', compact('nextId', 'membresias', 'modelos'));
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

    // Almacenar un nuevo precio
    public function store(Request $request)
{
    // 1. Generar nuevo ID antes de validar
    $ultima = Precio::orderBy('id_precio', 'desc')->first();
    $nuevoId = $ultima
        ? 'PRE' . str_pad((int)substr($ultima->id_precio, 3) + 1, 3, '0', STR_PAD_LEFT)
        : 'PRE001';

    // 2. Validar TODOS los campos, incluido id_precio
    $data = $request->validate([
        'id_precio'    => "required|string|unique:precio,id_precio",
        'id_membresia' => 'required|exists:membresia,id_membresia',
        'id_modelo'    => 'required|exists:modelos,id_modelo',
        'id_voltaje'   => 'required|exists:voltaje,id_voltaje',
        'precio'       => 'required|numeric|min:0',
    ]);

    // 3. Reemplazar id_precio validado con el que generaste
    $data['id_precio'] = $nuevoId;

    // 4. Crear el registro
    Precio::create($data);

    return redirect()->route('Precio.create')
                     ->with('success', '¡Precio registrado correctamente!');
}



    // Actualizar precio
    public function update(Request $request, $id)
    {
        $request->validate([
            'id_membresia' => 'required|exists:membresia,id_membresia',
            'id_modelo' => 'required|exists:modelos,id_modelo',
            'precio' => 'required|numeric|min:0',
        ]);

        $precio = Precio::findOrFail($id);
        $precio->id_membresia = $request->id_membresia;
        $precio->id_modelo = $request->id_modelo;
        $precio->precio = $request->precio;

        $precio->save();

        return redirect()->route('Precio.index')->with('success', '¡Precio actualizado correctamente!');
    }


public function buscar(Request $request)
{
    $busqueda = $request->input('q');
    $membresias = Membresia::all(); // Asegúrate de importar el modelo Membresia
    
    $precios = Precio::with(['modelo', 'voltaje', 'membresia'])
        ->whereHas('modelo', function($query) use ($busqueda) {
            $query->where('nombre_modelo', 'like', "%$busqueda%");
        })
        ->orWhere('id_precio', 'like', "%$busqueda%")
        ->orWhere('precio', 'like', "%$busqueda%")
        ->paginate(10);

    return view('Precio.index', compact('precios', 'busqueda', 'membresias'));
}

}