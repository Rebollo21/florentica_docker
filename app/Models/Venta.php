<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Venta extends Model
{
    use HasFactory;

    // Campos que permitimos llenar (Mass Assignment)
    protected $fillable = [
        'user_id',
        'total',
        'pago_id',
        'metodo_pago',
        'estatus'
    ];

    // Relación: Una venta pertenece a un usuario
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    // app/Models/DetalleVenta.php

}