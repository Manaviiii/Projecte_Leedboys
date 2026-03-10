<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\PaymentIntent;
use App\Models\Item;
use App\Models\Pago; // Asegúrate de importar tu modelo de la tabla pagos

class PaymentController extends Controller
{
    public function crearIntento(Request $request)
    {
        Stripe::setApiKey(env('STRIPE_SECRET'));

        // 1. Validar que vengan items y datos del cliente
        $itemsSolicitados = $request->items; // [1, 5]
        
        // 2. Calcular total y preparar descripción de lo que compra
        $itemsDB = Item::whereIn('id', $itemsSolicitados)->get();
        $total = $itemsDB->sum('precio');
        $nombresItems = $itemsDB->pluck('nombre')->implode(', ');

        // 3. Crear el intento en Stripe
        $intent = PaymentIntent::create([
            'amount' => $total * 100,
            'currency' => 'eur',
            'automatic_payment_methods' => ['enabled' => true],
        ]);

        // 4. GUARDAR EN TU BASE DE DATOS (Lo que te faltaba)
        // Usamos los campos de tu migración
        $pago = Pago::create([
            'amount' => $total,
            'estado' => 'pendiente',
            'stripe_payment_intent_id' => $intent->id,
            'detalles_items' => $nombresItems, // Para saber qué compró sin tablas extra
            // Si tienes estos campos en el request, añádelos:
            'evento_id' => $request->evento_id, 
            'residencia_id' => $request->residencia_id,
        ]);

        // 5. Responder al frontend
        return response()->json([
            'clientSecret' => $intent->client_secret,
            'pago_id' => $pago->id, // Le damos el ID a tu compañero por si lo necesita
            'total' => $total
        ]);
    }

    // Añade esta función para que tu compañero confirme cuando el pago termine
    public function confirmarPago($id)
    {
        $pago = Pago::findOrFail($id);
        $pago->update(['estado' => 'pagado']);
        
        return response()->json(['message' => 'Pago actualizado en DB']);
    }
}