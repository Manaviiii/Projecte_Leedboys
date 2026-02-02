<?php

namespace App\Filament\Resources\ItemTrajeResource\Pages;

use App\Filament\Resources\ItemTrajeResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditItemTraje extends EditRecord
{
    protected static string $resource = ItemTrajeResource::class;
    

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
