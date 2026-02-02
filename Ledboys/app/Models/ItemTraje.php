<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Item;

class ItemTraje extends Model
{
    use HasFactory;

    protected $table = 'item_trajes'; 
    protected $primaryKey = 'id';

    // Definir la relación con el modelo Item
    public function item()
    {
        return $this->belongsTo(Item::class, 'item_id');
    }

    // Definir los campos que son asignables masivamente
    protected $fillable = [
        'item_id',
        'tipo_traje',
        'genero',
        'stock_total',
    ];
}
