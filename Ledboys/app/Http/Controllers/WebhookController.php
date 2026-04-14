<?php
// app/Http/Controllers/WebhookController.php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Webhook;
use App\Models\Pago;

class WebhookController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | POST /stripe/webhook
    |
    | Stripe llama a esta URL cuando un pago cambia de estado.
    | No requiere Bearer token — usa firma HMAC de Stripe para verificar.
    |
    | Configura en Stripe Dashboard (sandbox):
    |   https://dashboard.stripe.com/test/webhooks
    |   → Añadir endpoint → tu-dominio.com/stripe/webhook
    |   → Eventos: payment_intent.succeeded, payment_intent.payment_failed
    |
    | Anota el "Signing secret" y ponlo en .env como STRIPE_WEBHOOK_SECRET
    |--------------------------------------------------------------------------
    */
    public function handle(Request $request)
    {
        $payload   = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');
        $secret    = env('STRIPE_WEBHOOK_SECRET');

        // Verificar que el webhook viene realmente de Stripe
        try {
            $event = Webhook::constructEvent($payload, $sigHeader, $secret);
        } catch (\Stripe\Exception\SignatureVerificationException $e) {
            return response()->json(['message' => 'Firma inválida'], 400);
        }

        // Gestionar los eventos que nos interesan
        match ($event->type) {
            'payment_intent.succeeded'       => $this->pagoExitoso($event->data->object),
            'payment_intent.payment_failed'  => $this->pagoFallido($event->data->object),
            default                          => null, // Ignorar el resto
        };

        // Stripe espera siempre un 200, si no reintenta
        return response()->json(['received' => true], 200);
    }

    /*
    |--------------------------------------------------------------------------
    | Pago completado con éxito
    |--------------------------------------------------------------------------
    */
    private function pagoExitoso($paymentIntent)
    {
        $pago = Pago::where('stripe_payment_intent_id', $paymentIntent->id)->first();

        if ($pago && $pago->estado !== 'pagado') {
            $pago->update(['estado' => 'pagado']);
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Pago fallido
    |--------------------------------------------------------------------------
    */
    private function pagoFallido($paymentIntent)
    {
        $pago = Pago::where('stripe_payment_intent_id', $paymentIntent->id)->first();

        if ($pago && $pago->estado === 'pendiente') {
            $pago->update(['estado' => 'fallido']);
        }
    }
}