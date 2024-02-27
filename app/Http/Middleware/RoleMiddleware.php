<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Vérifier si l'utilisateur a la permission "assign-roles"
        if ($request->user() && $request->user()->can('assign-roles')) {
            return $next($request);
        }

        // Rediriger ou renvoyer une réponse d'erreur selon votre logique
        return redirect()->route('users.login')->with('error', 'Vous n\'avez pas les autorisations nécessaires.');
    }
}
