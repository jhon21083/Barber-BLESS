<?php

namespace App\Models;

use Database\Factories\ReservaFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reserva extends Model
{
    /** @use HasFactory<ReservaFactory> */
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'folio',
        'cliente',
        'telefono',
        'email',
        'servicio',
        'barbero',
        'fecha',
        'hora',
        'duracion',
        'precio',
        'detalles',
        'metodo_pago',
        'estado_pago',
        'comprobante',
        'estado',
    ];
}
