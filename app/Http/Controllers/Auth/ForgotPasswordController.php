<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;

class ForgotPasswordController extends Controller
{
    /*
    | Este controlador maneja el envío de correos electrónicos con enlaces 
    | de restablecimiento de contraseña. Utiliza un trait para incluir 
    | esta lógica sin complicaciones.
    */
    use SendsPasswordResetEmails;

    /**
     * Muestra el formulario de solicitud de enlace (Tu Blade rosa).
     */
    public function showLinkRequestForm()
    {
        return view('auth.forgot-password');
    }
}