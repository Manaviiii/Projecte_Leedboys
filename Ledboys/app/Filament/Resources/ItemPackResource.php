<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ItemPackResource\Pages;
use App\Models\ItemPack;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Forms\Components\Section;

/**
 * Resource de Filament para gestionar los packs del catálogo.
 */
class ItemPackResource extends Resource
{
    protected static ?string $model = ItemPack::class;
    protected static ?string $navigationIcon = 'heroicon-o-collection';
    protected static ?string $navigationLabel = 'Packs';
    protected static ?string $navigationGroup = 'Catálogo';
    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form->schema([

            // ── Sección 1: Datos del item padre (tabla items) ──────────────────
            Section::make('Datos Generales')->schema([
                Forms\Components\TextInput::make('nombre')
                    ->label('Nombre del Pack')
                    ->required()
                    ->minLength(2)
                    ->maxLength(255)
                    ->placeholder('Ej: Pack Bronce')
                    ->rules(['regex:/\S/']),

                Forms\Components\TextInput::make('precio')
                    ->label('Precio (€)')
                    ->numeric()
                    ->prefix('€')
                    ->required()
                    ->minValue(0.01)
                    ->maxValue(9999.99)
                    ->placeholder('300.00'),

                Forms\Components\Textarea::make('descripcion')
                    ->label('Descripción')
                    ->rows(3)
                    ->maxLength(1000)
                    ->columnSpan(2),


                Forms\Components\Toggle::make('activo')
                    ->label('Activo')
                    ->default(true)
                    ->columnSpan(2),
            ])->columns(2),

            // numero_zancudos se fija a 2 automáticamente — no se muestra en el formulario
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('item.nombre')->label('Nombre')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('numero_zancudos')->label('Zancudos'),
                Tables\Columns\TextColumn::make('item.precio')->label('Precio')->money('eur'),
                Tables\Columns\IconColumn::make('item.activo')->label('Activo')->boolean(),
            ])
            ->actions([Tables\Actions\EditAction::make(), Tables\Actions\DeleteAction::make()])
            ->bulkActions([Tables\Actions\DeleteBulkAction::make()]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListItemPacks::route('/'),
            'create' => Pages\CreateItemPack::route('/create'),
            'edit'   => Pages\EditItemPack::route('/{record}/edit'),
        ];
    }
}