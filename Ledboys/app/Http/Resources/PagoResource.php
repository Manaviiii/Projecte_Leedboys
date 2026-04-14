<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Resources\Json\JsonResource;

class PagoResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'                       => $this->id,
            'amount'                   => (float) $this->amount,
            'estado'                   => $this->estado,
            'stripe_payment_intent_id' => $this->stripe_payment_intent_id,
            'detalles_items'           => $this->detalles_items,
            'evento'                   => new EventoResource($this->whenLoaded('evento')),
            'residencia'               => new ResidenciaResource($this->whenLoaded('residencia')),
            'created_at'               => $this->created_at?->toDateTimeString(),
        ];
    }
}
