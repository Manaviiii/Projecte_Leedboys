<?php

namespace App\Http\Controllers\Api;

use App\Models\Residencia;
use App\Http\Resources\Api\ResidenciaResource;
use Illuminate\Http\Request;

class ResidenciaController extends ApiController
{
    /**
     * GET /api/residencias
     * Filtros: ?cliente_id=1 &estado=activa &dia_semana=lunes
     */
    public function index(Request $request)
    {
        $residencias = Residencia::with('cliente')
            ->when($request->cliente_id, fn($q, $v) => $q->where('cliente_id', $v))
            ->when($request->estado,     fn($q, $v) => $q->where('estado', $v))
            ->when($request->dia_semana, fn($q, $v) => $q->where('dia_semana', $v))
            ->orderBy('fecha_inicio', 'desc')
            ->paginate($request->input('per_page', 15));

        return $this->success(ResidenciaResource::collection($residencias)->response()->getData());
    }

    /**
     * POST /api/residencias
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'cliente_id'   => 'required|exists:clientes,id',
            'fecha_inicio' => 'required|date',
            'fecha_fin'    => 'nullable|date|after:fecha_inicio',
            'dia_semana'   => 'required|in:lunes,martes,miercoles,jueves,viernes,sabado,domingo',
            'precio'       => 'required|numeric|min:0',
            'estado'       => 'in:activa,pausada,cancelada',
        ]);

        $residencia = Residencia::create($data);
        $residencia->load('cliente');

        return $this->success(new ResidenciaResource($residencia), 'Residencia creada', 201);
    }

    /**
     * GET /api/residencias/{id}
     */
    public function show(Residencia $residencia)
    {
        $residencia->load(['cliente', 'pagos']);
        return $this->success(new ResidenciaResource($residencia));
    }

    /**
     * PUT /api/residencias/{id}
     */
    public function update(Request $request, Residencia $residencia)
    {
        $data = $request->validate([
            'fecha_inicio' => 'sometimes|date',
            'fecha_fin'    => 'nullable|date|after:fecha_inicio',
            'dia_semana'   => 'sometimes|in:lunes,martes,miercoles,jueves,viernes,sabado,domingo',
            'precio'       => 'sometimes|numeric|min:0',
            'estado'       => 'sometimes|in:activa,pausada,cancelada',
        ]);

        $residencia->update($data);
        return $this->success(new ResidenciaResource($residencia->fresh('cliente')), 'Residencia actualizada');
    }

    /**
     * DELETE /api/residencias/{id}
     */
    public function destroy(Residencia $residencia)
    {
        $residencia->update(['estado' => 'cancelada']);
        return $this->success(null, 'Residencia cancelada');
    }
}
