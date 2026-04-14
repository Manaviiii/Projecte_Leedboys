<?php
namespace App\Filament\Resources\ResidenciaResource\Pages;
use App\Filament\Resources\ResidenciaResource;
use Filament\Resources\Pages\EditRecord;
class EditResidencia extends EditRecord
{
    protected static string $resource = ResidenciaResource::class;
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
