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
            // El Repeater permite añadir, editar y eliminar fotos.
            // Las fotos existentes se muestran como previsualización base64.
            // Las fotos nuevas se suben temporalmente y se convierten a BLOB en la Page.
            // Solo debe haber una foto marcada como principal.
            Section::make('Fotos del Traje')->schema([
                // Select para elegir cuál de las fotos añadidas es la principal.
                // Se basa en el orden — elige el número de orden de la foto principal.
                // Al guardar, la Page marca como principal la foto cuyo orden coincida.
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
                                $opciones[$orden] = "{$orden} — {$nombre}";
                            }
                        }
                        return $opciones;
                    })
                    ->reactive(),

                Repeater::make('fotos_input')
                    ->label('Fotos')
                    ->schema([

                        // Previsualización de la foto ya guardada en BD (solo en edición).
                        // Se renderiza como img con src base64 ya que el BLOB no tiene ruta en disco.
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

                        // Campo oculto con el ID de la foto existente.
                        // Si tiene valor, la Page actualiza esa fila. Si está vacío, crea una nueva.
                        Forms\Components\Hidden::make('foto_id'),

                        // Si se sube un archivo nuevo reemplaza el BLOB guardado.
                        // En edición es opcional — si se deja vacío se mantiene la foto existente.
                        Forms\Components\FileUpload::make('archivo')
                            ->label('Subir foto nueva (opcional en edición)')
                            ->image()
                            ->directory('fotos_tmp'),

                        Forms\Components\TextInput::make('nombre')
                            ->label('Nombre de la foto')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\TextInput::make('orden')
                            ->label('Orden de visualización')
                            ->numeric()
                            ->default(1)
                            ->minValue(1),

                    ])
                    ->columns(2)
                    ->createItemButtonLabel('+ Añadir foto')
                    ->defaultItems(0),

            ])->collapsible(),
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