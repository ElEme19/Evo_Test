<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RecordLastPage
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next)
{
    if (
        $request->method() === 'GET' &&
        !$request->ajax() &&
        $request->path() !== 'login' &&
        url()->previous() !== url()->current()
    ) {
        session(['Mexico.inicio' => url()->previous()]);
    }

    return $next($request);
}

}
