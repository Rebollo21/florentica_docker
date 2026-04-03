<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Merma extends Model
{
    use HasFactory;

    // Definimos los campos que se pueden llenar masivamente
    protected $fillable = [
        'lote_id',
        'cantidad',
        'costo_perdido',
        'motivo'
    ];

    /**
     * Relación: Una merma pertenece a un lote específico.
     */
    public function lote()
    {
        return $this->belongsTo(Lote::class);
    }
}