<?php

namespace App\Filament\Resources\ItemAccesorioResource\Pages;

use App\Filament\Resources\ItemAccesorioResource;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class EditItemAccesorio extends EditRecord
{
    protected static string $resource = ItemAccesorioResource::class;

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $item = $this->record->item;

        if ($item) {
            $data['nombre']      = $item->nombre;
            $data['precio']      = $item->precio;
            $data['descripcion'] = $item->descripcion;
            $data['activo']      = $item->activo;
        }

        return $data;
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        return $data;
    }

    protected function afterSave(): void
    {
        $data = $this->form->getState();

        $this->record->item->update([
            'nombre'      => $data['nombre'],
            'precio'      => $data['precio'],
            'descripcion' => $data['descripcion'] ?? null,
            'activo'      => $data['activo'] ?? true,
        ]);

        if (!empty($data['foto_archivo'])) {
            $rutaArchivo = Storage::disk('public')->path($data['foto_archivo']);

            if (file_exists($rutaArchivo)) {
                $blob = file_get_contents($rutaArchivo);

                DB::table('item_accesorios')
                    ->where('item_id', $this->record->item_id)
                    ->update(['imagen' => $blob]);

                Storage::disk('public')->delete($data['foto_archivo']);
            }
        }
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}