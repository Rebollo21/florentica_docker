<?php

namespace App\Http\Controllers;

use App\Models\Insumo;
use Illuminate\Http\Request;

class InsumoController extends Controller
{
    
    
    public function index(Request $request)
{
    $query = Insumo::query();

    // 🚩 ESTA ES LA CLAVE: Incluir los eliminados en la consulta
    $query->withTrashed();

    // Si tienes buscador, mantén tu lógica así:
    if ($request->has('search_insumo')) {
        $search = $request->search_insumo;
        $query->where('nombre_insumo', 'LIKE', "%{$search}%");
    }

    $insumos = $query->get();

    return view('admin.dashboard', compact('insumos'));
}
    
    public function create()
    {
        $insumos = Insumo::all(); 
        return view('admin.insumos.create', compact('insumos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre_insumo' => 'required|string|max:100|unique:insumos,nombre_insumo',
            'tipo'          => 'required|in:flor,relleno,empaque', // Ajustado a tus categorías
            'unidad_medida' => 'required|string',
        ], [
            'nombre_insumo.unique' => '⚠️ Este producto ya existe en el catálogo, Chief!',
        ]);

        $insumo = Insumo::create([
            'nombre_insumo' => trim($request->nombre_insumo),
            'tipo'          => $request->tipo,
            'unidad_medida' => $request->unidad_medida,
        ]);

        return redirect()->route('admin.dashboard')
            ->with('success', '🌸 ¡' . $insumo->nombre_insumo . ' registrado con éxito!');
    }

// 🚀 NUEVA: Función para Actualizar
    public function update(Request $request, $id)
    {
        $insumo = Insumo::findOrFail($id);

        $request->validate([
            'nombre_insumo' => 'required|string|max:100|unique:insumos,nombre_insumo,' . $id,
            // 🚩 IMPORTANTE: Aquí usamos los valores que tu DB acepta (materia_prima, accesorio)
            'tipo'          => 'required|in:flor,materia_prima,accesorio', 
            'unidad_medida' => 'required|string',
        ]);

        $insumo->update([
            'nombre_insumo' => trim($request->nombre_insumo),
            'tipo'          => $request->tipo,
            'unidad_medida' => $request->unidad_medida,
        ]);

        return redirect()->back()->with('success', '✅ Insumo actualizado correctamente.');
    }

   // Añade estas funciones a tu InsumoController

public function destroy($id)
{
    $insumo = Insumo::findOrFail($id);
    $insumo->delete(); // Esto lo marcará como eliminado (deleted_at)

    return redirect()->back()->with('success', '🌸 Insumo desactivado correctamente.');
}

public function restore($id)
{
    // Buscamos solo entre los eliminados y lo restauramos
    $insumo = Insumo::onlyTrashed()->findOrFail($id);
    $insumo->restore();

    return redirect()->back()->with('success', '✨ ¡Insumo restaurado al inventario!');
}
}