<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ClienteResource\Pages;
use App\Models\Cliente;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;

/**
 * Resource de Filament para visualizar clientes.
 * Los clientes se crean automáticamente al registrarse desde la web.
 * Desde el panel solo se pueden ver.
 */
class ClienteResource extends Resource
{
    protected static ?string $model = Cliente::class;
    protected static ?string $navigationIcon = 'heroicon-o-user-group';
    protected static ?string $navigationLabel = 'Clientes';
    protected static ?int $navigationSort = 1;

    // Sin formulario — no se crean ni editan desde Filament
    public static function form(Form $form): Form
    {
        return $form->schema([]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nombre')
                    ->label('Nombre')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('email')
                    ->label('Email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('telefono')
                    ->label('Teléfono')
                    ->formatStateUsing(fn ($state) => $state ?? '—'),
                Tables\Columns\TextColumn::make('eventos_count')
                    ->label('Eventos')
                    ->counts('eventos'),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Alta')
                    ->date('d/m/Y')
                    ->sortable(),
            ])
            ->filters([])
            ->actions([
                // Solo lectura — sin editar ni eliminar
            ])
            ->bulkActions([]);
    }

    public static function getRelationManagers(): array
    {
        return [
            ClienteResource\RelationManagers\EventosRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListClientes::route('/'),
            'view'  => Pages\ListClientes::route('/{record}'),
        ];
    }
}