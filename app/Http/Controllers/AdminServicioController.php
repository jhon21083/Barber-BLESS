<?php

namespace App\Http\Controllers;

use App\Models\Reserva;
use App\Models\Servicio;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\View\View;

class AdminServicioController extends Controller
{
    /** Directorio donde se guardan las imágenes de servicios. */
    private string $imgDir;

    public function __construct()
    {
        $this->imgDir = public_path('images/servicios');
    }

    /**
     * Show the services catalog with real DB stats.
     */
    public function index(Request $request): View
    {
        // Stats reales
        $totalActivos = Servicio::where('activo', true)->count();

        // Servicio más solicitado en reservas
        $topRow = Reserva::selectRaw('servicio, COUNT(*) as total')
            ->groupBy('servicio')
            ->orderByDesc('total')
            ->first();

        $servicioTop = $topRow?->servicio
            ? Str::limit($topRow->servicio, 30)
            : 'Sin datos aún';

        // Ticket promedio de reservas completadas
        $completadas = Reserva::where('estado', 'Completada')->get();
        $ticketProm = 'Bs. 0';
        if ($completadas->count() > 0) {
            $suma = $completadas->sum(function (Reserva $r) {
                preg_match('/\d+/', (string) $r->precio, $m);

                return isset($m[0]) ? (int) $m[0] : 0;
            });
            $ticketProm = 'Bs. '.round($suma / $completadas->count());
        }

        // Tiempo promedio (promedio de minutos de los servicios activos)
        $serviciosActivos = Servicio::where('activo', true)->get();
        $tiempoProm = '0 min';
        if ($serviciosActivos->count() > 0) {
            $mins = $serviciosActivos->map(function (Servicio $s) {
                preg_match('/\d+/', $s->duracion, $m);

                return isset($m[0]) ? (int) $m[0] : 0;
            });
            $tiempoProm = round($mins->avg()).' min';
        }

        $stats = [
            'total_servicios' => $totalActivos,
            'servicio_top' => $servicioTop,
            'ticket_promedio' => $ticketProm,
            'tiempo_promedio' => $tiempoProm,
        ];

        // Filtro de categoría
        $query = Servicio::orderBy('orden')->orderBy('id');
        if ($cat = $request->input('categoria')) {
            $query->where('categoria', $cat);
        }
        $servicios = $query->get();

        $categorias = Servicio::distinct('categoria')->pluck('categoria')->sort()->values();

        return view('dashboard.servicios', compact('stats', 'servicios', 'categorias'));
    }

    /**
     * Store a new service with optional image upload.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nombre' => ['required', 'string', 'max:150'],
            'categoria' => ['required', 'string', 'max:60'],
            'descripcion' => ['nullable', 'string', 'max:500'],
            'precio' => ['required', 'numeric', 'min:0'],
            'duracion' => ['required', 'string', 'max:20'],
            'popular' => ['nullable'],
            'imagen' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ]);

        $validated['popular'] = $request->boolean('popular');
        $validated['activo'] = true;
        $validated['orden'] = Servicio::max('orden') + 1;
        $validated['precio'] = (string) $validated['precio'];

        // Guardar imagen si se subió
        $validated['imagen'] = $this->saveImage($request, null);

        Servicio::create($validated);

        return back()->with('success_servicio', "Servicio \"{$validated['nombre']}\" creado correctamente.");
    }

    /**
     * Update an existing service.
     */
    public function update(Request $request, Servicio $servicio): RedirectResponse
    {
        $validated = $request->validate([
            'nombre' => ['required', 'string', 'max:150'],
            'categoria' => ['required', 'string', 'max:60'],
            'descripcion' => ['nullable', 'string', 'max:500'],
            'precio' => ['required', 'numeric', 'min:0'],
            'duracion' => ['required', 'string', 'max:20'],
            'popular' => ['nullable'],
            'imagen' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ]);

        $validated['popular'] = $request->boolean('popular');
        $validated['precio'] = (string) $validated['precio'];

        // Si se sube nueva imagen, borra la vieja
        $nuevaImagen = $this->saveImage($request, $servicio->imagen);
        if ($nuevaImagen !== null) {
            $validated['imagen'] = $nuevaImagen;
        } else {
            unset($validated['imagen']); // conserva la imagen existente
        }

        $servicio->update($validated);

        return back()->with('success_servicio', "Servicio \"{$servicio->nombre}\" actualizado.");
    }

    /**
     * Toggle activo/inactivo.
     */
    public function toggleActivo(Servicio $servicio): RedirectResponse
    {
        $servicio->update(['activo' => ! $servicio->activo]);
        $label = $servicio->activo ? 'activado' : 'desactivado';

        return back()->with('success_servicio', "\"{$servicio->nombre}\" {$label}.");
    }

    /**
     * Delete a service and its image.
     */
    public function destroy(Servicio $servicio): RedirectResponse
    {
        $this->deleteImage($servicio->imagen);
        $nombre = $servicio->nombre;
        $servicio->delete();

        return back()->with('success_servicio', "Servicio \"{$nombre}\" eliminado.");
    }

    // ── Helpers ──────────────────────────────────────────────────────────

    /**
     * Save uploaded image and return filename, or null if none uploaded.
     * Deletes $oldFilename if a new image is provided.
     */
    private function saveImage(Request $request, ?string $oldFilename): ?string
    {
        if (! $request->hasFile('imagen') || ! $request->file('imagen')->isValid()) {
            return null;
        }

        // Delete old image
        $this->deleteImage($oldFilename);

        File::ensureDirectoryExists($this->imgDir);

        $file = $request->file('imagen');
        $extension = $file->getClientOriginalExtension();
        $filename = Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME))
            .'-'.time().'.'.$extension;

        $file->move($this->imgDir, $filename);

        return $filename;
    }

    /** Delete an image file from disk if it exists. */
    private function deleteImage(?string $filename): void
    {
        if ($filename && File::exists($this->imgDir.'/'.$filename)) {
            File::delete($this->imgDir.'/'.$filename);
        }
    }
}
