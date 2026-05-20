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
 *
 * Los datos se distribuyen en dos tablas:
 * - 'items': datos generales (nombre, precio, descripción, activo)
 * - 'item_accesorios': stock y una única foto en formato BLOB
 *
 * Al crear: las Pages crean primero el Item y luego el ItemAccesorio con la foto.
 * Al editar: se precargan los datos del item padre para no tener el formulario vacío.
 */
class ItemAccesorioResource extends Resource
{
    protected static ?string $model = ItemAccesorio::class;
    protected static ?string $navigationIcon = 'heroicon-o-tag';
    protected static ?string $navigationLabel = 'Accesorios';
    protected static ?string $navigationGroup = 'Catálogo';
    protected static ?int $navigationSort = 2;

    /**
     * Formulario de creación y edición.
     * Dividido en dos secciones: datos generales y stock + foto.
     */
    public static function form(Form $form): Form
    {
        return $form->schema([

            // ── Sección 1: Datos del item padre (tabla items) ──────────────────
            Section::make('Datos Generales')->schema([
                Forms\Components\TextInput::make('nombre')
                    ->label('Nombre del Accesorio')
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

                Forms\Components\Toggle::make('activo')
                    ->label('Activo')
                    ->default(true)
                    ->columnSpan(2),
            ])->columns(2),

            // ── Sección 2: Stock y foto del accesorio (tabla item_accesorios) ──
            // La foto se sube como archivo temporal, se lee como BLOB y se guarda en la BD.
            Section::make('Stock y Foto')->schema([
                Forms\Components\TextInput::make('stock_total')
                    ->label('Stock Total')
                    ->numeric()
                    ->minValue(0)
                    ->required(),

                // El archivo se sube temporalmente — en la Page se convierte a BLOB
                Forms\Components\FileUpload::make('foto_archivo')
                    ->label('Foto del accesorio')
                    ->image()
                    ->directory('fotos_tmp'),
            ])->columns(2),
        ]);
    }

    /**
     * Tabla de listado de accesorios con columnas y acciones.
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

                Tables\Columns\TextColumn::make('stock_total')
                    ->label('Stock'),

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
            'index'  => Pages\ListItemAccesorios::route('/'),
            'create' => Pages\CreateItemAccesorio::route('/create'),
            'edit'   => Pages\EditItemAccesorio::route('/{record}/edit'),
        ];
    }
}