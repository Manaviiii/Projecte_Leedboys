<?php
// app/Models/Pago.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Evento;
use App\Models\Residencia;

class Pago extends Model
{
    protected $fillable = [
        'user_id',
        'evento_id',
        'residencia_id',
        'detalles_items',
        'amount',
        'estado',
        'stripe_payment_intent_id',
        'nombre_facturacion',
        'apellidos_facturacion',
        'dni',
        'telefono_facturacion',
        'direccion',
        'codigo_postal',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relaciones
    |--------------------------------------------------------------------------
    */

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function evento()
    {
        return $this->belongsTo(Evento::class);
    }

    public function residencia()
    {
        return $this->belongsTo(Residencia::class);
    }
}