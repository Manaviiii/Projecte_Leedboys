<?php

namespace App\Filament\Widgets;

use App\Models\Pago;
use Filament\Widgets\BarChartWidget;
use Carbon\Carbon;

/**
 * Widget de gráfico de barras con los ingresos de los últimos 6 meses.
 * Solo cuenta pagos en estado 'pagado'.
 * Visible en el dashboard principal del admin.
 */
class IngresosChartWidget extends BarChartWidget
{
    protected static ?string $heading = 'Ingresos últimos 6 meses';

    // Ocupa todo el ancho
    protected int|string|array $columnSpan = 'full';

    protected function getData(): array
    {
        $meses  = [];
        $totales = [];

        // Generar los últimos 6 meses
        for ($i = 5; $i >= 0; $i--) {
            $mes = Carbon::now()->subMonths($i);

            $meses[] = $mes->translatedFormat('M Y');

            $totales[] = Pago::where('estado', 'pagado')
                ->whereYear('created_at', $mes->year)
                ->whereMonth('created_at', $mes->month)
                ->sum('amount');
        }

        return [
            'datasets' => [
                [
                    'label'           => 'Ingresos (€)',
                    'data'            => $totales,
                    'backgroundColor' => '#c9a84c', // color dorado de la marca
                ],
            ],
            'labels' => $meses,
        ];
    }
}