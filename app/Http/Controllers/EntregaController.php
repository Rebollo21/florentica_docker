<?php

namespace App\Http\Controllers;

use App\Models\Ventas; // <--- CORRECCIÓN AQUÍ
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;


class EntregaController extends Controller
{
    // 1. PÁGINA: PEDIDOS NUEVOS (POR DESPACHAR)
public function index() 
{
    $user = auth()->user();

    // 1. PEDIDOS EN ESPERA (Lo que está en bodega)
    $nuevos = DB::table('entregas')
        ->join('detalle_ventas', 'entregas.detalle_venta_id', '=', 'detalle_ventas.id')
        ->select(
            'entregas.*', 
            'detalle_ventas.nombre_receptor', 
            'detalle_ventas.colonia', 
            'detalle_ventas.municipio',
            'detalle_ventas.codigo_postal AS cp'
        )
        ->where('entregas.status', 'preparacion')
        ->get(); 

    // 2. PEDIDOS ACTIVOS (Los 3 que ya traes en la Tornado 250)
    $enRuta = DB::table('entregas')
        ->join('detalle_ventas', 'entregas.detalle_venta_id', '=', 'detalle_ventas.id')
        ->select(
            'entregas.*', 
            'detalle_ventas.nombre_receptor', 
            'detalle_ventas.colonia',
            'detalle_ventas.calle',
            'detalle_ventas.num_ext'
        )
        ->where('entregas.status', 'en_ruta')
        ->where('entregas.repartidor_id', $user->id)
        ->get();

    return view('delivery.index', compact('nuevos', 'enRuta'));
}


public function enRuta() 
{
    $user = auth()->user();
    
    // Corregido a detalle_ventas (singular)
    $enRuta = DB::table('entregas')
        ->join('detalle_ventas', 'entregas.detalle_venta_id', '=', 'detalle_ventas.id')
        ->select(
            'entregas.id', 
            'entregas.detalle_venta_id', 
            'entregas.status',
            'detalle_ventas.nombre_receptor', 
            'detalle_ventas.colonia', 
            'detalle_ventas.calle', 
            'detalle_ventas.num_ext'
        )
        ->where('entregas.status', 'en_ruta')
        ->where('entregas.repartidor_id', $user->id)
        ->get();

    return view('delivery.en_ruta', compact('enRuta'));
}

    // 3. PÁGINA: MAPA GPS
    public function mapa($id) 
{
    // Buscamos la entrega uniendo con detalle_ventas (singular)
    $item = DB::table('entregas')
        ->join('detalle_ventas', 'entregas.detalle_venta_id', '=', 'detalle_ventas.id')
        ->select(
            'entregas.id',
            'detalle_ventas.calle', 
            'detalle_ventas.num_ext', 
            'detalle_ventas.colonia', 
            'detalle_ventas.municipio',
            'detalle_ventas.nombre_receptor'
        )
        ->where('entregas.id', $id)
        ->first();
    
    if (!$item) {
        return redirect()->route('delivery.enRuta')->with('error', 'No se encontró el mapa para esta entrega.');
    }

    return view('delivery.mapa', compact('item'));
}

  public function update(Request $request, $id)
{
    $user = auth()->user();
    $nuevoStatus = $request->input('status');
    $entrega = DB::table('entregas')->where('id', $id)->first();

    if (!$entrega) return redirect()->back()->with('error', 'Registro no encontrado.');

    // --- LÓGICA DE DESPACHO (Aquí estaba el fallo) ---
    if ($nuevoStatus == 'en_ruta') {
        
        // 1. Validar que el QR/Token coincida con el de la DB
        if ($request->qr_token_input !== $entrega->codigo_qr) {
            return redirect()->back()->with('error', 'El código QR o Token no coincide con el pedido.');
        }

        // 2. Actualizar a "En Ruta" y asignar al repartidor actual
        DB::table('entregas')->where('id', $id)->update([
            'status' => 'en_ruta',
            'repartidor_id' => $user->id,
            'fecha_salida' => now()
        ]);

        // 3. ¡REDIRECCIÓN CRÍTICA! Mandar a la vista de entregas activas
        return redirect()->route('delivery.enRuta')->with('success', '¡Pedido #'.$id.' validado! Ya puedes ver el mapa.');
    }

    // --- LÓGICA DE FINALIZACIÓN ---
    if ($nuevoStatus == 'entregado') {
        // ... (Tu código de validación de PIN y Foto está perfecto)
        
        // Solo asegúrate de tener el import de Str al inicio del archivo: 
        // use Illuminate\Support\Str;

        if ($request->pin_confirmacion !== $entrega->codigo_qr) {
            return redirect()->back()->with('error', 'PIN de seguridad incorrecto.');
        }

        if ($request->hasFile('foto_evidencia')) {
            $file = $request->file('foto_evidencia');
            $nombreArchivo = 'entrega_' . $id . '_' . now()->format('Ymd_His') . '_' . \Illuminate\Support\Str::random(4) . '.' . $file->getClientOriginalExtension();
            $pathFoto = $file->storeAs('evidencias_entregas', $nombreArchivo, 'public');
        } else {
            return redirect()->back()->with('error', 'La foto de evidencia es obligatoria.');
        }

        DB::table('entregas')->where('id', $id)->update([
            'status' => 'entregado',
            'fecha_entrega_real' => now(),
            'foto_evidencia' => $pathFoto,
        ]);

        return redirect()->route('delivery.enRuta')->with('success', 'Entrega finalizada con éxito.');
    }

    return redirect()->route('delivery.index');
}


}
