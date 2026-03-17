<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Resources\Json\JsonResource;

class ItemResource extends JsonResource
{
    public function toArray($request): array
    {
        $base = [
            'id'          => $this->id,
            'nombre'      => $this->nombre,
            'tipo'        => $this->tipo,
            'precio'      => (float) $this->precio,
            'descripcion' => $this->descripcion,
            'imagen'      => $this->imagen ? asset('storage/' . $this->imagen) : null,
            'activo'      => (bool) $this->activo,
            'created_at'  => $this->created_at?->toDateTimeString(),
        ];

        // Añadimos los campos específicos según el tipo
        $detalle = match ($this->tipo) {
            'traje' => $this->whenLoaded('traje', fn() => [
                'tipo_traje'  => $this->traje->tipo_traje,
                'genero'      => $this->traje->genero,
                'stock_total' => $this->traje->stock_total,
            ], []),
            'accesorio' => $this->whenLoaded('accesorio', fn() => [
                'stock_total' => $this->accesorio->stock_total,
            ], []),
            'pack' => $this->whenLoaded('pack', fn() => [
                'numero_zancudos' => $this->pack->numero_zancudos,
            ], []),
            default => [],
        };


        //Que hace esto?
        //Si es MissingValue: Lo convierte en un array vacío [].
        //Si son datos: Se asegura de que sea un array.
        //Returns El resultado final es un único objeto JSON plano que se envía al cliente (Postman, tu Frontend, etc.).
        return array_merge($base, $detalle instanceof \Illuminate\Http\Resources\MissingValue ? [] : (array) $detalle);
    }
}
