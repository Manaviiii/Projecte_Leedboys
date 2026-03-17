<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Resources\Json\JsonResource;

class EventoResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'           => $this->id,
            'fecha'        => $this->fecha?->toDateString(),
            'estado'       => $this->estado,
            'total_precio' => (float) $this->total_precio,
            'stripe_payment_intent_id' => $this->stripe_payment_intent_id,
            'cliente'      => new ClienteResource($this->whenLoaded('cliente')),
            'items'        => $this->whenLoaded('items', function () {
                return $this->items->map(fn($item) => [
                    'id'              => $item->id,
                    'nombre'          => $item->nombre,
                    'tipo'            => $item->tipo,
                    'cantidad'        => (int)   $item->pivot->cantidad,
                    'precio_unitario' => (float) $item->pivot->precio_unitario,
                    'subtotal'        => round($item->pivot->cantidad * $item->pivot->precio_unitario, 2),
                ]);
            }),
            'pagos'        => PagoResource::collection($this->whenLoaded('pagos')),
            'created_at'   => $this->created_at?->toDateTimeString(),
        ];
    }
}
