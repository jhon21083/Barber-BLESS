<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminClienteController extends Controller
{
    /**
     * Show the clients management page.
     */
    public function index(Request $request): View
    {
        $stats = [
            'total_clientes' => 312,
            'clientes_vip' => 48,
            'nuevos_mes' => 29,
            'frecuencia_promedio' => '18 días',
        ];

        $clientes = [
            [
                'id' => '#CLI-01',
                'nombre' => 'Carlos Méndez',
                'email' => 'carlos.mendez@example.com',
                'telefono' => '+591 7123 4567',
                'visitas' => 14,
                'total_gastado' => 'Bs. 980',
                'ultima_visita' => '15 Ene 2026',
                'barbero_favorito' => 'Cristian Macha',
                'es_vip' => true,
            ],
            [
                'id' => '#CLI-02',
                'nombre' => 'Andrés Torres',
                'email' => 'andres.torres@example.com',
                'telefono' => '+591 7234 5678',
                'visitas' => 9,
                'total_gastado' => 'Bs. 510',
                'ultima_visita' => '15 Ene 2026',
                'barbero_favorito' => 'Rogher Luara',
                'es_vip' => false,
            ],
            [
                'id' => '#CLI-03',
                'nombre' => 'Javier Ruiz',
                'email' => 'javier.ruiz@example.com',
                'telefono' => '+591 7345 6789',
                'visitas' => 22,
                'total_gastado' => 'Bs. 1,760',
                'ultima_visita' => '12 Ene 2026',
                'barbero_favorito' => 'Esteban Apaza',
                'es_vip' => true,
            ],
            [
                'id' => '#CLI-04',
                'nombre' => 'Sofía Lara',
                'email' => 'sofia.lara@example.com',
                'telefono' => '+591 7456 7890',
                'visitas' => 6,
                'total_gastado' => 'Bs. 360',
                'ultima_visita' => '10 Ene 2026',
                'barbero_favorito' => 'Cristian Macha',
                'es_vip' => false,
            ],
            [
                'id' => '#CLI-05',
                'nombre' => 'Pedro Salas',
                'email' => 'pedro.salas@example.com',
                'telefono' => '+591 7567 8901',
                'visitas' => 18,
                'total_gastado' => 'Bs. 1,350',
                'ultima_visita' => '15 Ene 2026',
                'barbero_favorito' => 'Rogher Luara',
                'es_vip' => true,
            ],
            [
                'id' => '#CLI-06',
                'nombre' => 'Fernando Castro',
                'email' => 'fernando.castro@example.com',
                'telefono' => '+591 7678 9012',
                'visitas' => 3,
                'total_gastado' => 'Bs. 190',
                'ultima_visita' => '05 Ene 2026',
                'barbero_favorito' => 'Esteban Apaza',
                'es_vip' => false,
            ],
        ];

        return view('dashboard.clientes', compact('stats', 'clientes'));
    }
}
