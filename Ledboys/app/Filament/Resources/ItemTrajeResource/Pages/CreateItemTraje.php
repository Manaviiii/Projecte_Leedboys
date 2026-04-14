<?php

namespace App\Filament\Resources\ItemTrajeResource\Pages;

use App\Filament\Resources\ItemTrajeResource;
use App\Models\Item;
use App\Models\ItemTraje;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateItemTraje extends CreateRecord
{
    protected static string $resource = ItemTrajeResource::class;

    /**
     * Interceptamos la creación para guardar primero en `items`
     * y luego crear el `item_traje` apuntando al item recién creado.
     */
    protected function handleRecordCreation(array $data): Model
    {
        // 1. Crear el Item base
        $item = Item::create([
            'nombre'      => $data['nombre_item'],
            'tipo'        => 'traje',
            'precio'      => $data['precio_item'],
            'descripcion' => $data['descripcion_item'] ?? null,
            'imagen'      => $data['imagen_item'] ?? null,
            'activo'      => true,
        ]);

        // 2. Crear el ItemTraje vinculado
        return ItemTraje::create([
            'item_id'     => $item->id,
            'tipo_traje'  => $data['tipo_traje'],
            'genero'      => $data['genero'],
            'stock_total' => $data['stock_total'],
        ]);
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
