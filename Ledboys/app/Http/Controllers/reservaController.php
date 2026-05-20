<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Evento;
use Carbon\Carbon;

/**
 * Controlador de reservas del usuario autenticado.
 * Una reserva es un evento en estado 'pagado' vinculado al cliente del usuario.
 * Permite listar reservas futuras (por defecto) o pasadas (con ?historial=true),
 * y ver el detalle de una reserva concreta.
 */
class ReservaController extends Controller
{
    /**
     * Devuelve las reservas del usuario autenticado.
     * 
     * Por defecto muestra los eventos futuros ordenados de más próximo a más lejano.
     * Con el parámetro ?historial=true muestra los eventos pasados del más reciente al más antiguo.
     * Solo se devuelven eventos en estado 'pagado'.
     * 
     * @route GET /api/reservas
     * @route GET /api/reservas?historial=true
     * @param Request $request — parámetro opcional 'historial' (boolean)
     * @return JsonResponse tipo, total y listado de eventos con sus items
     */
    public function index(Request $request)
    {
        $cliente = auth()->user()->cliente;

        if (!$cliente) {
            return response()->json(['message' => 'El usuario no tiene perfil de cliente'], 422);
        }

        $hoy      = Carbon::today();
        $historial = $request->boolean('historial', false); // false = futuras, true = pasadas

        $eventos = Evento::where('cliente_id', $cliente->id)
            ->where('estado', 'pagado')
            // Filtrar por fecha según si se piden futuras o pasadas
            ->when(!$historial, fn($q) => $q->where('fecha', '>=', $hoy))
            ->when($historial,  fn($q) => $q->where('fecha', '<', $hoy))
            // Cargar los items con los datos de la tabla pivote evento_items
            ->with(['items' => fn($q) => $q->withPivot(['cantidad', 'precio_unitario'])])
            // Futuras: de más próxima a más lejana. Pasadas: de más reciente a más antigua
            ->orderBy('fecha', $historial ? 'desc' : 'asc')
            ->get()
            ->map(fn($evento) => [
                'id'           => $evento->id,
                'fecha'        => $evento->fecha->format('d/m/Y'),
                'hora'         => $evento->hora,
                'ubicacion'    => $evento->ubicacion,
                'total_precio' => $evento->total_precio,
                'estado'       => $evento->estado,
                'items'        => $evento->items->map(fn($item) => [
                    'id'              => $item->id,
                    'nombre'          => $item->nombre,
                    'tipo'            => $item->tipo,
                    'cantidad'        => $item->pivot->cantidad,
                    'precio_unitario' => $item->pivot->precio_unitario,
                ]),
            ]);

        return response()->json([
            'tipo'    => $historial ? 'pasadas' : 'futuras',
            'total'   => $eventos->count(),
            'eventos' => $eventos,
        ]);
    }

    /**
     * Devuelve el detalle de una reserva concreta del usuario autenticado.
     * 
     * Solo devuelve la reserva si pertenece al cliente del usuario autenticado
     * y está en estado 'pagado'. Si no cumple estas condiciones, devuelve 404.
     * 
     * @route GET /api/reservas/{id}
     * @param int $id — ID del evento
     * @return JsonResponse datos completos del evento con sus items | 404 si no existe o no pertenece al usuario
     */
    public function detalle($id)
    {
        $cliente = auth()->user()->cliente;

        // Buscar el evento filtrando por cliente y estado para que un usuario
        // no pueda ver las reservas de otro aunque conozca el ID
        $evento = Evento::where('cliente_id', $cliente->id)
            ->where('estado', 'pagado')
            ->with(['items' => fn($q) => $q->withPivot(['cantidad', 'precio_unitario'])])
            ->findOrFail($id);

        return response()->json([
            'id'           => $evento->id,
            'fecha'        => $evento->fecha->format('d/m/Y'),
            'hora'         => $evento->hora,
            'ubicacion'    => $evento->ubicacion,
            'total_precio' => $evento->total_precio,
            'estado'       => $evento->estado,
            'items'        => $evento->items->map(fn($item) => [
                'id'              => $item->id,
                'nombre'          => $item->nombre,
                'tipo'            => $item->tipo,
                'cantidad'        => $item->pivot->cantidad,
                'precio_unitario' => $item->pivot->precio_unitario,
            ]),
        ]);
    }
}