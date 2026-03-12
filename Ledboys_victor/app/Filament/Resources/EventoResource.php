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

class EventoResource extends Resource
{
    protected static ?string $model = Evento::class;
    protected static ?string $navigationIcon = 'heroicon-o-calendar';
    protected static ?string $navigationLabel = 'Eventos';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make()->schema([
                    Select::make('cliente_id')
                        ->relationship('cliente', 'nombre')
                        ->searchable()
                        ->required(),
                    DatePicker::make('fecha')
                        ->required(),
                    Select::make('estado')
                        ->options([
                            'borrador' => 'Borrador',
                            'reservado' => 'Reservado',
                            'pagado' => 'Pagado',
                        ])->default('borrador'),
                ])->columns(3),

                Card::make()->schema([
                    Repeater::make('items')
                    ->relationship()
                    ->schema([
                        Select::make('item_id')
                            ->label('Traje / Accesorio')
                            ->options(Item::pluck('nombre', 'id'))
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
                            ->required()
                            ->reactive() // Cambiado a reactive para v2
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
                    ->createItemButtonLabel('Añadir Item')
                    ->reactive() // El repeater debe ser reactivo para avisar al total
                    ->afterStateUpdated(function (callable $get, callable $set) {
                        $items = $get('items') ?? [];
                        $total = 0;

                        foreach ($items as $item) {
                            $cant = floatval($item['cantidad'] ?? 0);
                            $prec = floatval($item['precio_unitario'] ?? 0);
                            $total += ($cant * $prec);
                        }

                        // Forzamos el seteo del total_precio
                        $set('total_precio', $total);
                    }),
                ]),

                Card::make()->schema([
                    TextInput::make('total_precio')
                    ->label('Total del Evento')
                    ->numeric()
                    ->prefix('€')
                    ->default(0)
                    // En v2, 'disabled' bloquea el JS, por eso usamos el atributo HTML
                    ->extraInputAttributes(['readonly' => true, 'class' => 'bg-gray-100'])
                    ->dehydrated(),
                ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('fecha')->date()->sortable(),
                Tables\Columns\TextColumn::make('cliente.nombre')->label('Cliente'),
                Tables\Columns\BadgeColumn::make('estado')
                    ->colors([
                        'secondary' => 'borrador',
                        'warning' => 'reservado',
                        'success' => 'pagado',
                    ]),
                Tables\Columns\TextColumn::make('total_precio')->money('eur'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListEventos::route('/'),
            'create' => Pages\CreateEvento::route('/create'),
            'edit' => Pages\EditEvento::route('/{record}/edit'),
        ];
    }
}