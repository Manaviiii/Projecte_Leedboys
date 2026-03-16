<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Cliente;


class Evento extends Model
{
    use HasFactory;

    protected $fillable = [
        'cliente_id', 
        'fecha', 
        'total_precio', 
        'estado', 
        'stripe_payment_intent_id'
    ];

    // Para que Laravel trate esto como fechas de verdad (Carbon)
    protected $casts = [
        'fecha' => 'date',
    ];

    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    // Relación Many-to-Many con Items
    public function items()
    {
        return $this->belongsToMany(Item::class, 'evento_items')
                    ->using(EventoItem::class) // Usamos el modelo pivote personalizado
                    ->withPivot(['cantidad', 'precio_unitario'])
                    ->withTimestamps();
    }

    public function pagos()
    {
        return $this->hasMany(Pago::class);
    }
    
}