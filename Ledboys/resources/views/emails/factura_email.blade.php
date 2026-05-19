<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: Arial, sans-serif; background: #0a0a0a; color: #fff; padding: 20px; }
        .titulo { font-size: 24px; color: #c9a84c; }
        .caja { background: #1a1a1a; padding: 20px; border-radius: 8px; margin-top: 20px; }
        .total { font-size: 18px; color: #c9a84c; font-weight: bold; }
        p { line-height: 1.6; }
    </style>
</head>
<body>
    <h1 class="titulo">LEDBOYSS & LEDGIRLSS</h1>

    <p>Hola <strong>{{ $pago->nombre_facturacion }}</strong>,</p>
    <p>Tu reserva ha sido confirmada. Adjuntamos tu factura en PDF.</p>

    <div class="caja">
        <p><strong>Nº Factura:</strong> #{{ str_pad($pago->id, 6, '0', STR_PAD_LEFT) }}</p>

        @if($pago->evento)
        <p><strong>Fecha del evento:</strong> {{ $pago->evento->fecha->format('d/m/Y') }}
            @if($pago->evento->hora) a las {{ $pago->evento->hora }} @endif
        </p>
        @if($pago->evento->ubicacion)
        <p><strong>Ubicación:</strong> {{ $pago->evento->ubicacion }}</p>
        @endif
        @endif

        <p><strong>Items:</strong> {{ $pago->detalles_items }}</p>
        <p class="total">Total pagado: {{ number_format($pago->amount, 2) }}€</p>
    </div>

    <p>Gracias por confiar en LEDBOYSS & LEDGIRLSS Performance.</p>
    <p>Para cualquier consulta puedes contactarnos en <a href="mailto:infoledboys@gmail.com" style="color:#c9a84c;">infoledboys@gmail.com</a></p>
</body>
</html>
