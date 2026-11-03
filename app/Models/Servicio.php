<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Servicio extends Model
{
    /** @var list<string> */
    protected $fillable = [
        'nombre',
        'categoria',
        'descripcion',
        'precio',
        'duracion',
        'imagen',
        'popular',
        'activo',
        'orden',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'popular' => 'boolean',
            'activo' => 'boolean',
            'orden' => 'integer',
        ];
    }

    /** URL pública de la imagen o null. */
    public function imagenUrl(): ?string
    {
        if (! $this->imagen) {
            return null;
        }

        return asset('images/servicios/'.$this->imagen);
    }

    /** Servicios activos ordenados. */
    public function scopeActivos(Builder $query): Builder
    {
        return $query->where('activo', true)->orderBy('orden')->orderBy('id');
    }
}
