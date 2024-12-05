<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CaptureLaborRateMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Verifica si hay un parámetro labor rate ID
        if ($request->has('camo_activity_id')) {
            // Guardar el ID en la sesión
            session(['camo_activity_id' => $request->get('camo_activity_id')]);
        }

        return $next($request);
    }
}
