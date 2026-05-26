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
use App\Mail\FacturaMail;
use Illuminate\Support\Facades\Mail;

/**
 * Controlador de pagos con Stripe.
 * Gestiona todo el ciclo de vida de un pago:
 * creación del intento, confirmación, historial, detalle y reembolso.
 * Al confirmar un pago, crea automáticamente el evento y envía la factura por email.
 */
class PaymentController extends Controller
{
    /** Porcentaje de IVA incluido en los precios (21%) */
    const IVA = 0.21;

    /**
     * Calcula el desglose de IVA a partir de un precio que ya lo incluye.
     */
    private function calcularDesglose(float $totalConIva): array
    {
        $baseImponible = round($totalConIva / (1 + self::IVA), 2);
        $cuotaIva      = round($totalConIva - $baseImponible, 2);

        return [
            'base_imponible' => $baseImponible,
            'iva_porcentaje' => self::IVA * 100,
            'cuota_iva'      => $cuotaIva,
            'total'          => round($totalConIva, 2),
        ];
    }

    /**
     * Crea un intento de pago en Stripe y registra el evento y el pago en la base de datos.
     *
     * @route POST /api/pagos/crear-intento
     */
    public function crearIntento(Request $request)
    {
        // Validar todos los campos del formulario de pago
        $request->validate([
            'fecha'                  => 'required|date|after_or_equal:today',
            'hora'                   => 'nullable|date_format:H:i',
            'ubicacion'              => 'nullable|string|max:255',
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

        // Verificar que el usuario autenticado tiene perfil de cliente
        $cliente = auth()->user()->cliente;
        if (!$cliente) {
            return response()->json(['message' => 'El usuario no tiene perfil de cliente'], 422);
        }

        Stripe::setApiKey(env('STRIPE_SECRET'));

        // Obtener los items de la BD y calcular el total sumando los precios
        $itemsDB      = Item::whereIn('id', $request->items)->get();
        $total        = collect($request->items)->sum(fn($id) => $itemsDB->firstWhere('id', $id)?->precio ?? 0);
        $nombresItems = $itemsDB->pluck('nombre')->implode(', ');
        $desglose     = $this->calcularDesglose($total);

        // Validar stock por fecha — solo para trajes
        // Comprueba que no se supere el stock_total del traje para esa fecha
        foreach ($request->items as $itemId) {
            $item = $itemsDB->firstWhere('id', $itemId);
            if (!$item || $item->tipo !== 'traje') continue;

            $traje = $item->traje;
            if (!$traje) continue;

            // Contar cuántas unidades de este traje ya están reservadas para esa fecha
            $stockOcupado = EventoItem::whereHas('evento', fn($q) =>
                $q->whereDate('fecha', $request->fecha)
                  ->whereIn('estado', ['pagado', 'pendiente', 'borrador'])
            )->where('item_id', $itemId)->sum('cantidad');

            // Contar cuántas veces aparece este traje en el pedido actual
            $cantidadPedido = collect($request->items)->filter(fn($id) => $id === $itemId)->count();

            if ($stockOcupado + $cantidadPedido > $traje->stock_total) {
                return response()->json([
                    'message' => "No hay stock disponible del traje '{$item->nombre}' para la fecha {$request->fecha}. Stock disponible: " . max(0, $traje->stock_total - $stockOcupado),
                ], 422);
            }
        }

        // Crear el evento en estado 'borrador'
        $evento = Evento::create([
            'cliente_id'   => $cliente->id,
            'fecha'        => $request->fecha,
            'hora'         => $request->hora,
            'ubicacion'    => $request->ubicacion,
            'total_precio' => $total,
            'estado'       => 'borrador',
        ]);

        // Guardar cada item contratado en evento_items
        foreach ($request->items as $itemId) {
            $item = $itemsDB->firstWhere('id', $itemId);
            EventoItem::create([
                'evento_id'       => $evento->id,
                'item_id'         => $itemId,
                'cantidad'        => 1,
                'precio_unitario' => $item->precio,
            ]);
        }

        // Crear el PaymentIntent en Stripe con el importe en céntimos
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

        // Registrar el pago en estado 'pendiente'
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
            'desglose' => $desglose,
        ], 201);
    }

    /**
     * Confirma un pago verificando su estado real en Stripe.
     *
     * @route POST /api/pagos/{id}/confirmar
     */
    public function confirmarPago($id)
    {
        Stripe::setApiKey(env('STRIPE_SECRET'));

        $pago = Pago::with('evento')->findOrFail($id);

        if ($pago->user_id !== auth()->id()) {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        $intent = PaymentIntent::retrieve($pago->stripe_payment_intent_id);

        if ($intent->status === 'succeeded') {
            $pago->update(['estado' => 'pagado']);

            if ($pago->evento_id) {
                Evento::where('id', $pago->evento_id)->update(['estado' => 'pagado']);
                $pago->refresh();
            }

            $desglose = $this->calcularDesglose((float) $pago->amount);

            // Enviar la factura en PDF al email del usuario
            $user = $pago->user;
            if ($user && $user->email) {
                try {
                    Mail::to($user->email)->send(new FacturaMail($pago, $desglose));
                    \Log::info('Email factura enviado a: ' . $user->email);
                } catch (\Exception $e) {
                    \Log::error('Error enviando email factura: ' . $e->getMessage());
                }
            }

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

    /**
     * Devuelve el historial de pagos del usuario autenticado paginado.
     *
     * @route GET /api/pagos
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

    /**
     * Devuelve el detalle de un pago concreto con su desglose de IVA.
     *
     * @route GET /api/pagos/{id}
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

    /**
     * Solicita un reembolso total del pago a Stripe y cancela el evento asociado.
     *
     * @route POST /api/pagos/{id}/reembolso
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