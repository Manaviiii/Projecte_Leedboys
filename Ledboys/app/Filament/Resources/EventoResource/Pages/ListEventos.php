<?php

namespace App\Filament\Resources\EventoResource\Pages;

use App\Filament\Resources\EventoResource;
use Filament\Resources\Pages\ListRecords;

/** Página de listado de eventos — solo lectura */
class ListEventos extends ListRecords
{
    protected static string $resource = EventoResource::class;
}