<?php

namespace App\Http\Controllers;
use App\Models\Insumo; // Asegúrate de tener este import arriba
// 1. Asegúrate de que el nombre del modelo coincida con el archivo en app/Models
use App\Models\Producto; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File; // <--- ESTA ES VITAL

class ProductoController extends Controller
{
// app/Http/Controllers/ProductoController.php

// Para el ADMIN (Lista de gestión)
public function index() {
    $productos = Producto::all();
    return view('admin.productos.index', compact('productos'));
}

public function create()
{
    // Necesitamos los insumos para poder armar la receta en el formulario
    $insumos = \App\Models\Insumo::all();
    return view('admin.productos.create', compact('insumos'));
}




public function store(Request $request)
{
    // 1. Quitamos 'precio_venta' de la validación obligatoria
    $request->validate([
        'nombre_ramo' => 'required',
        'categoria'   => 'required',
        'imagenes.*'  => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048'
    ]);

    $producto = new Producto();
    $producto->nombre_ramo = $request->nombre_ramo;
    $producto->descripcion = $request->descripcion;
    $producto->categoria   = $request->categoria;
    
    // 2. Seteamos el precio en 0 inicialmente (se definirá en la receta)
    $producto->precio_venta = 0;
    
    // El ramo nace como '0' (Borrador) hasta que el admin confirme el precio en la receta
    $producto->activo = 0;

    // 3. Lógica de subida múltiple (Tu lógica impecable)
    if ($request->hasFile('imagenes')) {
        $rutasGuardadas = [];

        foreach ($request->file('imagenes') as $file) {
            $nombreImagen = time() . '_' . uniqid() . '_' . $file->getClientOriginalName();
            $file->move(public_path('imagenes'), $nombreImagen);
            $rutasGuardadas[] = 'imagenes/' . $nombreImagen;
        }

        $producto->imagen_url = implode(',', $rutasGuardadas);
    }

    $producto->save();

    // 4. CAMBIO CLAVE: Redirigir a la receta del producto recién creado
    // Usamos el ID del producto que acabamos de guardar ($producto->id)
    return redirect()->route('productos.receta', $producto->id)
        ->with('success', '✨ Diseño creado. Agrega los materiales para definir el precio.');
}

public function shop() {
    // 1. Traemos todo con Eager Loading para optimizar la DB
    $productos = Producto::with(['insumos.lotes' => function($query) {
        $query->where('cantidad_actual', '>', 0); // Solo lotes con stock real
    }])->get();

    // 2. Ordenamos: Primero los que SÍ tienen stock disponible (según tu nueva lógica)
    $productos = $productos->sortByDesc(function($producto) {
        return $producto->calcularStockDisponible() > 0;
    });

    $comments = \App\Models\Comment::where('stars', '>=', 3)
                ->where('approved', 1)
                ->latest()
                ->take(6)
                ->get();

    return view('client.index', compact('productos', 'comments'));
}

