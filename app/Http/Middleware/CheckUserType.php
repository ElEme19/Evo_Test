<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class CheckUserType
{
    public function handle(Request $request, Closure $next, $type)
    {
        // Verificar si el usuario está autenticado y si su tipo coincide con el esperado
        if (Auth::guard('usuarios')->check() && Auth::guard('usuarios')->user()->user_tipo != $type) {
            // Redirigir a una página con un mensaje de error
            return redirect()->route('login')->withErrors(['error' => 'No tienes permisos para acceder a esta página.']);
        }

        return $next($request);
    }
}

