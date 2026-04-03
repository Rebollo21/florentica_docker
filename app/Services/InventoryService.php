<?php

namespace App\Services;

use App\Models\Lote;
use App\Models\Producto;

class InventoryService
{
    public function verificarDisponibilidad($cart)
    {
        $insumosNecesarios = [];
        $errores = [];

        if (empty($cart)) return $errores;

        // 1. Recorrer el carrito y sumar insumos según la RECETA
        foreach ($cart as $id => $details) {
            // Buscamos el producto con sus insumos (relación belongsToMany en tu modelo)
            $producto = Producto::with('insumos')->find($id);
            
            if (!$producto) continue;

            foreach ($producto->insumos as $insumo) {
                // 'cantidad' en la tabla pivote es lo que gasta 1 ramo
                $totalRequerido = $insumo->pivot->cantidad * $details['cantidad'];

                if (isset($insumosNecesarios[$insumo->id])) {
                    $insumosNecesarios[$insumo->id]['total'] += $totalRequerido;
                } else {
                    $insumosNecesarios[$insumo->id] = [
                        'nombre' => $insumo->nombre_insumo,
                        'total' => $totalRequerido
                    ];
                }
            }
        }

        // 2. Comparar el total requerido contra la suma de todos los LOTES activos
        foreach ($insumosNecesarios as $idInsumo => $data) {
            $stockDisponible = Lote::where('insumo_id', $idInsumo)
                                    ->where('cantidad_actual', '>', 0)
                                    ->sum('cantidad_actual');

            if ($stockDisponible < $data['total']) {
                $errores[] = "Stock insuficiente de {$data['nombre']}. Requieres {$data['total']} y solo hay {$stockDisponible} en bodega.";
            }
        }

        return $errores;
    }
}