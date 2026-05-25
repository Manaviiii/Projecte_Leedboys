<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemPack extends Model
{
    use HasFactory;

    protected $primaryKey = 'item_id';
    public $incrementing  = false;
    public $timestamps    = false; // item_packs no tiene created_at ni updated_at

    protected $fillable = [
        'item_id',
        'numero_zancudos',
    ];

    public function item()
    {
        return $this->belongsTo(Item::class, 'item_id');
    }
}