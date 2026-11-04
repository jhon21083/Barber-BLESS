@extends('layouts.admin')

@section('title', 'Barberos | Barber Bless')
@section('page-title', 'Equipo de Barberos')
@section('page-subtitle', 'Staff profesional, estado de turnos y métricas de desempeño')

@section('admin-content')

    {{-- Alerta de éxito --}}
    @if(session('success_barbero'))
        <div class="flex items-center gap-2 px-4 py-3 rounded-lg bg-emerald-500/15 border border-emerald-500/30 text-emerald-300 text-sm">
            ✔ {{ session('success_barbero') }}
        </div>
    @endif

    {{-- ── KPI stats reales ────────────────────────────────────────────── --}}
    <section class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4">
        <div class="card-dark p-5">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-xs tracking-wider text-slate-400 uppercase">Total en Plantilla</p>
                    <p class="mt-2 text-3xl font-extrabold {{ $stats['total_barberos'] === 0 ? 'text-slate-500' : 'text-[#00d4ff] glow-cyan' }}" id="stat-total-barberos">
                        {{ $stats['total_barberos'] }}
                    </p>
                    <p class="mt-1 text-xs text-slate-400">Barberos activos</p>
                </div>
                <span class="grid place-items-center w-11 h-11 rounded-xl bg-[#00d4ff]/15 text-2xl">✂</span>
            </div>
        </div>

        <div class="card-dark p-5">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-xs tracking-wider text-slate-400 uppercase">Con Reservas Hoy</p>
                    <p class="mt-2 text-3xl font-extrabold {{ $stats['en_turno'] === 0 ? 'text-slate-500' : 'text-emerald-400' }}">
                        {{ $stats['en_turno'] }}
                    </p>
                    <p class="mt-1 text-xs {{ $stats['en_turno'] === 0 ? 'text-slate-500' : 'text-emerald-400' }}">
                        {{ $stats['en_turno'] === 0 ? 'Sin reservas hoy' : 'Con clientes hoy' }}
                    </p>
                </div>
                <span class="grid place-items-center w-11 h-11 rounded-xl bg-emerald-400/15 text-2xl">⚡</span>
            </div>
        </div>

        <div class="card-dark p-5">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-xs tracking-wider text-slate-400 uppercase">Reservas del Día</p>
                    <p class="mt-2 text-3xl font-extrabold {{ $stats['cortes_hoy'] === 0 ? 'text-slate-500' : 'text-white' }}">
                        {{ $stats['cortes_hoy'] }}
                    </p>
                    <p class="mt-1 text-xs text-slate-400">
                        {{ $stats['cortes_hoy'] === 0 ? 'Sin reservas aún' : 'Reservas registradas hoy' }}
                    </p>
                </div>
                <span class="grid place-items-center w-11 h-11 rounded-xl bg-slate-800 text-2xl">📅</span>
            </div>
        </div>

        <div class="card-dark p-5">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-xs tracking-wider text-slate-400 uppercase">Rating Promedio</p>
                    <p class="mt-2 text-3xl font-extrabold {{ $stats['rating_promedio'] === '—' ? 'text-slate-500' : 'text-amber-300' }}">
                        {{ $stats['rating_promedio'] }}
                    </p>
                    <p class="mt-1 text-xs text-slate-400">
                        {{ $stats['rating_promedio'] === '—' ? 'Sin barberos aún' : 'Promedio del equipo' }}
                    </p>
                </div>
                <span class="grid place-items-center w-11 h-11 rounded-xl bg-amber-400/15 text-2xl">⭐</span>
            </div>
        </div>
    </section>

    {{-- ── Header con botón agregar ─────────────────────────────────────── --}}
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-lg font-bold text-white">Estado del Staff</h2>
            <p class="text-xs text-slate-400">Datos en tiempo real desde las reservas registradas</p>
        </div>
        <button type="button" id="btn-add-barbero"
            class="btn-cyan text-sm flex items-center gap-2">
            + Añadir Barbero
        </button>
    </div>

    {{-- ── Modal agregar barbero ────────────────────────────────────────── --}}
    <div id="modal-add-barbero" class="fixed inset-0 z-50 hidden" role="dialog" aria-modal="true">
        <div id="modal-add-backdrop" class="absolute inset-0 bg-black/70 backdrop-blur-sm"></div>
        <div class="relative flex items-center justify-center min-h-screen px-4">
            <div class="relative w-full max-w-md bg-[#0a1428] border border-[#00d4ff]/25 rounded-2xl shadow-[0_0_60px_rgba(0,212,255,0.12)] overflow-hidden">

                <div class="flex items-center justify-between px-6 py-4 border-b border-[#00d4ff]/15 bg-[#080d19]">
                    <h3 class="text-base font-extrabold text-white">Agregar Nuevo Barbero</h3>
                    <button id="btn-close-modal" type="button"
                        class="w-8 h-8 grid place-items-center rounded-lg border border-[#00d4ff]/25 text-slate-400 hover:text-white transition-colors">✕</button>
                </div>

                <form id="form-add-barbero" class="p-6 space-y-4">
                    @csrf

                    {{-- Mensaje de error inline --}}
                    <div id="barbero-error" class="hidden px-4 py-3 rounded-lg bg-rose-500/15 border border-rose-500/30 text-rose-300 text-sm"></div>

                    <div>
                        <label class="block text-xs tracking-wider text-slate-400 mb-1.5">NOMBRE COMPLETO *</label>
                        <input type="text" name="nombre" id="b-nombre" class="input-dark" placeholder="Ej. Carlos Quispe" required>
                    </div>
                    <div>
                        <label class="block text-xs tracking-wider text-slate-400 mb-1.5">ROL / TÍTULO</label>
                        <input type="text" name="rol" id="b-rol" class="input-dark" placeholder="Ej. Master Barber & Estilista">
                    </div>
                    <div>
                        <label class="block text-xs tracking-wider text-slate-400 mb-1.5">ESPECIALIDAD</label>
                        <input type="text" name="especialidad" id="b-especialidad" class="input-dark" placeholder="Ej. Fade, Degradados y Diseños">
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs tracking-wider text-slate-400 mb-1.5">HORARIO</label>
                            <input type="text" name="horario" id="b-horario" class="input-dark" placeholder="09:00 AM - 06:00 PM">
                        </div>
                        <div>
                            <label class="block text-xs tracking-wider text-slate-400 mb-1.5">TELÉFONO</label>
                            <input type="text" name="telefono" id="b-telefono" class="input-dark" placeholder="+591 7000 0000">
                        </div>
                    </div>

                    <div class="flex gap-3 pt-2">
                        <button type="submit" id="btn-submit-barbero" class="btn-cyan flex-1">Guardar Barbero</button>
                        <button type="button" id="btn-cancel-modal" class="btn-outline flex-1 text-sm">Cancelar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- ── Cards de barberos ───────────────────────────────────────────── --}}
    @if(count($barberoData) === 0)
        <div id="barberos-empty" class="card-dark p-16 flex flex-col items-center justify-center text-center gap-4">
            <span class="text-6xl opacity-20">✂</span>
            <p class="text-slate-400 font-semibold text-lg">No hay barberos registrados aún</p>
            <p class="text-slate-500 text-sm">Usa el botón "Añadir Barbero" para registrar al primer miembro del equipo.</p>
            <button type="button" id="btn-add-barbero-2" class="btn-cyan text-sm mt-2">+ Añadir Primer Barbero</button>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-5" id="barberos-grid">
            @foreach($barberoData as $b)
                @php
                    $estadoColor = match($b['estado']) {
                        'Disponible' => 'text-emerald-300 bg-emerald-500/15 border-emerald-500/30',
                        'En corte'   => 'text-[#00d4ff] bg-[#00d4ff]/15 border-[#00d4ff]/30',
                        'Descanso'   => 'text-amber-300 bg-amber-500/15 border-amber-500/30',
                        default      => 'text-slate-400 bg-slate-500/15 border-slate-500/30',
                    };
                    $inicial = strtoupper(substr($b['nombre'], 0, 1));
                @endphp

                <div class="card-dark p-5 flex flex-col gap-4">

                    {{-- Header --}}
                    <div class="flex items-start justify-between gap-3">
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-[#00d4ff]/30 to-[#0096ff]/30 border border-[#00d4ff]/30 text-[#00d4ff] font-extrabold text-xl grid place-items-center shrink-0">
                                {{ $inicial }}
                            </div>
                            <div>
                                <p class="font-bold text-white leading-tight">{{ $b['nombre'] }}</p>
                                <p class="text-xs text-[#00d4ff]">{{ $b['rol'] }}</p>
                            </div>
                        </div>
                        {{-- Badge estado --}}
                        <span class="px-2.5 py-1 rounded-full text-[11px] font-bold border {{ $estadoColor }} whitespace-nowrap shrink-0">
                            {{ $b['estado'] }}
                        </span>
                    </div>

                    {{-- Especialidad --}}
                    @if($b['especialidad'])
                        <p class="text-xs text-slate-400">
                            🎯 <strong class="text-slate-300">Especialidad:</strong> {{ $b['especialidad'] }}
                        </p>
                    @endif

                    {{-- Métricas reales --}}
                    <div class="grid grid-cols-3 gap-2 text-center">
                        <div class="bg-[#080d19] rounded-lg p-2">
                            <p class="text-[10px] text-slate-500 uppercase tracking-wider">Hoy</p>
                            <p class="text-lg font-extrabold {{ $b['cortes_hoy'] === 0 ? 'text-slate-600' : 'text-white' }}">
                                {{ $b['cortes_hoy'] }}
                            </p>
                        </div>
                        <div class="bg-[#080d19] rounded-lg p-2">
                            <p class="text-[10px] text-slate-500 uppercase tracking-wider">Rating</p>
                            <p class="text-lg font-extrabold text-amber-300">{{ number_format($b['rating'], 1) }} ★</p>
                        </div>
                        <div class="bg-[#080d19] rounded-lg p-2">
                            <p class="text-[10px] text-slate-500 uppercase tracking-wider">Completados</p>
                            <p class="text-lg font-extrabold {{ $b['total_cortes'] === 0 ? 'text-slate-600' : 'text-[#00d4ff]' }}">
                                {{ $b['total_cortes'] }}
                            </p>
                        </div>
                    </div>

                    {{-- Info contacto --}}
                    <div class="flex items-center justify-between text-xs text-slate-500 pt-1 border-t border-[#00d4ff]/10">
                        <span>🕐 {{ $b['horario'] }}</span>
                        @if($b['telefono'])
                            <span>📱 {{ $b['telefono'] }}</span>
                        @endif
                    </div>

                    {{-- Acciones --}}
                    <div class="flex gap-2">
                        {{-- Cambiar estado --}}
                        <form method="POST" action="{{ route('admin.barberos.estado', $b['id']) }}" class="flex-1">
                            @csrf @method('PATCH')
                            <select name="estado" onchange="this.form.submit()"
                                class="input-dark text-xs py-2 w-full">
                                @foreach(['Disponible','En corte','Descanso','Inactivo'] as $opt)
                                    <option value="{{ $opt }}" {{ $b['estado'] === $opt ? 'selected' : '' }}>
                                        {{ $opt }}
                                    </option>
                                @endforeach
                            </select>
                        </form>

                        {{-- Desactivar --}}
                        <form method="POST" action="{{ route('admin.barberos.destroy', $b['id']) }}"
                            onsubmit="return confirm('¿Desactivar a {{ $b['nombre'] }}?')">
                            @csrf @method('DELETE')
                            <button type="submit"
                                class="px-3 py-2 rounded-lg border border-rose-500/30 text-rose-400 hover:bg-rose-500/15 transition-colors text-xs font-semibold">
                                Desactivar
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

@endsection

@push('scripts')
<script>
(function () {
    // ── Modal open/close ──────────────────────────────────────────────────
    const modal     = document.getElementById('modal-add-barbero');
    const openModal  = () => { modal.classList.remove('hidden'); document.body.style.overflow = 'hidden'; };
    const closeModal = () => {
        modal.classList.add('hidden');
        document.body.style.overflow = '';
        document.getElementById('form-add-barbero').reset();
        document.getElementById('barbero-error').classList.add('hidden');
        document.getElementById('btn-submit-barbero').textContent = 'Guardar Barbero';
        document.getElementById('btn-submit-barbero').disabled = false;
    };

    document.getElementById('btn-add-barbero').addEventListener('click', openModal);
    document.getElementById('btn-close-modal').addEventListener('click', closeModal);
    document.getElementById('btn-cancel-modal').addEventListener('click', closeModal);
    document.getElementById('modal-add-backdrop').addEventListener('click', closeModal);
    const btn2 = document.getElementById('btn-add-barbero-2');
    if (btn2) { btn2.addEventListener('click', openModal); }
    document.addEventListener('keydown', e => { if (e.key === 'Escape') { closeModal(); } });

    // ── AJAX submit ───────────────────────────────────────────────────────
    document.getElementById('form-add-barbero').addEventListener('submit', async (e) => {
        e.preventDefault();

        const btn      = document.getElementById('btn-submit-barbero');
        const errBox   = document.getElementById('barbero-error');
        const nombre   = document.getElementById('b-nombre').value.trim();
        const rol      = document.getElementById('b-rol').value.trim();
        const esp      = document.getElementById('b-especialidad').value.trim();
        const horario  = document.getElementById('b-horario').value.trim();
        const telefono = document.getElementById('b-telefono').value.trim();

        if (!nombre) {
            errBox.textContent = 'El nombre del barbero es obligatorio.';
            errBox.classList.remove('hidden');
            return;
        }

        btn.disabled = true;
        btn.textContent = '⏳ Guardando…';
        errBox.classList.add('hidden');

        try {
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content ?? '';

            // Usar FormData es más confiable con Laravel CSRF + validación
            const formData = new FormData();
            formData.append('nombre',      nombre);
            formData.append('rol',         rol);
            formData.append('especialidad', esp);
            formData.append('horario',     horario);
            formData.append('telefono',    telefono);
            formData.append('_token',      csrfToken);

            const res = await fetch('{{ route('admin.barberos.store') }}', {
                method:  'POST',
                headers: {
                    'Accept':             'application/json',
                    'X-CSRF-TOKEN':       csrfToken,
                    'X-Requested-With':   'XMLHttpRequest',
                },
                body: formData,
            });

            // Si el servidor devuelve HTML (error inesperado) en vez de JSON
            const contentType = res.headers.get('content-type') || '';
            if (!contentType.includes('application/json')) {
                errBox.textContent = '⚠ Error del servidor. Recarga la página e intenta de nuevo.';
                errBox.classList.remove('hidden');
                btn.disabled = false;
                btn.textContent = 'Guardar Barbero';
                return;
            }

            const data = await res.json();

            if (!res.ok || !data.success) {
                // Mostrar el error exacto del servidor
                let errorMsg = 'No se pudo guardar el barbero.';
                if (data.errors) {
                    errorMsg = Object.values(data.errors).flat().join(' · ');
                } else if (data.mensaje) {
                    errorMsg = data.mensaje;
                } else if (data.message) {
                    errorMsg = data.message;
                }
                errBox.textContent = '⚠ ' + errorMsg;
                errBox.classList.remove('hidden');
                btn.disabled = false;
                btn.textContent = 'Guardar Barbero';
                return;
            }

            // ── Insertar card del nuevo barbero sin recargar ──────────────
            const b = data.barbero;
            insertarCardBarbero(b);

            // Actualizar contador
            const statEl = document.getElementById('stat-total-barberos');
            if (statEl) {
                const actual = parseInt(statEl.textContent.trim()) || 0;
                statEl.textContent = actual + 1;
                statEl.classList.remove('text-slate-500');
                statEl.classList.add('text-[#00d4ff]', 'glow-cyan');
            }

            // Ocultar estado vacío si estaba
            const emptyEl = document.getElementById('barberos-empty');
            if (emptyEl) { emptyEl.remove(); }

            // Mostrar alerta de éxito temporal
            mostrarAlertaExito(data.mensaje);

            closeModal();

        } catch (err) {
            errBox.textContent = '⚠ Error: ' + (err.message || 'Conexión fallida. Verifica que estás conectado.');
            errBox.classList.remove('hidden');
            btn.disabled = false;
            btn.textContent = 'Guardar Barbero';
        }
    });

    // ── Generar HTML de la card del barbero ───────────────────────────────
    function insertarCardBarbero(b) {
        const grid = document.getElementById('barberos-grid');
        if (!grid) { return; }

        const inicial     = (b.nombre.charAt(0) || '?').toUpperCase();
        const estadoColor = 'text-emerald-300 bg-emerald-500/15 border-emerald-500/30';
        const updateRoute = '{{ url('admin/barberos') }}/' + b.id + '/estado';
        const deleteRoute = '{{ url('admin/barberos') }}/' + b.id;
        const csrf        = document.querySelector('meta[name="csrf-token"]')?.content ?? '';

        const card = document.createElement('div');
        card.className = 'card-dark p-5 flex flex-col gap-4';
        card.innerHTML = `
            <div class="flex items-start justify-between gap-3">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-[#00d4ff]/30 to-[#0096ff]/30 border border-[#00d4ff]/30 text-[#00d4ff] font-extrabold text-xl grid place-items-center shrink-0">
                        ${inicial}
                    </div>
                    <div>
                        <p class="font-bold text-white leading-tight">${escHtml(b.nombre)}</p>
                        <p class="text-xs text-[#00d4ff]">${escHtml(b.rol)}</p>
                    </div>
                </div>
                <span class="px-2.5 py-1 rounded-full text-[11px] font-bold border ${estadoColor} whitespace-nowrap shrink-0">
                    ${escHtml(b.estado)}
                </span>
            </div>

            ${b.especialidad ? `<p class="text-xs text-slate-400">🎯 <strong class="text-slate-300">Especialidad:</strong> ${escHtml(b.especialidad)}</p>` : ''}

            <div class="grid grid-cols-3 gap-2 text-center">
                <div class="bg-[#080d19] rounded-lg p-2">
                    <p class="text-[10px] text-slate-500 uppercase tracking-wider">Hoy</p>
                    <p class="text-lg font-extrabold text-slate-600">0</p>
                </div>
                <div class="bg-[#080d19] rounded-lg p-2">
                    <p class="text-[10px] text-slate-500 uppercase tracking-wider">Rating</p>
                    <p class="text-lg font-extrabold text-amber-300">${escHtml(b.rating)} ★</p>
                </div>
                <div class="bg-[#080d19] rounded-lg p-2">
                    <p class="text-[10px] text-slate-500 uppercase tracking-wider">Completados</p>
                    <p class="text-lg font-extrabold text-slate-600">0</p>
                </div>
            </div>

            <div class="flex items-center justify-between text-xs text-slate-500 pt-1 border-t border-[#00d4ff]/10">
                <span>🕐 ${escHtml(b.horario)}</span>
                ${b.telefono ? `<span>📱 ${escHtml(b.telefono)}</span>` : ''}
            </div>

            <div class="flex gap-2">
                <form method="POST" action="${updateRoute}" class="flex-1">
                    <input type="hidden" name="_token" value="${csrf}">
                    <input type="hidden" name="_method" value="PATCH">
                    <select name="estado" onchange="this.form.submit()" class="input-dark text-xs py-2 w-full">
                        <option value="Disponible" selected>Disponible</option>
                        <option value="En corte">En corte</option>
                        <option value="Descanso">Descanso</option>
                        <option value="Inactivo">Inactivo</option>
                    </select>
                </form>
                <form method="POST" action="${deleteRoute}" onsubmit="return confirm('¿Desactivar a ${escHtml(b.nombre)}?')">
                    <input type="hidden" name="_token" value="${csrf}">
                    <input type="hidden" name="_method" value="DELETE">
                    <button type="submit" class="px-3 py-2 rounded-lg border border-rose-500/30 text-rose-400 hover:bg-rose-500/15 transition-colors text-xs font-semibold">
                        Desactivar
                    </button>
                </form>
            </div>
        `;

        grid.appendChild(card);
    }

    // ── Alerta temporal de éxito ──────────────────────────────────────────
    function mostrarAlertaExito(mensaje) {
        const alerta = document.createElement('div');
        alerta.className = 'fixed top-4 right-4 z-[9999] flex items-center gap-2 px-5 py-3 rounded-xl bg-emerald-500/20 border border-emerald-500/40 text-emerald-300 text-sm font-semibold shadow-lg transition-all';
        alerta.textContent = '✔ ' + mensaje;
        document.body.appendChild(alerta);
        setTimeout(() => {
            alerta.style.opacity = '0';
            alerta.style.transform = 'translateY(-10px)';
            alerta.style.transition = 'opacity 0.4s, transform 0.4s';
            setTimeout(() => alerta.remove(), 400);
        }, 3000);
    }

    // ── Escapar HTML para prevenir XSS ────────────────────────────────────
    function escHtml(str) {
        if (!str) { return ''; }
        return String(str)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;');
    }
})();
</script>
@endpush
