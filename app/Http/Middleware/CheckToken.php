<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckToken
{
public function handle(Request $request, Closure $next)
{
    if (!session('token')) {
        return redirect('/login');
    }

    $response = $next($request);

    // Evitar que el navegador cachee páginas protegidas
    $response->headers->set('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0');
    $response->headers->set('Pragma', 'no-cache');
    $response->headers->set('Expires', '0');

    return $response;
}
}