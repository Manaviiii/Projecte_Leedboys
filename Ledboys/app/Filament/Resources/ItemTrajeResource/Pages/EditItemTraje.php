<?php

namespace App\Filament\Resources\ItemTrajeResource\Pages;

use App\Filament\Resources\ItemTrajeResource;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;

class EditItemTraje extends EditRecord
{
    protected static string $resource = ItemTrajeResource::class;

    /**
     * Al cargar el formulario, combinamos los campos de `items` y de `item_trajes`
     * en un único array de estado para que el formulario los muestre correctamente.
     */
    protected function mutateFormDataBeforeFill(array $data): array
    {
        $item = $this->record->item;

        $data['nombre_item']      = $item?->nombre;
        $data['precio_item']      = $item?->precio;
        $data['descripcion_item'] = $item?->descripcion;
        $data['imagen_item']      = $item?->imagen;

        return $data;
    }

    /**
     * Al guardar, actualizamos el Item base y el ItemTraje por separado.
     */
    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        // Actualizar el Item base
        $record->item->update([
            'nombre'      => $data['nombre_item'],
            'precio'      => $data['precio_item'],
            'descripcion' => $data['descripcion_item'] ?? null,
            'imagen'      => $data['imagen_item'] ?? null,
        ]);

        // Actualizar el ItemTraje
        $record->update([
            'tipo_traje'  => $data['tipo_traje'],
            'genero'      => $data['genero'],
            'stock_total' => $data['stock_total'],
        ]);

        return $record;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