    public function show($id)
{
    // Buscamos el producto por su ID o lanzamos error 404 si no existe
    $producto = Producto::where('id', $id)
    ->where('activo', 1) // Solo mostramos productos activos
    ->firstOrFail(); // Obtenemos el primer resultado (debería ser uno solo por ID
    
    // Retornamos una nueva vista que crearemos en la carpeta client
    return view('client.show', compact('producto'));
}

    
    // 1. Mostrar la pantalla para elegir las flores del ramo
// ... tus otros métodos (index, show, create) se mantienen igual
public function editarReceta($id)
{
    // Cargamos el producto con sus insumos y forzamos la carga del último lote
    // Esto asegura que 'precio_compra_lote' y 'precio_venta_lote' viajen a la vista
    $producto = Producto::with(['insumos.ultimoLote'])->findOrFail($id);
    
    // Lógica de Imagen para galería (Mantenemos su fix de la primera imagen)
    if ($producto->imagen_url && str_contains($producto->imagen_url, ',')) {
        $imagenes = explode(',', $producto->imagen_url);
        $producto->primera_imagen = trim($imagenes[0]);
    } else {
        // Si no hay imágenes, usamos una por defecto para no romper el diseño
        $producto->primera_imagen = $producto->imagen_url ?? 'imagenes/default.png'; 
    }

    // Traemos todos los insumos disponibles para el catálogo del select
    // Tip de Arquitecto: También cargamos su último lote aquí por si quieres mostrar precios en el select
    $insumos = Insumo::with('ultimoLote')->get(); 

    return view('admin.productos.receta', compact('producto', 'insumos'));
}
public function guardarReceta(Request $request)
{
    $request->validate([
        'producto_id' => 'required|exists:productos,id',
        'insumo_id' => 'required|exists:insumos,id',
        'cantidad' => 'required|numeric|min:0.1',
    ]);

    $producto = Producto::findOrFail($request->producto_id);

    // ELIMINAMOS attach() Y USAMOS syncWithoutDetaching()
    // Esto vincula el ID del insumo con los datos de la tabla pivote
    $producto->insumos()->syncWithoutDetaching([
        $request->insumo_id => [
            'cantidad' => $request->cantidad,
            'updated_at' => now(),
            'created_at' => now()
        ]
    ]);

    return redirect()->back()->with('success', '🌸 Receta actualizada sin duplicados.');
}

public function eliminarInsumo($productoId, $insumoId)
{
    $producto = Producto::findOrFail($productoId);
    
    // Usamos detach para eliminar la relación en la tabla pivote
    $producto->insumos()->detach($insumoId);

    return redirect()->back()->with('success', '🗑️ Insumo eliminado de la receta.');
}

public function updatePrecio(Request $request, $id)
{
    // 1. Validamos que el precio sea un número real
    $request->validate([
        'precio_venta' => 'required|numeric|min:0'
    ]);

    // 2. Buscamos el producto
    $producto = Producto::findOrFail($id);

    // 3. Actualizamos precio y lo ponemos como ACTIVO (Listo para la venta)
    $producto->update([
        'precio_venta' => $request->precio_venta,
        'activo' => 1 // Ya tiene precio, ya puede salir al mercado
    ]);

    // 4. Redirigimos al catálogo con mensaje de éxito
    return redirect()->route('productos.index')
        ->with('success', '✅ Ramo "' . $producto->nombre_ramo . '" actualizado y publicado con éxito.');
}

public function update(Request $request, $id)
    {
        $producto = Producto::findOrFail($id);

        // Actualización de campos básicos
        $producto->nombre_ramo = $request->nombre_ramo;
        $producto->categoria = $request->categoria;
        $producto->descripcion = $request->descripcion;
        $producto->precio_venta = $request->precio_venta;
        $producto->activo = $request->has('activo') ? 1 : 0;

        // LÓGICA PARA MÚLTIPLES FOTOS
        if ($request->hasFile('imagenes')) {
            // Obtenemos las fotos que ya tenía (si existen)
            $fotosActuales = $producto->imagen_url ? explode(',', $producto->imagen_url) : [];
            
            foreach ($request->file('imagenes') as $file) {
                $nombreImagen = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('imagenes/productos'), $nombreImagen);
                $fotosActuales[] = 'imagenes/productos/' . $nombreImagen;
            }

            // Guardamos como una cadena separada por comas
            $producto->imagen_url = implode(',', $fotosActuales);
        }

        $producto->save();

        return redirect()->back()->with('success', '✅ Producto actualizado con éxito.');
    }

public function destroy($id)
{
    try {
        $producto = Producto::findOrFail($id);
        
        // Al ejecutar delete(), Laravel detecta el SoftDeletes del modelo
        $producto->delete(); 

        return redirect()->back()->with('success', '✅ Diseño movido a la papelera (SoftDelete aplicado).');
    } catch (\Exception $e) {
        return redirect()->back()->with('error', 'Error al eliminar: ' . $e->getMessage());
    }
}


public function eliminarFotoGaleria(Request $request)
{
    try {
        // 1. Extraer datos del Request (enviados por el Fetch de JS)
        $productoId = $request->input('producto_id');
        $indiceAEliminar = $request->input('indice');
        $fotoUrl = $request->input('foto_url');

        // 2. Buscar el producto
        $producto = Producto::findOrFail($productoId);

        // 3. Convertir la cadena de imágenes en Array
        $fotos = $producto->imagen_url ? explode(',', $producto->imagen_url) : [];

        // 4. Validar que el índice exista en el array
        if (isset($fotos[$indiceAEliminar])) {
            
            // A. Borrar el archivo físico del almacenamiento (Storage/Public)
            $rutaFisica = public_path($fotoUrl);
            if (File::exists($rutaFisica)) {
                File::delete($rutaFisica);
            }

            // B. Quitar la foto del array
            unset($fotos[$indiceAEliminar]);

            // C. RE-INDEXAR Y GUARDAR
            // array_values() es clave aquí para que los índices vuelvan a ser 0, 1, 2...
            // Si el array queda vacío, guardamos null
            $producto->imagen_url = count($fotos) > 0 ? implode(',', array_values($fotos)) : null;
            $producto->save();

            return response()->json([
                'success' => true, 
                'message' => 'Foto eliminada correctamente de Florentica.'
            ]);
        }

        return response()->json([
            'success' => false, 
            'message' => 'La foto no fue encontrada en el registro.'
        ], 404);

    } catch (\Exception $e) {
        return response()->json([
            'success' => false, 
            'message' => 'Error de servidor: ' . $e->getMessage()
        ], 500);
    }
}

public function actualizarCantidadReceta(Request $request)
{
    $producto = Producto::findOrFail($request->producto_id);
    
    $producto->insumos()->updateExistingPivot($request->insumo_id, [
        'cantidad' => $request->cantidad
    ]);

    return response()->json(['success' => true]);
}
}


