<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Producto;
use App\Models\Comment;

class ShopController extends Controller
{
    /**
     * Esta es la "Landing" o Catálogo para el cliente final.
     */
    public function index()
    {
        $productos = Producto::where('stock', '>', 0)->get(); // Solo lo que hay para vender
        
        $comments = Comment::where('stars', '>=', 3)
                    ->where('approved', 1)
                    ->latest()
                    ->take(6)
                    ->get();

        return view('client.index', compact('productos', 'comments'));
    }

    /**
     * Vista de detalle de un ramo específico.
     */
    public function show($id)
    {
        $producto = Producto::findOrFail($id);
        return view('client.show', compact('producto'));
    }
}