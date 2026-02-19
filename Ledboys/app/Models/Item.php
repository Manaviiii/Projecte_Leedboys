<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre', 
        'tipo', 
        'precio', 
        'descripcion', 
        'imagen', 
        'activo'
    ];

    #region RELACIONES CON TABLAS 1-1

    /**
     * Relación uno a uno con ItemTraje.
     * Indica que un Item puede tener un único traje asociado.
     * clave foránea es 'item_id'
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */

     
    public function traje()
    {
        return $this->hasOne(ItemTraje::class);
    }


    /**
     * Relación uno a uno con ItemAccesorio.
     * 
     * Se especifica la clave foránea 'item_id'
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */

    public function accesorio()
    {
        // Ojo: En tu migración de accesorios la PK es item_id
        return $this->hasOne(ItemAccesorio::class, 'item_id');
    }


    /**
     * Relación uno a uno con ItemPack.
     *
     * Define que un Item puede tener un único pack asociado.
     * Usa 'item_id' como clave foránea.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */

    public function pack()
    {
        return $this->hasOne(ItemPack::class, 'item_id');
    }

    #endregion
    

    #region RELACION MUCHOS A MUCHOS

    /**
     * Relación muchos a muchos con Evento.
     *
     * Un Item puede estar asociado a múltiples Eventos,
     * y un Evento puede contener múltiples Items.
     *
     * Se utiliza la tabla pivote 'evento_items'.
     * Incluye los campos adicionales:
     * - cantidad
     * - precio_unitario
     *
     * También gestiona automáticamente los timestamps
     * (created_at y updated_at) en la tabla pivote.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function eventos()
    {
        return $this->belongsToMany(Evento::class, 'evento_items')
                    ->withPivot(['cantidad', 'precio_unitario'])
                    ->withTimestamps();
    }

    #endregion
}