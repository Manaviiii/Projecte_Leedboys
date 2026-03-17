<?php

namespace App\Filament\Resources\ItemPackResource\Pages;

use App\Filament\Resources\ItemPackResource;
use App\Models\Item;
use App\Models\ItemPack;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateItemPack extends CreateRecord
{
    protected static string $resource = ItemPackResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        $item = Item::create([
            'nombre'      => $data['nombre_item'],
            'tipo'        => 'pack',
            'precio'      => $data['precio_item'],
            'descripcion' => $data['descripcion_item'] ?? null,
            'imagen'      => $data['imagen_item'] ?? null,
            'activo'      => true,
        ]);

        return ItemPack::create([
            'item_id'          => $item->id,
            'numero_zancudos'  => $data['numero_zancudos'],
        ]);
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
