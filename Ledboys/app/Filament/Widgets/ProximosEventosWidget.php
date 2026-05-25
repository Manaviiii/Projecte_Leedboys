<?php

namespace App\Filament\Widgets;

use App\Models\Evento;
use Carbon\Carbon;
use Filament\Tables;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;

/**
 * Widget de tabla con los próximos eventos confirmados.
 * Muestra los eventos futuros pagados ordenados por fecha,
 * con el cliente, hora, ubicación y total.
 * Visible en el dashboard principal del admin.
 */
class ProximosEventosWidget extends BaseWidget
{
    protected static ?string $heading = 'Próximos Eventos';

    // Ocupa todo el ancho del dashboard
    protected int|string|array $columnSpan = 'full';

    // Solo mostrar los 10 más próximos
    protected function getTableQuery(): Builder
    {
        \Log::info('proximos: ' . Evento::where('estado', 'pagado')->where('fecha', '>=', Carbon::today())->count());
\Log::info('todos eventos: ' . Evento::count());
\Log::info(Evento::select('id', 'fecha', 'estado')->get()->toJson());

        return Evento::query()
            ->where('estado', 'pagado')
            ->where('fecha', '>=', Carbon::today())
            ->with('cliente')
            ->orderBy('fecha', 'asc')
            ->limit(10);
    }

    protected function getTableColumns(): array
    {
        return [
            Tables\Columns\TextColumn::make('fecha')
                ->label('Fecha')
                ->date('d/m/Y')
                ->sortable(),

            Tables\Columns\TextColumn::make('hora')
                ->label('Hora')
                ->formatStateUsing(fn ($state) => $state ?? '—'),

            Tables\Columns\TextColumn::make('cliente.nombre')
                ->label('Cliente')
                ->searchable(),

            Tables\Columns\TextColumn::make('ubicacion')
                ->label('Ubicación')
                ->formatStateUsing(fn ($state) => $state ?? '—')
                ->limit(30),

            Tables\Columns\TextColumn::make('total_precio')
                ->label('Total')
                ->money('eur'),

            // Días que faltan para el evento
            Tables\Columns\TextColumn::make('fecha')
                ->label('Faltan')
                ->formatStateUsing(fn ($state) => Carbon::parse($state)->diffInDays(Carbon::today()) . ' días'),
        ];
    }
}