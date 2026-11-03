<?php

namespace Database\Seeders;

use App\Models\Servicio;
use Illuminate\Database\Seeder;

class ServicioSeeder extends Seeder
{
    public function run(): void
    {
        $servicios = [
            [
                'nombre' => 'Corte Fade & Degrafilado',
                'categoria' => 'Cortes',
                'descripcion' => 'Degradado con máquina de precisión, tijera en zona superior y peinado final con cera mate.',
                'precio' => '60',
                'duracion' => '40 min',
                'imagen' => null,
                'popular' => true,
                'activo' => true,
                'orden' => 1,
            ],
            [
                'nombre' => 'Afeitado Tradicional Spa',
                'categoria' => 'Barba',
                'descripcion' => 'Toalla caliente aromatizada, aplicación de espuma con brocha de tejón y navaja libre.',
                'precio' => '45',
                'duracion' => '30 min',
                'imagen' => null,
                'popular' => false,
                'activo' => true,
                'orden' => 2,
            ],
            [
                'nombre' => 'Experiencia VIP Bless (Corte + Barba + Facial)',
                'categoria' => 'Combos VIP',
                'descripcion' => 'Servicio prémium completo: corte personalizado, ritual de barba y mascarilla negra purificante.',
                'precio' => '120',
                'duracion' => '75 min',
                'imagen' => null,
                'popular' => true,
                'activo' => true,
                'orden' => 3,
            ],
            [
                'nombre' => 'Diseño Freestyle & Tribales',
                'categoria' => 'Cortes',
                'descripcion' => 'Trazos artísticos personalizados a navaja y trimmer con pigmentación temporal opcional.',
                'precio' => '70',
                'duracion' => '50 min',
                'imagen' => null,
                'popular' => false,
                'activo' => true,
                'orden' => 4,
            ],
            [
                'nombre' => 'Perfilado de Barba Express',
                'categoria' => 'Barba',
                'descripcion' => 'Marcación de líneas de mejilla y cuello, reducción de volumen y bálsamo hidratante.',
                'precio' => '30',
                'duracion' => '20 min',
                'imagen' => null,
                'popular' => false,
                'activo' => true,
                'orden' => 5,
            ],
            [
                'nombre' => 'Exfoliación y Mascarilla Capilar',
                'categoria' => 'Tratamientos',
                'descripcion' => 'Limpieza profunda del cuero cabelludo, control de caspa y masaje estimulante.',
                'precio' => '40',
                'duracion' => '25 min',
                'imagen' => null,
                'popular' => false,
                'activo' => true,
                'orden' => 6,
            ],
        ];

        foreach ($servicios as $data) {
            Servicio::firstOrCreate(
                ['nombre' => $data['nombre']],
                $data
            );
        }
    }
}
