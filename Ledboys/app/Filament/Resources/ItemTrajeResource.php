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
 */
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

            // ── Sección 1: Datos del item padre (tabla items) ──────────────────
            Section::make('Datos Generales')->schema([
                Forms\Components\TextInput::make('nombre')
                    ->label('Nombre del Traje')
                    ->required()
                    ->minLength(2)
                    ->maxLength(255)
                    ->placeholder('Ej: Daft Punk')
                    ->rules(['regex:/\S/']), // no puede ser solo espacios

                Forms\Components\TextInput::make('precio')
                    ->label('Precio (€)')
                    ->numeric()
                    ->prefix('€')
                    ->required()
                    ->minValue(0.01) // no puede ser 0 ni negativo
                    ->maxValue(9999.99)
                    ->placeholder('150.00'),

                Forms\Components\Textarea::make('descripcion')
                    ->label('Descripción')
                    ->rows(3)
                    ->maxLength(1000)
                    ->columnSpan(2),

                Forms\Components\FileUpload::make('imagen')
                    ->label('Imagen de referencia')
                    ->image()
                    ->maxSize(5120) // máximo 5MB
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
                    ->required()
                    ->placeholder('Selecciona un tipo'),

                Forms\Components\Select::make('genero')
                    ->label('Género')
                    ->options([
                        'chico'  => 'Chico',
                        'chica'  => 'Chica',
                        'unisex' => 'Unisex',
                    ])
                    ->default('unisex')
                    ->required()
                    ->placeholder('Selecciona un género'),

                Forms\Components\TextInput::make('stock_total')
                    ->label('Stock Total')
                    ->numeric()
                    ->integer()
                    ->minValue(0) // no puede ser negativo
                    ->maxValue(999)
                    ->required()
                    ->placeholder('4'),
            ])->columns(3),

            // ── Sección 3: Fotos ───────────────────────────────────────────────
            Section::make('Fotos del Traje')->schema([
                Forms\Components\Select::make('foto_principal_orden')
                    ->label('Foto principal')
                    ->placeholder('Selecciona el orden de la foto principal')
                    ->options(function ($get) {
                        $fotos = $get('fotos_input') ?? [];
                        $opciones = [];
                        foreach ($fotos as $foto) {
                            $orden = $foto['orden'] ?? null;
                            $nombre = $foto['nombre'] ?? 'Sin nombre';
                            if ($orden) {
                                $opciones[$orden] = "Orden {$orden} — {$nombre}";
                            }
                        }
                        return $opciones;
                    })
                    ->reactive(),

                Repeater::make('fotos_input')
                    ->label('Fotos')
                    ->schema([
                        Forms\Components\Placeholder::make('preview')
                            ->label('Foto actual')
                            ->content(function ($get) {
                                $fotoId = $get('foto_id');
                                if (!$fotoId) {
                                    return new \Illuminate\Support\HtmlString(
                                        '<span style="color:#aaa;">Sin foto guardada — sube una nueva abajo</span>'
                                    );
                                }
                                $foto = \App\Models\Foto::find($fotoId);
                                if (!$foto || !$foto->imagen) {
                                    return new \Illuminate\Support\HtmlString(
                                        '<span style="color:#aaa;">Sin foto guardada</span>'
                                    );
                                }
                                $base64 = base64_encode($foto->imagen);
                                return new \Illuminate\Support\HtmlString(
                                    "<img src=\"data:image/jpeg;base64,{$base64}\" style=\"max-height:150px;border-radius:6px;\">"
                                );
                            })
                            ->columnSpan(2),

                        Forms\Components\Hidden::make('foto_id'),

                        Forms\Components\FileUpload::make('archivo')
                            ->label('Subir foto nueva (opcional en edición)')
                            ->image()
                            ->directory('fotos_tmp'),

                        Forms\Components\TextInput::make('nombre')
                            ->label('Nombre de la foto')
                            ->required()
                            ->minLength(2)
                            ->maxLength(255),

                        Forms\Components\TextInput::make('orden')
                            ->label('Orden de visualización')
                            ->numeric()
                            ->integer()
                            ->default(1)
                            ->minValue(1)
                            ->maxValue(99),

                    ])
                    ->columns(2)
                    ->createItemButtonLabel('+ Añadir foto')
                    ->defaultItems(0),
            ])->collapsible(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('item.imagen')->label(''),
                Tables\Columns\TextColumn::make('item.nombre')->label('Nombre')->searchable()->sortable(),
                Tables\Columns\BadgeColumn::make('tipo_traje')
                    ->label('Tipo')
                    ->colors(['primary' => 'zancos', 'secondary' => 'sin_zancos'])
                    ->formatStateUsing(fn ($state) => $state === 'zancos' ? 'Con Zancos' : 'Sin Zancos'),
                Tables\Columns\BadgeColumn::make('genero')
                    ->label('Género')
                    ->colors(['primary' => 'unisex', 'danger' => 'chica', 'success' => 'chico'])
                    ->formatStateUsing(fn ($state) => ucfirst($state)),
                Tables\Columns\TextColumn::make('stock_total')->label('Stock'),
                Tables\Columns\TextColumn::make('item.precio')->label('Precio')->money('eur'),
                Tables\Columns\IconColumn::make('item.activo')->label('Activo')->boolean(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('tipo_traje')->label('Tipo')->options(['zancos' => 'Con Zancos', 'sin_zancos' => 'Sin Zancos']),
                Tables\Filters\SelectFilter::make('genero')->label('Género')->options(['chico' => 'Chico', 'chica' => 'Chica', 'unisex' => 'Unisex']),
            ])
            ->actions([Tables\Actions\EditAction::make(), Tables\Actions\DeleteAction::make()])
            ->bulkActions([Tables\Actions\DeleteBulkAction::make()]);
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