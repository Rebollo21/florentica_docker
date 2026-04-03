<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; // <--- Importante

class Insumo extends Model
{
    use SoftDeletes;
    
    protected $table = 'insumos';

    // 1. ATRIBUTOS ASIGNABLES: Control total sobre la identidad del insumo
    protected $fillable = [
        'nombre_insumo',
        'tipo', 
        'unidad_medida',
    ];

    // --- RELACIONES (EL CORAZÓN DEL SISTEMA) ---

    public function lotes() {
        /** * Ordenamos por fecha_vencimiento para cumplir con la regla de oro: 
         * "Lo primero que vence, es lo primero que sale" (FIFO).
         */
        return $this->hasMany(Lote::class, 'insumo_id')->orderBy('fecha_vencimiento', 'asc');
    }

    // --- SCOPES (FILTROS RÁPIDOS) ---

    public function scopeFlores($query) {
        return $query->where('tipo', 'flor');
    }

    public function scopeMateriales($query) {
        return $query->where('tipo', 'materia_prima');
    }

    // --- LÓGICA DE NEGOCIO: MOTOR FIFO FLORENTICA ---

    /**
     * Algoritmo inteligente que descuenta existencias de los lotes más viejos
     * hacia los más nuevos, ignorando lo marchito.
     */
   /**
 * Algoritmo FIFO que descuenta stock y registra la trazabilidad por lote.
 */
public function descontarStock($cantidadADescontar, $ventaId) { 
    // 1. Obtenemos lotes útiles (con stock y no caducados)
    $lotesVivos = $this->lotes()
        ->where('cantidad_actual', '>', 0)
        ->where(function($query) {
            $query->whereNull('fecha_vencimiento') 
                  ->orWhere('fecha_vencimiento', '>=', now()); 
        })
        ->get();

    foreach ($lotesVivos as $lote) {
        if ($cantidadADescontar <= 0) break;

        $cantidadTomada = 0;

        // 2. Determinamos cuánto podemos sacar de este lote
        if ($lote->cantidad_actual >= $cantidadADescontar) {
            $cantidadTomada = $cantidadADescontar;
            $lote->decrement('cantidad_actual', $cantidadADescontar);
            $cantidadADescontar = 0;
        } else {
            $cantidadTomada = $lote->cantidad_actual;
            $cantidadADescontar -= $lote->cantidad_actual;
            $lote->update(['cantidad_actual' => 0]);
        }

        // 3. REGISTRO DE TRAZABILIDAD (La parte que faltaba)
        // Guardamos en la nueva tabla de auditoría
        \DB::table('detalle_venta_lotes')->insert([
            'venta_id'            => $ventaId,
            'lote_id'             => $lote->id,
            'cantidad_descontada' => $cantidadTomada,
            'created_at'          => now(),
            'updated_at'          => now(),
        ]);
    }
    
    return $cantidadADescontar == 0; 
}

    // --- ACCESSORS (INTELIGENCIA PARA LA VISTA) ---

    /**
     * Calcula el STOCK TOTAL disponible sumando todos los lotes.
     * Uso en Blade: {{ $insumo->stock_total }}
     */
    public function getStockTotalAttribute() {
        return $this->lotes()->sum('cantidad_actual');
    }

    /**
     * Calcula los días restantes del lote que va a morir primero.
     * Si no es una flor o no hay lotes, devuelve 0 o null.
     */
    public function getDiasRestantesAttribute()
    {
        if ($this->tipo !== 'flor') return null;

        $proximoVencimiento = $this->lotes()
            ->where('cantidad_actual', '>', 0)
            ->where('fecha_vencimiento', '>', now())
            ->min('fecha_vencimiento');

        if (!$proximoVencimiento) return 0;

        /**
         * Carbon::parse asegura que aunque la nube devuelva un string, 
         * nosotros lo tratemos como una fecha real de PHP.
         */
        return (int) now()->diffInDays(Carbon::parse($proximoVencimiento), false);
    }

    // app/Models/Insumo.php
public function ultimoLote()
{
    return $this->hasOne(Lote::class)->latestOfMany();
}
}