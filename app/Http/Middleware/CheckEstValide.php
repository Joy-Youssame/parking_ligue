<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckEstValide
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next)
{
    if (auth()->check() && auth()->user()->est_valide == 0) {
        // Si l'utilisateur est connecté mais pas validé
        return redirect()->route('dashboard')->with('error', 'Votre compte est en attente de validation par un administrateur.');
    }

    return $next($request);
}
}
