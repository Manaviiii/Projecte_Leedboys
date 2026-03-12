<?php

namespace App\Filament\Resources\EventoResource\Pages;

use App\Filament\Resources\EventoResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditEvento extends EditRecord
{
    protected static string $resource = EventoResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
