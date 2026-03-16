<?php
// app/Http/Controllers/PaymentController.php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\PaymentIntent;
use Stripe\Refund;
use App\Models\Item;
use App\Models\Pago;

class PaymentController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | POST /api/pagos/crear-intento
    |
    | Crea un PaymentIntent en Stripe y guarda el pago en estado "pendiente".
    | El frontend usa el clientSecret para montar el formulario de Stripe.js.
    |
    | Body JSON:
    |   items        array (requerido) — IDs de los items a comprar  [1, 3, 5]
    |   evento_id    int   (opcional)
    |   residencia_id int  (opcional)
    |--------------------------------------------------------------------------
    */
    public function crearIntento(Request $request)
    {
        $request->validate([
            'items'         => 'required|array|min:1',
            'items.*'       => 'integer|exists:items,id',
            'evento_id'     => 'nullable|exists:eventos,id',
            'residencia_id' => 'nullable|exists:residencias,id',
        ]);

        Stripe::setApiKey(env('STRIPE_SECRET'));

        // Sacar items de la DB y calcular total
        $itemsDB       = Item::whereIn('id', $request->items)->get();
        $total         = $itemsDB->sum('precio');           // en euros (decimal)
        $nombresItems  = $itemsDB->pluck('nombre')->implode(', ');

        // Crear PaymentIntent en Stripe (importe en céntimos)
        $intent = PaymentIntent::create([
            'amount'                    => (int) round($total * 100),
            'currency'                  => 'eur',
            'automatic_payment_methods' => ['enabled' => true],
            'metadata'                  => [
                'user_id'    => auth()->id(),
                'items'      => implode(',', $request->items),
            ],
        ]);

        // Guardar pago en DB en estado pendiente
        $pago = Pago::create([
            'user_id'                  => auth()->id(),
            'evento_id'                => $request->evento_id,
            'residencia_id'            => $request->residencia_id,
            'amount'                   => $total,
            'detalles_items'           => $nombresItems,
            'estado'                   => 'pendiente',
            'stripe_payment_intent_id' => $intent->id,
        ]);

        return response()->json([
            'clientSecret' => $intent->client_secret,
            'pago_id'      => $pago->id,
            'total'        => $total,
            'items'        => $itemsDB->map(fn($i) => [
                'id'     => $i->id,
                'nombre' => $i->nombre,
                'precio' => $i->precio,
            ]),
        ], 201);
    }

    /*
    |--------------------------------------------------------------------------
    | POST /api/pagos/{id}/confirmar
    |
    | El frontend llama a este endpoint tras recibir confirmación de Stripe.js.
    | Doble seguridad: verificamos el estado real en Stripe antes de marcar pagado.
    |
    | Params:
    |   id  — ID interno del pago (pago_id devuelto por crearIntento)
    |--------------------------------------------------------------------------
    */
    public function confirmarPago($id)
    {
        Stripe::setApiKey(env('STRIPE_SECRET'));

        $pago = Pago::findOrFail($id);

        // Solo el dueño del pago puede confirmarlo
        if ($pago->user_id !== auth()->id()) {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        // Verificar estado real en Stripe (no fiarse solo del frontend)
        $intent = PaymentIntent::retrieve($pago->stripe_payment_intent_id);

        if ($intent->status === 'succeeded') {
            $pago->update(['estado' => 'pagado']);

            return response()->json([
                'message' => 'Pago confirmado correctamente',
                'pago'    => $pago,
            ]);
        }

        // Si Stripe dice que no ha ido bien
        $pago->update(['estado' => 'fallido']);

        return response()->json([
            'message'       => 'El pago no se ha completado en Stripe',
            'stripe_status' => $intent->status,
        ], 422);
    }

    /*
    |--------------------------------------------------------------------------
    | GET /api/pagos
    |
    | Devuelve el historial de pagos del usuario autenticado.
    | Soporta paginación: ?per_page=10
    |--------------------------------------------------------------------------
    */
    public function historial(Request $request)
    {
        $perPage = $request->query('per_page', 10);

        $pagos = Pago::where('user_id', auth()->id())
                     ->latest()
                     ->paginate($perPage);

        return response()->json($pagos);
    }

    /*
    |--------------------------------------------------------------------------
    | GET /api/pagos/{id}
    |
    | Detalle de un pago concreto.
    | Solo accesible por el propio usuario.
    |--------------------------------------------------------------------------
    */
    public function detalle($id)
    {
        $pago = Pago::findOrFail($id);

        if ($pago->user_id !== auth()->id()) {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        return response()->json($pago);
    }

    /*
    |--------------------------------------------------------------------------
    | POST /api/pagos/{id}/reembolso
    |
    | Solicita un reembolso total a Stripe y actualiza el estado en DB.
    | Solo se puede reembolsar un pago en estado "pagado".
    |
    | Body JSON (opcional):
    |   motivo  string — Razón del reembolso (se guarda en metadata de Stripe)
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
                'message' => 'Solo se pueden reembolsar pagos en estado "pagado"',
                'estado_actual' => $pago->estado,
            ], 422);
        }

        // Crear reembolso en Stripe (reembolso total)
        $refund = Refund::create([
            'payment_intent' => $pago->stripe_payment_intent_id,
            'metadata'       => [
                'motivo'  => $request->input('motivo', 'Sin motivo especificado'),
                'user_id' => auth()->id(),
            ],
        ]);

        $pago->update(['estado' => 'reembolsado']);

        return response()->json([
            'message'   => 'Reembolso procesado correctamente',
            'refund_id' => $refund->id,
            'pago'      => $pago,
        ]);
    }
}