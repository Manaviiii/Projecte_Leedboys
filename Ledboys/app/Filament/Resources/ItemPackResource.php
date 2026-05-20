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
 *
 * Los datos se distribuyen en dos tablas:
 * - 'items': datos generales (nombre, precio, descripción, imagen, activo)
 * - 'item_packs': número de zancudos incluidos en el pack
 *
 * Los packs no tienen fotos — usan solo la imagen de referencia del item padre.
 * Al crear: las Pages crean primero el Item y luego el ItemPack.
 * Al editar: se precargan los datos del item padre para no tener el formulario vacío.
 */
class ItemPackResource extends Resource
{
    protected static ?string $model = ItemPack::class;
    protected static ?string $navigationIcon = 'heroicon-o-collection';
    protected static ?string $navigationLabel = 'Packs';
    protected static ?string $navigationGroup = 'Catálogo';
    protected static ?int $navigationSort = 3;

    /**
     * Formulario de creación y edición.
     * Dividido en dos secciones: datos generales y detalles del pack.
     */
    public static function form(Form $form): Form
    {
        return $form->schema([

            // ── Sección 1: Datos del item padre (tabla items) ──────────────────
            Section::make('Datos Generales')->schema([
                Forms\Components\TextInput::make('nombre')
                    ->label('Nombre del Pack')
                    ->required()
                    ->maxLength(255),

                Forms\Components\TextInput::make('precio')
                    ->label('Precio (€)')
                    ->numeric()
                    ->prefix('€')
                    ->required(),

                Forms\Components\Textarea::make('descripcion')
                    ->label('Descripción')
                    ->rows(3)
                    ->columnSpan(2),

                // Imagen de referencia del pack (no es BLOB, se guarda como ruta en disco)
                Forms\Components\FileUpload::make('imagen')
                    ->label('Imagen')
                    ->image()
                    ->directory('items')
                    ->columnSpan(2),

                Forms\Components\Toggle::make('activo')
                    ->label('Activo')
                    ->default(true)
                    ->columnSpan(2),
            ])->columns(2),

            // ── Sección 2: Detalles del pack (tabla item_packs) ────────────────
            Section::make('Detalles del Pack')->schema([
                // Número de zancudos que incluye el pack contratado
                Forms\Components\TextInput::make('numero_zancudos')
                    ->label('Nº de Zancudos incluidos')
                    ->numeric()
                    ->minValue(1)
                    ->required(),
            ]),
        ]);
    }

    /**
     * Tabla de listado de packs con columnas y acciones.
     */
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('item.imagen')
                    ->label(''),

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