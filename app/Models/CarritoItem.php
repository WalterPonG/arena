<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CarritoItem extends Model
{
    use HasFactory;

    /**
     * Tabla asociada (opcional si sigues convención)
     */
    protected $table = 'carrito_items';

    /**
     * Campos asignables
     */
    protected $fillable = [
        'user_id',
        'evento_id',
        'asiento_id',
        'sector',
        'fila',
        'numero',
        'precio',
    ];

    /**
     * CASTS (importante para precios)
     */
    protected $casts = [
        'precio' => 'decimal:2',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELACIONES
    |--------------------------------------------------------------------------
    */

    /**
     * Usuario propietario del carrito
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Evento asociado al asiento
     */
    public function evento()
    {
        return $this->belongsTo(Evento::class);
    }

    /**
     * Asiento seleccionado
     */
    public function asiento()
    {
        return $this->belongsTo(Asiento::class);
    }
}
