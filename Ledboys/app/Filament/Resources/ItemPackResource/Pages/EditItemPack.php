<?php

namespace App\Filament\Resources\ItemPackResource\Pages;

use App\Filament\Resources\ItemPackResource;
use Filament\Resources\Pages\EditRecord;

/**
 * Página de edición de un pack.
 * El numero_zancudos no se toca al editar.
 */
class EditItemPack extends EditRecord
{
    protected static string $resource = ItemPackResource::class;

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $item = $this->record->item;

        if ($item) {
            $data['nombre']      = $item->nombre;
            $data['precio']      = $item->precio;
            $data['descripcion'] = $item->descripcion;
            $data['imagen']      = $item->imagen;
            $data['activo']      = $item->activo;
        }

        return $data;
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        unset($data['nombre'], $data['precio'], $data['descripcion'], $data['imagen'], $data['activo']);
        return $data;
    }

    protected function afterSave(): void
    {
        $data = $this->form->getState();

        $this->record->item->update([
            'nombre'      => $data['nombre'],
            'precio'      => $data['precio'],
            'descripcion' => $data['descripcion'] ?? null,
            'imagen'      => $data['imagen'] ?? null,
            'activo'      => $data['activo'] ?? true,
        ]);
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}