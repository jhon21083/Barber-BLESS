@extends('layouts.admin')

@section('title', 'Servicios | Barber Bless')
@section('page-title', 'Catálogo de Servicios')
@section('page-subtitle', 'Administra los cortes, paquetes, precios y tiempos de atención')

@section('admin-content')

    {{-- Alerta éxito --}}
    @if(session('success_servicio'))
        <div class="flex items-center gap-2 px-4 py-3 rounded-lg bg-emerald-500/15 border border-emerald-500/30 text-emerald-300 text-sm">
            ✔ {{ session('success_servicio') }}
        </div>
    @endif

    {{-- ── KPI stats reales ────────────────────────────────────────────── --}}
    <section class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4">
        <div class="card-dark p-5">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-xs tracking-wider text-slate-400 uppercase">Servicios Activos</p>
                    <p class="mt-2 text-3xl font-extrabold {{ $stats['total_servicios'] === 0 ? 'text-slate-500' : 'text-[#00d4ff] glow-cyan' }}">
                        {{ $stats['total_servicios'] }}
                    </p>
                    <p class="mt-1 text-xs text-slate-400">En el catálogo</p>
                </div>
                <span class="grid place-items-center w-11 h-11 rounded-xl bg-[#00d4ff]/15 text-2xl">📋</span>
            </div>
        </div>
        <div class="card-dark p-5">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-xs tracking-wider text-slate-400 uppercase">Más Solicitado</p>
                    <p class="mt-2 text-sm font-extrabold {{ $stats['servicio_top'] === 'Sin datos aún' ? 'text-slate-500' : 'text-[#00d4ff]' }} leading-tight">
                        {{ $stats['servicio_top'] }}
                    </p>
                    <p class="mt-1 text-xs text-slate-400">En reservas totales</p>
                </div>
                <span class="grid place-items-center w-11 h-11 rounded-xl bg-amber-400/15 text-2xl">🔥</span>
            </div>
        </div>
        <div class="card-dark p-5">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-xs tracking-wider text-slate-400 uppercase">Ticket Promedio</p>
                    <p class="mt-2 text-3xl font-extrabold {{ $stats['ticket_promedio'] === 'Bs. 0' ? 'text-slate-500' : 'text-white' }}">
                        {{ $stats['ticket_promedio'] }}
                    </p>
                    <p class="mt-1 text-xs text-slate-400">Por servicio completado</p>
                </div>
                <span class="grid place-items-center w-11 h-11 rounded-xl bg-emerald-400/15 text-2xl">💵</span>
            </div>
        </div>
        <div class="card-dark p-5">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-xs tracking-wider text-slate-400 uppercase">Tiempo Promedio</p>
                    <p class="mt-2 text-3xl font-extrabold {{ $stats['tiempo_promedio'] === '0 min' ? 'text-slate-500' : 'text-[#0096ff]' }}">
                        {{ $stats['tiempo_promedio'] }}
                    </p>
                    <p class="mt-1 text-xs text-slate-400">Duración media de servicios</p>
                </div>
                <span class="grid place-items-center w-11 h-11 rounded-xl bg-[#0096ff]/15 text-2xl">⏱</span>
            </div>
        </div>
    </section>

    {{-- ── Filtros + botón nuevo ────────────────────────────────────────── --}}
    <div class="flex flex-wrap items-center justify-between gap-3">
        <div class="flex flex-wrap gap-2">
            <a href="{{ route('admin.servicios') }}"
               class="px-4 py-1.5 rounded-full text-sm font-semibold transition-all {{ !request('categoria') ? 'bg-[#00d4ff] text-[#04121f] shadow-[0_0_12px_rgba(0,212,255,0.35)]' : 'border border-[#00d4ff]/25 text-slate-300 hover:border-[#00d4ff]/50' }}">
                Todos
            </a>
            @foreach($categorias as $cat)
                <a href="{{ route('admin.servicios', ['categoria' => $cat]) }}"
                   class="px-4 py-1.5 rounded-full text-sm font-semibold transition-all {{ request('categoria') === $cat ? 'bg-[#00d4ff] text-[#04121f] shadow-[0_0_12px_rgba(0,212,255,0.35)]' : 'border border-[#00d4ff]/25 text-slate-300 hover:border-[#00d4ff]/50' }}">
                    {{ $cat }}
                </a>
            @endforeach
        </div>
        <button type="button" id="btn-nuevo-servicio" class="btn-cyan text-sm">+ Nuevo Servicio</button>
    </div>

    {{-- ── MODAL AGREGAR / EDITAR ──────────────────────────────────────── --}}
    <div id="modal-servicio" class="fixed inset-0 z-50 hidden" role="dialog" aria-modal="true">
        <div id="modal-servicio-backdrop" class="absolute inset-0 bg-black/75 backdrop-blur-sm"></div>
        <div class="relative flex items-start justify-center min-h-screen px-3 py-8 overflow-y-auto">
            <div class="relative w-full max-w-2xl bg-[#0a1428] border border-[#00d4ff]/25 rounded-2xl shadow-[0_0_60px_rgba(0,212,255,0.12)] overflow-hidden">

                {{-- Header --}}
                <div class="flex items-center justify-between px-6 py-4 border-b border-[#00d4ff]/15 bg-[#080d19]">
                    <h3 id="modal-servicio-title" class="text-base font-extrabold text-white">Nuevo Servicio</h3>
                    <button id="btn-close-modal-servicio" type="button"
                        class="w-8 h-8 grid place-items-center rounded-lg border border-[#00d4ff]/25 text-slate-400 hover:text-white transition-colors">✕</button>
                </div>

                {{-- Form --}}
                <form id="form-servicio" method="POST" action="{{ route('admin.servicios.store') }}"
                      enctype="multipart/form-data" class="p-6 space-y-4">
                    @csrf
                    {{-- se cambia a POST /admin/servicios/{id} al editar --}}

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                        <div class="sm:col-span-2">
                            <label class="block text-xs tracking-wider text-slate-400 mb-1.5">NOMBRE DEL SERVICIO *</label>
                            <input id="f-nombre" type="text" name="nombre" class="input-dark"
                                   placeholder="Ej. Corte Fade & Degrafilado" required>
                        </div>

                        <div>
                            <label class="block text-xs tracking-wider text-slate-400 mb-1.5">CATEGORÍA *</label>
                            <input id="f-categoria" type="text" name="categoria" class="input-dark"
                                   list="lista-categorias" placeholder="Ej. Cortes" required>
                            <datalist id="lista-categorias">
                                @foreach($categorias as $cat)
                                    <option value="{{ $cat }}">
                                @endforeach
                                <option value="Cortes">
                                <option value="Barba">
                                <option value="Combos VIP">
                                <option value="Tratamientos">
                            </datalist>
                        </div>

                        <div>
                            <label class="block text-xs tracking-wider text-slate-400 mb-1.5">PRECIO (Bs.) *</label>
                            <input id="f-precio" type="number" name="precio" min="0" step="0.5"
                                   class="input-dark" placeholder="60" required>
                        </div>

                        <div>
                            <label class="block text-xs tracking-wider text-slate-400 mb-1.5">DURACIÓN *</label>
                            <input id="f-duracion" type="text" name="duracion" class="input-dark"
                                   placeholder="40 min" required>
                        </div>

                        <div class="flex items-center gap-3 pt-5">
                            <input id="f-popular" type="checkbox" name="popular" value="1"
                                   class="w-4 h-4 rounded text-[#00d4ff] bg-slate-900 border-slate-700 focus:ring-[#00d4ff]">
                            <label for="f-popular" class="text-sm text-slate-300 cursor-pointer">Marcar como Popular ⭐</label>
                        </div>

                        <div class="sm:col-span-2">
                            <label class="block text-xs tracking-wider text-slate-400 mb-1.5">DESCRIPCIÓN</label>
                            <textarea id="f-descripcion" name="descripcion" rows="3"
                                      class="input-dark resize-none"
                                      placeholder="Describe brevemente el servicio…"></textarea>
                        </div>

                        {{-- Subida de imagen --}}
                        <div class="sm:col-span-2">
                            <label class="block text-xs tracking-wider text-slate-400 mb-1.5">IMAGEN DEL SERVICIO</label>
                            <label for="f-imagen"
                                   class="flex flex-col items-center justify-center gap-2 w-full rounded-xl border-2 border-dashed border-[#00d4ff]/30 bg-[#00d4ff]/5 hover:bg-[#00d4ff]/10 transition-colors cursor-pointer py-5 text-center relative overflow-hidden"
                                   id="imagen-drop-area">
                                {{-- Preview --}}
                                <img id="imagen-preview" src="" alt=""
                                     class="hidden w-full h-40 object-cover rounded-lg absolute inset-0">
                                <div id="imagen-placeholder" class="flex flex-col items-center gap-2">
                                    <span class="text-3xl">🖼</span>
                                    <span id="imagen-label" class="text-sm text-[#00d4ff] font-semibold">Subir imagen del corte</span>
                                    <span class="text-xs text-slate-500">JPG, PNG o WEBP · Máx. 5 MB</span>
                                </div>
                            </label>
                            <input id="f-imagen" type="file" name="imagen"
                                   accept="image/jpg,image/jpeg,image/png,image/webp" class="hidden">

                            {{-- Imagen actual al editar --}}
                            <div id="imagen-actual-wrap" class="hidden mt-3 flex items-center gap-3">
                                <img id="imagen-actual-thumb" src="" alt="Imagen actual"
                                     class="w-16 h-16 rounded-lg object-cover border border-[#00d4ff]/30">
                                <div>
                                    <p class="text-xs text-slate-400">Imagen actual guardada</p>
                                    <p class="text-xs text-slate-500">Sube una nueva para reemplazarla</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex gap-3 pt-2">
                        <button type="submit" id="btn-submit-servicio" class="btn-cyan flex-1">
                            Guardar Servicio
                        </button>
                        <button type="button" id="btn-cancel-modal-servicio" class="btn-outline flex-1 text-sm">
                            Cancelar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- ── Grid de servicios ───────────────────────────────────────────── --}}
    @if($servicios->isEmpty())
        <div class="card-dark p-16 flex flex-col items-center justify-center text-center gap-4">
            <span class="text-6xl opacity-20">📋</span>
            <p class="text-slate-400 font-semibold text-lg">No hay servicios registrados aún</p>
            <p class="text-slate-500 text-sm">Usa "+ Nuevo Servicio" para agregar el primer servicio al catálogo.</p>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-5">
            @foreach($servicios as $s)
                @php
                    $catColor = match($s->categoria) {
                        'Cortes'      => 'bg-[#00d4ff]/15 text-[#00d4ff] border-[#00d4ff]/30',
                        'Barba'       => 'bg-emerald-400/15 text-emerald-300 border-emerald-400/30',
                        'Combos VIP'  => 'bg-amber-400/15 text-amber-300 border-amber-400/30',
                        'Tratamientos'=> 'bg-purple-400/15 text-purple-300 border-purple-400/30',
                        default       => 'bg-slate-700/40 text-slate-300 border-slate-600/30',
                    };
                @endphp

                <div class="card-dark overflow-hidden flex flex-col">

                    {{-- Imagen del servicio --}}
                    <div class="relative aspect-video bg-[#070b16] overflow-hidden">
                        @if($s->imagen && file_exists(public_path('images/servicios/'.$s->imagen)))
                            <img src="/images/servicios/{{ $s->imagen }}"
                                 alt="{{ $s->nombre }}"
                                 class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center">
                                <span class="text-5xl opacity-20">✂</span>
                            </div>
                        @endif

                        {{-- Badges top --}}
                        <div class="absolute top-2 left-2 flex gap-1.5 flex-wrap">
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold border {{ $catColor }}">
                                {{ strtoupper($s->categoria) }}
                            </span>
                            @if($s->popular)
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-400/20 text-amber-300 border border-amber-400/40">
                                    ⭐ POPULAR
                                </span>
                            @endif
                        </div>

                        {{-- Estado activo/inactivo --}}
                        <div class="absolute top-2 right-2">
                            <form method="POST" action="{{ route('admin.servicios.toggle', $s->id) }}">
                                @csrf @method('PATCH')
                                <button type="submit"
                                    class="px-2 py-0.5 rounded-full text-[10px] font-bold border transition-all
                                        {{ $s->activo
                                            ? 'bg-emerald-500/20 text-emerald-300 border-emerald-500/40 hover:bg-rose-500/20 hover:text-rose-300 hover:border-rose-500/40'
                                            : 'bg-slate-700/50 text-slate-400 border-slate-600/40 hover:bg-emerald-500/20 hover:text-emerald-300 hover:border-emerald-500/40' }}"
                                    title="{{ $s->activo ? 'Clic para desactivar' : 'Clic para activar' }}">
                                    {{ $s->activo ? '● Activo' : '○ Inactivo' }}
                                </button>
                            </form>
                        </div>
                    </div>

                    {{-- Info --}}
                    <div class="p-4 flex flex-col gap-3 flex-1">
                        <div>
                            <h3 class="font-bold text-white leading-tight">{{ $s->nombre }}</h3>
                            @if($s->descripcion)
                                <p class="text-xs text-slate-400 mt-1 leading-relaxed line-clamp-2">
                                    {{ $s->descripcion }}
                                </p>
                            @endif
                        </div>

                        <div class="flex items-center gap-4 text-xs text-slate-400 mt-auto">
                            <span>⏱ {{ $s->duracion }}</span>
                            <span class="text-2xl font-extrabold text-[#00d4ff] glow-cyan ml-auto">
                                Bs. {{ $s->precio }}
                            </span>
                        </div>

                        {{-- Botones editar / eliminar --}}
                        <div class="flex gap-2 pt-2 border-t border-[#00d4ff]/10">
                            <button type="button"
                                class="btn-editar flex-1 text-xs py-2 px-3 rounded-lg bg-[#00d4ff]/10 text-[#00d4ff] border border-[#00d4ff]/25 hover:bg-[#00d4ff]/20 transition-colors font-semibold"
                                data-id="{{ $s->id }}"
                                data-nombre="{{ $s->nombre }}"
                                data-categoria="{{ $s->categoria }}"
                                data-precio="{{ $s->precio }}"
                                data-duracion="{{ $s->duracion }}"
                                data-descripcion="{{ $s->descripcion }}"
                                data-popular="{{ $s->popular ? '1' : '0' }}"
                                data-imagen="{{ $s->imagen ? '/images/servicios/'.$s->imagen : '' }}">
                                ✏ Editar
                            </button>

                            <form method="POST" action="{{ route('admin.servicios.destroy', $s->id) }}"
                                  onsubmit="return confirm('¿Eliminar el servicio «{{ $s->nombre }}»? Esta acción no se puede deshacer.')">
                                @csrf @method('DELETE')
                                <button type="submit"
                                    class="px-3 py-2 rounded-lg border border-rose-500/30 text-rose-400 hover:bg-rose-500/15 transition-colors text-xs font-semibold">
                                    🗑
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

