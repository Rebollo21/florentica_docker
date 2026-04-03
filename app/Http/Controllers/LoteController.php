<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use App\Models\Insumo;
use App\Models\Lote;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Merma;
use Illuminate\Support\Facades\DB;

class LoteController extends Controller
{
    public function create()
    {
        $insumos = Insumo::orderBy('nombre_insumo', 'asc')->get();
        return view('admin.lotes.create', compact('insumos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'insumo_id'         => 'required|exists:insumos,id',
            'cantidad_inicial'  => 'required|integer|min:1',
            'costo_unitario'    => 'required|numeric|min:0',
            'precio_venta_lote' => 'required|numeric|min:0',
            'vida_flor_dias'    => 'nullable|integer|min:1',
        ]);

        $insumo = Insumo::findOrFail($request->insumo_id);
        $fechaVencimiento = null; 
        
        if ($insumo->tipo === 'flor' && $request->vida_flor_dias) {
            $fechaVencimiento = Carbon::now()->addDays((int)$request->vida_flor_dias);
        }

        Lote::create([
            'insumo_id'         => $request->insumo_id,
            'cantidad_inicial'  => $request->cantidad_inicial,
            'cantidad_actual'   => $request->cantidad_inicial, 
            'costo_unitario'    => $request->costo_unitario,
            'precio_venta_lote' => $request->precio_venta_lote,
            'vida_flor_dias'    => $request->vida_flor_dias,
            'fecha_vencimiento' => $fechaVencimiento,
            'fecha_llegada'     => now(),
        ]);

        return redirect()->route('admin.dashboard')->with('success', 'Lote registrado con éxito.');    
    }

   public function sugerirPrecioIA(Request $request)
{
    $insumoId = $request->query('insumo_id');
    $costo = (float) $request->query('costo'); // Aseguramos que sea número
    
    $insumo = Insumo::find($insumoId);
    $nombre = $insumo ? $insumo->nombre_insumo : 'Producto';

    // --- CONFIGURACIÓN DE RESPALDO (MANUAL) ---
    // Si la IA falla, sugerimos un margen del 30% (puedes cambiarlo aquí)
    $margenManual = 1.50; 
    $precioSugeridoManual = number_format($costo * $margenManual, 2, '.', '');

    $apiKey = 'AIzaSyAIfNRwOuxnz9Zu1bfequhnduxLvJRabT0';
    $prompt = "Eres experto en ventas de floristería. Un producto ({$nombre}) cuesta {$costo} pesos. Sugiere un precio de venta óptimo considerando margen de ganancia. Responde SOLO el número.";

    try {
        $response = \Illuminate\Support\Facades\Http::withOptions([
            'verify' => false, 
        ])->timeout(5)->post("https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent?key={$apiKey}", [
            'contents' => [['parts' => [['text' => $prompt]]]]
        ]);

        if ($response->successful()) {
            $data = $response->json();
            $precioIA = $data['candidates'][0]['content']['parts'][0]['text'] ?? null;
            
            // Limpiamos la respuesta de la IA (solo números y puntos)
            $precioLimpio = preg_replace('/[^0-9.]/', '', $precioIA);
            
            if (!empty($precioLimpio)) {
                return response()->json([
                    'precio' => number_format((float)$precioLimpio, 2, '.', ''),
                    'metodo' => 'Inteligencia Artificial'
                ]);
            }
        }

        // --- SI LA IA NO RESPONDE BIEN, SE ACTIVA EL PLAN B ---
        return response()->json([
            'precio' => $precioSugeridoManual,
            'metodo' => 'Cálculo Manual (Respaldo)',
            'nota' => 'La IA no está disponible en este momento.'
        ]);

    } catch (\Exception $e) {
        // --- SI HAY ERROR DE CONEXIÓN (INFINITYFREE), SE ACTIVA EL PLAN B ---
        return response()->json([
            'precio' => $precioSugeridoManual,
            'metodo' => 'Cálculo Manual (Respaldo)',
            'error_log' => 'Error de conexión: Servidor de pruebas limitado.'
        ]);
    }
}
    
    
    public function destroy($id)
{
    $lote = \App\Models\Lote::findOrFail($id);
    
    // Al tener SoftDeletes en el modelo, esto NO borra la fila, 
    // solo llena el campo 'deleted_at'.
    $lote->delete(); 

    return redirect()->back()->with('success', 'Lote #'.$id.' enviado a la papelera.');
}public function restore($id)
{
    // Buscamos el lote incluso si está marcado como eliminado
    $lote = \App\Models\Lote::withTrashed()->findOrFail($id);
    
    // Restauramos el registro (pone deleted_at en NULL)
    $lote->restore();

    return redirect()->back()->with('success', '¡El lote #' . $id . ' ha sido restaurado!');
}
    
    public function update(Request $request, $id)
{
    // Buscamos el lote (incluyendo los archivados por si acaso)
    $lote = \App\Models\Lote::withTrashed()->findOrFail($id);

    // Actualizamos los datos
    $lote->update([
        'cantidad_actual'   => $request->cantidad_actual,
        'precio_venta_lote' => $request->precio_venta_lote,
        'fecha_vencimiento' => $request->fecha_vencimiento,
    ]);

    // Regresamos a la tabla con un mensaje de éxito
    return redirect()->back()->with('success', '¡Lote #' . $id . ' actualizado con éxito!');
}


public function registrarMerma(Request $request, $id)
    {
        $request->validate([
            'cantidad_merma' => 'required|integer|min:1',
            'motivo' => 'nullable|string|max:255'
        ]);

        $lote = Lote::findOrFail($id);

        if ($request->cantidad_merma > $lote->stock_actual) {
            return back()->with('error', 'No puedes mermar más de lo que hay en stock.');
        }

        DB::transaction(function () use ($lote, $request) {
            // 1. Creamos el registro de la pérdida
            Merma::create([
                'lote_id' => $lote->id,
                'cantidad' => $request->cantidad_merma,
                'costo_perdido' => $request->cantidad_merma * $lote->precio_compra,
                'motivo' => $request->motivo ?? 'Marchitamiento / Caducidad'
            ]);

            // 2. Bajamos el stock del lote
            $lote->decrement('stock_actual', $request->cantidad_merma);
        });

        return back()->with('success', 'Inventario actualizado: Merma registrada correctamente.');
    }

}