<?php

namespace App\Filament\Widgets;

use App\Models\Evento;
use Saade\FilamentFullCalendar\Widgets\FullCalendarWidget;

class CalendarWidget extends FullCalendarWidget
{
    /**
     * Esta propiedad hace que el calendario ocupe todo el ancho de la pantalla
     */
    protected int | string | array $columnSpan = 'full';

    /**
     * Aquí es donde recuperamos los datos de tus eventos
     */
    public function getViewData(): array
{
    return [
        [
            'id' => 'prueba-1',
            'title' => 'SI VES ESTO, EL JS CARGA BIEN',
            'start' => date('Y-m-d'), // Fecha de hoy en texto
            'backgroundColor' => 'red',
        ],
    ];
}

    /**
     * Configuración del calendario (idioma, primer día, etc.)
     */
    public function getConfig(): array
    {
        return [
            'firstDay' => 1, // Empezar en Lunes
            'locale' => 'es', // Idioma en español
            'headerToolbar' => [
                'left' => 'prev,next today',
                'center' => 'title',
                'right' => 'dayGridMonth,timeGridWeek',
            ],
            'buttonText' => [
                'today' => 'Hoy',
                'month' => 'Mes',
                'week' => 'Semana',
            ],
        ];
    }
}