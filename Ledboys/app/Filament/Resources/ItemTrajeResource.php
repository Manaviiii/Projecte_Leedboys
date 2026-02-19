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
    protected static ?string $navigationIcon = 'heroicon-o-sparkles'; // Icono mas chulo
    protected static ?string $navigationLabel = 'Trajes';

    public static function form(Form $form): Form
    {
        return $form
        ->schema([
            Section::make('Datos Generales (Tabla Items)')
                ->schema([
                    // Estos campos NO existen en item_trajes, existen en items.
                    // Los procesaremos en el CreateItemTraje.
                    Forms\Components\TextInput::make('nombre_item')
                        ->label('Nombre del Traje')
                        ->required(),

                    Forms\Components\TextInput::make('precio_item')
                        ->label('Precio')
                        ->numeric()
                        ->prefix('€')
                        ->required(),

                    Forms\Components\Textarea::make('descripcion_item')
                        ->label('Descripción')
                        ->rows(3),
                    
                    Forms\Components\FileUpload::make('imagen_item')
                        ->label('Imagen')
                        ->image()
                        ->directory('items'),
                ]),

            Section::make('Detalles del Traje (Tabla Trajes)')
                ->schema([
                    Forms\Components\Select::make('tipo_traje')
                        ->options([
                            'zancos' => 'Zancos',
                            'sin_zancos' => 'Sin Zancos',
                        ])
                        ->required(),

                    Forms\Components\Select::make('genero')
                        ->options([
                            'chico' => 'Chico',
                            'chica' => 'Chica',
                            'unisex' => 'Unisex',
                        ])
                        ->default('unisex')
                        ->required(),

                    Forms\Components\TextInput::make('stock_total')
                        ->numeric()
                        ->required()
                        ->label('Stock Total'),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                // Usamos la relación 'item' definida en el Modelo para sacar el nombre
                Tables\Columns\ImageColumn::make('item.imagen')->label('Img'),
                Tables\Columns\TextColumn::make('item.nombre')->label('Nombre')->searchable(),
                Tables\Columns\TextColumn::make('tipo_traje')->label('Tipo')->sortable(),
                Tables\Columns\BadgeColumn::make('genero')
                    ->colors(['primary' => 'unisex', 'danger' => 'chica', 'success' => 'chico']),
                Tables\Columns\TextColumn::make('stock_total')->label('Stock'),
                Tables\Columns\TextColumn::make('item.precio')->label('Precio')->money('eur'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('tipo_traje')
                    ->options([
                        'zancos' => 'Con Zancos',
                        'sin_zancos' => 'Sin Zancos',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(), // Ojo, necesitarás cascadeOnDelete en la BD
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListItemTrajes::route('/'),
            'create' => Pages\CreateItemTraje::route('/create'),
            'edit' => Pages\EditItemTraje::route('/{record}/edit'),
        ];
    }
}