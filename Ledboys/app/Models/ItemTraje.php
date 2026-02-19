<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemTraje extends Model
{
    use HasFactory;

    protected $fillable = ['item_id', 'tipo_traje', 'genero', 'stock_total'];

    public function item()
    {
        return $this->belongsTo(Item::class);
    }
}