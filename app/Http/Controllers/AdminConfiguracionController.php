<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\View\View;

class AdminConfiguracionController extends Controller
{
    protected string $configFile;

    public function __construct()
    {
        $this->configFile = storage_path('app/business_config.json');
    }

    /**
     * Get the current business configuration merged with defaults.
     *
     * @return array<string, mixed>
     */
    protected function getConfig(): array
    {
        $defaults = [
            'nombre_negocio' => 'Barber Bless',
            'slogan' => 'Cortes con estilo y elegancia urbana',
            'telefono' => '+591 7123 4567',
            'email_contacto' => 'contacto@barberbless.bo',
            'direccion' => 'Av. 16 de Julio (El Prado) #1440, La Paz',
            'codigo_postal' => '0000',
            'ciudad' => 'La Paz, Bolivia',
            'pais' => 'Bolivia',
            'moneda' => 'BOB',
            'hora_apertura' => '09:00',
            'hora_cierre' => '20:00',
            'dias_laborables' => 'Lunes a Sábado',
            'intervalo_citas' => '30',
            'anticipacion_maxima' => '15',
            'recordatorios_whatsapp' => true,
            'notificaciones_email' => true,
        ];

        if (File::exists($this->configFile)) {
            $saved = json_decode((string) File::get($this->configFile), true);
            if (is_array($saved)) {
                return array_merge($defaults, $saved);
            }
        }

        return $defaults;
    }

    /**
     * Show the business configuration page.
     */
    public function index(Request $request): View
    {
        $config = $this->getConfig();

        return view('dashboard.configuracion', compact('config'));
    }

    /**
     * Update business settings.
     */
    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nombre_negocio' => ['required', 'string', 'max:100'],
            'slogan' => ['nullable', 'string', 'max:150'],
            'telefono' => ['required', 'string', 'max:30'],
            'email_contacto' => ['nullable', 'string', 'max:100'],
            'direccion' => ['required', 'string', 'max:255'],
            'codigo_postal' => ['nullable', 'string', 'max:20'],
            'ciudad' => ['nullable', 'string', 'max:100'],
            'pais' => ['nullable', 'string', 'max:50'],
            'moneda' => ['nullable', 'string', 'max:50'],
            'hora_apertura' => ['nullable', 'string', 'max:10'],
            'hora_cierre' => ['nullable', 'string', 'max:10'],
            'dias_laborables' => ['nullable', 'string', 'max:100'],
            'intervalo_citas' => ['nullable', 'string', 'max:10'],
            'anticipacion_maxima' => ['nullable', 'string', 'max:10'],
            'recordatorios_whatsapp' => ['nullable'],
            'notificaciones_email' => ['nullable'],
        ]);

        $validated['recordatorios_whatsapp'] = $request->boolean('recordatorios_whatsapp');
        $validated['notificaciones_email'] = $request->boolean('notificaciones_email');

        $current = $this->getConfig();
        $updated = array_merge($current, $validated);

        File::ensureDirectoryExists(dirname($this->configFile));
        File::put($this->configFile, json_encode($updated, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

        return back()->with('success_config', 'La configuración del negocio ha sido actualizada correctamente.');
    }
}
