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
            Section::make('Datos Generales')->schema([
                Forms\Components\TextInput::make('nombre_item')
                    ->label('Nombre del Pack')
                    ->required()
                    ->maxLength(255),

                Forms\Components\TextInput::make('precio_item')
                    ->label('Precio (€)')
                    ->numeric()
                    ->prefix('€')
                    ->required(),

                Forms\Components\Textarea::make('descripcion_item')
                    ->label('Descripción')
                    ->rows(3)
                    ->columnSpan(2),

                Forms\Components\FileUpload::make('imagen_item')
                    ->label('Imagen')
                    ->image()
                    ->directory('items')
                    ->columnSpan(2),
            ])->columns(2),

            Section::make('Detalles del Pack')->schema([
                Forms\Components\TextInput::make('numero_zancudos')
                    ->label('Nº de Zancudos incluidos')
                    ->numeric()
                    ->minValue(1)
                    ->required(),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('item.imagen')->label(''),
                Tables\Columns\TextColumn::make('item.nombre')
                    ->label('Nombre')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('numero_zancudos')
                    ->label('Zancudos'),
                Tables\Columns\TextColumn::make('item.precio')
                    ->label('Precio')
                    ->money('eur'),
                Tables\Columns\IconColumn::make('item.activo')
                    ->label('Activo')
                    ->boolean(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
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
