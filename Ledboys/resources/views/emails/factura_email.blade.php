<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        * { box-sizing: border-box; }
        body {
            font-family: Arial, sans-serif;
            background-color: #0a0a0a !important;
            color: #ffffff !important;
            padding: 0;
            margin: 0;
        }
        .wrapper {
            background-color: #0a0a0a;
            padding: 40px 20px;
            max-width: 100%;
            margin: 0 auto;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #c9a84c;
            padding-bottom: 24px;
            margin-bottom: 24px;
        }
        .logo {
            font-size: 28px;
            font-weight: bold;
            color: #c9a84c !important;
            letter-spacing: 4px;
        }
        .logo-sub {
            font-size: 12px;
            color: #888888 !important;
            letter-spacing: 3px;
            text-transform: uppercase;
            margin-top: 4px;
        }
        
        h2 {
            color: #ffffff !important;
            font-size: 22px;
            letter-spacing: 2px;
            margin: 8px 0;
        }
        .brand {
            color: #c9a84c !important;
            font-size: 13px;
            letter-spacing: 4px;
            text-transform: uppercase;
        }
        .divider {
            border: none;
            border-top: 1px solid #c9a84c;
            margin: 20px 0;
            opacity: 0.4;
        }
        .caja {
            background-color: #1a1a1a;
            border: 1px solid #333;
            border-left: 3px solid #c9a84c;
            padding: 20px;
            margin: 20px 0;
        }
        .caja p {
            margin: 8px 0;
            font-size: 14px;
            color: #cccccc !important;
        }
        .caja strong {
            color: #ffffff !important;
        }
        .total {
            font-size: 20px;
            color: #c9a84c !important;
            font-weight: bold;
            margin-top: 14px !important;
        }
        .msg {
            font-size: 14px;
            color: #aaaaaa !important;
            line-height: 1.7;
        }
        .footer {
            margin-top: 30px;
            border-top: 1px solid #222;
            padding-top: 20px;
            font-size: 12px;
            color: #666666 !important;
            text-align: center;
        }
        a { color: #c9a84c !important; text-decoration: none; }
    </style>
</head>
<body>
    <div class="wrapper">

        <div class="header">
            <div class="logo">LEDBOYSS</div>
            <div class="logo-sub">&amp; LEDGIRLSS Performance</div>
        </div>

        <div style="text-align:center;">
            <h2>¡Gracias por tu compra!</h2>
            <p class="brand">LEDBOYSS &amp; LEDGIRLSS</p>
        </div>

        <hr class="divider">

        <p class="msg">
            Hola <strong style="color:#ffffff;">{{ $pago->nombre_facturacion }}</strong>,<br><br>
            Tu reserva ha sido confirmada con éxito. Nuestro equipo se pondrá en contacto contigo para coordinar todos los detalles del evento.
        </p>

        <div class="caja">
            <p><strong>Nº Factura:</strong> #{{ str_pad($pago->id, 6, '0', STR_PAD_LEFT) }}</p>

            @if($pago->evento)
            <p><strong>Fecha del evento:</strong> {{ $pago->evento->fecha->format('d/m/Y') }}
                @if($pago->evento->hora) a las {{ $pago->evento->hora }}@endif
            </p>
            @if($pago->evento->ubicacion)
            <p><strong>Ubicación:</strong> {{ $pago->evento->ubicacion }}</p>
            @endif
            @endif

            <p><strong>Items:</strong> {{ $pago->detalles_items }}</p>
            <p class="total">Total pagado: {{ number_format($pago->amount, 2) }}€</p>
        </div>

        <div class="footer">
            <p>Para cualquier consulta: <a href="mailto:infoledboys@gmail.com">infoledboys@gmail.com</a></p>
            <p style="margin-top:8px;">LEDBOYSS &amp; LEDGIRLSS Performance S.L. · Institut Milà i Fontanals, Igualada</p>
        </div>

    </div>
</body>
</html>
