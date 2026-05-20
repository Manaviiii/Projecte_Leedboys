<?php

namespace App\Filament\Resources\ItemPackResource\Pages;

use App\Filament\Resources\ItemPackResource;
use Filament\Resources\Pages\ListRecords;

/** Página de listado de packs */
class ListItemPacks extends ListRecords
{
    protected static string $resource = ItemPackResource::class;
}