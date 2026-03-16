<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item; // Importante para poder usar el modelo Item

class TrajeController extends Controller
{
    /**
     * Obtiene todos los items que tienen relación con un traje
     */
    public function index()
    {
        $trajes = Item::has('traje')->with('traje')->get();
        return response()->json($trajes);
    }

    public function mostrarTraje($id)
    {
        // Buscamos el item por su ID, incluyendo su relación 'traje'
        $traje = Item::with('traje')->find($id);

        // Si no existe el traje, devolvemos un error 404
        if (!$traje) {
            return response()->json(['message' => 'Traje no encontrado'], 404);
        }

        return response()->json($traje);
    }

    public function filtrarPorGenero($genero) {
        $trajes = Item::whereHas('traje', function($q) use ($genero) {
            $q->where('genero', $genero);
        })->with('traje')->get();
        return response()->json($trajes);
    }

    public function buscar(Request $request) {
        $termino = $request->query('q');
        $resultados = Item::has('traje')
            ->where('nombre', 'LIKE', "%{$termino}%")
            ->with('traje')
            ->get();
        return response()->json($resultados);
    }

    
}
