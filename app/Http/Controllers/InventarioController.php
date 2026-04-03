<?php

namespace App\Http\Controllers;

use App\Models\Insumo;
use App\Models\Lote;
use Illuminate\Http\Request;
use Carbon\Carbon;

class InventarioController extends Controller
{
    /** Registra la llegada de nueva mercancía */
    public function registrarEntrada(Request $request)
    {
        $request->validate([
            'insumo_id' => 'required|exists:insumos,id',
            'cantidad' => 'required|integer|min:1',
            'vida_flor' => 'nullable|integer|min:1', // Solo si es flor
        ]);

        $insumo = Insumo::findOrFail($request->insumo_id);

        // 1. Creamos el LOTE
        $lote = Lote::create([
            'insumo_id' => $insumo->id,
            'cantidad_inicial' => $request->cantidad,
            'cantidad_actual' => $request->cantidad,
            'vida_flor_dias' => $insumo->tipo === 'flor' ? ($request->vida_flor ?? $insumo->vida_flor) : null,
            'fecha_vencimiento' => $insumo->tipo === 'flor' 
    ? Carbon::now()->addDays((int) ($request->vida_flor ?? $insumo->vida_flor))->endOfDay() 
    : null,
        ]);

        // 2. Sincronizamos el stock global en la tabla 'insumos'
        $insumo->update([
            'stock_actual' => $insumo->lotes()->sum('cantidad_actual')
        ]);

        return back()->with('success', "¡Entrada registrada! Se sumaron {$request->cantidad} unidades a {$insumo->nombre_insumo}.");
    }
}