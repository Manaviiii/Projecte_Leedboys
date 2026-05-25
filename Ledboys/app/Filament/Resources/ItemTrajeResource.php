<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ItemTrajeResource\Pages;
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
 * Datos en dos tablas:
 * - 'items': nombre, precio, descripción, activo
 * - 'item_trajes': tipo_traje, género, stock
 * - 'fotos': fotos BLOB, una marcada como principal
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

            // ── Sección 1: Datos generales (tabla items) ───────────────────────
            Section::make('Datos Generales')->schema([
                Forms\Components\TextInput::make('nombre')
                    ->label('Nombre del Traje')
                    ->required()
                    ->minLength(2)
                    ->maxLength(255)
                    ->placeholder('Ej: Daft Punk')
                    ->rules(['regex:/\S/']),

                Forms\Components\TextInput::make('precio')
                    ->label('Precio (€)')
                    ->numeric()
                    ->prefix('€')
                    ->required()
                    ->minValue(0.01)
                    ->maxValue(9999.99)
                    ->placeholder('150.00'),

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

            // ── Sección 2: Detalles del traje (tabla item_trajes) ──────────────
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
                    ->minValue(0)
                    ->maxValue(999)
                    ->required()
                    ->placeholder('4'),
            ])->columns(3),

            // ── Sección 3: Fotos BLOB ──────────────────────────────────────────
            // El nombre se asigna automáticamente (Foto1, Foto2...) al guardar.
            // El Select de principal muestra "Foto 1", "Foto 2"... según posición.
            // El orden se asigna según la posición en el Repeater.
            Section::make('Fotos del Traje')->schema([

                // Select para elegir la foto principal por su posición
                Forms\Components\Select::make('foto_principal_orden')
                    ->label('Foto principal (portada)')
                    ->placeholder('Selecciona qué foto es la portada')
                    ->options(function ($get) {
                        $fotos = $get('fotos_input') ?? [];
                        $opciones = [];
                        $num = 1;
                        foreach ($fotos as $foto) {
                            $opciones[$num] = "Foto {$num}";
                            $num++;
                        }
                        return $opciones;
                    })
                    ->reactive(),

                Repeater::make('fotos_input')
                    ->label('Fotos')
                    ->schema([

                        // Previsualización de la foto guardada en BD (solo edición)
                        Forms\Components\Placeholder::make('preview')
                            ->label('Foto actual')
                            ->content(function ($get) {
                                $fotoId = $get('foto_id');
                                if (!$fotoId) {
                                    return new \Illuminate\Support\HtmlString(
                                        '<span style="color:#aaa;">Sin foto guardada</span>'
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

                        // ID oculto para saber si es una foto existente o nueva
                        Forms\Components\Hidden::make('foto_id'),

                        // Archivo — en edición es opcional
                        Forms\Components\FileUpload::make('archivo')
                            ->label('Foto')
                            ->image()
                            ->maxSize(5120)
                            ->directory('fotos_tmp')
                            ->columnSpan(2),
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
                // Foto principal desde BLOB — se genera como base64 inline
                Tables\Columns\TextColumn::make('foto_principal')
                    ->label('')
                    ->formatStateUsing(function ($record) {
                        $foto = $record->fotos()->where('principal', true)->first();
                        if (!$foto || !$foto->imagen) return '—';
                        $base64 = base64_encode($foto->imagen);
                        return "<img src=\"data:image/jpeg;base64,{$base64}\" style=\"height:50px;width:50px;object-fit:cover;border-radius:4px;\">";
                    })
                    ->html(),

                Tables\Columns\TextColumn::make('item.nombre')
                    ->label('Nombre')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\BadgeColumn::make('tipo_traje')
                    ->label('Tipo')
                    ->colors(['primary' => 'zancos', 'secondary' => 'sin_zancos'])
                    ->formatStateUsing(fn ($state) => $state === 'zancos' ? 'Con Zancos' : 'Sin Zancos'),

                Tables\Columns\BadgeColumn::make('genero')
                    ->label('Género')
                    ->colors(['primary' => 'unisex', 'danger' => 'chica', 'success' => 'chico'])
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
                    ->options(['zancos' => 'Con Zancos', 'sin_zancos' => 'Sin Zancos']),
                Tables\Filters\SelectFilter::make('genero')
                    ->label('Género')
                    ->options(['chico' => 'Chico', 'chica' => 'Chica', 'unisex' => 'Unisex']),
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