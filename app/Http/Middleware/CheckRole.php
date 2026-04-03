<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
public function handle(Request $request, Closure $next, string $role): Response
{
    // 1. Usamos la Fachada Auth (Asegúrate de tener el 'use' arriba)
    if (!\Illuminate\Support\Facades\Auth::check()) {
        abort(404);
    }

    // 2. Extraemos el valor del rol
    $user = \Illuminate\Support\Facades\Auth::user();
    
    // Obtenemos el valor string del Enum ('admin', 'buyer', etc.)
    $currentRole = $user->role instanceof \App\Enums\UserRole 
                   ? $user->role->value 
                   : $user->role;

    // 3. LA VALIDACIÓN CRÍTICA
    // En CheckRole.php
    // Si el rol no coincide (ej: un Buyer intentando entrar a /admin), lanzamos 404.
if ($currentRole !== $role) {
    // Abortamos con el código 403 (Prohibido)
    abort(404);
}

    return $next($request);
}
}
