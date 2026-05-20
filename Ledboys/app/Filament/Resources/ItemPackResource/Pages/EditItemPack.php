<?php

namespace App\Filament\Resources\ItemPackResource\Pages;

use App\Filament\Resources\ItemPackResource;
use Filament\Resources\Pages\EditRecord;

/**
 * Página de edición de un pack.
 *
 * Flujo:
 * 1. mutateFormDataBeforeFill: precarga los datos del item padre.
 * 2. mutateFormDataBeforeSave: limpia campos extra antes de guardar en item_packs.
 * 3. afterSave: actualiza el item padre con los datos generales.
 */
class EditItemPack extends EditRecord
{
    protected static string $resource = ItemPackResource::class;

    /**
     * Precarga los datos del item padre al abrir el formulario de edición.
     * Sin esto los campos nombre, precio, etc. aparecerían vacíos.
     */
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

    /**
     * Limpia los campos del item padre antes de guardar en item_packs.
     */
    protected function mutateFormDataBeforeSave(array $data): array
    {
        unset($data['nombre'], $data['precio'], $data['descripcion'], $data['imagen'], $data['activo']);
        return $data;
    }

    /**
     * Tras guardar, actualiza el item padre con los datos generales del formulario.
     */
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