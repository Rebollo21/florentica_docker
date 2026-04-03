<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log; // Este es el que te falta para el Log::warning
use App\Models\Venta;
use App\Models\Producto;
use App\Models\Lote;
use Stripe\Stripe;
use Stripe\Charge;
use Stripe\Exception\CardException;
use Illuminate\Support\Str; // Asegúrate de tener esta importación arriba

class CartController extends Controller
{
    /**
     * Muestra el carrito de compras.
     */
    public function index()
    {
        $cart = session()->get('cart', []);
        $total = array_sum(array_map(fn($item) => $item['precio'] * $item['cantidad'], $cart));
        
        return view('cart.index', compact('cart', 'total'));
    }

    /**
     * Añade un ramo al carrito.
     */
   public function add(Request $request, $id)
{
    $producto = Producto::findOrFail($id);
    $cart = session()->get('cart', []);
    
    // Obtenemos la cantidad actual
    $cantidadEnCarrito = isset($cart[$id]['cantidad']) ? (int)$cart[$id]['cantidad'] : 0;
    
    // Límite de Stock real
    $limiteStock = (int)$producto->stock; 

    if (($cantidadEnCarrito + 1) > $limiteStock) {
        return redirect()->back()->with('error', "¡Límite alcanzado! Solo tenemos {$limiteStock} unidades disponibles de este ramo.");
    }

    if(isset($cart[$id])) {
        $cart[$id]['cantidad']++;
    } else {
        // UNIFICAMOS LOS NOMBRES: Usamos los mismos que pide 'procesarVenta'
        $cart[$id] = [
            "id" => $producto->id, // <--- OBLIGATORIO para el loop de venta
            "nombre" => $producto->nombre_ramo,
            "cantidad" => 1,
            "precio" => $producto->precio_venta, // <--- Cambiado de 'price' a 'precio'
            "imagen" => $producto->portada_url
        ];
    }

    session()->put('cart', $cart);
    return redirect()->back()->with('success', "¡Ramo añadido! Revisa tu carrito para finalizar la compra.");
}

    /**
     * Procesa el pago con Stripe y descuenta inventario mediante FIFO.
     */
   // ... (Tus imports se mantienen igual)


public function procesarVenta(Request $request)
{
    $cart = session()->get('cart', []);
    if(empty($cart)) {
        return redirect()->back()->with('error', 'El carrito está vacío.');
    }

    // 1. Validaciones
    $request->validate([
        'nombre_receptor' => 'required|string|max:255',
        'telefono'        => 'required|string|max:20',
        'stripeToken'     => 'required',
        'calle'           => 'required|string',
        'num_ext'         => 'required|string',
        'colonia'         => 'required|string',
        'codigo_postal'   => 'required|string',
        'municipio'       => 'required|string',
    ]);

    $total = array_sum(array_map(fn($item) => $item['precio'] * $item['cantidad'], $cart));

    DB::beginTransaction();

    try {
        // Cargo en Stripe
        \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));

        $charge = \Stripe\Charge::create([
            "amount"      => round($total * 100),
            "currency"    => "mxn",
            "source"      => $request->stripeToken, 
            "description" => "Venta Florentica - Entrega: " . $request->nombre_receptor
        ]);

        // 2. Registrar Cabecera de Venta
        $idVenta = DB::table('ventas')->insertGetId([
            'user_id'     => auth()->id() ?? 1,
            'total'       => $total,
            'pago_id'     => $charge->id,
            'metodo_pago' => 'Tarjeta',
            'estatus'     => 'Pagado',
            'created_at'  => now(),
            'updated_at'  => now(),
        ]);

