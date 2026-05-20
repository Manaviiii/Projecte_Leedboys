<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Item;
use Illuminate\Http\Request;

/**
 * Controlador del catálogo de accesorios.
 * Los accesorios son items de tipo 'accesorio' que tienen una relación con item_accesorios,
 * donde se guarda el stock disponible.
 * Se trabaja siempre sobre el modelo Item filtrando por la relación 'accesorio'.
 */
class AccesorioController extends Controller
{
    /**
     * Devuelve el listado completo de accesorios activos con su stock.
     * 
     * @route GET /api/accesorios
     * @return JsonResponse listado de accesorios activos con sus datos de item_accesorios
     */
    public function index()
    {
        // has('accesorio') filtra solo los items que tienen un registro en item_accesorios
        // with('accesorio') carga la relación para evitar N+1 queries
        $accesorios = Item::has('accesorio')
            ->with('accesorio')
            ->where('activo', true)
            ->get();

        return response()->json($accesorios);
    }

    /**
     * Devuelve el detalle de un accesorio concreto por su ID.
     * 
     * @route GET /api/accesorios/{id}
     * @param int $id — ID del item
     * @return JsonResponse datos del accesorio | 404 si no existe
     */
    public function mostrar($id)
    {
        $accesorio = Item::has('accesorio')
            ->with('accesorio')
            ->find($id);

        if (!$accesorio) {
            return response()->json(['message' => 'Accesorio no encontrado'], 404);
        }

        return response()->json($accesorio);
    }

    /**
     * Busca accesorios por nombre o descripción.
     * 
     * @route GET /api/accesorios/buscar?q={termino}
     * @param Request $request — parámetro 'q' con el término de búsqueda
     * @return JsonResponse listado de accesorios que coinciden con la búsqueda
     */
    public function buscar(Request $request)
    {
        $query = $request->query('q');

        // Búsqueda parcial con LIKE en nombre y descripción
        $resultados = Item::has('accesorio')
            ->with('accesorio')
            ->where(function($q) use ($query) {
                $q->where('nombre', 'LIKE', "%{$query}%")
                  ->orWhere('descripcion', 'LIKE', "%{$query}%");
            })
            ->get();

        return response()->json($resultados);
    }
}