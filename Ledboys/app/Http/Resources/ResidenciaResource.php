<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Resources\Json\JsonResource;

class ResidenciaResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'                     => $this->id,
            'fecha_inicio'           => $this->fecha_inicio?->toDateString(),
            'fecha_fin'              => $this->fecha_fin?->toDateString(),
            'dia_semana'             => $this->dia_semana,
            'precio'                 => (float) $this->precio,
            'estado'                 => $this->estado,
            'stripe_subscription_id' => $this->stripe_subscription_id,
            'cliente'                => new ClienteResource($this->whenLoaded('cliente')),
            'pagos'                  => PagoResource::collection($this->whenLoaded('pagos')),
            'created_at'             => $this->created_at?->toDateTimeString(),
        ];
    }
}
