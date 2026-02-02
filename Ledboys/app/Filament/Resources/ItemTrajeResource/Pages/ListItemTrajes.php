<?php

namespace App\Filament\Resources\ItemTrajeResource\Pages;

use App\Filament\Resources\ItemTrajeResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListItemTrajes extends ListRecords
{
    protected static string $resource = ItemTrajeResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
