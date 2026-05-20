<?php

namespace App\Filament\Resources\ItemAccesorioResource\Pages;

use App\Filament\Resources\ItemAccesorioResource;
use App\Models\Item;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Storage;

class CreateItemAccesorio extends CreateRecord
{
    protected static string $resource = ItemAccesorioResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $item = Item::create([
            'nombre'      => $data['nombre'],
            'tipo'        => 'accesorio',
            'precio'      => $data['precio'],
            'descripcion' => $data['descripcion'] ?? null,
            'activo'      => $data['activo'] ?? true,
        ]);

        $data['item_id'] = $item->id;

        if (!empty($data['foto_archivo'])) {
            $rutaArchivo = Storage::disk('public')->path($data['foto_archivo']);
            if (file_exists($rutaArchivo)) {
                $data['imagen'] = file_get_contents($rutaArchivo);
                Storage::disk('public')->delete($data['foto_archivo']);
            }
        }

        unset($data['nombre'], $data['precio'], $data['descripcion'], $data['activo'], $data['foto_archivo']);

        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}