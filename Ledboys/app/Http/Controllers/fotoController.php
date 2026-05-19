<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Foto;
use App\Models\ItemTraje;

class FotoController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | GET /api/fotos
    |
    | Devuelve una foto principal por cada traje (para el catálogo).
    |--------------------------------------------------------------------------
    */
    public function principales()
    {
        $fotos = Foto::where('principal', true)
            ->with('itemTraje.item')
            ->get()
            ->map(fn($foto) => [
                'id'      => $foto->id,
                'idTraje' => $foto->idTraje,
                'nombre'  => $foto->nombre,
                'orden'   => $foto->orden,
                'imagen'  => base64_encode($foto->imagen),
                'traje'   => [
                    'id'         => $foto->itemTraje?->item?->id,  // item_id para consistencia con TrajeController
                    'nombre'     => $foto->itemTraje?->item?->nombre,
                    'precio'     => $foto->itemTraje?->item?->precio,
                    'tipo_traje' => $foto->itemTraje?->tipo_traje,
                    'genero'     => $foto->itemTraje?->genero,
                ],
            ]);

        return response()->json($fotos);
    }

    /*
    |--------------------------------------------------------------------------
    | GET /api/fotos/traje/{id}
    |
    | {id} es el ID de items (el mismo que devuelve TrajeController)
    |--------------------------------------------------------------------------
    */
    public function porTraje($id)
    {
        // Buscamos por item_id, no por el id de item_trajes
        $traje = ItemTraje::with(['item', 'fotos'])
            ->where('item_id', $id)
            ->first();

        if (!$traje) {
            return response()->json(['message' => 'Traje no encontrado'], 404);
        }

        $fotos = $traje->fotos
            ->sortBy('orden')
            ->map(fn($foto) => [
                'id'        => $foto->id,
                'nombre'    => $foto->nombre,
                'orden'     => $foto->orden,
                'principal' => $foto->principal,
                'imagen'    => base64_encode($foto->imagen),
            ])
            ->values();

        return response()->json([
            'traje' => [
                'id'          => $traje->item?->id,  // item_id
                'nombre'      => $traje->item?->nombre,
                'precio'      => $traje->item?->precio,
                'tipo_traje'  => $traje->tipo_traje,
                'genero'      => $traje->genero,
                'stock_total' => $traje->stock_total,
            ],
            'fotos' => $fotos,
        ]);
    }
}