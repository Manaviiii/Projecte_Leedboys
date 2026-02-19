<?php

namespace App\Filament\Resources\ItemTrajeResource\Pages;

use App\Filament\Resources\ItemTrajeResource;
use Filament\Resources\Pages\CreateRecord;
use App\Models\Item;
use Illuminate\Database\Eloquent\Model;

class CreateItemTraje extends CreateRecord
{
    protected static string $resource = ItemTrajeResource::class;

    // Esto se ejecuta cuando le das al botón "Crear"
    protected function handleRecordCreation(array $data): Model
    {
        // 1. Creamos primero el Padre (Item)
        $item = Item::create([
            'nombre'      => $data['nombre_item'],
            'precio'      => $data['precio_item'],
            'descripcion' => $data['descripcion_item'] ?? null,
            'imagen'      => $data['imagen_item'] ?? null, // Si subiste imagen
            'tipo'        => 'traje', // Forzamos el tipo
            'activo'      => true,
        ]);

        // 2. Creamos el Hijo (ItemTraje) vinculado al padre
        // Usamos la relación o static::getModel()::create
        $traje = static::getModel()::create([
            'item_id'     => $item->id,
            'tipo_traje'  => $data['tipo_traje'],
            'genero'      => $data['genero'],
            'stock_total' => $data['stock_total'],
        ]);

        return $traje;
    }
}