<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemAccesorio extends Model
{
    use HasFactory;

    protected $primaryKey = 'item_id';
    public $incrementing  = false;
    public $timestamps    = false;

    protected $fillable = [
        'item_id',
        'stock_total',
        'imagen',
    ];

    public function item()
    {
        return $this->belongsTo(Item::class, 'item_id');
    }
}