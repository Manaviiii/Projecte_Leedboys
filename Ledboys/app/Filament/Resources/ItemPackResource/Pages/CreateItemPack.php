<?php

namespace App\Filament\Resources\ItemPackResource\Pages;

use App\Filament\Resources\ItemPackResource;
use App\Models\Item;
use Filament\Resources\Pages\CreateRecord;

/**
 * Página de creación de un pack.
 * El numero_zancudos se fija siempre a 2 — no se muestra en el formulario.
 */
class CreateItemPack extends CreateRecord
{
    protected static string $resource = ItemPackResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $item = Item::create([
            'nombre'      => $data['nombre'],
            'tipo'        => 'pack',
            'precio'      => $data['precio'],
            'descripcion' => $data['descripcion'] ?? null,
            'imagen'      => $data['imagen'] ?? null,
            'activo'      => $data['activo'] ?? true,
        ]);

        $data['item_id']          = $item->id;
        $data['numero_zancudos']  = 2; // fijo siempre a 2

        unset($data['nombre'], $data['precio'], $data['descripcion'], $data['imagen'], $data['activo']);

        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}