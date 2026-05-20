<?php

namespace App\Filament\Resources\ItemTrajeResource\Pages;

use App\Filament\Resources\ItemTrajeResource;
use Filament\Resources\Pages\ListRecords;

/** Página de listado de trajes */
class ListItemTrajes extends ListRecords
{
    protected static string $resource = ItemTrajeResource::class;
}