@endsection

@push('scripts')
<script>
(function () {
    const modal      = document.getElementById('modal-servicio');
    const form       = document.getElementById('form-servicio');
    const title      = document.getElementById('modal-servicio-title');
    const btnSubmit  = document.getElementById('btn-submit-servicio');
    const backdrop   = document.getElementById('modal-servicio-backdrop');

    // Campos
    const fNombre      = document.getElementById('f-nombre');
    const fCategoria   = document.getElementById('f-categoria');
    const fPrecio      = document.getElementById('f-precio');
    const fDuracion    = document.getElementById('f-duracion');
    const fDescripcion = document.getElementById('f-descripcion');
    const fPopular     = document.getElementById('f-popular');
    const fImagen      = document.getElementById('f-imagen');
    const imgLabel     = document.getElementById('imagen-label');
    const imgPreview   = document.getElementById('imagen-preview');
    const imgPlaceholder = document.getElementById('imagen-placeholder');
    const imgActualWrap  = document.getElementById('imagen-actual-wrap');
    const imgActualThumb = document.getElementById('imagen-actual-thumb');

    const baseStore = "{{ route('admin.servicios.store') }}";
    const baseUpdate = "{{ url('admin/servicios') }}";

    function openModal() { modal.classList.remove('hidden'); document.body.style.overflow = 'hidden'; }
    function closeModal() { modal.classList.add('hidden'); document.body.style.overflow = ''; }

    function resetForm() {
        form.reset();
        form.action = baseStore;
        // Quitar _method si existe
        form.querySelectorAll('input[name="_method"]').forEach(el => el.remove());
        title.textContent = 'Nuevo Servicio';
        btnSubmit.textContent = 'Guardar Servicio';
        imgLabel.textContent = 'Subir imagen del corte';
        imgPreview.classList.add('hidden');
        imgPlaceholder.classList.remove('hidden');
        imgActualWrap.classList.add('hidden');
        imgActualThumb.src = '';
    }

    // Abrir modal nuevo
    document.getElementById('btn-nuevo-servicio').addEventListener('click', () => {
        resetForm();
        openModal();
    });

    // Cerrar
    document.getElementById('btn-close-modal-servicio').addEventListener('click', closeModal);
    document.getElementById('btn-cancel-modal-servicio').addEventListener('click', closeModal);
    backdrop.addEventListener('click', closeModal);
    document.addEventListener('keydown', e => { if (e.key === 'Escape') closeModal(); });

    // Preview imagen al seleccionar
    fImagen.addEventListener('change', function () {
        const file = this.files[0];
        if (!file) return;
        imgLabel.textContent = file.name;
        const reader = new FileReader();
        reader.onload = e => {
            imgPreview.src = e.target.result;
            imgPreview.classList.remove('hidden');
            imgPlaceholder.classList.add('hidden');
        };
        reader.readAsDataURL(file);
    });

    // Botones editar
    document.querySelectorAll('.btn-editar').forEach(btn => {
        btn.addEventListener('click', function () {
            resetForm();

            const id       = this.dataset.id;
            const imagen   = this.dataset.imagen;

            // Acción del form → POST /admin/servicios/{id} + _method=POST (el controller lo recibe como update)
            form.action = baseUpdate + '/' + id;
            // Agregar _method override para que Laravel lo trate como update
            const m = document.createElement('input');
            m.type = 'hidden'; m.name = '_method'; m.value = 'POST';
            form.appendChild(m);

            title.textContent = 'Editar Servicio';
            btnSubmit.textContent = 'Actualizar Servicio';

            fNombre.value      = this.dataset.nombre;
            fCategoria.value   = this.dataset.categoria;
            fPrecio.value      = this.dataset.precio;
            fDuracion.value    = this.dataset.duracion;
            fDescripcion.value = this.dataset.descripcion;
            fPopular.checked   = this.dataset.popular === '1';

            if (imagen) {
                imgActualThumb.src = imagen;
                imgActualWrap.classList.remove('hidden');
                imgLabel.textContent = 'Subir nueva imagen (opcional)';
            }

            openModal();
        });
    });
})();
</script>
@endpush
