<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Item;
use Illuminate\Http\Request;

/**
 * Controlador de packs del catálogo.
 * Los packs son items de tipo 'pack' que incluyen un número determinado de zancudos.
 * Se trabaja siempre sobre el modelo Item filtrando por la relación 'pack'.
 */
class PackController extends Controller
{
    /**
     * Devuelve el listado completo de packs activos con sus datos de zancudos.
     * 
     * @route GET /api/packs
     * @return JsonResponse listado de packs activos
     */
    public function index()
    {
        // Filtramos items que tienen relación con pack y están activos
        $packs = Item::has('pack')
            ->with('pack')
            ->where('activo', true)
            ->get();

        return response()->json($packs);
    }

    /**
     * Devuelve el detalle de un pack específico por su ID.
     * 
     * @route GET /api/packs/{id}
     * @param int $id — ID del item
     * @return JsonResponse datos del pack | 404 si no existe
     */
    public function mostrar($id)
    {
        $pack = Item::has('pack')
            ->with('pack')
            ->find($id);

        if (!$pack) {
            return response()->json(['message' => 'Pack no encontrado'], 404);
        }

        return response()->json($pack);
    }

    /**
     * Busca packs por nombre o descripción.
     * 
     * @route GET /api/packs/buscar?q={termino}
     * @param Request $request — parámetro 'q' con el término de búsqueda
     * @return JsonResponse listado de packs que coinciden con la búsqueda
     */
    public function buscar(Request $request)
    {
        $query = $request->query('q');

        // Buscar en nombre y descripción con LIKE para búsqueda parcial
        $resultados = Item::has('pack')
            ->with('pack')
            ->where(function($q) use ($query) {
                $q->where('nombre', 'LIKE', "%{$query}%")
                  ->orWhere('descripcion', 'LIKE', "%{$query}%");
            })
            ->get();

        return response()->json($resultados);
    }
}