<?php
 
namespace App\Models;
 
use Illuminate\Database\Eloquent\Model;
 
class Foto extends Model
{
    protected $table = 'fotos';
 
    protected $fillable = [
        'item_traje_id',
        'principal',
        'nombre',
        'orden',
        'imagen',
    ];
 
    protected $casts = [
        'principal' => 'boolean',
    ];
 
    public function itemTraje()
    {
        return $this->belongsTo(ItemTraje::class, 'item_traje_id');
    }
}