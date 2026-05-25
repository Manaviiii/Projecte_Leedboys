<?php

namespace App\Filament\Resources\ItemTrajeResource\Pages;

use App\Filament\Resources\ItemTrajeResource;
use App\Models\Foto;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Filament\Resources\Pages\EditRecord;

/**
 * Página de edición de un traje.
 *
 * Flujo:
 * 1. mutateFormDataBeforeFill: precarga datos del item padre y fotos existentes.
 *    El orden se precarga según la posición actual de cada foto.
 * 2. mutateFormDataBeforeSave: reasigna nombre y orden según posición en el Repeater.
 * 3. afterSave: actualiza item padre, procesa fotos nuevas/existentes/eliminadas.
 */
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
            $data['activo']      = $item->activo;
        }

        // Cargar fotos existentes ordenadas por 'orden'
        $data['fotos_input'] = $this->record->fotos
            ->sortBy('orden')
            ->values()
            ->map(fn($foto) => [
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
        $fotos = $data['fotos_input'] ?? [];

        // Reasignar nombre default y orden según posición actual en el Repeater
        foreach ($fotos as $index => &$foto) {
            $posicion = $index + 1;

            if (empty($foto['nombre']) || $foto['nombre'] === 'Foto') {
                $foto['nombre'] = 'Foto' . $posicion;
            }

            $foto['orden'] = $posicion;
        }
        unset($foto);

        $this->fotosNuevas    = $fotos;
        $this->ordenPrincipal = isset($data['foto_principal_orden']) ? (int) $data['foto_principal_orden'] : null;

        unset($data['fotos_input'], $data['foto_principal_orden']);
        unset($data['nombre'], $data['precio'], $data['descripcion'], $data['activo']);

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

        $idsMantenidos = [];

        foreach ($this->fotosNuevas as $fotoData) {
            $orden       = (int) $fotoData['orden'];
            $esPrincipal = ($this->ordenPrincipal !== null && $orden === $this->ordenPrincipal);

            if (!empty($fotoData['foto_id'])) {
                // Foto existente — actualizar
                $foto = Foto::find($fotoData['foto_id']);
                if ($foto) {
                    $updateData = [
                        'nombre'    => $fotoData['nombre'],
                        'orden'     => $orden,
                        'principal' => $esPrincipal ? 1 : 0,
                    ];

                    if (!empty($fotoData['archivo'])) {
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
                // Foto nueva — insertar como BLOB
                if (!empty($fotoData['archivo'])) {
                    $rutaArchivo = Storage::disk('public')->path($fotoData['archivo']);
                    if (file_exists($rutaArchivo)) {
                        $blob = file_get_contents($rutaArchivo);

                        $id = DB::table('fotos')->insertGetId([
                            'idTraje'    => $this->record->id,
                            'principal'  => $esPrincipal ? 1 : 0,
                            'nombre'     => $fotoData['nombre'],
                            'orden'      => $orden,
                            'imagen'     => $blob,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);

                        Storage::disk('public')->delete($fotoData['archivo']);
                        $idsMantenidos[] = $id;
                    }
                }
            }
        }

        // Eliminar fotos que el usuario quitó del Repeater
        Foto::where('idTraje', $this->record->id)
            ->whereNotIn('id', $idsMantenidos)
            ->delete();
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}