<?php

namespace App\Http\Controllers\Api;

use App\Models\Pago;
use App\Http\Resources\Api\PagoResource;
use Illuminate\Http\Request;

class PagoController extends ApiController
{
    /**
     * GET /api/pagos
     * Filtros: ?evento_id=1 &residencia_id=2 &estado=completado
     */
    public function index(Request $request)
    {
        $pagos = Pago::with(['evento.cliente', 'residencia.cliente'])
            ->when($request->evento_id,     fn($q, $v) => $q->where('evento_id', $v))
            ->when($request->residencia_id, fn($q, $v) => $q->where('residencia_id', $v))
            ->when($request->estado,        fn($q, $v) => $q->where('estado', $v))
            ->orderBy('created_at', 'desc')
            ->paginate($request->input('per_page', 15));

        return $this->success(PagoResource::collection($pagos)->response()->getData());
    }

    /**
     * POST /api/pagos
     * Normalmente los pagos los crea Stripe via webhook, pero este endpoint
     * permite crear pagos manuales desde el panel.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'evento_id'     => 'nullable|exists:eventos,id',
            'residencia_id' => 'nullable|exists:residencias,id',
            'amount'        => 'required|numeric|min:0',
            'estado'        => 'required|in:pendiente,completado,fallido,reembolsado',
            'detalles_items'=> 'nullable|string',
        ]);

        if (empty($data['evento_id']) && empty($data['residencia_id'])) {
            return $this->error('Debe asociarse a un evento o a una residencia', 422);
        }

        $pago = Pago::create($data);
        $pago->load(['evento.cliente', 'residencia.cliente']);

        return $this->success(new PagoResource($pago), 'Pago registrado', 201);
    }

    /**
     * GET /api/pagos/{id}
     */
    public function show(Pago $pago)
    {
        $pago->load(['evento.cliente', 'residencia.cliente']);
        return $this->success(new PagoResource($pago));
    }

    /**
     * PUT /api/pagos/{id}
     * Solo permite cambiar el estado (ej: marcar como reembolsado)
     */
    public function update(Request $request, Pago $pago)
    {
        $data = $request->validate([
            'estado' => 'required|in:pendiente,completado,fallido,reembolsado',
        ]);

        $pago->update($data);
        return $this->success(new PagoResource($pago->fresh()), 'Pago actualizado');
    }
}
