<?php

namespace App\Filament\Resources\ItemTrajeResource\Pages;

use App\Filament\Resources\ItemTrajeResource;
use App\Models\Item;
use App\Models\Foto;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class CreateItemTraje extends CreateRecord
{
    protected static string $resource = ItemTrajeResource::class;

    protected array $fotosPendientes = [];
    protected ?int  $ordenPrincipal  = null;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $this->fotosPendientes = $data['fotos_input'] ?? [];
        $this->ordenPrincipal  = isset($data['foto_principal_orden']) ? (int) $data['foto_principal_orden'] : null;
        unset($data['fotos_input'], $data['foto_principal_orden']);

        $item = Item::create([
            'nombre'      => $data['nombre'],
            'tipo'        => 'traje',
            'precio'      => $data['precio'],
            'descripcion' => $data['descripcion'] ?? null,
            'imagen'      => $data['imagen'] ?? null,
            'activo'      => $data['activo'] ?? true,
        ]);

        $data['item_id'] = $item->id;
        unset($data['nombre'], $data['precio'], $data['descripcion'], $data['imagen'], $data['activo']);

        return $data;
    }

    protected function afterCreate(): void
    {
        foreach ($this->fotosPendientes as $fotoData) {
            if (empty($fotoData['archivo'])) continue;

            $orden       = (int) ($fotoData['orden'] ?? 1);
            $esPrincipal = ($this->ordenPrincipal !== null && $orden === $this->ordenPrincipal);

            // Usar disk public — Filament guarda los archivos en storage/app/public
            $rutaArchivo = Storage::disk('public')->path($fotoData['archivo']);

            if (!file_exists($rutaArchivo)) continue;

            $blob = file_get_contents($rutaArchivo);

            $foto = \Illuminate\Support\Facades\DB::table('fotos')->insertGetId([
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