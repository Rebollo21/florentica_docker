<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class Lote extends Model
{
    use SoftDeletes;
    
    protected $table = 'lotes';

    // 1. FILLABLE ACTUALIZADO: Agregamos costo_unitario y quitamos lo innecesario
    protected $fillable = [
        'insumo_id', 
        'cantidad_inicial', 
        'cantidad_actual', 
        'costo_unitario', // Vital para saber cuánto invertiste
        'precio_venta_lote',
        'vida_flor_dias', 
        'fecha_llegada',
        'fecha_vencimiento',
    ];

    // 2. CASTS: Para que Laravel trate los datos correctamente
    protected $casts = [
        'fecha_vencimiento' => 'datetime',
        'cantidad_actual'   => 'integer',
        'cantidad_inicial'  => 'integer',
        'costo_unitario'    => 'decimal:2', // 💵 Asegura que siempre tenga 2 decimales
        'precio_venta_lote' => 'decimal:2',
    ];

    // --- RELACIONES ---

    /** Un lote pertenece a un producto del catálogo */
    public function insumo() {
        return $this->belongsTo(Insumo::class, 'insumo_id');
    }

    // --- ACCESSORS (INTELIGENCIA DE NEGOCIO) ---

    /**
     * Calcula la utilidad proyectada de este lote específico.
     * Uso: $lote->utilidad_estimada
     */
    public function getUtilidadEstimadaAttribute() {
// Usamos el precio que definimos para este lote, no el del insumo
        $utilidadPorUnidad = $this->precio_venta_lote - $this->costo_unitario;
        return $this->cantidad_actual * $utilidadPorUnidad;
    }

    /**
     * Indica si el lote está próximo a vencer (menos de 2 días).
     * Uso: $lote->esta_critico
     */
    public function getEstaCriticoAttribute() {
        if (!$this->fecha_vencimiento) return false;
        return $this->fecha_vencimiento->isPast() || $this->fecha_vencimiento->diffInDays(now()) <= 2;
    }
    
    // Agregamos el Accessor para la ganancia (esto es puro cálculo de software)
    public function getGananciaLoteAttribute() {
        return ($this->precio_venta_lote - $this->costo_unitario) * $this->cantidad_actual;
    }
    
    
    /**
 * Calcula los días reales que le quedan de vida.
 * Uso: $lote->dias_restantes
 */
public function getDiasRestantesAttribute() {
    if (!$this->fecha_vencimiento) return null;

   
    $vencimiento = \Carbon\Carbon::parse($this->fecha_vencimiento)->startOfDay();
	 $hoy = now()->startOfDay();
    
    // diffInDays con 'false' permite que el número sea negativo si ya venció
    return (int) $hoy->diffInDays($vencimiento, false);
}
public function mermas()
{
    return $this->hasMany(Merma::class);
}


}