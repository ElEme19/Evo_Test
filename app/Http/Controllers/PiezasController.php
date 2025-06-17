<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Piezas;
use Illuminate\Support\Facades\Hash;

class PiezasController extends Controller
{

    public function __construct(){

        $user = auth()-> guard('usuarios')-> user();
        $this->middleware('auth:usuarios',compact('user'));

    }

    public function crear (){

        return view('piezas.crear');
    }

    public function inicio (){

        return view('Mexico.inicio');
    }

    public function ver (){
        $piezas = Piezas::all();
        //dd($piezas);
        return view ('piezas.ver', compact('piezas'));


    }


    public function update (Request $request, Piezas $piezas){
        
        $request->validate([

            'nombre_pieza' => 'required|string|max:45',
            'descripcion_pieza' => 'required|string|',
        ]);
            $piezas->update($request->all());
            

            return redirect()->back()->with('success','Pieza actualizada correctamente!');
            


    }

    
    public function store (Request $request){
        $request -> validate([
            
            'nombre_pieza' => 'required|string|max:45',
            'descripcion_pieza' => 'required|string|',
        ]);

        $piezas = new Piezas();
        $piezas -> nombre_pieza =$request->nombre_pieza;
        $piezas -> descripcion_pieza =$request->descripcion_pieza;

        $piezas->save();

        return redirect()->back()->with('success','Pieza insertada correctamente!');
        
        

    }
}
