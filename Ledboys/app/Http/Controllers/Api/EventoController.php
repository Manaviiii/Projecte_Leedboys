<?php

namespace App\Http\Controllers\Api;

use App\Models\Evento;
use App\Models\Item;
use App\Http\Resources\Api\EventoResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EventoController extends ApiController
{
    /**
     * GET /api/eventos
     * Filtros opcionales: ?cliente_id=1 &estado=pagado &fecha_desde=2024-01-01 &fecha_hasta=2024-12-31
     */
    public function index(Request $request)
    {
        $eventos = Evento::with(['cliente', 'items'])
            ->when($request->cliente_id, fn($q, $v) => $q->where('cliente_id', $v))
            ->when($request->estado,     fn($q, $v) => $q->where('estado', $v))
            ->when($request->fecha_desde, fn($q, $v) => $q->whereDate('fecha', '>=', $v))
            ->when($request->fecha_hasta, fn($q, $v) => $q->whereDate('fecha', '<=', $v))
            ->orderBy('fecha', 'desc')
            ->paginate($request->input('per_page', 15));

        return $this->success(EventoResource::collection($eventos)->response()->getData());
    }

    /**
     * POST /api/eventos
     * Body: { cliente_id, fecha, estado, items: [{item_id, cantidad, precio_unitario}] }
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'cliente_id'               => 'required|exists:clientes,id',
            'fecha'                    => 'required|date',
            'estado'                   => 'in:borrador,reservado,pagado',
            'items'                    => 'array',
            'items.*.item_id'          => 'required|exists:items,id',
            'items.*.cantidad'         => 'required|integer|min:1',
            'items.*.precio_unitario'  => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();
        try {
            $total = collect($data['items'] ?? [])->sum(
                fn($i) => $i['cantidad'] * $i['precio_unitario']
            );

            $evento = Evento::create([
                'cliente_id'   => $data['cliente_id'],
                'fecha'        => $data['fecha'],
                'estado'       => $data['estado'] ?? 'borrador',
                'total_precio' => $total,
            ]);

            foreach ($data['items'] ?? [] as $item) {
                $evento->items()->attach($item['item_id'], [
                    'cantidad'        => $item['cantidad'],
                    'precio_unitario' => $item['precio_unitario'],
                ]);
            }

            DB::commit();
            $evento->load(['cliente', 'items']);
            return $this->success(new EventoResource($evento), 'Evento creado', 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return $this->error('Error al crear el evento: ' . $e->getMessage(), 500);
        }
    }

    /**
     * GET /api/eventos/{id}
     */
    public function show(Evento $evento)
    {
        $evento->load(['cliente', 'items', 'pagos']);
        return $this->success(new EventoResource($evento));
    }

    /**
     * PUT /api/eventos/{id}
     * Permite actualizar datos generales y reemplazar los items del evento.
     */
    public function update(Request $request, Evento $evento)
    {
        $data = $request->validate([
            'cliente_id'              => 'sometimes|exists:clientes,id',
            'fecha'                   => 'sometimes|date',
            'estado'                  => 'sometimes|in:borrador,reservado,pagado',
            'items'                   => 'sometimes|array',
            'items.*.item_id'         => 'required_with:items|exists:items,id',
            'items.*.cantidad'        => 'required_with:items|integer|min:1',
            'items.*.precio_unitario' => 'required_with:items|numeric|min:0',
        ]);

        DB::beginTransaction();
        try {
            if (isset($data['items'])) {
                // Sincronizamos los items (reemplaza los anteriores)
                $sync = [];
                foreach ($data['items'] as $item) {
                    $sync[$item['item_id']] = [
                        'cantidad'        => $item['cantidad'],
                        'precio_unitario' => $item['precio_unitario'],
                    ];
                }
                $evento->items()->sync($sync);

                // Recalculamos el total
                $data['total_precio'] = collect($data['items'])->sum(
                    fn($i) => $i['cantidad'] * $i['precio_unitario']
                );

                unset($data['items']);
            }

            $evento->update($data);

            DB::commit();
            $evento->load(['cliente', 'items']);
            return $this->success(new EventoResource($evento), 'Evento actualizado');

        } catch (\Exception $e) {
            DB::rollBack();
            return $this->error('Error al actualizar: ' . $e->getMessage(), 500);
        }
    }

    /**
     * DELETE /api/eventos/{id}
     */
    public function destroy(Evento $evento)
    {
        $evento->items()->detach();
        $evento->delete();
        return $this->success(null, 'Evento eliminado');
    }
}
