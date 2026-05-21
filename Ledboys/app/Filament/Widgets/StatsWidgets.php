<?php

namespace App\Filament\Widgets;

use App\Models\Evento;
use App\Models\Cliente;
use App\Models\Pago;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Card;
use Carbon\Carbon;

/**
 * Widget de estadísticas generales del panel de administración.
 * Muestra tarjetas con métricas clave: eventos del mes, ingresos y clientes.
 * Se actualiza automáticamente cada 30 segundos.
 */
class StatsWidgets extends BaseWidget
{
    // Actualizar automáticamente cada 30 segundos
    protected static ?string $pollingInterval = '30s';

    protected function getCards(): array
    {
        $hoy         = Carbon::today();
        $inicioMes   = Carbon::now()->startOfMonth();
        $finMes      = Carbon::now()->endOfMonth();

        // Eventos pagados este mes
        $eventosMes = Evento::where('estado', 'pagado')
            ->whereBetween('fecha', [$inicioMes, $finMes])
            ->count();

        // Próximos eventos (futuros pagados)
        $proximosEventos = Evento::where('estado', 'pagado')
            ->where('fecha', '>=', $hoy)
            ->count();

        // Ingresos totales del mes (pagos completados)
        $ingresosMes = Pago::where('estado', 'pagado')
            ->whereBetween('created_at', [$inicioMes, $finMes])
            ->sum('amount');

        // Total de clientes registrados
        $totalClientes = Cliente::count();

        return [
            Card::make('Eventos este mes', $eventosMes)
                ->description('Eventos pagados en ' . Carbon::now()->translatedFormat('F'))
                ->color('success')
                ->icon('heroicon-o-calendar'),

            Card::make('Próximos eventos', $proximosEventos)
                ->description('Eventos futuros confirmados')
                ->color('primary')
                ->icon('heroicon-o-clock'),

            Card::make('Ingresos este mes', number_format($ingresosMes, 2) . ' €')
                ->description('Total cobrado en ' . Carbon::now()->translatedFormat('F'))
                ->color('warning')
                ->icon('heroicon-o-currency-euro'),

            Card::make('Clientes totales', $totalClientes)
                ->description('Clientes registrados en la plataforma')
                ->color('secondary')
                ->icon('heroicon-o-user-group'),
        ];
    }
}