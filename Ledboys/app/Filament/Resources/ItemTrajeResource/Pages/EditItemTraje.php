<?php

namespace App\Filament\Resources\ItemTrajeResource\Pages;

use App\Filament\Resources\ItemTrajeResource;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;

class EditItemTraje extends EditRecord
{
    protected static string $resource = ItemTrajeResource::class;

    // 1. Rellenar el formulario con datos de las dos tablas al abrir
    protected function mutateFormDataBeforeFill(array $data): array
    {
        // $this->record es el ItemTraje actual
        // Accedemos a su padre ($this->record->item)
        $data['nombre_item']      = $this->record->item->nombre;
        $data['precio_item']      = $this->record->item->precio;
        $data['descripcion_item'] = $this->record->item->descripcion;
        $data['imagen_item']      = $this->record->item->imagen;
        
        return $data;
    }

    // 2. Guardar en las dos tablas al dar "Guardar"
    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        // Actualizamos el Padre (Item)
        $record->item->update([
            'nombre'      => $data['nombre_item'],
            'precio'      => $data['precio_item'],
            'descripcion' => $data['descripcion_item'],
            'imagen'      => $data['imagen_item'] ?? $record->item->imagen,
        ]);

        // Actualizamos el Hijo (ItemTraje) - $record es el ItemTraje
        $record->update([
            'tipo_traje'  => $data['tipo_traje'],
            'genero'      => $data['genero'],
            'stock_total' => $data['stock_total'],
        ]);

        return $record;
    }
}