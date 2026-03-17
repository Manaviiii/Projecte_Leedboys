<?php

namespace App\Http\Controllers\Api;

use App\Models\Item;
use App\Models\ItemTraje;
use App\Models\ItemAccesorio;
use App\Models\ItemPack;
use App\Http\Resources\Api\ItemResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ItemController extends ApiController
{
    /**
     * GET /api/items
     * Filtros opcionales: ?tipo=traje|accesorio|pack &activo=1 &search=nombre
     */
    public function index(Request $request)
    {
        $items = Item::with(['traje', 'accesorio', 'pack'])
            ->when($request->tipo,   fn($q, $v) => $q->where('tipo', $v))
            ->when($request->activo !== null, fn($q) => $q->where('activo', (bool)$request->activo))
            ->when($request->search, fn($q, $s) => $q->where('nombre', 'like', "%{$s}%"))
            ->orderBy('nombre')
            ->paginate($request->input('per_page', 20));

        return $this->success(ItemResource::collection($items)->response()->getData());
    }

    /**
     * GET /api/items/{id}
     */
    public function show(Item $item)
    {
        $item->load(['traje', 'accesorio', 'pack']);
        return $this->success(new ItemResource($item));
    }

    /**
     * POST /api/items
     * Crea un item base + su subtipo (traje/accesorio/pack) en la misma petición.
     * Body: { nombre, tipo, precio, descripcion?, imagen?, activo?, ...campos del subtipo }
     *
     * Para traje:     tipo_traje, genero, stock_total
     * Para accesorio: stock_total
     * Para pack:      numero_zancudos
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre'      => 'required|string|max:255',
            'tipo'        => 'required|in:traje,accesorio,pack',
            'precio'      => 'required|numeric|min:0',
            'descripcion' => 'nullable|string',
            'activo'      => 'boolean',
            // Traje
            'tipo_traje'  => 'required_if:tipo,traje|in:zancos,sin_zancos',
            'genero'      => 'required_if:tipo,traje|in:chico,chica,unisex',
            'stock_total' => 'required_if:tipo,traje|required_if:tipo,accesorio|integer|min:0',
            // Pack
            'numero_zancudos' => 'required_if:tipo,pack|integer|min:1',
        ]);

        DB::beginTransaction();
        try {
            $item = Item::create([
                'nombre'      => $data['nombre'],
                'tipo'        => $data['tipo'],
                'precio'      => $data['precio'],
                'descripcion' => $data['descripcion'] ?? null,
                'activo'      => $data['activo'] ?? true,
            ]);

            match ($data['tipo']) {
                'traje' => ItemTraje::create([
                    'item_id'     => $item->id,
                    'tipo_traje'  => $data['tipo_traje'],
                    'genero'      => $data['genero'],
                    'stock_total' => $data['stock_total'],
                ]),
                'accesorio' => ItemAccesorio::create([
                    'item_id'     => $item->id,
                    'stock_total' => $data['stock_total'],
                ]),
                'pack' => ItemPack::create([
                    'item_id'         => $item->id,
                    'numero_zancudos' => $data['numero_zancudos'],
                ]),
            };

            DB::commit();
            $item->load(['traje', 'accesorio', 'pack']);
            return $this->success(new ItemResource($item), 'Item creado', 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return $this->error('Error al crear el item: ' . $e->getMessage(), 500);
        }
    }

    /**
     * PUT /api/items/{id}
     */
    public function update(Request $request, Item $item)
    {
        $data = $request->validate([
            'nombre'      => 'sometimes|string|max:255',
            'precio'      => 'sometimes|numeric|min:0',
            'descripcion' => 'nullable|string',
            'activo'      => 'boolean',
            // Subtipo
            'tipo_traje'      => 'sometimes|in:zancos,sin_zancos',
            'genero'          => 'sometimes|in:chico,chica,unisex',
            'stock_total'     => 'sometimes|integer|min:0',
            'numero_zancudos' => 'sometimes|integer|min:1',
        ]);

        DB::beginTransaction();
        try {
            $item->update(array_intersect_key($data, array_flip(['nombre', 'precio', 'descripcion', 'activo'])));

            match ($item->tipo) {
                'traje'     => $item->traje?->update(array_intersect_key($data, array_flip(['tipo_traje', 'genero', 'stock_total']))),
                'accesorio' => $item->accesorio?->update(array_intersect_key($data, array_flip(['stock_total']))),
                'pack'      => $item->pack?->update(array_intersect_key($data, array_flip(['numero_zancudos']))),
                default     => null,
            };

            DB::commit();
            $item->load(['traje', 'accesorio', 'pack']);
            return $this->success(new ItemResource($item), 'Item actualizado');

        } catch (\Exception $e) {
            DB::rollBack();
            return $this->error('Error al actualizar: ' . $e->getMessage(), 500);
        }
    }

    /**
     * DELETE /api/items/{id}
     */
    public function destroy(Item $item)
    {
        // Soft-delete lógico: marcamos como inactivo en vez de borrar
        $item->update(['activo' => false]);
        return $this->success(null, 'Item desactivado');
    }
}
