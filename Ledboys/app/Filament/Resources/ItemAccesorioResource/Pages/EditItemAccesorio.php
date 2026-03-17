<?php

namespace App\Filament\Resources\ItemAccesorioResource\Pages;

use App\Filament\Resources\ItemAccesorioResource;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;

class EditItemAccesorio extends EditRecord
{
    protected static string $resource = ItemAccesorioResource::class;

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $item = $this->record->item;

        $data['nombre_item']      = $item?->nombre;
        $data['precio_item']      = $item?->precio;
        $data['descripcion_item'] = $item?->descripcion;
        $data['imagen_item']      = $item?->imagen;

        return $data;
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        $record->item->update([
            'nombre'      => $data['nombre_item'],
            'precio'      => $data['precio_item'],
            'descripcion' => $data['descripcion_item'] ?? null,
            'imagen'      => $data['imagen_item'] ?? null,
        ]);

        $record->update([
            'stock_total' => $data['stock_total'],
        ]);

        return $record;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
