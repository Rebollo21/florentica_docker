<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Producto extends Model
{
    use SoftDeletes;

    // Ajusté los campos para que coincidan con tu base de datos
    protected $fillable = ['nombre', 'descripcion', 'precio_venta', 'imagen_url'];

    /**
     * Relación con los Insumos (La Receta del Ramo)
     */
    public function insumos()
    {
        return $this->belongsToMany(Insumo::class, 'producto_insumo')
                    ->withPivot('cantidad') 
                    ->withTimestamps();
    }

    /**
     * Accessor para la imagen
     */
    public function getPortadaUrlAttribute()
    {
        // Usamos imagen_url que es el nombre real en tu tabla
        if (!$this->imagen_url) return asset('imagenes/default.png');

        $primera = trim(explode(',', $this->imagen_url)[0]);
        
        if (!str_contains($primera, 'imagenes/')) {
            $primera = 'imagenes/' . $primera;
        }
        
        return asset($primera);
    }

    /**
     * Cálculo de Stock basado en Insumos Reales
     */
    /**
     * 
 * Accessor para obtener el stock dinámico
 * Uso: $producto->stock
 */
public function getStockAttribute()
{
    return (int)$this->calcularStockDisponible();
}

/**
 * Cálculo de Stock basado en Insumos Reales
 */
public function calcularStockDisponible()
{
    // Usamos 'relationLoaded' o cargamos manualmente si no existe para evitar errores
    $insumosNecesarios = $this->insumos; 

    if ($insumosNecesarios->isEmpty()) {
        return 0; 
    }

    $posiblesPorInsumo = [];

    foreach ($insumosNecesarios as $insumo) {
        // Sumamos la cantidad_actual de todos los lotes de este insumo
        $stockTotalInsumo = $insumo->lotes()->sum('cantidad_actual');

        $cantidadRequerida = $insumo->pivot->cantidad;

        if ($cantidadRequerida > 0) {
            // floor() asegura que si ocupas 2.5 flores, solo cuente como 2 ramos
            $posiblesPorInsumo[] = floor($stockTotalInsumo / $cantidadRequerida);
        }
    }

    return count($posiblesPorInsumo) > 0 ? (int)min($posiblesPorInsumo) : 0;
}


}