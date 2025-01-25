<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class SetLocaleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param Closure(Request): (Response) $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check() && Auth::user()->locale) {
            App::setLocale(Auth::user()->locale);
        } else {
            // Si no tiene un idioma preferido, usar el idioma por defecto
            App::setLocale(config('app.locale'));
        }

        return $next($request);
    }
}
