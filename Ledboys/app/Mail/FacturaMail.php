<?php

namespace App\Mail;

use App\Models\Pago;
use Illuminate\Mail\Mailable;
use Barryvdh\DomPDF\Facade\Pdf;

class FacturaMail extends Mailable
{
    public Pago $pago;
    public array $desglose;

    public function __construct(Pago $pago, array $desglose)
    {
        $this->pago    = $pago;
        $this->desglose = $desglose;
    }

    public function build()
    {
        $pdf = Pdf::loadView('emails.factura', [
            'pago'     => $this->pago,
            'desglose' => $this->desglose,
        ]);

        return $this->subject('Confirmación de reserva - LEDBOYSS & LEDGIRLSS')
                    ->view('emails.factura_email')
                    ->with([
                        'pago'     => $this->pago,
                        'desglose' => $this->desglose,
                    ])
                    ->attachData($pdf->output(), "factura-{$this->pago->id}.pdf", [
                        'mime' => 'application/pdf',
                    ]);
    }
}
