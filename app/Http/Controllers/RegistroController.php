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
            'correo' => 'required|email|unique:usuarios,correo',
            'user_pass' => 'required|min:7|confirmed',
            'nombre_user' => 'required',
            'apellido_usuario' => 'required'
        ]);

        if ($validarDatos->fails()) {
            return redirect()->back()->withErrors($validarDatos)->withInput();
        }

        $user = new usuarios();
        $user->correo = $request->correo;
        $user->user_pass = Hash::make($request->user_pass);
        $user->nombre_user = $request->nombre_user;
        $user->apellido_usuario = $request->apellido_usuario;
        $user -> save();

        return redirect()->back()->with('success', 'Bienvenido Evobiker');

    }
}
