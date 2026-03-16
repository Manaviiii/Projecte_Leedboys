<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ResidenciaResource\Pages;
use App\Models\Residencia;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;

class ResidenciaResource extends Resource
{
    protected static ?string $model = Residencia::class;
    protected static ?string $navigationIcon = 'heroicon-o-home';
    protected static ?string $navigationLabel = 'Residencias';
    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Card::make()->schema([
                Select::make('cliente_id')
                    ->relationship('cliente', 'nombre')
                    ->searchable()
                    ->preload()
                    ->required()
                    ->label('Cliente'),

                Select::make('dia_semana')
                    ->label('Día de la semana')
                    ->options([
                        'lunes'     => 'Lunes',
                        'martes'    => 'Martes',
                        'miercoles' => 'Miércoles',
                        'jueves'    => 'Jueves',
                        'viernes'   => 'Viernes',
                        'sabado'    => 'Sábado',
                        'domingo'   => 'Domingo',
                    ])
                    ->required(),

                TextInput::make('precio')
                    ->numeric()
                    ->prefix('€')
                    ->required(),

                Select::make('estado')
                    ->options([
                        'activa'    => 'Activa',
                        'pausada'   => 'Pausada',
                        'cancelada' => 'Cancelada',
                    ])
                    ->default('activa')
                    ->required(),

                DatePicker::make('fecha_inicio')
                    ->label('Fecha inicio')
                    ->required()
                    ->displayFormat('d/m/Y'),

                DatePicker::make('fecha_fin')
                    ->label('Fecha fin')
                    ->displayFormat('d/m/Y')
                    ->after('fecha_inicio'),

                TextInput::make('stripe_subscription_id')
                    ->label('Stripe Subscription ID')
                    ->disabled()
                    ->dehydrated(false)
                    ->visibleOn('edit'),
            ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('cliente.nombre')
                    ->label('Cliente')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('dia_semana')
                    ->label('Día')
                    ->sortable(),
                Tables\Columns\TextColumn::make('fecha_inicio')
                    ->label('Inicio')
                    ->date('d/m/Y')
                    ->sortable(),
                Tables\Columns\TextColumn::make('fecha_fin')
                    ->label('Fin')
                    ->date('d/m/Y'),
                Tables\Columns\TextColumn::make('precio')
                    ->money('eur'),
                Tables\Columns\BadgeColumn::make('estado')
                    ->colors([
                        'success' => 'activa',
                        'warning' => 'pausada',
                        'danger'  => 'cancelada',
                    ]),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('estado')
                    ->options([
                        'activa'    => 'Activa',
                        'pausada'   => 'Pausada',
                        'cancelada' => 'Cancelada',
                    ]),
                Tables\Filters\SelectFilter::make('dia_semana')
                    ->label('Día')
                    ->options([
                        'lunes'     => 'Lunes',
                        'martes'    => 'Martes',
                        'miercoles' => 'Miércoles',
                        'jueves'    => 'Jueves',
                        'viernes'   => 'Viernes',
                        'sabado'    => 'Sábado',
                        'domingo'   => 'Domingo',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ])
            ->defaultSort('fecha_inicio', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListResidencias::route('/'),
            'create' => Pages\CreateResidencia::route('/create'),
            'edit'   => Pages\EditResidencia::route('/{record}/edit'),
        ];
    }
}
