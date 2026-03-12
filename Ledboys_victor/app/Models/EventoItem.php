<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class EventoItem extends Pivot
{
    protected $table = 'evento_items';
    
    // Si tu tabla pivote tiene ID auto-incremental (la tuya lo tiene):
    public $incrementing = true; 

    protected $fillable = ['evento_id', 'item_id', 'cantidad', 'precio_unitario'];

    public function evento()
    {
        return $this->belongsTo(Evento::class);
    }

    public function item()
    {
        return $this->belongsTo(Item::class);
    }
}