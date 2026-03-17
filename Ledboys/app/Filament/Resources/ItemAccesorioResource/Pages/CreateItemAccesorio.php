<?php

namespace App\Filament\Resources\ItemAccesorioResource\Pages;

use App\Filament\Resources\ItemAccesorioResource;
use App\Models\Item;
use App\Models\ItemAccesorio;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateItemAccesorio extends CreateRecord
{
    protected static string $resource = ItemAccesorioResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        $item = Item::create([
            'nombre'      => $data['nombre_item'],
            'tipo'        => 'accesorio',
            'precio'      => $data['precio_item'],
            'descripcion' => $data['descripcion_item'] ?? null,
            'imagen'      => $data['imagen_item'] ?? null,
            'activo'      => true,
        ]);

        return ItemAccesorio::create([
            'item_id'     => $item->id,
            'stock_total' => $data['stock_total'],
        ]);
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
