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
        $accesorios = Item::has('accesorio')
            ->with('accesorio')
            ->where('activo', true)
            ->get();

        return response()->json($accesorios->map(function ($item) {
            $data = $item->toArray();
            if (!empty($item->accesorio->imagen)) {
                $data['accesorio']['imagen'] = base64_encode($item->accesorio->imagen);
            }
                return $data;
            }));
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

        $data = $accesorio->toArray();
        if (!empty($accesorio->accesorio->imagen)) {
            $data['accesorio']['imagen'] = base64_encode($accesorio->accesorio->imagen);
        }

        return response()->json($data);
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

        $resultados = Item::has('accesorio')
            ->with('accesorio')
            ->where(function($q) use ($query) {
                $q->where('nombre', 'LIKE', "%{$query}%")
                ->orWhere('descripcion', 'LIKE', "%{$query}%");
            })
            ->get();

        return response()->json($resultados->map(function ($item) {
            $data = $item->toArray();
            if (!empty($item->accesorio->imagen)) {
                $data['accesorio']['imagen'] = base64_encode($item->accesorio->imagen);
            }
            return $data;
        }));
    }
}