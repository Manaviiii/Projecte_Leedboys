<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\PaymentIntent;
use Stripe\Refund;
use App\Models\Item;
use App\Models\Pago;
use App\Models\Evento;
use App\Models\EventoItem;

class PaymentController extends Controller
{
    // IVA incluido en los precios
    const IVA = 0.21;

    /*
    |--------------------------------------------------------------------------
    | Calcula el desglose de IVA dado un total con IVA incluido
    |--------------------------------------------------------------------------
    */
    private function calcularDesglose(float $totalConIva): array
    {
        $baseImponible = round($totalConIva / (1 + self::IVA), 2);
        $cuotaIva      = round($totalConIva - $baseImponible, 2);

        return [
            'base_imponible' => $baseImponible,
            'iva_porcentaje' => self::IVA * 100, // 21
            'cuota_iva'      => $cuotaIva,
            'total'          => round($totalConIva, 2),
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | POST /api/pagos/crear-intento
    |--------------------------------------------------------------------------
    */
    public function crearIntento(Request $request)
    {
        $request->validate([
            'fecha'                  => 'required|date|after_or_equal:today',
            'items'                  => 'required|array|min:1',
            'items.*'                => 'integer|exists:items,id',
            'residencia_id'          => 'nullable|exists:residencias,id',
            'nombre_facturacion'     => 'nullable|string|max:255',
            'apellidos_facturacion'  => 'nullable|string|max:255',
            'dni'                    => 'nullable|string|max:20',
            'telefono_facturacion'   => 'nullable|string|max:20',
            'direccion'              => 'nullable|string|max:255',
            'codigo_postal'          => 'nullable|string|max:10',
        ]);

        $cliente = auth()->user()->cliente;
        if (!$cliente) {
            return response()->json(['message' => 'El usuario no tiene perfil de cliente'], 422);
        }

        Stripe::setApiKey(env('STRIPE_SECRET'));

        // Sacar items de la DB y calcular total
        $itemsDB = Item::whereIn('id', $request->items)->get();
        $total   = collect($request->items)->sum(function ($id) use ($itemsDB) {
            return $itemsDB->firstWhere('id', $id)?->precio ?? 0;
        });
        $nombresItems = $itemsDB->pluck('nombre')->implode(', ');

        // Desglose IVA
        $desglose = $this->calcularDesglose($total);

        // Crear el evento
        $evento = Evento::create([
            'cliente_id'   => $cliente->id,
            'fecha'        => $request->fecha,
            'total_precio' => $total,
            'estado'       => 'borrador',
        ]);

        // Crear los evento_items
        foreach ($request->items as $itemId) {
            $item = $itemsDB->firstWhere('id', $itemId);
            EventoItem::create([
                'evento_id'       => $evento->id,
                'item_id'         => $itemId,
                'cantidad'        => 1,
                'precio_unitario' => $item->precio,
            ]);
        }

        // Crear PaymentIntent en Stripe
        $intent = PaymentIntent::create([
            'amount'                    => (int) round($total * 100),
            'currency'                  => 'eur',
            'automatic_payment_methods' => ['enabled' => true],
            'metadata'                  => [
                'user_id'        => auth()->id(),
                'cliente_id'     => $cliente->id,
                'evento_id'      => $evento->id,
                'items'          => implode(',', $request->items),
                'base_imponible' => $desglose['base_imponible'],
                'cuota_iva'      => $desglose['cuota_iva'],
            ],
        ]);

        // Guardar pago en DB
        $pago = Pago::create([
            'user_id'                  => auth()->id(),
            'evento_id'                => $evento->id,
            'residencia_id'            => $request->residencia_id,
            'amount'                   => $total,
            'detalles_items'           => $nombresItems,
            'estado'                   => 'pendiente',
            'stripe_payment_intent_id' => $intent->id,
            'nombre_facturacion'       => $request->nombre_facturacion,
            'apellidos_facturacion'    => $request->apellidos_facturacion,
            'dni'                      => $request->dni,
            'telefono_facturacion'     => $request->telefono_facturacion,
            'direccion'                => $request->direccion,
            'codigo_postal'            => $request->codigo_postal,
        ]);

        return response()->json([
            'clientSecret' => $intent->client_secret,
            'pago_id'      => $pago->id,
            'evento_id'    => $evento->id,
            'items'        => $itemsDB->map(fn($i) => [
                'id'             => $i->id,
                'nombre'         => $i->nombre,
                'precio_con_iva' => $i->precio,
                'base_imponible' => round($i->precio / (1 + self::IVA), 2),
                'cuota_iva'      => round($i->precio - ($i->precio / (1 + self::IVA)), 2),
            ]),
            'desglose'     => $desglose,
        ], 201);
    }

    /*
    |--------------------------------------------------------------------------
    | POST /api/pagos/{id}/confirmar
    |--------------------------------------------------------------------------
    */
    public function confirmarPago($id)
    {
        Stripe::setApiKey(env('STRIPE_SECRET'));

        $pago = Pago::findOrFail($id);

        if ($pago->user_id !== auth()->id()) {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        $intent = PaymentIntent::retrieve($pago->stripe_payment_intent_id);

        if ($intent->status === 'succeeded') {
            $pago->update(['estado' => 'pagado']);

            if ($pago->evento_id) {
                Evento::where('id', $pago->evento_id)->update(['estado' => 'pagado']);
            }

            // Devolver también el desglose al confirmar
            $desglose = $this->calcularDesglose((float) $pago->amount);

            return response()->json([
                'message'  => 'Pago confirmado correctamente',
                'pago'     => $pago,
                'desglose' => $desglose,
            ]);
        }

        $pago->update(['estado' => 'fallido']);

        if ($pago->evento_id) {
            Evento::where('id', $pago->evento_id)->update(['estado' => 'cancelado']);
        }

        return response()->json([
            'message'       => 'El pago no se ha completado en Stripe',
            'stripe_status' => $intent->status,
        ], 422);
    }

    /*
    |--------------------------------------------------------------------------
    | GET /api/pagos
    |--------------------------------------------------------------------------
    */
    public function historial(Request $request)
    {
        $perPage = $request->query('per_page', 10);

        $pagos = Pago::where('user_id', auth()->id())
                     ->with('evento')
                     ->latest()
                     ->paginate($perPage);

        return response()->json($pagos);
    }

    /*
    |--------------------------------------------------------------------------
    | GET /api/pagos/{id}
    |--------------------------------------------------------------------------
    */
    public function detalle($id)
    {
        $pago = Pago::with('evento.items')->findOrFail($id);

        if ($pago->user_id !== auth()->id()) {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        $desglose = $this->calcularDesglose((float) $pago->amount);

        return response()->json([
            'pago'     => $pago,
            'desglose' => $desglose,
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | POST /api/pagos/{id}/reembolso
    |--------------------------------------------------------------------------
    */
    public function reembolso(Request $request, $id)
    {
        Stripe::setApiKey(env('STRIPE_SECRET'));

        $pago = Pago::findOrFail($id);

        if ($pago->user_id !== auth()->id()) {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        if ($pago->estado !== 'pagado') {
            return response()->json([
                'message'       => 'Solo se pueden reembolsar pagos en estado "pagado"',
                'estado_actual' => $pago->estado,
            ], 422);
        }

        $refund = Refund::create([
            'payment_intent' => $pago->stripe_payment_intent_id,
            'metadata'       => [
                'motivo'  => $request->input('motivo', 'Sin motivo especificado'),
                'user_id' => auth()->id(),
            ],
        ]);

        $pago->update(['estado' => 'reembolsado']);

        if ($pago->evento_id) {
            Evento::where('id', $pago->evento_id)->update(['estado' => 'cancelado']);
        }

        return response()->json([
            'message'   => 'Reembolso procesado correctamente',
            'refund_id' => $refund->id,
            'pago'      => $pago,
        ]);
    }
}