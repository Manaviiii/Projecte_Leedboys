<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ItemTrajeResource\Pages;
use App\Models\Item;
use App\Models\ItemTraje;
use App\Models\Foto;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Repeater;

/**
 * Resource de Filament para gestionar los trajes del catálogo.
 *
 * Los datos se distribuyen en dos tablas:
 * - 'items': datos generales (nombre, precio, descripción, imagen, activo)
 * - 'item_trajes': datos específicos (tipo_traje, género, stock)
 * - 'fotos': múltiples fotos en formato BLOB, una marcada como principal
 *
 * Al crear: las Pages se encargan de insertar primero en 'items', obtener el id
 * y luego insertar en 'item_trajes' y 'fotos'.
 * Al editar: mutateFormDataBeforeFill precarga los campos del item padre
 * para que el formulario no aparezca vacío.
 */
class ItemTrajeResource extends Resource
{
    protected static ?string $model = ItemTraje::class;
    protected static ?string $navigationIcon = 'heroicon-o-sparkles';
    protected static ?string $navigationLabel = 'Trajes';
    protected static ?string $navigationGroup = 'Catálogo';
    protected static ?int $navigationSort = 1;

    /**
     * Formulario de creación y edición.
     * Dividido en tres secciones: datos generales, detalles del traje y fotos.
     */
    public static function form(Form $form): Form
    {
        return $form->schema([

            // ── Sección 1: Datos del item padre (tabla items) ──────────────────
            Section::make('Datos Generales')->schema([
                Forms\Components\TextInput::make('nombre')
                    ->label('Nombre del Traje')
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

                Forms\Components\FileUpload::make('imagen')
                    ->label('Imagen de referencia')
                    ->image()
                    ->directory('items')
                    ->columnSpan(2),

                Forms\Components\Toggle::make('activo')
                    ->label('Activo')
                    ->default(true)
                    ->columnSpan(2),
            ])->columns(2),

            // ── Sección 2: Datos específicos del traje (tabla item_trajes) ─────
            Section::make('Detalles del Traje')->schema([
                Forms\Components\Select::make('tipo_traje')
                    ->label('Tipo')
                    ->options([
                        'zancos'     => 'Con Zancos',
                        'sin_zancos' => 'Sin Zancos',
                    ])
                    ->required(),

                Forms\Components\Select::make('genero')
                    ->label('Género')
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

            // ── Sección 3: Fotos (tabla fotos, guardadas como BLOB) ────────────
            // El Repeater permite añadir múltiples fotos.
            // El archivo se sube temporalmente a disco y en la Page se lee
            // con file_get_contents() y se guarda como BLOB en la BD.
            // Solo debe haber una foto marcada como principal — se controla en la Page.
            Section::make('Fotos del Traje')->schema([
                Repeater::make('fotos_input')
                    ->label('Fotos')
                    ->schema([
                        Forms\Components\FileUpload::make('archivo')
                            ->label('Foto')
                            ->image()
                            ->required()
                            ->directory('fotos_tmp'), // directorio temporal antes de convertir a BLOB

                        Forms\Components\TextInput::make('nombre')
                            ->label('Nombre de la foto')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\TextInput::make('orden')
                            ->label('Orden de visualización')
                            ->numeric()
                            ->default(1)
                            ->minValue(1),

                        // Toggle para marcar cuál es la foto principal del traje en el catálogo
                        Forms\Components\Toggle::make('principal')
                            ->label('Foto principal')
                            ->default(false),
                    ])
                    ->columns(2)
                    ->createItemButtonLabel('+ Añadir foto')
                    ->defaultItems(0), // sin fotos por defecto al abrir el formulario
            ])->collapsible(), // colapsable para no ocupar espacio innecesario
        ]);
    }

    /**
     * Tabla de listado de trajes con columnas, filtros y acciones.
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

                // Badge coloreado según el tipo: zancos o sin zancos
                Tables\Columns\BadgeColumn::make('tipo_traje')
                    ->label('Tipo')
                    ->colors([
                        'primary'   => 'zancos',
                        'secondary' => 'sin_zancos',
                    ])
                    ->formatStateUsing(fn ($state) => $state === 'zancos' ? 'Con Zancos' : 'Sin Zancos'),

                // Badge coloreado según el género del traje
                Tables\Columns\BadgeColumn::make('genero')
                    ->label('Género')
                    ->colors([
                        'primary' => 'unisex',
                        'danger'  => 'chica',
                        'success' => 'chico',
                    ])
                    ->formatStateUsing(fn ($state) => ucfirst($state)),

                Tables\Columns\TextColumn::make('stock_total')
                    ->label('Stock'),

                Tables\Columns\TextColumn::make('item.precio')
                    ->label('Precio')
                    ->money('eur'),

                Tables\Columns\IconColumn::make('item.activo')
                    ->label('Activo')
                    ->boolean(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('tipo_traje')
                    ->label('Tipo')
                    ->options([
                        'zancos'     => 'Con Zancos',
                        'sin_zancos' => 'Sin Zancos',
                    ]),
                Tables\Filters\SelectFilter::make('genero')
                    ->label('Género')
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