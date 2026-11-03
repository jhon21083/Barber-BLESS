<?php

namespace App\Http\Controllers;

use App\Models\Barbero;
use App\Models\Servicio;
use Illuminate\Support\Facades\File;
use Illuminate\View\View;

class LandingController extends Controller
{
    /**
     * Path to the persisted business configuration file.
     */
    protected string $configFile;

    public function __construct()
    {
        $this->configFile = storage_path('app/business_config.json');
    }

    /**
     * Read the business config, falling back to sensible defaults.
     *
     * @return array<string, mixed>
     */
    protected function getConfig(): array
    {
        $defaults = [
            'nombre_negocio' => 'Barber Bless',
            'slogan' => 'ESTILO QUE HABLA POR TI',
            'telefono' => '+591 71234567',
            'email_contacto' => 'contacto@barberbless.bo',
            'direccion' => 'Av. 16 de Julio (El Prado) #1440, La Paz',
            'ciudad' => 'La Paz, Bolivia',
            'hora_apertura' => '09:00',
            'hora_cierre' => '20:00',
            'dias_laborables' => 'Lunes a Sábado',
            'moneda' => 'BOB',
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
     * Show the application landing page with dynamic business config.
     */
    public function index(): View
    {
        $biz = $this->getConfig();

        // Build a clean WhatsApp link from the stored phone number
        $waNumber = preg_replace('/\D/', '', $biz['telefono']);
        $waLink = 'https://wa.me/'.$waNumber;

        // Servicios activos desde BD
        $serviciosLanding = Servicio::activos()->get();

        // Destacados: los populares primero, máx. 3
        $destacados = Servicio::activos()
            ->orderByDesc('popular')
            ->limit(3)
            ->get();

        // Barberos activos desde BD
        $barberosLanding = Barbero::activos()->get();

        return view('landing', compact('biz', 'waLink', 'serviciosLanding', 'destacados', 'barberosLanding'));
    }
}
