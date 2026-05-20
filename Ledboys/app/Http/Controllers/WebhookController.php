<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Webhook;
use App\Models\Pago;

/**
 * Controlador del webhook de Stripe.
 * 
 * Stripe llama a este endpoint automáticamente cuando un pago cambia de estado.
 * No usa autenticación Bearer — en su lugar verifica la firma HMAC de Stripe
 * para garantizar que la petición viene realmente de Stripe y no de un tercero.
 * 
 * Configuración en Stripe Dashboard:
 *   https://dashboard.stripe.com/test/webhooks
 *   → Añadir endpoint → tu-dominio.com/stripe/webhook
 *   → Eventos a escuchar: payment_intent.succeeded, payment_intent.payment_failed
 *   → Copiar el "Signing secret" y guardarlo en .env como STRIPE_WEBHOOK_SECRET
 */
class WebhookController extends Controller
{
    /**
     * Recibe y procesa los eventos enviados por Stripe.
     * 
     * Verifica la autenticidad del webhook con la firma HMAC,
     * luego delega el procesamiento al método correspondiente
     * según el tipo de evento recibido.
     * Stripe espera siempre un 200, si devolvemos otro código reintentará el envío.
     * 
     * @route POST /stripe/webhook
     * @param Request $request
     * @return JsonResponse 200 si se procesó correctamente | 400 si la firma es inválida
     */
    public function handle(Request $request)
    {
        $payload   = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');
        $secret    = env('STRIPE_WEBHOOK_SECRET');

        // Verificar que el webhook viene realmente de Stripe usando la firma HMAC
        try {
            $event = Webhook::constructEvent($payload, $sigHeader, $secret);
        } catch (\Stripe\Exception\SignatureVerificationException $e) {
            return response()->json(['message' => 'Firma inválida'], 400);
        }

        // Redirigir al método correspondiente según el tipo de evento
        // Los eventos no contemplados se ignoran silenciosamente
        match ($event->type) {
            'payment_intent.succeeded'      => $this->pagoExitoso($event->data->object),
            'payment_intent.payment_failed' => $this->pagoFallido($event->data->object),
            default                         => null,
        };

        // Siempre devolver 200 para que Stripe no reintente el envío
        return response()->json(['received' => true], 200);
    }

    /**
     * Procesa un pago completado con éxito.
     * 
     * Busca el pago por el ID del PaymentIntent de Stripe y lo marca como 'pagado'.
     * Solo actúa si el pago no estaba ya marcado como pagado (idempotencia).
     * 
     * @param object $paymentIntent — objeto PaymentIntent de Stripe
     */
    private function pagoExitoso($paymentIntent)
    {
        $pago = Pago::where('stripe_payment_intent_id', $paymentIntent->id)->first();

        // Verificar que existe y que no está ya marcado como pagado (evitar duplicados)
        if ($pago && $pago->estado !== 'pagado') {
            $pago->update(['estado' => 'pagado']);
        }
    }

    /**
     * Procesa un pago fallido.
     * 
     * Busca el pago por el ID del PaymentIntent de Stripe y lo marca como 'fallido'.
     * Solo actúa si el pago estaba en estado 'pendiente'.
     * 
     * @param object $paymentIntent — objeto PaymentIntent de Stripe
     */
    private function pagoFallido($paymentIntent)
    {
        $pago = Pago::where('stripe_payment_intent_id', $paymentIntent->id)->first();

        // Solo marcar como fallido si estaba pendiente
        if ($pago && $pago->estado === 'pendiente') {
            $pago->update(['estado' => 'fallido']);
        }
    }
}