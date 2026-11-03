<?php

namespace App\Http\Controllers;

use App\Models\Reserva;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminReservaController extends Controller
{
    /**
     * Show the reservations management page.
     */
    public function index(Request $request): View
    {
        $query = Reserva::query()->latest();

        if ($search = $request->input('buscar')) {
            $query->where(function ($q) use ($search) {
                $q->where('cliente', 'like', "%{$search}%")
                    ->orWhere('folio', 'like', "%{$search}%")
                    ->orWhere('telefono', 'like', "%{$search}%")
                    ->orWhere('barbero', 'like', "%{$search}%");
            });
        }

        if ($filtro = $request->input('estado')) {
            $query->where('estado', $filtro);
        }

        $reservas = $query->get()->map(function (Reserva $r) {
            return [
                'id' => $r->folio,
                'db_id' => $r->id,
                'cliente' => $r->cliente,
                'telefono' => $r->telefono,
                'email' => $r->email,
                'servicio' => $r->servicio,
                'barbero' => $r->barbero,
                'fecha' => $r->fecha,
                'duracion' => $r->duracion,
                'precio' => $r->precio,
                'metodo_pago' => $r->metodo_pago,
                'estado_pago' => $r->estado_pago,
                'comprobante' => $r->comprobante,
                'estado' => $r->estado,
            ];
        })->all();

        $stats = [
            'total_hoy' => Reserva::count(),
            'pendientes' => Reserva::where('estado', 'Pendiente')->orWhere('estado_pago', 'like', '%Pendiente%')->count(),
            'en_proceso' => Reserva::where('estado', 'En proceso')->count(),
            'completadas_hoy' => Reserva::where('estado', 'Completada')->count(),
        ];

        return view('dashboard.reservas', compact('stats', 'reservas'));
    }

    /**
     * Update the status of a reservation.
     */
    public function updateStatus(Request $request, Reserva $reserva): RedirectResponse
    {
        $validated = $request->validate([
            'estado' => ['nullable', 'string', 'in:Confirmada,En proceso,Pendiente,Completada,Cancelada'],
            'estado_pago' => ['nullable', 'string'],
        ]);

        $reserva->update(array_filter($validated));

        return back()->with('success_reserva', 'Estado de la reserva actualizado.');
    }
}
