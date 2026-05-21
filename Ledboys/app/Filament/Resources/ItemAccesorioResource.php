<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ItemAccesorioResource\Pages;
use App\Models\ItemAccesorio;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Forms\Components\Section;

/**
 * Resource de Filament para gestionar los accesorios del catálogo.
 */
class ItemAccesorioResource extends Resource
{
    protected static ?string $model = ItemAccesorio::class;
    protected static ?string $navigationIcon = 'heroicon-o-tag';
    protected static ?string $navigationLabel = 'Accesorios';
    protected static ?string $navigationGroup = 'Catálogo';
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form->schema([

            // ── Sección 1: Datos del item padre (tabla items) ──────────────────
            Section::make('Datos Generales')->schema([
                Forms\Components\TextInput::make('nombre')
                    ->label('Nombre del Accesorio')
                    ->required()
                    ->minLength(2)
                    ->maxLength(255)
                    ->placeholder('Ej: Barra Limbo')
                    ->rules(['regex:/\S/']),

                Forms\Components\TextInput::make('precio')
                    ->label('Precio (€)')
                    ->numeric()
                    ->prefix('€')
                    ->required()
                    ->minValue(0.01)
                    ->maxValue(9999.99)
                    ->placeholder('50.00'),

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

            // ── Sección 2: Stock y foto ────────────────────────────────────────
            Section::make('Stock y Foto')->schema([
                Forms\Components\TextInput::make('stock_total')
                    ->label('Stock Total')
                    ->numeric()
                    ->integer()
                    ->minValue(0)
                    ->maxValue(999)
                    ->required()
                    ->placeholder('10'),

                Forms\Components\Placeholder::make('preview_foto')
                    ->label('Foto actual')
                    ->content(function ($record) {
                        if (!$record || !$record->imagen) {
                            return new \Illuminate\Support\HtmlString(
                                '<span style="color:#aaa;">Sin foto guardada</span>'
                            );
                        }
                        $base64 = base64_encode($record->imagen);
                        return new \Illuminate\Support\HtmlString(
                            "<img src=\"data:image/jpeg;base64,{$base64}\" style=\"max-height:150px;border-radius:6px;\">"
                        );
                    })
                    ->visibleOn('edit'),

                Forms\Components\FileUpload::make('foto_archivo')
                    ->label('Subir foto nueva (opcional en edición)')
                    ->image()
                    ->directory('fotos_tmp'),
            ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('item.imagen')->label(''),
                Tables\Columns\TextColumn::make('item.nombre')->label('Nombre')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('stock_total')->label('Stock'),
                Tables\Columns\TextColumn::make('item.precio')->label('Precio')->money('eur'),
                Tables\Columns\IconColumn::make('item.activo')->label('Activo')->boolean(),
            ])
            ->actions([Tables\Actions\EditAction::make(), Tables\Actions\DeleteAction::make()])
            ->bulkActions([Tables\Actions\DeleteBulkAction::make()]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListItemAccesorios::route('/'),
            'create' => Pages\CreateItemAccesorio::route('/create'),
            'edit'   => Pages\EditItemAccesorio::route('/{record}/edit'),
        ];
    }
}