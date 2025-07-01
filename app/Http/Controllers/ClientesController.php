<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cliente;
use App\Models\Membresia;

class ClientesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:usuarios');
    }

    // Mostrar todos los clientes
    // app/Http/Controllers/ClienteController.php

public function index()
{
    // Paginamos a 20 registros por página y ordenamos por nombre (o el campo que prefieras)
    $clientes = Cliente::with('membresia')
                       ->orderBy('nombre', 'asc')
                       ->paginate(20);

    $membresias = Membresia::all();
    return view('Clientes.index', compact('clientes', 'membresias'));
}



    // Mostrar formulario de creación
    public function create()
    {
        $ultima = Cliente::orderBy('id_cliente', 'desc')->first();
        $nextId = $ultima
            ? 'CLT' . str_pad((int)substr($ultima->id_cliente, 3) + 1, 3, '0', STR_PAD_LEFT)
            : 'CLT001';

        $membresias = Membresia::all();
        return view('Clientes.create', compact('nextId', 'membresias'));
    }

    // Almacenar un nuevo cliente
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:100',
            'apellido' => 'required|string|max:100',
            'telefono' => 'required|string|max:10',
            'id_membresia' => 'required|exists:membresia,id_membresia',
            'foto_persona' => 'nullable|image|max:2048',
        ]);

        $ultima = Cliente::orderBy('id_cliente', 'desc')->first();
        $nuevoId = $ultima
            ? 'CLT' . str_pad((int)substr($ultima->id_cliente, 3) + 1, 3, '0', STR_PAD_LEFT)
            : 'CLT001';

        $cliente = new Cliente();
        $cliente->id_cliente = $nuevoId;
        $cliente->nombre = $request->nombre;
        $cliente->apellido = $request->apellido;
        $cliente->telefono = $request->telefono;
        $cliente->id_membresia = $request->id_membresia;

        // Manejo de imagen
        if ($request->hasFile('foto_persona')) {
            $archivo = $request->file('foto_persona');
            $nombreArchivo = $nuevoId . '.' . $archivo->getClientOriginalExtension();
            $archivo->move(public_path('img/clientes'), $nombreArchivo);
            $cliente->foto_persona = $nombreArchivo;
        }

        $cliente->save();

        return redirect()->route('Clientes.create')->with('success', '¡Cliente registrado correctamente!');
    }

    // Mostrar formulario de edición
    

    // Actualizar cliente
    public function update(Request $request, $id)
    {
        $request->validate([
            'nombre' => 'required|string|max:100',
            'apellido' => 'required|string|max:100',
            'telefono' => 'required|string|max:20',
            'id_membresia' => 'required|exists:membresia,id_membresia',
            'foto_persona' => 'nullable|image|max:2048',
        ]);

        $cliente = Cliente::findOrFail($id);
        $cliente->nombre = $request->nombre;
        $cliente->apellido = $request->apellido;
        $cliente->telefono = $request->telefono;
        $cliente->id_membresia = $request->id_membresia;
        

        if ($request->hasFile('foto_persona')) {
            $archivo = $request->file('foto_persona');
            $nombreArchivo = $cliente->id_cliente . '.' . $archivo->getClientOriginalExtension();
            $archivo->move(public_path('img/clientes'), $nombreArchivo);
            $cliente->foto_persona = $nombreArchivo;
        }

        $cliente->save();

        return redirect()->route('Clientes.index' )->with('success', '¡Cliente actualizado correctamente!');
    }

   public function buscar(Request $request)
    {
        $busqueda = $request->input('q');
        $membresias = Membresia::all(); // Para usar en selects de formularios

        $clientes = Cliente::with(['membresia'])
            ->where(function($query) use ($busqueda) {
                $query->where('id_cliente', 'like', "%$busqueda%")
                      ->orWhere('nombre', 'like', "%$busqueda%")
                      ->orWhere('apellido', 'like', "%$busqueda%")
                      ->orWhere('telefono', 'like', "%$busqueda%")
                      ->orWhereHas('membresia', function($q) use ($busqueda) {
                          $q->where('descripcion_general', 'like', "%$busqueda%");
                      });
            })
            ->orderBy('nombre', 'asc')
            ->paginate(20);

        return view('Clientes.index', ['clientes' => $clientes,'busqueda' => $busqueda,'membresias' => $membresias]);



}
}