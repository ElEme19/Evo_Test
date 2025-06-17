<?php
namespace App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
namespace App\Http\Controllers;

use App\Models\Piezas;
use Illuminate\Http\Request;
use App\Models\usuarios;
use Dotenv\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator as FacadesValidator;

class RegistroController extends Controller
{
    public function registrarse(){
        $user = auth()->guard ('usuarios') -> user();
        return view('piezas.registrarse',compact('user'));

    }

    public function registrar(Request $request){

        $validarDatos = FacadesValidator::make($request->all(),[
            'user_name' => 'required|unique:usuarios,user_name',
            'user_pass' => 'required|min:7',
            'user_tipo' => 'required'
        ]);

        if ($validarDatos->fails()) {
            return redirect()->back()->withErrors($validarDatos)->withInput();
        }

        $user = new usuarios();
        $user->user_name = $request->user_name;
        $user->user_pass = Hash::make($request->user_pass);
        $user->user_tipo = $request->user_tipo;
        $user -> save();

        return redirect()->back()->with('success', 'Usuario Creado');

    }
}
