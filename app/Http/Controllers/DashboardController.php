<?php

namespace App\Http\Controllers;

use App\Models\Reserva;
use Carbon\Carbon;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Show the admin dashboard with real data from the database.
     */
    public function index(): View
    {
        // ── Estadísticas reales ───────────────────────────────────────────
        $totalReservas = Reserva::count();
        $reservasHoy = Reserva::whereDate('created_at', today())->count();
        $pendientes = Reserva::whereIn('estado', ['Pendiente', 'Confirmada'])->count();
        $completadasHoy = Reserva::where('estado', 'Completada')
            ->whereDate('created_at', today())
            ->count();

        // Clientes únicos = teléfonos distintos registrados
        $clientesUnicos = Reserva::distinct('telefono')->count('telefono');

        // Ingresos: suma de precios de reservas Completadas
        // El precio está guardado como "Bs. 60" → extraemos el número
        $ingresosMes = Reserva::where('estado', 'Completada')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->get()
            ->sum(function (Reserva $r) {
                preg_match('/[\d]+/', (string) $r->precio, $m);

                return isset($m[0]) ? (int) $m[0] : 0;
            });

        // Barberos activos (distintos en reservas)
        $barberosActivos = Reserva::distinct('barbero')
            ->whereNotNull('barbero')
            ->count('barbero');

        $stats = [
            'reservas_hoy' => $reservasHoy,
            'clientes_activos' => $clientesUnicos,
            'ingresos_mes' => 'Bs. '.number_format($ingresosMes, 0, '.', ','),
            'barberos_disponibles' => $barberosActivos ?: 0,
        ];

        // ── Últimas 5 reservas reales ─────────────────────────────────────
        $reservas = Reserva::latest()
            ->take(5)
            ->get()
            ->map(fn (Reserva $r) => [
                'cliente' => $r->cliente,
                'servicio' => $r->servicio,
                'barbero' => $r->barbero,
                'fecha' => $r->fecha,
                'estado' => $r->estado,
            ])
            ->all();

        // ── Gráfico: reservas por día (últimos 7 días reales) ─────────────
        $chart = [];
        $diasNombres = ['Dom', 'Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb'];

        for ($i = 6; $i >= 0; $i--) {
            $dia = Carbon::today()->subDays($i);
            $count = Reserva::whereDate('created_at', $dia)->count();
            $chart[] = [
                $diasNombres[$dia->dayOfWeek],
                $count,
            ];
        }

        return view('dashboard.index', compact('stats', 'reservas', 'chart'));
    }
}
