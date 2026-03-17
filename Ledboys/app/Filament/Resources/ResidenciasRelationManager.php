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

class ResidenciasRelationManager extends RelationManager
{
    protected static string $relationship = 'residencias';
    protected static ?string $recordTitleAttribute = 'fecha_inicio';
    protected static ?string $title = 'Residencias';

    public static function form(Form $form): Form
    {
        return $form->schema([
            DatePicker::make('fecha_inicio')->required(),
            DatePicker::make('fecha_fin'),
            Select::make('dia_semana')
                ->options([
                    'lunes'     => 'Lunes',
                    'martes'    => 'Martes',
                    'miercoles' => 'Miércoles',
                    'jueves'    => 'Jueves',
                    'viernes'   => 'Viernes',
                    'sabado'    => 'Sábado',
                    'domingo'   => 'Domingo',
                ]),
            TextInput::make('precio')->numeric()->prefix('€'),
            Select::make('estado')
                ->options([
                    'activa'   => 'Activa',
                    'pausada'  => 'Pausada',
                    'cancelada'=> 'Cancelada',
                ])->default('activa'),
        ])->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('fecha_inicio')->date('d/m/Y')->sortable(),
                Tables\Columns\TextColumn::make('fecha_fin')->date('d/m/Y'),
                Tables\Columns\TextColumn::make('dia_semana')->label('Día'),
                Tables\Columns\TextColumn::make('precio')->money('eur'),
                Tables\Columns\BadgeColumn::make('estado')
                    ->colors([
                        'success' => 'activa',
                        'warning' => 'pausada',
                        'danger'  => 'cancelada',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]);
    }
}
