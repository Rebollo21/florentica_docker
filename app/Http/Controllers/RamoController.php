<?php

namespace App\Http\Controllers;

use App\Models\Producto; // Tu Ramo
use App\Models\Insumo;
use App\Models\Lote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RamoController extends Controller
{
    public function armar(Request $request)
{
    $producto = Producto::with('insumos')->findOrFail($request->producto_id);
    $cantidadARamos = $request->cantidad ?? 1;

    try {
        return DB::transaction(function () use ($producto, $cantidadARamos) {
            foreach ($producto->insumos as $insumo) {
                $cantidadNecesariaTotal = $insumo->pivot->cantidad * $cantidadARamos;
                $this->descontarConTrazabilidad($producto->id, $insumo, $cantidadNecesariaTotal);
            }

            return redirect()->back()->with('success', "✨ Se armaron {$cantidadARamos} '{$producto->nombre}' correctamente.");
        });
    } catch (\Exception $e) {
        // Si algo sale mal (como falta de stock), regresamos con el error
        return redirect()->back()->with('error', $e->getMessage());
    }
}

    private function descontarConTrazabilidad($productoId, $insumo, $cantidadPendiente)
    {
        // Buscamos lotes disponibles usando FIFO (el que vence primero)
        $lotes = Lote::where('insumo_id', $insumo->id)
            ->where('cantidad_actual', '>', 0)
            ->where('fecha_vencimiento', '>=', now()->startOfDay())
            ->orderBy('fecha_vencimiento', 'asc')
            ->get();

        foreach ($lotes as $lote) {
            if ($cantidadPendiente <= 0) break;

            $cantidadASacar = min($lote->cantidad_actual, $cantidadPendiente);

            // A. Descontamos del lote
            $lote->decrement('cantidad_actual', $cantidadASacar);

            // B. GUARDAMOS EN LA TABLA DE TRAZABILIDAD (La que diseñamos)
            DB::table('producto_lote')->insert([
                'producto_id' => $productoId,
                'lote_id'     => $lote->id,
                'cantidad_usada' => $cantidadASacar,
                'costo_al_momento' => $lote->costo_unitario,
                'created_at' => now(),
            ]);

            $cantidadPendiente -= $cantidadASacar;
        }

        if ($cantidadPendiente > 0) {
            throw new \Exception("¡Error! No hay suficiente stock de {$insumo->nombre_insumo} para completar el ramo.");
        }
    }
    
    
    public function index()
{
    // Traemos todos los productos (Ramos) con sus insumos (la receta)
    $productos = Producto::with('insumos')->get();

    // Retornamos la vista donde aparecerán las "cards" de cada ramo
    return view('admin.ramos.index', compact('productos'));
}

public function catalogo()
{
    // 1. Cargamos los productos con sus insumos y lotes (Eager Loading para no saturar la BD)
    $productos = Producto::with('insumos.lotes')->get();

    // 2. Filtramos la colección para que solo queden los que tienen stock > 0
    $productosDisponibles = $productos->filter(function ($producto) {
        return $producto->calcularStockDisponible() > 0;
    });

    // 3. Enviamos solo los disponibles a la vista
    return view('catalogo.index', [
        'productos' => $productosDisponibles
    ]);
}
}