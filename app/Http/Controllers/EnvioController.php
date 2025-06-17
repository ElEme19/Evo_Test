<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Envio;
use App\Models\Sucursal;
//use App\Models\Personal;
use Illuminate\Support\Str;

class EnvioController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:usuarios');
    }

    public function crear()
    {
        // Para el formulario de creación
        $sucursales = Sucursal::all();
       

        return view('Envio.crear', compact('sucursales'));
    }



        public function store(Request $request)
     {
        $request->validate([
            'id_sucursal' => 'nullable|string|max:64|exists:sucursales,id_sucursal',
            'id_personal' => 'nullable|string|max:64|exists:personal,id_personal',
            'fecha_envio' => 'nullable|date',
        ]);

        try {
           
            do {
                $generatedId = strtoupper('ENV-' . Str::random(10));
            } while (Envio::where('id_envio', $generatedId)->exists());

            $envio = new Envio();
            $envio->id_envio = $generatedId;
            $envio->id_sucursal = $request->id_sucursal;
            $envio->id_personal = $request->id_personal;
            $envio->fecha_envio = $request->fecha_envio;

            if ($envio->save()) {
                return redirect()->back()->with('success', '¡Envío creado correctamente! ID: ' . $generatedId);
            } else {
                return redirect()->back()->with('error', 'No se pudo crear el envío. Intenta nuevamente.');
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Ocurrió un error inesperado :( : ' . $e->getMessage());
        }
    }


    public function ver()
    {
        $envios = Envio::with(['sucursal'])->get(); 
        return view('Envio.vista', compact('envios'));
    }

    public function update(Request $request, $id_envio)
    {
        $request->validate([
            'id_sucursal' => 'nullable|string|max:64|exists:sucursales,id_sucursal',
            'id_personal' => 'nullable|string|max:64|exists:personal,id_personal',
            'fecha_envio' => 'nullable|date',
        ]);

        $updated = Envio::where('id_envio', $id_envio)
            ->update([
                'id_sucursal' => $request->id_sucursal,
                'id_personal' => $request->id_personal,
                'fecha_envio' => $request->fecha_envio,
            ]);

        if ($updated) {
            return redirect()->route('Envio.vista')->with('success', '¡Envío actualizado correctamente!');
        } else {
            return redirect()->back()->with('error', 'No se pudo actualizar el envío.');
        }
    }
}
