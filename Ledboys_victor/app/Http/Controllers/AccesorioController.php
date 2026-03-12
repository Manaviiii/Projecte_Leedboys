<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Item;
use Illuminate\Http\Request;

class AccesorioController extends Controller
{
    /**
     * Listado completo de accesorios
     */
    public function index()
    {
        // Solo items que tienen la relación 'accesorio'
        $accesorios = Item::has('accesorio')
            ->with('accesorio')
            ->where('activo', true)
            ->get();

        return response()->json($accesorios);
    }

    /**
     * Detalle de un accesorio específico
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
     * Buscador de accesorios
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

        return response()->json($resultados);
    }
}