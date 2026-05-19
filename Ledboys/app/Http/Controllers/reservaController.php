<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Evento;
use Carbon\Carbon;

class ReservaController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | GET /api/reservas
    | GET /api/reservas?historial=true
    |
    | Devuelve los eventos pagados del usuario autenticado.
    | Por defecto muestra los futuros, con ?historial=true muestra los pasados.
    |--------------------------------------------------------------------------
    */
    public function index(Request $request)
    {
        $cliente = auth()->user()->cliente;

        if (!$cliente) {
            return response()->json(['message' => 'El usuario no tiene perfil de cliente'], 422);
        }

        $hoy      = Carbon::today();
        $historial = $request->boolean('historial', false);

        $eventos = Evento::where('cliente_id', $cliente->id)
            ->where('estado', 'pagado')
            ->when(!$historial, fn($q) => $q->where('fecha', '>=', $hoy))
            ->when($historial,  fn($q) => $q->where('fecha', '<', $hoy))
            ->with(['items' => fn($q) => $q->withPivot(['cantidad', 'precio_unitario'])])
            ->orderBy('fecha', $historial ? 'desc' : 'asc')
            ->get()
            ->map(fn($evento) => [
                'id'          => $evento->id,
                'fecha'       => $evento->fecha->format('d/m/Y'),
                'hora'        => $evento->hora,
                'ubicacion'   => $evento->ubicacion,
                'total_precio'=> $evento->total_precio,
                'estado'      => $evento->estado,
                'items'       => $evento->items->map(fn($item) => [
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

    /*
    |--------------------------------------------------------------------------
    | GET /api/reservas/{id}
    |
    | Detalle de una reserva concreta.
    |--------------------------------------------------------------------------
    */
    public function detalle($id)
    {
        $cliente = auth()->user()->cliente;

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