<?php

namespace App\Filament\Resources\ItemAccesorioResource\Pages;

use App\Filament\Resources\ItemAccesorioResource;
use Filament\Resources\Pages\ListRecords;

/** Página de listado de accesorios */
class ListItemAccesorios extends ListRecords
{
    protected static string $resource = ItemAccesorioResource::class;
}