        // 3. Procesar cada ramo del carrito y generar su logística
        foreach ($cart as $detalles) {

            // Generar el token único para el QR (Ej: FLOR-A1B2-C3D4)
            $qrToken = 'FLOR-' . strtoupper(Str::random(4)) . '-' . strtoupper(Str::random(4));

            // INSERTAR DETALLE Y OBTENER ID
            $detalleId = DB::table('detalle_ventas')->insertGetId([
                'venta_id'        => $idVenta,
                'user_id'        => auth()->id(),
                'producto_id'     => $detalles['id'],
                'cantidad'        => $detalles['cantidad'],
                'precio_unitario' => $detalles['precio'],
                'subtotal'        => $detalles['precio'] * $detalles['cantidad'],
                'nombre_receptor' => $request->nombre_receptor,
                'telefono'        => $request->telefono,
                'calle'           => $request->calle,
                'num_ext'         => $request->num_ext,
                'num_int'         => $request->num_int, 
                'colonia'         => $request->colonia,
                'codigo_postal'   => $request->codigo_postal,
                'municipio'       => $request->municipio,
                'estado'          => $request->estado ?? 'Estado de México',
                'qr_token'        => $qrToken,
                'status_entrega'  => 'preparacion',
                'referencias'     => $request->referencias ?? 'N/A',
                'created_at'      => now(),
                'updated_at'      => now(),
            ]);

            // INSERTAR EN TABLA ENTREGAS (La nueva lógica de logística)
            DB::table('entregas')->insert([
                'detalle_venta_id'   => $detalleId,
                'repartidor_id'      => null, // Se asigna al escanear
                'status'             => 'preparacion',
                'codigo_qr'          => $qrToken,
                'vehiculo_usado'     => null,
                'fecha_salida'       => null,
                'fecha_entrega_real' => null,
                'created_at'         => now(),
                'updated_at'         => now(),
            ]);

            // 4. Lógica FIFO de Insumos (Tu código original)
            $producto = Producto::with('insumos')->find($detalles['id']);
            if ($producto) {
                foreach ($producto->insumos as $insumo) {
                    $cantidadADescontar = $insumo->pivot->cantidad * $detalles['cantidad'];
                    $descontado = $insumo->descontarStock($cantidadADescontar, $idVenta);

                    if (!$descontado) {
                        Log::warning("Bollotech: Stock insuficiente para {$insumo->nombre_insumo} en Venta #$idVenta");
                    }
                }
            }
        }

        DB::commit();
        session()->forget('cart');

return redirect()->route('carrito.seguimiento', ['id' => $idVenta])
                         ->with('success', '¡Tu pedido de Florentica ha sido confirmado!');

    } catch (\Exception $e) {
        DB::rollBack();
        Log::error("Error Fatal en Florentica (Venta/Logística): " . $e->getMessage());
        return redirect()->back()->with('error', 'Error al procesar la orden: ' . $e->getMessage());
    }
}


    // Métodos de utilidad
    public function remove($id)
    {
        $cart = session()->get('cart', []);
        if(isset($cart[$id])) {
            unset($cart[$id]);
            session()->put('cart', $cart);
        }
        return redirect()->back()->with('success', 'Ramo removido del carrito.');
    }

    public function clear()
    {
        session()->forget('cart');
        return redirect()->back()->with('success', 'El carrito se ha vaciado.');
    }


    public function update(Request $request, $id)
{
    $cart = session()->get('cart', []);
    $producto = \App\Models\Producto::find($id);

    if (!$producto) {
        return redirect()->back()->with('error', 'Producto no encontrado');
    }

    // Calculamos el máximo permitido según tus insumos reales
    $maxStock = $producto->calcularStockDisponible();

    if (isset($cart[$id])) {
        if ($request->change == 'increase') {
            // VALIDACIÓN CRÍTICA: No subir más allá del stock disponible
            if ($cart[$id]['cantidad'] < $maxStock) {
                $cart[$id]['cantidad']++;
            } else {
                return redirect()->back()->with('info', "Solo hay {$maxStock} unidades disponibles.");
            }
        } elseif ($request->change == 'decrease' && $cart[$id]['cantidad'] > 1) {
            $cart[$id]['cantidad']--;
        }
        
        session()->put('cart', $cart);
    }

    return redirect()->back()->with('success', 'Carrito actualizado');
}




public function getStatus($id)
{
    $entrega = DB::table('detalle_ventas')
                ->where('venta_id', $id)
                ->select('status_entrega')
                ->first();

    return response()->json([
        'status' => $entrega->status_entrega ?? 'preparacion'
    ]);
}

// Carga la vista inicial
public function mostrarSeguimiento($id)
{
    $pedido = DB::table('ventas')->where('id', $id)->first();

    if (!$pedido) {
        return redirect('/shop')->with('error', 'Pedido no encontrado.');
    }

    // Obtenemos la información de entrega (donde está el status_entrega)
    $entrega = DB::table('detalle_ventas')
                ->where('venta_id', $id)
                ->first();

    return view('cart.seguimiento', compact('pedido', 'entrega'));
}

// API interna para que el progreso avance automáticamente
public function consultarStatus($id)
{
    $entrega = DB::table('detalle_ventas')
                ->where('venta_id', $id)
                ->select('status_entrega')
                ->first();

    return response()->json([
        'status' => $entrega->status_entrega ?? 'preparacion'
    ]);
}
}