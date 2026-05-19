<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Evento extends Model
{
    use HasFactory;

    protected $fillable = [
        'cliente_id',
        'fecha',
        'hora',
        'ubicacion',
        'total_precio',
        'estado',
        'stripe_payment_intent_id',
    ];

    protected $casts = [
        'fecha' => 'date',
    ];

    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    public function items()
    {
        return $this->belongsToMany(Item::class, 'evento_items')
                    ->using(EventoItem::class)
                    ->withPivot(['cantidad', 'precio_unitario'])
                    ->withTimestamps();
    }

    public function pagos()
    {
        return $this->hasMany(Pago::class);
    }
}