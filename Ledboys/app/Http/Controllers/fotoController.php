<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Foto;
use App\Models\ItemTraje;

/**
 * Controlador de fotos de trajes.
 * Gestiona la obtención de imágenes almacenadas en la base de datos como BLOB.
 * Las imágenes se devuelven codificadas en base64 para que el frontend pueda mostrarlas directamente.
 */
class FotoController extends Controller
{
    /**
     * Devuelve una foto principal por cada traje, para mostrar en el catálogo.
     * 
     * Solo devuelve las fotos marcadas como 'principal = true'.
     * Cada foto incluye los datos básicos del traje al que pertenece.
     * El ID del traje que se devuelve es el item_id, que es el mismo
     * que usa el TrajeController, para que el frontend sea consistente.
     * 
     * @route GET /api/fotos
     * @return JsonResponse listado de fotos principales con datos del traje
     */
    public function principales()
    {
        $fotos = Foto::where('principal', true)
            ->with('itemTraje.item') // cargamos el item_traje y su item padre en una sola consulta
            ->get()
            ->map(fn($foto) => [
                'id'      => $foto->id,
                'idTraje' => $foto->idTraje,
                'nombre'  => $foto->nombre,
                'orden'   => $foto->orden,
                'imagen'  => base64_encode($foto->imagen), // convertir BLOB a base64 para el frontend
                'traje'   => [
                    'id'         => $foto->itemTraje?->item?->id,  // usamos item_id para consistencia con TrajeController
                    'nombre'     => $foto->itemTraje?->item?->nombre,
                    'precio'     => $foto->itemTraje?->item?->precio,
                    'tipo_traje' => $foto->itemTraje?->tipo_traje,
                    'genero'     => $foto->itemTraje?->genero,
                ],
            ]);

        return response()->json($fotos);
    }

    /**
     * Devuelve todas las fotos de un traje concreto, ordenadas por el campo 'orden'.
     * 
     * Recibe el ID del item (el mismo que devuelve TrajeController),
     * no el ID interno de item_trajes, para que el frontend use siempre el mismo identificador.
     * 
     * @route GET /api/fotos/traje/{id}
     * @param int $id — ID del item (tabla items)
     * @return JsonResponse datos del traje + listado de fotos ordenadas | 404 si no existe
     */
    public function porTraje($id)
    {
        // Buscamos el item_traje por item_id, no por su propio id autoincremental
        $traje = ItemTraje::with(['item', 'fotos'])
            ->where('item_id', $id)
            ->first();

        if (!$traje) {
            return response()->json(['message' => 'Traje no encontrado'], 404);
        }

        // Ordenar las fotos por el campo 'orden' y convertir cada imagen a base64
        $fotos = $traje->fotos
            ->sortBy('orden')
            ->map(fn($foto) => [
                'id'        => $foto->id,
                'nombre'    => $foto->nombre,
                'orden'     => $foto->orden,
                'principal' => $foto->principal,
                'imagen'    => base64_encode($foto->imagen),
            ])
            ->values(); // reindexar el array tras el sortBy

        return response()->json([
            'traje' => [
                'id'          => $traje->item?->id,
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