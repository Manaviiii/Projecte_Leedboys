<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Resources\Json\JsonResource;

class ClienteResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'               => $this->id,
            'nombre'           => $this->nombre,
            'email'            => $this->email,
            'telefono'         => $this->telefono,
            'stripe_customer_id' => $this->stripe_customer_id,
            'eventos_count'    => $this->whenCounted('eventos'),
            'residencias_count'=> $this->whenCounted('residencias'),
            'eventos'          => EventoResource::collection($this->whenLoaded('eventos')),
            'residencias'      => ResidenciaResource::collection($this->whenLoaded('residencias')),
            'created_at'       => $this->created_at?->toDateTimeString(),
        ];
    }
}
