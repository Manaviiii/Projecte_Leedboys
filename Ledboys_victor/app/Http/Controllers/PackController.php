<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Item;
use Illuminate\Http\Request;

class PackController extends Controller
{
    /**
     * Listado de todos los packs (incluyendo datos de zancudos)
     */
    public function index()
    {
        $packs = Item::has('pack')
            ->with('pack')
            ->where('activo', true)
            ->get();

        return response()->json($packs);
    }

    /**
     * Detalle de un pack específico por ID
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
     * Buscador de packs por nombre o descripción
     */
    public function buscar(Request $request)
    {
        $query = $request->query('q');

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