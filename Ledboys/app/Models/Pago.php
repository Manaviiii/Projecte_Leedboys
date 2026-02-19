<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pago extends Model
{
    use HasFactory;

    protected $fillable = [
        'evento_id', 'residencia_id', 'amount', 
        'estado', 'stripe_payment_intent_id'
    ];

    public function evento()
    {
        return $this->belongsTo(Evento::class);
    }
    
    // Es polimórfico "a lo pobre" (nullable FKs), pero funciona bien
    public function residencia() 
    {
        return $this->belongsTo(Residencia::class);
    }
}