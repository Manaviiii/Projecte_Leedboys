<?php
 
namespace App\Models;
 
use Illuminate\Database\Eloquent\Model;
 
class Foto extends Model
{
    protected $table = 'fotos';
 
    protected $fillable = [
        'idTraje',
        'principal',
        'nombre',
        'orden',
        'imagen',
    ];
    
    public function itemTraje()
    {
        return $this->belongsTo(ItemTraje::class, 'idTraje');
    }
 
    protected $casts = [
        'principal' => 'boolean',
    ];
 
}