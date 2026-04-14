<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ItemTrajeResource\Pages;
use App\Models\ItemTraje;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Forms\Components\Section;

class ItemTrajeResource extends Resource
{
    protected static ?string $model = ItemTraje::class;
    protected static ?string $navigationIcon = 'heroicon-o-sparkles';
    protected static ?string $navigationLabel = 'Trajes';
    protected static ?string $navigationGroup = 'Catálogo';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Section::make('Datos Generales')->schema([
                Forms\Components\TextInput::make('nombre_item')
                    ->label('Nombre del Traje')
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

            Section::make('Detalles del Traje')->schema([
                Forms\Components\Select::make('tipo_traje')
                    ->label('Tipo')
                    ->options([
                        'zancos'     => 'Con Zancos',
                        'sin_zancos' => 'Sin Zancos',
                    ])
                    ->required(),

                Forms\Components\Select::make('genero')
                    ->options([
                        'chico'  => 'Chico',
                        'chica'  => 'Chica',
                        'unisex' => 'Unisex',
                    ])
                    ->default('unisex')
                    ->required(),

                Forms\Components\TextInput::make('stock_total')
                    ->label('Stock Total')
                    ->numeric()
                    ->minValue(0)
                    ->required(),
            ])->columns(3),
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
                Tables\Columns\BadgeColumn::make('tipo_traje')
                    ->label('Tipo')
                    ->colors([
                        'primary' => 'zancos',
                        'secondary' => 'sin_zancos',
                    ]),
                Tables\Columns\BadgeColumn::make('genero')
                    ->colors([
                        'primary' => 'unisex',
                        'danger'  => 'chica',
                        'success' => 'chico',
                    ]),
                Tables\Columns\TextColumn::make('stock_total')->label('Stock'),
                Tables\Columns\TextColumn::make('item.precio')
                    ->label('Precio')
                    ->money('eur'),
                Tables\Columns\IconColumn::make('item.activo')
                    ->label('Activo')
                    ->boolean(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('tipo_traje')
                    ->options([
                        'zancos'     => 'Con Zancos',
                        'sin_zancos' => 'Sin Zancos',
                    ]),
                Tables\Filters\SelectFilter::make('genero')
                    ->options([
                        'chico'  => 'Chico',
                        'chica'  => 'Chica',
                        'unisex' => 'Unisex',
                    ]),
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
            'index'  => Pages\ListItemTrajes::route('/'),
            'create' => Pages\CreateItemTraje::route('/create'),
            'edit'   => Pages\EditItemTraje::route('/{record}/edit'),
        ];
    }
}
