<?php

namespace App\Filament\Resources\ItemTrajeResource\Pages;

use App\Filament\Resources\ItemTrajeResource;
use App\Models\Item;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Filament\Resources\Pages\CreateRecord;

class CreateItemTraje extends CreateRecord
{
    protected static string $resource = ItemTrajeResource::class;

    protected array $fotosPendientes = [];
    protected ?int  $ordenPrincipal  = null;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $fotos = $data['fotos_input'] ?? [];

        // Asignar nombre automático (Foto1, Foto2...) y orden según posición
        foreach ($fotos as $index => &$foto) {
            $foto['nombre'] = 'Foto' . ($index + 1);
            $foto['orden']  = $index + 1;
        }
        unset($foto);

        $this->fotosPendientes = $fotos;
        $this->ordenPrincipal  = isset($data['foto_principal_orden']) ? (int) $data['foto_principal_orden'] : null;
        unset($data['fotos_input'], $data['foto_principal_orden']);

        $item = Item::create([
            'nombre'      => $data['nombre'],
            'tipo'        => 'traje',
            'precio'      => $data['precio'],
            'descripcion' => $data['descripcion'] ?? null,
            'activo'      => $data['activo'] ?? true,
        ]);

        $data['item_id'] = $item->id;
        unset($data['nombre'], $data['precio'], $data['descripcion'], $data['activo']);

        return $data;
    }

    protected function afterCreate(): void
    {
        foreach ($this->fotosPendientes as $fotoData) {
            if (empty($fotoData['archivo'])) continue;

            $orden       = (int) $fotoData['orden'];
            $esPrincipal = ($this->ordenPrincipal !== null && $orden === $this->ordenPrincipal);

            $rutaArchivo = Storage::disk('public')->path($fotoData['archivo']);
            if (!file_exists($rutaArchivo)) continue;

            $blob = file_get_contents($rutaArchivo);

            DB::table('fotos')->insert([
                'idTraje'    => $this->record->id,
                'principal'  => $esPrincipal ? 1 : 0,
                'nombre'     => $fotoData['nombre'],
                'orden'      => $orden,
                'imagen'     => $blob,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            Storage::disk('public')->delete($fotoData['archivo']);
        }
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}