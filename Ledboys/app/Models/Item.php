<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\ItemTraje;

class Item extends Model
{
    use HasFactory;

    // Definir las columnas que se pueden llenar de manera masiva
    protected $fillable = [
        'nombre', 
        'tipo', 
        'precio', 
        'descripcion', 
        'imagen', 
        'activo'
    ];

    // Relación con los trajes (uno a uno)
    public function itemTraje()
    {
        return $this->hasOne(ItemTraje::class);
    }
}