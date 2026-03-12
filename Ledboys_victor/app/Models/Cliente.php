<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    use HasFactory;

    protected $fillable = ['nombre', 'email', 'telefono', 'stripe_customer_id'];

    public function eventos()
    {
        return $this->hasMany(Evento::class);
    }

    public function residencias()
    {
        return $this->hasMany(Residencia::class);
    }
}