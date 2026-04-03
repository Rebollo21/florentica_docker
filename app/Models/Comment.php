<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 
        'stars', 
        'comment', 
        'photo'
    ]; // Campos que permitimos guardar

    // Relación: Un comentario pertenece a un usuario
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
