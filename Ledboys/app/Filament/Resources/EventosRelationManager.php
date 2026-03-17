<?php

namespace App\Filament\Resources\ClienteResource\RelationManagers;

use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;

class EventosRelationManager extends RelationManager
{
    protected static string $relationship = 'eventos';
    protected static ?string $recordTitleAttribute = 'fecha';
    protected static ?string $title = 'Eventos';

    public static function form(Form $form): Form
    {
        return $form->schema([
            DatePicker::make('fecha')->required(),
            Select::make('estado')
                ->options([
                    'borrador'  => 'Borrador',
                    'reservado' => 'Reservado',
                    'pagado'    => 'Pagado',
                ])->default('borrador'),
            TextInput::make('total_precio')
                ->numeric()
                ->prefix('€')
                ->default(0),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('fecha')->date('d/m/Y')->sortable(),
                Tables\Columns\BadgeColumn::make('estado')
                    ->colors([
                        'secondary' => 'borrador',
                        'warning'   => 'reservado',
                        'success'   => 'pagado',
                    ]),
                Tables\Columns\TextColumn::make('total_precio')->money('eur'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]);
    }
}
