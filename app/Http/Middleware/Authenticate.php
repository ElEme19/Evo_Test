<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request; // ==> Tal ves quitar estooo

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
     protected function redirectTo($request)
    {
        if (! $request->expectsJson()){
            return route ('login');
        }

    } 

   /*  protected function redirectTo(Request $request): ?string
    {
        return $request->expectsJson() ? null :route('login');
    }
 */

}
