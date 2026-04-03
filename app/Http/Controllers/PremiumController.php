<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Stripe\Stripe;
use Stripe\Checkout\Session;

class PremiumController extends Controller
{
    /**
     * Muestra la interfaz de beneficios Premium.
     */
    public function index()
{
    $user = Auth::user();
    
    // ANTES: return view('profile.premium', compact('user'));
    // AHORA: Debe apuntar a resources/views/client/premium.blade.php
    return view('client.premium', compact('user')); 
}



    /**
     * Activa el estatus Premium en la base de datos.
     */
    public function suscribir(Request $request)
    {
        Stripe::setApiKey(env('STRIPE_SECRET'));

        // Creamos la sesión de pago de Stripe
        $session = Session::create([
        'payment_method_types' => ['card'],
        'line_items' => [[
            'price_data' => [
                'currency' => 'mxn',
                'product_data' => [
                    'name' => 'Florentica Premium',
                ],
                'unit_amount' => 20000, 
            ],
            'quantity' => 1,
        ]],
        'mode' => 'payment',
        // CAMBIA ESTA LÍNEA:
        'success_url' => route('premium.success') . '?session_id={CHECKOUT_SESSION_ID}',
        'cancel_url' => route('premium.index'),
        'customer_email' => Auth::user()->email,
        ]);

        return redirect($session->url);
    }

    public function success(Request $request)
{
    $user = Auth::user();
    
    // Capturamos el ID que viene en la URL (?session_id=cs_test...)
    $sessionId = $request->query('session_id');

    Payment::create([
        'user_id'   => $user->id,
        'stripe_id' => $sessionId, 
        'amount'    => 200.00,
        'status'    => 'completed',
    ]);

    $usuario = \App\Models\User::find($user->id);
    $usuario->es_premium = true;
    $usuario->save();

    return redirect()->route('client.profile')->with('success', '¡Suscripción Elite Activada!');
}
    /**
     * Desactiva el estatus Premium.
     */
    public function cancelar()
    {
        $usuario = User::findOrFail(Auth::id());
        $usuario->es_premium = false;
        $usuario->save();

        return redirect()->route('premium.index')
            ->with('info', 'Has cancelado tu suscripción Premium.');
    }
}