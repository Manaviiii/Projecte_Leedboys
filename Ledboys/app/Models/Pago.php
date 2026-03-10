<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pago extends Model
{
    use HasFactory;

    protected $fillable = [
        'evento_id', 
        'residencia_id', 
        'amount', 
        'estado', 
        'stripe_payment_intent_id',
        'detalles_items' // <-- ¡Añade esto si vas a guardar qué compraron!
    ];

    // Para que el precio siempre se trate como número en el código
    protected $casts = [
        'amount' => 'decimal:2',
    ];

    public function evento()
    {
        return $this->belongsTo(Evento::class);
    }
    
    public function residencia() 
    {
        return $this->belongsTo(Residencia::class);
    }
}