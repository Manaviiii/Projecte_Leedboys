<?php

namespace App\Filament\Resources\ItemTrajeResource\Pages;

use App\Filament\Resources\ItemTrajeResource;
use App\Models\Item;
use App\Models\Foto;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Storage;

class EditItemTraje extends EditRecord
{
    protected static string $resource = ItemTrajeResource::class;

    protected array $fotosNuevas    = [];
    protected ?int  $ordenPrincipal = null;

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

        $data['fotos_input'] = $this->record->fotos->map(fn($foto) => [
            'archivo'   => null,
            'foto_id'   => $foto->id,
            'nombre'    => $foto->nombre,
            'orden'     => $foto->orden,
        ])->toArray();

        $fotoPrincipal = $this->record->fotos->firstWhere('principal', true);
        $data['foto_principal_orden'] = $fotoPrincipal?->orden;

        return $data;
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $this->fotosNuevas    = $data['fotos_input'] ?? [];
        $this->ordenPrincipal = isset($data['foto_principal_orden']) ? (int) $data['foto_principal_orden'] : null;

        unset($data['fotos_input'], $data['foto_principal_orden']);
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

        $idsMantenidos = [];

        foreach ($this->fotosNuevas as $fotoData) {
            $orden       = (int) ($fotoData['orden'] ?? 1);
            $esPrincipal = ($this->ordenPrincipal !== null && $orden === $this->ordenPrincipal);

            if (!empty($fotoData['foto_id'])) {
                $foto = Foto::find($fotoData['foto_id']);
                if ($foto) {
                    $updateData = [
                        'nombre'    => $fotoData['nombre'],
                        'orden'     => $orden,
                        'principal' => $esPrincipal,
                    ];

                    if (!empty($fotoData['archivo'])) {
                        // Usar disk public — Filament guarda los archivos en storage/app/public
                        $rutaArchivo = Storage::disk('public')->path($fotoData['archivo']);
                        if (file_exists($rutaArchivo)) {
                            $updateData['imagen'] = file_get_contents($rutaArchivo);
                            Storage::disk('public')->delete($fotoData['archivo']);
                        }
                    }

                    $foto->update($updateData);
                    $idsMantenidos[] = $foto->id;
                }
            } else {
                if (!empty($fotoData['archivo'])) {
                    $rutaArchivo = Storage::disk('public')->path($fotoData['archivo']);
                    if (file_exists($rutaArchivo)) {
                        $blob = file_get_contents($rutaArchivo);

                        $foto = Foto::create([
                            'idTraje'   => $this->record->id,
                            'principal' => $esPrincipal,
                            'nombre'    => $fotoData['nombre'],
                            'orden'     => $orden,
                            'imagen'    => $blob,
                        ]);

                        Storage::disk('public')->delete($fotoData['archivo']);
                        $idsMantenidos[] = $foto->id;
                    }
                }
            }
        }

        Foto::where('idTraje', $this->record->id)
            ->whereNotIn('id', $idsMantenidos)
            ->delete();
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}