<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EventoResource\Pages;
use App\Models\Evento;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;

/**
 * Resource de Filament para visualizar eventos.
 * Los eventos se crean automáticamente al pagar desde la web.
 * Desde el panel solo se pueden ver y eliminar.
 */
class EventoResource extends Resource
{
    protected static ?string $model = Evento::class;
    protected static ?string $navigationIcon = 'heroicon-o-calendar';
    protected static ?string $navigationLabel = 'Eventos';
    protected static ?int $navigationSort = 2;

    // Sin formulario — no se crean ni editan desde Filament
    public static function form(Form $form): Form
    {
        return $form->schema([]);
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
                Tables\Columns\TextColumn::make('hora')
                    ->label('Hora')
                    ->formatStateUsing(fn ($state) => $state ?? '—'),
                Tables\Columns\TextColumn::make('cliente.nombre')
                    ->label('Cliente')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('ubicacion')
                    ->label('Ubicación')
                    ->formatStateUsing(fn ($state) => $state ?? '—')
                    ->limit(30),
                Tables\Columns\BadgeColumn::make('estado')
                    ->colors([
                        'secondary' => 'borrador',
                        'warning'   => 'reservado',
                        'success'   => 'pagado',
                        'danger'    => 'cancelado',
                    ]),
                Tables\Columns\TextColumn::make('total_precio')
                    ->label('Total')
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
                        'cancelado' => 'Cancelado',
                    ]),
            ])
            ->actions([
                // Solo se puede eliminar, no editar
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
            // Solo listado — sin create ni edit
            'index' => Pages\ListEventos::route('/'),
        ];
    }
}