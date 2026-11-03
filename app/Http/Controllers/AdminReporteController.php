<?php

namespace App\Http\Controllers;

use App\Models\Reserva;
use Carbon\Carbon;
use Illuminate\View\View;

class AdminReporteController extends Controller
{
    /**
     * Extract a numeric amount from strings like "Bs. 60" or "60".
     */
    private function parsePrecio(?string $precio): int
    {
        if (! $precio) {
            return 0;
        }
        preg_match('/[\d]+/', $precio, $m);

        return isset($m[0]) ? (int) $m[0] : 0;
    }

    /**
     * Show financial and performance reports with real DB data.
     */
    public function index(): View
    {
        // ── Ingresos del mes actual (solo Completadas) ────────────────────
        $completadasMes = Reserva::where('estado', 'Completada')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->get();

        $ingresosMes = $completadasMes->sum(fn (Reserva $r) => $this->parsePrecio($r->precio));

        // ── Ingresos del mes anterior ─────────────────────────────────────
        $completadasMesAnterior = Reserva::where('estado', 'Completada')
            ->whereMonth('created_at', now()->subMonth()->month)
            ->whereYear('created_at', now()->subMonth()->year)
            ->get();

        $ingresosMesAnterior = $completadasMesAnterior->sum(fn (Reserva $r) => $this->parsePrecio($r->precio));

        // Crecimiento %
        $crecimiento = '—';
        if ($ingresosMesAnterior > 0) {
            $pct = round((($ingresosMes - $ingresosMesAnterior) / $ingresosMesAnterior) * 100, 1);
            $crecimiento = ($pct >= 0 ? '+' : '').$pct.'%';
        } elseif ($ingresosMes > 0) {
            $crecimiento = '+100%';
        }

        // ── Ticket promedio ───────────────────────────────────────────────
        $totalCompletadas = Reserva::where('estado', 'Completada')->count();
        $sumaTodos = Reserva::where('estado', 'Completada')
            ->get()
            ->sum(fn (Reserva $r) => $this->parsePrecio($r->precio));

        $ticketPromedio = $totalCompletadas > 0
            ? 'Bs. '.round($sumaTodos / $totalCompletadas)
            : 'Bs. 0';

        // ── Citas finalizadas totales ─────────────────────────────────────
        $citasCompletadas = $totalCompletadas;

        // ── Ocupación del sillón (completadas / total reservas) ──────────
        $totalReservas = Reserva::count();
        $ocupacion = $totalReservas > 0
            ? round(($citasCompletadas / $totalReservas) * 100).'%'
            : '0%';

        $metrics = [
            'ingresos_mes' => 'Bs. '.number_format($ingresosMes, 0, '.', ','),
            'ingresos_mes_anterior' => 'Bs. '.number_format($ingresosMesAnterior, 0, '.', ','),
            'crecimiento' => $crecimiento,
            'ticket_promedio' => $ticketPromedio,
            'citas_completadas' => $citasCompletadas,
            'ocupacion_sillon' => $ocupacion,
        ];

        // ── Evolución mensual (últimos 6 meses) ───────────────────────────
        $ingresosMensuales = [];
        $mesesNombres = ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'];

        for ($i = 5; $i >= 0; $i--) {
            $mes = Carbon::now()->subMonths($i);
            $monto = Reserva::where('estado', 'Completada')
                ->whereMonth('created_at', $mes->month)
                ->whereYear('created_at', $mes->year)
                ->get()
                ->sum(fn (Reserva $r) => $this->parsePrecio($r->precio));

            $ingresosMensuales[] = [
                'mes' => $mesesNombres[$mes->month - 1],
                'monto' => $monto,
            ];
        }

        // ── Rendimiento por barbero (mes actual) ──────────────────────────
        $barberosData = Reserva::where('estado', 'Completada')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->get()
            ->groupBy('barbero');

        $rendimientoBarberos = [];
        $totalIngresosMes = max($ingresosMes, 1); // evitar división por cero

        foreach ($barberosData as $nombre => $citas) {
            if (! $nombre) {
                continue;
            }
            $ingresosBarbero = $citas->sum(fn (Reserva $r) => $this->parsePrecio($r->precio));
            $rendimientoBarberos[] = [
                'nombre' => $nombre,
                'citas' => $citas->count(),
                'ingresos' => 'Bs. '.number_format($ingresosBarbero, 0, '.', ','),
                'porcentaje' => round(($ingresosBarbero / $totalIngresosMes) * 100),
            ];
        }

        // Ordenar por ingresos desc
        usort($rendimientoBarberos, fn ($a, $b) => $b['porcentaje'] <=> $a['porcentaje']);

        // ── Métodos de pago (todas las reservas) ─────────────────────────
        $todasReservas = Reserva::where('estado', 'Completada')->get();
        $totalIngresosGlobal = max(
            $todasReservas->sum(fn (Reserva $r) => $this->parsePrecio($r->precio)),
            1
        );

        $pagoAgrupado = $todasReservas->groupBy('metodo_pago');
        $metodosPago = [];

        $labels = [
            'qr' => 'Transferencia / QR Simple',
            'local' => 'Efectivo / POS en Local',
        ];

        foreach ($pagoAgrupado as $metodo => $items) {
            $monto = $items->sum(fn (Reserva $r) => $this->parsePrecio($r->precio));
            $pct = round(($monto / $totalIngresosGlobal) * 100);
            $metodosPago[] = [
                'metodo' => $labels[$metodo] ?? ucfirst((string) $metodo),
                'porcentaje' => $pct,
                'monto' => 'Bs. '.number_format($monto, 0, '.', ','),
            ];
        }

        usort($metodosPago, fn ($a, $b) => $b['porcentaje'] <=> $a['porcentaje']);

        return view('dashboard.reportes', compact(
            'metrics',
            'ingresosMensuales',
            'rendimientoBarberos',
            'metodosPago'
        ));
    }
}
