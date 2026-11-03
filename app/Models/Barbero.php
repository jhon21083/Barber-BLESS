<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Barbero extends Model
{
    /** @var list<string> */
    protected $fillable = [
        'nombre',
        'rol',
        'especialidad',
        'estado',
        'rating',
        'resenas',
        'horario',
        'telefono',
        'activo',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'rating' => 'decimal:1',
            'activo' => 'boolean',
        ];
    }

    /** Reservas completadas por este barbero hoy. */
    public function cortesHoy(): int
    {
        return Reserva::where('barbero', $this->nombre)
            ->whereDate('created_at', today())
            ->count();
    }

    /** Total de reservas completadas por este barbero. */
    public function totalCortes(): int
    {
        return Reserva::where('barbero', $this->nombre)
            ->where('estado', 'Completada')
            ->count();
    }

    /** Solo barberos activos. */
    public function scopeActivos(Builder $query): Builder
    {
        return $query->where('activo', true);
    }
}
