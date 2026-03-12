<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemAccesorio extends Model
{
    use HasFactory;

    protected $primaryKey = 'item_id'; // Importante
    public $incrementing = false;      // Importante porque no es autoincrement ID normal

    protected $fillable = ['item_id', 'stock_total'];

    public function item()
    {
        return $this->belongsTo(Item::class, 'item_id');
    }
}