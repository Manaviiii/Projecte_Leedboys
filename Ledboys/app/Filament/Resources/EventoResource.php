<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EventoResource\Pages;
use App\Models\Evento;
use App\Models\Item;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Placeholder;

class EventoResource extends Resource
{
    protected static ?string $model = Evento::class;
    protected static ?string $navigationIcon = 'heroicon-o-calendar';
    protected static ?string $navigationLabel = 'Eventos';
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make()->schema([
                    Select::make('cliente_id')
                        ->relationship('cliente', 'nombre')
                        ->searchable()
                        ->preload()
                        ->required()
                        ->label('Cliente'),

                    DatePicker::make('fecha')
                        ->required()
                        ->displayFormat('d/m/Y'),

                    Select::make('estado')
                        ->options([
                            'borrador'  => 'Borrador',
                            'reservado' => 'Reservado',
                            'pagado'    => 'Pagado',
                        ])
                        ->default('borrador')
                        ->required(),
                ])->columns(3),

                Card::make()->schema([
                    Repeater::make('items')
                        ->relationship()
                        ->schema([
                            Select::make('item_id')
                                ->label('Traje / Accesorio / Pack')
                                ->options(
                                    Item::where('activo', true)
                                        ->orderBy('nombre')
                                        ->pluck('nombre', 'id')
                                )
                                ->required()
                                ->reactive()
                                ->afterStateUpdated(function ($state, callable $set) {
                                    $item = Item::find($state);
                                    $set('precio_unitario', $item?->precio ?? 0);
                                })
                                ->columnSpan(3),

                            TextInput::make('cantidad')
                                ->numeric()
                                ->default(1)
                                ->minValue(1)
                                ->required()
                                ->reactive()
                                ->columnSpan(1),

                            TextInput::make('precio_unitario')
                                ->label('Precio/u')
                                ->numeric()
                                ->prefix('€')
                                ->required()
                                ->reactive()
                                ->columnSpan(2),
                        ])
                        ->columns(6)
                        ->createItemButtonLabel('+ Añadir ítem')
                        ->reactive()
                        ->afterStateUpdated(function (callable $get, callable $set) {
                            self::recalcularTotal($get, $set);
                        })
                        ->defaultItems(1),
                ])->label('Ítems del Evento'),

                Card::make()->schema([
                    TextInput::make('total_precio')
                        ->label('Total del Evento')
                        ->numeric()
                        ->prefix('€')
                        ->default(0)
                        ->extraInputAttributes(['readonly' => true, 'class' => 'bg-gray-100 font-bold text-lg'])
                        ->dehydrated(),
                ]),
            ]);
    }

    protected static function recalcularTotal(callable $get, callable $set): void
    {
        $items = $get('items') ?? [];
        $total = 0;

        foreach ($items as $item) {
            $cant  = floatval($item['cantidad'] ?? 0);
            $prec  = floatval($item['precio_unitario'] ?? 0);
            $total += ($cant * $prec);
        }

        $set('total_precio', round($total, 2));
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('#')
                    ->sortable(),
                Tables\Columns\TextColumn::make('fecha')
                    ->date('d/m/Y')
                    ->sortable(),
                Tables\Columns\TextColumn::make('cliente.nombre')
                    ->label('Cliente')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\BadgeColumn::make('estado')
                    ->colors([
                        'secondary' => 'borrador',
                        'warning'   => 'reservado',
                        'success'   => 'pagado',
                    ]),
                Tables\Columns\TextColumn::make('total_precio')
                    ->money('eur')
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Creado')
                    ->date('d/m/Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('estado')
                    ->options([
                        'borrador'  => 'Borrador',
                        'reservado' => 'Reservado',
                        'pagado'    => 'Pagado',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ])
            ->defaultSort('fecha', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListEventos::route('/'),
            'create' => Pages\CreateEvento::route('/create'),
            'edit'   => Pages\EditEvento::route('/{record}/edit'),
        ];
    }
}
