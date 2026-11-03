<?php

namespace Database\Factories;

use App\Models\Reserva;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Reserva>
 */
class ReservaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'folio' => 'BARBER-'.fake()->unique()->numerify('2026-####'),
            'cliente' => fake()->name(),
            'telefono' => '+591 7'.fake()->numerify('#######'),
            'email' => fake()->safeEmail(),
            'servicio' => fake()->randomElement(['Corte Fade & Degrafilado', 'Afeitado Tradicional Spa', 'Experiencia VIP Bless']),
            'barbero' => fake()->randomElement(['Cristian Macha', 'Rogher Luara', 'Esteban Apaza']),
            'fecha' => 'Hoy, 10:30 AM',
            'hora' => '10:30',
            'duracion' => '45 min',
            'precio' => 'Bs. 60',
            'detalles' => fake()->sentence(),
            'metodo_pago' => 'qr',
            'estado_pago' => 'Pendiente',
            'comprobante' => null,
            'estado' => 'Confirmada',
        ];
    }
}
