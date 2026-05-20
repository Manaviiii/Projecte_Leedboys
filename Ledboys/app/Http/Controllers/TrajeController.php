<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;

/**
 * Controlador del catálogo de trajes.
 * Los trajes son items de tipo 'traje' que tienen una relación con item_trajes,
 * donde se guardan los detalles específicos como tipo, género y stock.
 * Se trabaja siempre sobre el modelo Item filtrando por la relación 'traje'.
 */
class TrajeController extends Controller
{
    /**
     * Devuelve el listado completo de trajes con sus detalles.
     * 
     * @route GET /api/trajes
     * @return JsonResponse listado de todos los trajes con sus datos de item_trajes
     */
    public function index()
    {
        // has('traje') filtra solo los items que tienen un registro en item_trajes
        // with('traje') carga la relación para evitar N+1 queries
        $trajes = Item::has('traje')->with('traje')->get();
        return response()->json($trajes);
    }

    /**
     * Devuelve el detalle de un traje concreto por su ID.
     * 
     * @route GET /api/trajes/{id}
     * @param int $id — ID del item
     * @return JsonResponse datos del traje | 404 si no existe
     */
    public function mostrarTraje($id)
    {
        $traje = Item::with('traje')->find($id);

        if (!$traje) {
            return response()->json(['message' => 'Traje no encontrado'], 404);
        }

        return response()->json($traje);
    }

    /**
     * Filtra los trajes por género (chico, chica, unisex).
     * 
     * @route GET /api/trajes/filtrar/{genero}
     * @param string $genero — 'chico', 'chica' o 'unisex'
     * @return JsonResponse listado de trajes del género indicado
     */
    public function filtrarPorGenero($genero)
    {
        // whereHas filtra los items cuyo item_traje tenga el género indicado
        $trajes = Item::whereHas('traje', function($q) use ($genero) {
            $q->where('genero', $genero);
        })->with('traje')->get();

        return response()->json($trajes);
    }

    /**
     * Busca trajes por nombre.
     * 
     * @route GET /api/trajes/buscar?q={termino}
     * @param Request $request — parámetro 'q' con el término de búsqueda
     * @return JsonResponse listado de trajes que coinciden con el nombre
     */
    public function buscar(Request $request)
    {
        $termino = $request->query('q');

        // Búsqueda parcial con LIKE — encuentra coincidencias aunque no sea el nombre exacto
        $resultados = Item::has('traje')
            ->where('nombre', 'LIKE', "%{$termino}%")
            ->with('traje')
            ->get();

        return response()->json($resultados);
    }
}