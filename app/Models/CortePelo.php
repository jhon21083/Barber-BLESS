<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class CortePelo extends Model
{
    /** @var list<string> */
    protected $fillable = [
        'nombre',
        'filtro',
        'descripcion',
        'precio',
        'imagen',
        'emoji',
        'activo',
        'orden',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'activo' => 'boolean',
            'orden' => 'integer',
        ];
    }

    /** Solo cortes visibles, ordenados. */
    public function scopeActivos(Builder $query): Builder
    {
        return $query->where('activo', true)->orderBy('orden')->orderBy('id');
    }
}
