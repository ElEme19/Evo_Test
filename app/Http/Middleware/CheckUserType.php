<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckUserType
{
    public function handle(Request $request, Closure $next, ...$types)
    {
        $user = Auth::guard('usuarios')->user();

        if (!$user || (!in_array($user->user_tipo, $types) && $user->id != 1)) {
            return redirect()->route('login')->withErrors(['error' => 'No tienes permisos para acceder a esta página.']);
        }

        return $next($request);
    }
}
