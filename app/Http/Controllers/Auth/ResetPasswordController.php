<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ResetsPasswords;

class ResetPasswordController extends Controller
{
    /*
    | Este controlador es responsable de manejar las solicitudes de 
    | restablecimiento de contraseña. Utiliza un trait para incluir
    | esta lógica sin complicaciones.
    */
    use ResetsPasswords;

    /**
     * Dónde redireccionar al usuario después de restablecer su contraseña.
     */
    protected $redirectTo = '/login';

    /**
     * Muestra el formulario para restablecer la contraseña (Tu Blade rosa).
     */
    public function showResetForm($token = null)
    {
        return view('auth.reset-password')->with(
            ['token' => $token]
        );
    }
}