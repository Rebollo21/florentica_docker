<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comment;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
  public function store(Request $request)
{
    $request->validate([
        'stars' => 'required|integer|min:1|max:5',
        'comment' => 'required|string|max:500',
        'photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
    ]);

    $path = null;
    if ($request->hasFile('photo')) {
        $file = $request->file('photo');
        $nombreArchivo = time() . '_' . $file->getClientOriginalName();
        
        // El truco: Mover el archivo directamente a la carpeta public/comments
        $file->move(public_path('comments'), $nombreArchivo);
        
        // Guardamos solo el nombre o la ruta relativa para la DB
        $path = 'comments/' . $nombreArchivo;
    }

    Comment::create([
        'user_id' => auth()->id(), // El ID del usuario logueado
        'stars' => $request->stars,
        'comment' => $request->comment,
        'photo' => $path,
    ]);

    return back()->with('success', '¡Gracias por tu comentario!');
}
}
