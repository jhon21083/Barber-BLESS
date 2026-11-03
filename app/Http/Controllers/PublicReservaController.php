<?php

namespace App\Http\Controllers;

use App\Models\Reserva;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PublicReservaController extends Controller
{
    /**
     * Store a new public reservation from the booking modal.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'cliente' => ['required', 'string', 'max:100'],
            'telefono' => ['required', 'string', 'max:30'],
            'email' => ['required', 'email', 'max:100'],
            'servicio' => ['required', 'string', 'max:150'],
            'barbero' => ['required', 'string', 'max:100'],
            'fecha' => ['required', 'string', 'max:50'],
            'hora' => ['required', 'string', 'max:20'],
            'metodo_pago' => ['required', 'string', 'in:qr,local'],
            'detalles' => ['nullable', 'string', 'max:500'],
            'comprobante' => ['nullable', 'string', 'max:100'],
        ]);

        // Generate unique folio
        do {
            $folio = 'BARBER-'.date('Y').'-'.mt_rand(1000, 9999);
        } while (Reserva::where('folio', $folio)->exists());

        // Extract or calculate price
        $precio = 'Bs. 60';
        if (preg_match('/Bs\.?\s*(\d+)/i', $validated['servicio'], $matches)) {
            $precio = 'Bs. '.$matches[1];
        } elseif (str_contains($validated['servicio'], '120')) {
            $precio = 'Bs. 120';
        } elseif (str_contains($validated['servicio'], '45')) {
            $precio = 'Bs. 45';
        } elseif (str_contains($validated['servicio'], '70')) {
            $precio = 'Bs. 70';
        } elseif (str_contains($validated['servicio'], '30')) {
            $precio = 'Bs. 30';
        } elseif (str_contains($validated['servicio'], '40')) {
            $precio = 'Bs. 40';
        }

        // Determine payment status
        if ($validated['metodo_pago'] === 'qr') {
            $estadoPago = ! empty($validated['comprobante']) ? 'Pagado (QR)' : 'Pendiente (QR)';
        } else {
            $estadoPago = 'Pago en Local';
        }

        $reserva = Reserva::create([
            'folio' => $folio,
            'cliente' => $validated['cliente'],
            'telefono' => $validated['telefono'],
            'email' => $validated['email'],
            'servicio' => $validated['servicio'],
            'barbero' => $validated['barbero'],
            'fecha' => $validated['fecha'].' '.$validated['hora'],
            'hora' => $validated['hora'],
            'duracion' => '45 min',
            'precio' => $precio,
            'detalles' => $validated['detalles'] ?? null,
            'metodo_pago' => $validated['metodo_pago'],
            'estado_pago' => $estadoPago,
            'comprobante' => $validated['comprobante'] ?? null,
            'estado' => 'Confirmada',
        ]);

        return response()->json([
            'success' => true,
            'folio' => $folio,
            'mensaje' => '¡Tu cita ha sido agendada con éxito y registrada en el sistema!',
            'reserva' => $reserva,
        ], 201);
    }

    /**
     * Search an appointment by folio or telephone number.
     */
    public function consultar(Request $request): JsonResponse
    {
        $query = trim((string) $request->input('query', ''));

        if ($query === '') {
            return response()->json([
                'success' => false,
                'mensaje' => 'Por favor ingresa un número de folio o teléfono.',
            ], 422);
        }

        $reserva = Reserva::where('folio', $query)
            ->orWhere('telefono', $query)
            ->orWhere('telefono', 'like', '%'.$query.'%')
            ->latest()
            ->first();

        if (! $reserva) {
            return response()->json([
                'success' => false,
                'mensaje' => 'No se encontró ninguna cita con el folio o teléfono: '.$query,
            ], 404);
        }

        return response()->json([
            'success' => true,
            'reserva' => $reserva,
        ]);
    }
}
