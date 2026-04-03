<?php


namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Venta; // <--- ASEGÚRATE DE QUE EL MODELO SE LLAME ASÍ
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function index()
    {
        // 1. Obtenemos el ID del usuario logueado
        $userId = Auth::id();

        // 2. Consultamos sus compras reales de la BD
        // Si tu tabla se llama distinto, cambia 'DetalleVenta' por tu Modelo
$detalle_ventas = Venta::where('user_id', Auth::id()) 
                            ->orderBy('created_at', 'asc')
                            ->get();

        // 3. Retornamos la vista pasando la variable
        return view('client.profile', compact('detalle_ventas'));
    }
}