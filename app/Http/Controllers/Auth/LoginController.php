<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\usuarios;

class LoginController extends Controller
{
    public function verFormLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        // Validación básica
        $request->validate([
            'user_name' => 'required|string',
            'user_pass' => 'required|string',
        ]);

        $credenciales = $request->only('user_name', 'user_pass');
        $usuario = usuarios::where('user_name', $credenciales['user_name'])->first();

        if ($usuario && Hash::check($credenciales['user_pass'], $usuario->user_pass)) {
            Auth::guard('usuarios')->login($usuario);
            return redirect()->intended('/Mexico/inicio');
        }

        return back()->withErrors([
            'user_name' => 'Credenciales erróneas.',
        ]);
    }

    public function logout(Request $request)
    {
        Auth::guard('usuarios')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
