<?php

namespace App\Http\Controllers;

use App\Models\Barbero;
use App\Models\Reserva;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;

class AdminBarberoController extends Controller
{
    /**
     * Show the barbers management page with real DB data.
     */
    public function index(): View
    {
        $barberos = Barbero::activos()->orderBy('id')->get();

        // ── Stats reales ──────────────────────────────────────────────────
        $totalBarberos = $barberos->count();
        $enTurnoHoy = Reserva::whereDate('created_at', today())
            ->distinct('barbero')
            ->count('barbero');

        $cortesHoy = Reserva::whereDate('created_at', today())->count();

        $ratingPromedio = $barberos->count() > 0
            ? round($barberos->avg('rating'), 1).' ★'
            : '—';

        $stats = [
            'total_barberos' => $totalBarberos,
            'en_turno' => $enTurnoHoy,
            'cortes_hoy' => $cortesHoy,
            'rating_promedio' => $ratingPromedio,
        ];

        // ── Enriquecer cada barbero con datos reales de reservas ──────────
        $barberoData = $barberos->map(function (Barbero $b) {
            return [
                'id' => $b->id,
                'nombre' => $b->nombre,
                'rol' => $b->rol,
                'especialidad' => $b->especialidad,
                'estado' => $b->estado,
                'rating' => $b->rating,
                'resenas' => $b->resenas,
                'horario' => $b->horario,
                'telefono' => $b->telefono,
                // Calculados en tiempo real desde reservas
                'cortes_hoy' => Reserva::where('barbero', $b->nombre)
                    ->whereDate('created_at', today())
                    ->count(),
                'total_cortes' => Reserva::where('barbero', $b->nombre)
                    ->where('estado', 'Completada')
                    ->count(),
            ];
        })->all();

        return view('dashboard.barberos', compact('stats', 'barberoData'));
    }

    /**
     * Update a barber's status (Disponible / En corte / Descanso).
     */
    public function updateEstado(Request $request, Barbero $barbero): RedirectResponse
    {
        $validated = $request->validate([
            'estado' => ['required', 'string', 'in:Disponible,En corte,Descanso,Inactivo'],
        ]);

        $barbero->update($validated);

        return back()->with('success_barbero', "Estado de {$barbero->nombre} actualizado.");
    }

    /**
     * Store a new barber — returns JSON when called via fetch (AJAX).
     */
    public function store(Request $request): RedirectResponse|JsonResponse
    {
        $isAjax = $request->expectsJson() || $request->ajax();

        // Validar manualmente para devolver JSON en caso de error AJAX
        $validator = Validator::make($request->all(), [
            'nombre' => ['required', 'string', 'max:100'],
            'rol' => ['nullable', 'string', 'max:100'],
            'especialidad' => ['nullable', 'string', 'max:200'],
            'horario' => ['nullable', 'string', 'max:50'],
            'telefono' => ['nullable', 'string', 'max:30'],
        ]);

        if ($validator->fails()) {
            if ($isAjax) {
                return response()->json([
                    'success' => false,
                    'mensaje' => $validator->errors()->first(),
                    'errors' => $validator->errors()->toArray(),
                ], 422);
            }

            return back()->withErrors($validator)->withInput();
        }

        $validated = $validator->validated();

        $barbero = Barbero::create(array_merge($validated, [
            'estado' => 'Disponible',
            'rating' => 5.0,
            'resenas' => 0,
            'activo' => true,
        ]));

        if ($isAjax) {
            return response()->json([
                'success' => true,
                'mensaje' => "Barbero {$barbero->nombre} agregado correctamente.",
                'barbero' => [
                    'id' => $barbero->id,
                    'nombre' => $barbero->nombre,
                    'rol' => $barbero->rol ?? 'Barbero',
                    'especialidad' => $barbero->especialidad ?? '',
                    'estado' => $barbero->estado,
                    'rating' => number_format((float) $barbero->rating, 1),
                    'horario' => $barbero->horario ?? '09:00 AM - 06:00 PM',
                    'telefono' => $barbero->telefono ?? '',
                    'cortes_hoy' => 0,
                    'total_cortes' => 0,
                ],
            ]);
        }

        return back()->with('success_barbero', "Barbero {$barbero->nombre} agregado correctamente.");
    }

    /**
     * Remove a barber (soft-deactivate).
     */
    public function destroy(Barbero $barbero): RedirectResponse
    {
        $barbero->update(['activo' => false]);

        return back()->with('success_barbero', "{$barbero->nombre} ha sido desactivado.");
    }
}
