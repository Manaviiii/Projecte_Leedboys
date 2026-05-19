<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: Arial, sans-serif; background: #0a0a0a; color: #fff; }
        .titulo { font-size: 28px; color: #c9a84c; }
        .subtitulo { font-size: 16px; color: #aaa; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th { background: #1a1a1a; color: #c9a84c; padding: 8px; text-align: left; }
        td { padding: 8px; border-bottom: 1px solid #222; }
        .total { font-size: 20px; color: #c9a84c; font-weight: bold; }
        .desglose { margin-top: 20px; font-size: 14px; color: #aaa; }
        .desglose td { border: none; padding: 4px 8px; }
    </style>
</head>
<body>
    <h1 class="titulo">LEDBOYSS & LEDGIRLSS</h1>
    <p class="subtitulo">Performance</p>

    <h2>FACTURA #{{ str_pad($pago->id, 6, '0', STR_PAD_LEFT) }}</h2>
    <p>Fecha de emisión: {{ now()->format('d/m/Y') }}</p>

    <p><strong>Facturado a:</strong> {{ $pago->nombre_facturacion }} {{ $pago->apellidos_facturacion }}</p>
    <p>DNI: {{ $pago->dni }} | Tel: {{ $pago->telefono_facturacion }}</p>
    <p>{{ $pago->direccion }} — CP: {{ $pago->codigo_postal }}</p>

    @if($pago->evento)
    <p>
        <strong>Fecha del evento:</strong> {{ $pago->evento->fecha->format('d/m/Y') }}
        @if($pago->evento->hora) a las {{ $pago->evento->hora }} @endif
        @if($pago->evento->ubicacion) — {{ $pago->evento->ubicacion }} @endif
    </p>
    @endif

    <table>
        <tr>
            <th>Descripción</th>
            <th>Total (IVA incl.)</th>
        </tr>
        <tr>
            <td>{{ $pago->detalles_items }}</td>
            <td>{{ number_format($pago->amount, 2) }}€</td>
        </tr>
    </table>

    <table class="desglose">
        <tr>
            <td>Base imponible:</td>
            <td>{{ number_format($desglose['base_imponible'], 2) }}€</td>
        </tr>
        <tr>
            <td>IVA ({{ $desglose['iva_porcentaje'] }}%):</td>
            <td>{{ number_format($desglose['cuota_iva'], 2) }}€</td>
        </tr>
    </table>

    <p class="total">TOTAL: {{ number_format($pago->amount, 2) }}€</p>
</body>
</html>
