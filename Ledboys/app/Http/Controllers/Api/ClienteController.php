<?php

namespace App\Http\Controllers\Api;

use App\Models\Cliente;
use App\Http\Resources\Api\ClienteResource;
use Illuminate\Http\Request;

class ClienteController extends ApiController
{
    /**
     * GET /api/clientes
     * Lista todos los clientes con conteo de eventos y residencias.
     */
    public function index(Request $request)
    {
        $clientes = Cliente::withCount(['eventos', 'residencias'])
            ->when($request->search, fn($q, $s) => $q->where('nombre', 'like', "%{$s}%")
                ->orWhere('email', 'like', "%{$s}%"))
            ->orderBy('nombre')
            ->paginate($request->input('per_page', 15));

        return $this->success(ClienteResource::collection($clientes)->response()->getData());
    }

    /**
     * POST /api/clientes
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre'   => 'required|string|max:255',
            'email'    => 'nullable|email|unique:clientes,email',
            'telefono' => 'nullable|string|max:20',
        ]);

        $cliente = Cliente::create($data);

        return $this->success(new ClienteResource($cliente), 'Cliente creado', 201);
    }

    /**
     * GET /api/clientes/{id}
     * Incluye sus eventos y residencias.
     */
    public function show(Cliente $cliente)
    {
        $cliente->loadCount(['eventos', 'residencias'])
                ->load(['eventos', 'residencias']);

        return $this->success(new ClienteResource($cliente));
    }

    /**
     * PUT /api/clientes/{id}
     */
    public function update(Request $request, Cliente $cliente)
    {
        $data = $request->validate([
            'nombre'   => 'sometimes|required|string|max:255',
            'email'    => 'nullable|email|unique:clientes,email,' . $cliente->id,
            'telefono' => 'nullable|string|max:20',
        ]);

        $cliente->update($data);

        return $this->success(new ClienteResource($cliente), 'Cliente actualizado');
    }

    /**
     * DELETE /api/clientes/{id}
     */
    public function destroy(Cliente $cliente)
    {
        $cliente->delete();

        return $this->success(null, 'Cliente eliminado');
    }
}
