import './bootstrap';

/**
 * Barber Bless — interacciones de la landing.
 * Todo está envuelto en null-checks para ser seguro en cualquier página.
 */
document.addEventListener('DOMContentLoaded', () => {
    // ---------------------------------------------------------------------------
    // 1. Barra de anuncio: cierre persistente con localStorage
    // ---------------------------------------------------------------------------
    const announcementBar = document.querySelector('.announcement-bar');
    const announcementClose = document.querySelector('.announcement-close');

    if (announcementBar && announcementClose) {
        if (localStorage.getItem('announcement-closed') === '1') {
            announcementBar.style.display = 'none';
        }

        announcementClose.addEventListener('click', () => {
            announcementBar.style.display = 'none';
            localStorage.setItem('announcement-closed', '1');
        });
    }

    // ---------------------------------------------------------------------------
    // 2. Toggle de tema claro/oscuro
    // ---------------------------------------------------------------------------
    const themeToggle = document.getElementById('theme-toggle');
    const iconDark = document.querySelector('.theme-icon-dark');
    const iconLight = document.querySelector('.theme-icon-light');

    const applyTheme = (isLight) => {
        document.documentElement.classList.toggle('theme-light', isLight);
        if (iconDark) iconDark.classList.toggle('hidden', isLight);
        if (iconLight) iconLight.classList.toggle('hidden', !isLight);
    };

    // Restaurar preferencia guardada
    applyTheme(localStorage.getItem('theme') === 'light');

    if (themeToggle) {
        themeToggle.addEventListener('click', () => {
            const isLight = !document.documentElement.classList.contains('theme-light');
            applyTheme(isLight);
            localStorage.setItem('theme', isLight ? 'light' : 'dark');
        });
    }

    // ---------------------------------------------------------------------------
    // 3. Hero slider: indicadores + contador + auto-avance
    // ---------------------------------------------------------------------------
    const slider = document.getElementById('hero-slider');
    const slides = slider ? Array.from(slider.querySelectorAll('.hero-slide')) : [];
    const indicators = Array.from(document.querySelectorAll('.hero-indicator'));
    const counterCurrent = document.getElementById('hero-counter-current');
    const counterTotal = document.getElementById('hero-counter-total');

    if (slides.length > 0 && indicators.length > 0) {
        if (counterTotal) counterTotal.textContent = String(slides.length).padStart(2, '0');

        let current = 0;
        let autoTimer = null;

        const pad = (n) => String(n + 1).padStart(2, '0');

        const showSlide = (index) => {
            current = (index + slides.length) % slides.length;

            slides.forEach((slide, i) => {
                const isActive = i === current;
                slide.classList.toggle('active', isActive);
                slide.classList.toggle('hidden', !isActive);
            });

            indicators.forEach((dot, i) => {
                const isActive = i === current;
                dot.classList.toggle('bg-[#00d4ff]', isActive);
                dot.classList.toggle('bg-slate-600', !isActive);
                dot.classList.toggle('shadow-[0_0_8px_rgba(0,212,255,0.6)]', isActive);
            });

            if (counterCurrent) counterCurrent.textContent = pad(current);
        };

        const startAuto = () => {
            stopAuto();
            autoTimer = setInterval(() => showSlide(current + 1), 6000);
        };

        const stopAuto = () => {
            if (autoTimer) clearInterval(autoTimer);
        };

        indicators.forEach((dot) => {
            dot.addEventListener('click', () => {
                const index = Number(dot.dataset.slide);
                showSlide(index);
                startAuto();
            });
        });

        showSlide(0);
        startAuto();
    }

    // ---------------------------------------------------------------------------
    // 4. Formulario de folio: comprobación simulada (solo cliente)
    // ---------------------------------------------------------------------------
    const folioForm = document.getElementById('folio-form');
    const folioInput = document.getElementById('folio-input');
    const folioResult = document.getElementById('folio-result');

    if (folioForm && folioInput && folioResult) {
        folioForm.addEventListener('submit', async (event) => {
            event.preventDefault();
            const value = folioInput.value.trim();

            if (!value) {
                folioResult.innerHTML = '<span class="text-amber-300">⚠ Ingresa un folio o número de teléfono para continuar.</span>';
                return;
            }

            folioResult.innerHTML = '<span class="text-[#00bcd4]">🔍 Consultando reserva <strong>' + value + '</strong>…</span>';

            try {
                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content ?? '';
                const response = await fetch('/consulta-cita', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                    },
                    body: JSON.stringify({ query: value }),
                });

                const data = await response.json();

                if (!data.success) {
                    folioResult.innerHTML =
                        '<span class="text-rose-400">✗ ' + (data.mensaje || 'No se encontró ninguna cita.') + '</span>';
                    return;
                }

                const r = data.reserva;
                const estadoColor = {
                    'Confirmada':  'text-emerald-300',
                    'En proceso':  'text-[#00d4ff]',
                    'Pendiente':   'text-amber-300',
                    'Completada':  'text-slate-300',
                    'Cancelada':   'text-rose-400',
                }[r.estado] || 'text-slate-300';

                folioResult.innerHTML =
                    '<div class="p-3 rounded-lg bg-emerald-500/10 border border-emerald-500/25 space-y-1">' +
                    '<p class="text-emerald-300 font-bold text-sm">✓ Cita encontrada · <span class="font-mono">' + r.folio + '</span></p>' +
                    '<p class="text-slate-300 text-xs"><span class="text-slate-400">Cliente:</span> ' + r.cliente + '</p>' +
                    '<p class="text-slate-300 text-xs"><span class="text-slate-400">Servicio:</span> ' + r.servicio + '</p>' +
                    '<p class="text-slate-300 text-xs"><span class="text-slate-400">Barbero:</span> ' + r.barbero + '</p>' +
                    '<p class="text-slate-300 text-xs"><span class="text-slate-400">Fecha y hora:</span> ' + r.fecha + (r.hora ? ' · ' + r.hora : '') + '</p>' +
                    '<p class="text-slate-300 text-xs"><span class="text-slate-400">Precio:</span> ' + (r.precio || '—') + ' · <span class="text-slate-400">Pago:</span> ' + (r.estado_pago || '—') + '</p>' +
                    '<p class="text-xs font-bold ' + estadoColor + '">Estado: ' + r.estado + '</p>' +
                    '</div>';

            } catch (err) {
                folioResult.innerHTML = '<span class="text-rose-400">Error de conexión. Intenta de nuevo.</span>';
            }
        });
    }

    // ---------------------------------------------------------------------------
    // 5. Filtros de galería de cortes
    // ---------------------------------------------------------------------------
    const corteFilters = document.querySelectorAll('.corte-filter-btn');
    const corteCards   = document.querySelectorAll('.corte-card');

    if (corteFilters.length > 0 && corteCards.length > 0) {
        corteFilters.forEach((btn) => {
            btn.addEventListener('click', () => {
                const filter = btn.dataset.filter;

                // Actualizar botón activo
                corteFilters.forEach((b) => {
                    b.classList.remove('active-corte-filter');
                    b.classList.add('border-[#00d4ff]/25', 'text-slate-300');
                });
                btn.classList.add('active-corte-filter');
                btn.classList.remove('border-[#00d4ff]/25', 'text-slate-300');

                // Mostrar / ocultar tarjetas con animación
                corteCards.forEach((card) => {
                    if (filter === 'all' || card.dataset.filter === filter) {
                        card.classList.remove('hidden-card');
                    } else {
                        card.classList.add('hidden-card');
                    }
                });
            });
        });
    }
});
