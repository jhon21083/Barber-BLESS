<?php

namespace Database\Seeders;

use App\Models\Barbero;
use Illuminate\Database\Seeder;

class BarberoSeeder extends Seeder
{
    public function run(): void
    {
        $barberos = [
            [
                'nombre' => 'Cristian Macha',
                'rol' => 'Master Barber & Estilista',
                'especialidad' => 'Fade, Degrafilado y Diseños Freestyle',
                'estado' => 'Disponible',
                'rating' => 5.0,
                'resenas' => 0,
                'horario' => '10:00 AM - 07:00 PM',
                'telefono' => '+591 7122 3344',
                'activo' => true,
            ],
            [
                'nombre' => 'Rogher Luara',
                'rol' => 'Especialista en Barba',
                'especialidad' => 'Perfilado a Navaja y Toalla Caliente',
                'estado' => 'Disponible',
                'rating' => 5.0,
                'resenas' => 0,
                'horario' => '09:00 AM - 06:00 PM',
                'telefono' => '+591 7233 4455',
                'activo' => true,
            ],
            [
                'nombre' => 'Esteban Apaza',
                'rol' => 'Barbero VIP & Colorista',
                'especialidad' => 'Cortes Clásicos, Pompadour y Tintes',
                'estado' => 'Disponible',
                'rating' => 5.0,
                'resenas' => 0,
                'horario' => '11:00 AM - 08:00 PM',
                'telefono' => '+591 7344 5566',
                'activo' => true,
            ],
        ];

        foreach ($barberos as $data) {
            Barbero::firstOrCreate(
                ['nombre' => $data['nombre']],
                $data
            );
        }
    }
}
