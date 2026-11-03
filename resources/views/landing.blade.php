@extends('layouts.app')

@section('title', $biz['nombre_negocio'] . ' | Club VIP')

@section('content')
    {{-- 1. Announcement top bar --}}
    <div class="announcement-bar relative bg-gradient-to-r from-[#0096ff] to-[#00bcd4] text-[#04121f] text-sm font-semibold">
        <div class="max-w-7xl mx-auto px-4 py-2 flex items-center justify-center gap-3 text-center">
            <p class="leading-snug">
                🏆 CLUB OFICIAL | ¡Agenda tu corte o barba 24/7! Bebida de cortesía y toalla caliente incluida en todos los servicios VIP.
            </p>
        </div>
        <button type="button" class="announcement-close absolute right-3 top-1/2 -translate-y-1/2 text-[#04121f] hover:text-white transition-colors text-lg leading-none" aria-label="Cerrar anuncio">&times;</button>
    </div>

    {{-- 2. Navbar --}}
    <header class="sticky top-0 z-40 bg-[#0a0f1e]/90 backdrop-blur border-b border-[#00d4ff]/15">
        <nav class="max-w-7xl mx-auto px-4 py-3 flex items-center justify-between gap-4">
            {{-- Logo --}}
            <a href="{{ route('home') }}" class="flex items-center gap-3 shrink-0">
                <img src="/images/logo.png" alt="{{ $biz['nombre_negocio'] }} Logo" class="w-10 h-10 rounded-xl object-cover shadow-[0_0_18px_rgba(0,212,255,0.55)]">
                <span class="leading-tight">
                    <span class="block font-extrabold tracking-wide text-white text-lg">{{ strtoupper($biz['nombre_negocio']) }}</span>
                    <span class="block text-[10px] tracking-[0.25em] text-[#00d4ff]">{{ strtoupper($biz['slogan']) }}</span>
                </span>
            </a>

            {{-- Desktop links --}}
            <ul class="hidden lg:flex items-center gap-5 text-sm text-slate-300">
                <li><a href="#destacados" class="hover:text-[#00d4ff] transition-colors">Destacados</a></li>
                <li><a href="#servicios" class="hover:text-[#00d4ff] transition-colors">Servicios &amp; Cortes</a></li>
                <li><a href="#folio" class="hover:text-[#00d4ff] transition-colors">Consultar Cita/Folio</a></li>
                <li><a href="#galeria-cortes" class="hover:text-[#00d4ff] transition-colors">Galería</a></li>
                <li><a href="#barberos" class="hover:text-[#00d4ff] transition-colors">Maestros Barberos</a></li>
                <li><a href="{{ route('login') }}" class="hover:text-[#00d4ff] transition-colors">Administración</a></li>                <li><a href="#como-funciona" class="hover:text-[#00d4ff] transition-colors">¿Cómo Funciona?</a></li>
                <li><a href="#ayuda" class="hover:text-[#00d4ff] transition-colors">Ayuda</a></li>
            </ul>

            {{-- Right actions --}}
            <div class="flex items-center gap-2 sm:gap-3">
                <button id="theme-toggle" type="button" class="w-9 h-9 grid place-items-center rounded-xl border border-[#00d4ff]/30 text-[#00d4ff] hover:bg-[#00d4ff]/10 transition-colors" aria-label="Cambiar tema">
                    <span class="theme-icon-dark">🌙</span>
                    <span class="theme-icon-light hidden">☀️</span>
                </button>
                <a href="{{ route('login') }}" class="btn-outline text-sm hidden sm:inline-flex">Acceder</a>
                <button type="button" id="open-booking" class="btn-cyan text-sm">Reservar Cita</button>
                {{-- Hamburguesa mobile --}}
                <button id="nav-menu-btn" type="button" class="lg:hidden w-9 h-9 grid place-items-center rounded-xl border border-[#00d4ff]/30 text-[#00d4ff] hover:bg-[#00d4ff]/10 transition-colors" aria-label="Menú" aria-expanded="false" aria-controls="nav-mobile-menu">
                    <span class="nav-hamburger-icon flex flex-col gap-[5px]">
                        <span class="block w-5 h-0.5 bg-[#00d4ff] rounded transition-all origin-center"></span>
                        <span class="block w-5 h-0.5 bg-[#00d4ff] rounded transition-all"></span>
                        <span class="block w-5 h-0.5 bg-[#00d4ff] rounded transition-all origin-center"></span>
                    </span>
                </button>
            </div>
        </nav>

        {{-- Mobile menu (hidden by default) --}}
        <div id="nav-mobile-menu" class="hidden lg:hidden border-t border-[#00d4ff]/10 bg-[#080d19]/95 backdrop-blur">
            <ul class="max-w-7xl mx-auto px-4 py-4 flex flex-col gap-1 text-sm text-slate-300">
                <li><a href="#destacados" class="nav-mobile-link block py-2.5 px-3 rounded-lg hover:bg-[#00d4ff]/10 hover:text-[#00d4ff] transition-colors">⭐ Destacados</a></li>
                <li><a href="#servicios" class="nav-mobile-link block py-2.5 px-3 rounded-lg hover:bg-[#00d4ff]/10 hover:text-[#00d4ff] transition-colors">✂ Servicios &amp; Cortes</a></li>
                <li><a href="#folio" class="nav-mobile-link block py-2.5 px-3 rounded-lg hover:bg-[#00d4ff]/10 hover:text-[#00d4ff] transition-colors">🔍 Consultar Cita/Folio</a></li>
                <li><a href="#galeria-cortes" class="nav-mobile-link block py-2.5 px-3 rounded-lg hover:bg-[#00d4ff]/10 hover:text-[#00d4ff] transition-colors">🖼 Galería de Cortes</a></li>
                <li><a href="#barberos" class="nav-mobile-link block py-2.5 px-3 rounded-lg hover:bg-[#00d4ff]/10 hover:text-[#00d4ff] transition-colors">👤 Maestros Barberos</a></li>
                <li><a href="{{ route('login') }}" class="nav-mobile-link block py-2.5 px-3 rounded-lg hover:bg-[#00d4ff]/10 hover:text-[#00d4ff] transition-colors">🔐 Administración</a></li>
                <li><a href="#como-funciona" class="nav-mobile-link block py-2.5 px-3 rounded-lg hover:bg-[#00d4ff]/10 hover:text-[#00d4ff] transition-colors">❓ ¿Cómo Funciona?</a></li>
                <li><a href="#ayuda" class="nav-mobile-link block py-2.5 px-3 rounded-lg hover:bg-[#00d4ff]/10 hover:text-[#00d4ff] transition-colors">🆘 Ayuda</a></li>
                <li class="pt-2 border-t border-[#00d4ff]/10">
                    <a href="{{ route('login') }}" class="btn-outline w-full text-sm text-center">Acceder</a>
                </li>
            </ul>
        </div>
    </header>

    {{-- 3. Hero / Slider --}}
    <section class="relative overflow-hidden" id="reservar">
        <div class="absolute inset-0 pointer-events-none bg-[radial-gradient(circle_at_20%_10%,rgba(0,150,255,0.18),transparent_55%),radial-gradient(circle_at_85%_80%,rgba(0,212,255,0.15),transparent_50%)]"></div>

        <div class="relative max-w-7xl mx-auto px-4 py-14">
            <div id="hero-slider">
                {{-- Slide 1 (active) --}}
                <div class="hero-slide active grid lg:grid-cols-2 gap-10 items-center">
                    <div>
                        <span class="badge mb-5">Membresía Oficial de Caballeros</span>
                        <h1 class="text-4xl sm:text-5xl font-extrabold text-white leading-tight">
                            Únete al Club <span class="text-[#00d4ff] glow-cyan">VIP</span>
                        </h1>
                        <p class="mt-2 text-xl font-bold text-[#00bcd4]">Cortes Ilimitados &amp; Beneficios</p>
                        <p class="mt-5 text-slate-300 max-w-lg leading-relaxed">
                            Regístrate como socio oficial para recibir turnos preferenciales sin espera, descuentos en pomadas, aceites para barba y hasta 2 cortes mensuales incluidos.
                        </p>
                        <div class="mt-8 flex flex-wrap gap-3">
                            <button type="button" class="btn-cyan open-booking-btn">Registrarme en el Club VIP</button>
                            <button type="button" class="btn-outline">Planes y Membresías</button>
                        </div>
                    </div>

                    <div class="card-dark p-7 relative">
                        <div class="flex items-center justify-between">
                            <span class="grid place-items-center w-12 h-12 rounded-xl bg-[#00d4ff]/15 text-[#00d4ff] text-2xl">✦</span>
                            <span class="badge">Beneficio Exclusivo</span>
                        </div>
                        <p class="mt-6 text-xs tracking-[0.3em] text-slate-400">CLUB DE FIDELIDAD</p>
                        <h3 class="mt-1 text-2xl font-extrabold text-white">TARJETA BLACK BLESS</h3>
                        <p class="mt-3 text-slate-300 leading-relaxed">
                            20% descuento en productos de estilismo y prioridad en fines de semana.
                        </p>
                        <div class="mt-6 flex flex-wrap gap-2">
                            <span class="badge">Beneficio Exclusivo</span>
                            <span class="badge">Pase Anual</span>
                        </div>
                        <div class="mt-6 h-px gradient-divider"></div>
                        <div class="mt-4 flex items-center justify-between text-sm text-slate-400">
                            <span>•••• •••• •••• VIP</span>
                            <span class="text-[#00d4ff] font-semibold">BB-2026</span>
                        </div>
                    </div>
                </div>

                {{-- Slide 2 --}}
                <div class="hero-slide hidden grid lg:grid-cols-2 gap-10 items-center">
                    <div>
                        <span class="badge mb-5">Servicio Premium</span>
                        <h1 class="text-4xl sm:text-5xl font-extrabold text-white leading-tight">
                            Corta con <span class="text-[#00d4ff] glow-cyan">Estilo</span>
                        </h1>
                        <p class="mt-2 text-xl font-bold text-[#00bcd4]">Barberos Maestros Certificados</p>
                        <p class="mt-5 text-slate-300 max-w-lg leading-relaxed">
                            Reserva tu turno en segundos y deja tu estilo en manos de verdaderos maestros barberos. Toalla caliente y bebida de cortesía incluidas.
                        </p>
                        <div class="mt-8 flex flex-wrap gap-3">
                            <button type="button" class="btn-cyan open-booking-btn">Agendar Mi Turno</button>
                            <button type="button" class="btn-outline">Ver Servicios</button>
                        </div>
                    </div>
                    <div class="card-dark p-7">
                        <span class="badge mb-5">Plata Anual</span>
                        <p class="text-xs tracking-[0.3em] text-slate-400">CLUB DE FIDELIDAD</p>
                        <h3 class="mt-1 text-2xl font-extrabold text-white">TARJETA SILVER BLESS</h3>
                        <p class="mt-3 text-slate-300 leading-relaxed">
                            1 corte mensual incluido y 10% de descuento en productos de estilismo.
                        </p>
                        <div class="mt-6 h-px gradient-divider"></div>
                        <div class="mt-4 flex items-center justify-between text-sm text-slate-400">
                            <span>•••• •••• •••• AG</span>
                            <span class="text-[#00d4ff] font-semibold">BB-SILV</span>
                        </div>
                    </div>
                </div>

                {{-- Slide 3 --}}
                <div class="hero-slide hidden grid lg:grid-cols-2 gap-10 items-center">
                    <div>
                        <span class="badge mb-5">Oferta de Apertura</span>
                        <h1 class="text-4xl sm:text-5xl font-extrabold text-white leading-tight">
                            Estrena <span class="text-[#00d4ff] glow-cyan">Look</span>
                        </h1>
                        <p class="mt-2 text-xl font-bold text-[#00bcd4]">Corte + Barba + Diseño</p>
                        <p class="mt-5 text-slate-300 max-w-lg leading-relaxed">
                            Llévate un combo completo por precio de socio: corte de tijera, arreglo de barba y diseño personalizado por un maestro barbero.
                        </p>
                        <div class="mt-8 flex flex-wrap gap-3">
                            <button type="button" class="btn-cyan open-booking-btn">Reservar Combo</button>
                            <button type="button" class="btn-outline">Ver Precios</button>
                        </div>
                    </div>
                    <div class="card-dark p-7">
                        <span class="badge mb-5">Combo Socio</span>
                        <p class="text-xs tracking-[0.3em] text-slate-400">CLUB DE FIDELIDAD</p>
                        <h3 class="mt-1 text-2xl font-extrabold text-white">TARJETA COMBO BLESS</h3>
                        <p class="mt-3 text-slate-300 leading-relaxed">
                            3 servicios incluidos al mes y diseño de cejas sin costo adicional.
                        </p>
                        <div class="mt-6 h-px gradient-divider"></div>
                        <div class="mt-4 flex items-center justify-between text-sm text-slate-400">
                            <span>•••• •••• •••• CB</span>
                            <span class="text-[#00d4ff] font-semibold">BB-COMBO</span>
                        </div>
                    </div>
                </div>

                {{-- Slide 4 --}}
                <div class="hero-slide hidden grid lg:grid-cols-2 gap-10 items-center">
                    <div>
                        <span class="badge mb-5">Higiene Garantizada</span>
                        <h1 class="text-4xl sm:text-5xl font-extrabold text-white leading-tight">
                            Tu <span class="text-[#00d4ff] glow-cyan">Bienestar</span> Primero
                        </h1>
                        <p class="mt-2 text-xl font-bold text-[#00bcd4]">100% Puntualidad y Limpieza</p>
                        <p class="mt-5 text-slate-300 max-w-lg leading-relaxed">
                            Turnos puntuales, materiales esterilizados y un espacio pensado para tu comodidad. Café y trago de cortesía mientras esperas.
                        </p>
                        <div class="mt-8 flex flex-wrap gap-3">
                            <button type="button" class="btn-cyan open-booking-btn">Agendar Mi Turno Ahora</button>
                            <button type="button" class="btn-outline">Conocer el Club</button>
                        </div>
                    </div>
                    <div class="card-dark p-7">
                        <span class="badge mb-5">Pase Anual</span>
                        <p class="text-xs tracking-[0.3em] text-slate-400">CLUB DE FIDELIDAD</p>
                        <h3 class="mt-1 text-2xl font-extrabold text-white">TARJETA GOLD BLESS</h3>
                        <p class="mt-3 text-slate-300 leading-relaxed">
                            2 cortes mensuales, prioridad en fines de semana y regalo de bienvenida.
                        </p>
                        <div class="mt-6 h-px gradient-divider"></div>
                        <div class="mt-4 flex items-center justify-between text-sm text-slate-400">
                            <span>•••• •••• •••• GD</span>
                            <span class="text-[#00d4ff] font-semibold">BB-GOLD</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Slide counter + indicators --}}
            <div class="mt-10 flex flex-col sm:flex-row items-center justify-between gap-4">
                <div class="flex items-center gap-2">
                    <span class="text-[#00d4ff] font-bold tracking-widest"><span id="hero-counter-current">01</span><span class="text-slate-500">/</span><span id="hero-counter-total">04</span></span>
                    <span class="text-slate-500 text-sm">SLIDE</span>
                </div>
                <div id="hero-indicators" class="flex items-center gap-2">
                    <button type="button" class="hero-indicator w-2.5 h-2.5 rounded-full bg-[#00d4ff] shadow-[0_0_8px_rgba(0,212,255,0.6)] transition-all" data-slide="0" aria-label="Slide 1"></button>
                    <button type="button" class="hero-indicator w-2.5 h-2.5 rounded-full bg-slate-600 hover:bg-slate-400 transition-all" data-slide="1" aria-label="Slide 2"></button>
                    <button type="button" class="hero-indicator w-2.5 h-2.5 rounded-full bg-slate-600 hover:bg-slate-400 transition-all" data-slide="2" aria-label="Slide 3"></button>
                    <button type="button" class="hero-indicator w-2.5 h-2.5 rounded-full bg-slate-600 hover:bg-slate-400 transition-all" data-slide="3" aria-label="Slide 4"></button>
                </div>
            </div>
        </div>
    </section>

    {{-- Destacados Section --}}
    <section class="py-20 bg-[#070b16] border-t border-[#00d4ff]/15 relative" id="destacados">
        <div class="max-w-7xl mx-auto px-4">
            <div class="text-center max-w-2xl mx-auto mb-14">
                <span class="badge mb-3">EXPERIENCIA EXCLUSIVA</span>
                <h2 class="text-3xl sm:text-4xl font-extrabold text-white leading-tight">
                    Servicios <span class="text-[#00d4ff] glow-cyan">Destacados</span> del Club
                </h2>
                <p class="mt-3 text-sm text-slate-400">
                    Cortes de vanguardia, afeitados con toalla caliente aromatizada y una atención prémium pensada para el caballero moderno.
                </p>
            </div>

            @php
                $emojisDestacados = ['✂️','💈','👑'];
                $badgesDestacados = ['TOP POPULAR','RITUAL VIP','TODO INCLUIDO'];
            @endphp

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @forelse($destacados as $i => $s)
                    <div class="card-dark overflow-hidden flex flex-col hover:border-[#00d4ff]/40 transition-all group">
                        {{-- Imagen o ícono --}}
                        @if($s->imagen && file_exists(public_path('images/servicios/'.$s->imagen)))
                            <div class="aspect-video overflow-hidden">
                                <img src="/images/servicios/{{ $s->imagen }}"
                                     alt="{{ $s->nombre }}"
                                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                            </div>
                        @else
                            <div class="p-6 pb-0">
                                <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-[#00d4ff]/20 to-[#0096ff]/20 border border-[#00d4ff]/30 grid place-items-center text-3xl mb-5 group-hover:scale-105 transition-transform">
                                    {{ $emojisDestacados[$i] ?? '✂️' }}
                                </div>
                            </div>
                        @endif

                        <div class="p-6 flex flex-col flex-1 justify-between">
                            <div>
                                <span class="badge mb-3">{{ $s->popular ? '⭐ POPULAR' : ($badgesDestacados[$i] ?? strtoupper($s->categoria)) }}</span>
                                <h3 class="text-xl font-bold text-white mb-2 group-hover:text-[#00d4ff] transition-colors">
                                    {{ $s->nombre }}
                                </h3>
                                @if($s->descripcion)
                                    <p class="text-xs text-slate-300 leading-relaxed">{{ $s->descripcion }}</p>
                                @endif
                            </div>
                            <div class="mt-6 pt-4 border-t border-[#00d4ff]/15 flex items-center justify-between">
                                <span class="text-[#00d4ff] font-extrabold text-lg">Bs. {{ $s->precio }}</span>
                                <button type="button" class="open-booking-btn text-xs font-semibold px-3 py-1.5 rounded-lg bg-[#00d4ff]/15 text-[#00d4ff] hover:bg-[#00d4ff] hover:text-[#04121f] transition-all">
                                    Reservar Turno
                                </button>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="md:col-span-3 text-center py-10 text-slate-500">
                        Agrega servicios desde el panel de administración para verlos aquí.
                    </div>
                @endforelse
            </div>
        </div>
    </section>
    {{-- 4. "Tu Estilo en Manos de Verdaderos Maestros" --}}
    <section class="relative bg-[#080d19] py-16" id="folio">
        <div class="max-w-7xl mx-auto px-4 grid lg:grid-cols-2 gap-12 items-center">
            <div>
                <span class="badge mb-5">PORTAL OFICIAL DE RESERVAS &amp; REGISTRO 2026</span>
                <h2 class="text-3xl sm:text-4xl font-extrabold text-white leading-tight">
                    Tu Estilo en Manos de <span class="text-[#00d4ff] glow-cyan">Verdaderos Maestros</span>
                </h2>
                <p class="mt-5 text-slate-300 max-w-lg leading-relaxed">
                    Agenda tu turno en segundos y recibe un folio de confirmación para rastrear tu reserva en cualquier momento. Sin esperas, sin filas, solo buen estilo.
                </p>
                <div class="mt-8 flex flex-wrap gap-3">
                    <button type="button" class="btn-cyan open-booking-btn">Agendar Mi Turno Ahora</button>
                    <a href="#folio-form" class="btn-outline">Rastrear Reserva</a>
                </div>
            </div>

            <div class="card-dark p-6 sm:p-8">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-xl font-bold text-white">Consulta tu Cita</h3>
                    <span class="badge">Sillones Activos</span>
                </div>

                <form id="folio-form" class="mt-2">
                    <label for="folio-input" class="block text-xs tracking-[0.2em] text-slate-400 mb-2">CÓDIGO DE FOLIO O N° TELÉFONO</label>
                    <input id="folio-input" type="text" class="input-dark" placeholder="EJ. BARBER-2026-9041" autocomplete="off">
                    <button type="submit" class="btn-cyan w-full mt-4">Comprobar Estado de Cita</button>
                </form>

                <div id="folio-result" class="mt-4 text-sm text-slate-300 min-h-[1.5rem]"></div>

                <div class="mt-6 pt-4 border-t border-[#00d4ff]/15 text-xs text-slate-400 text-center">
                    Barberos Certificados | Café &amp; Trago Gratis | Higiene Sanitaria 100%
                </div>
            </div>
        </div>
    </section>

    {{-- ===================== SERVICIOS & GALERÍA DE CORTES ===================== --}}
    <section class="py-20 bg-[#0a0f1e] border-t border-[#00d4ff]/15" id="galeria-cortes">
        <div class="max-w-7xl mx-auto px-4">

            {{-- Header --}}
            <div class="flex flex-col md:flex-row md:items-end justify-between mb-10 gap-4">
                <div>
                    <span class="badge mb-3">SERVICIOS &amp; GALERÍA</span>
                    <h2 class="text-3xl sm:text-4xl font-extrabold text-white leading-tight">
                        Nuestros <span class="text-[#00d4ff] glow-cyan">Cortes &amp; Servicios</span>
                    </h2>
                    <p class="mt-2 text-sm text-slate-400">
                        Cada corte es una obra de arte. Elige tu estilo y agenda tu turno con uno de nuestros maestros.
                    </p>
                </div>
                <button type="button" class="open-booking-btn btn-cyan self-start md:self-auto text-sm shrink-0">
                    ✂ Agendar Mi Turno
                </button>
            </div>

            {{-- Filtros de categoría --}}
            <div class="flex flex-wrap gap-2 mb-8" id="corte-filters">
                <button type="button" class="corte-filter-btn active-corte-filter px-4 py-2 rounded-full text-sm font-bold transition-all" data-filter="all">
                    Todos
                </button>
                @php
                    $categoriasGaleria = $serviciosLanding->pluck('categoria')->unique()->sort()->values();
                @endphp
                @foreach($categoriasGaleria as $cat)
                    <button type="button"
                        class="corte-filter-btn px-4 py-2 rounded-full text-sm font-bold transition-all border border-[#00d4ff]/25 text-slate-300 hover:border-[#00d4ff]/50 hover:text-[#00d4ff]"
                        data-filter="{{ Str::slug($cat) }}">
                        {{ $cat }}
                    </button>
                @endforeach
                <button type="button"
                    class="corte-filter-btn px-4 py-2 rounded-full text-sm font-bold transition-all border border-[#00d4ff]/25 text-slate-300 hover:border-[#00d4ff]/50 hover:text-[#00d4ff]"
                    data-filter="galeria">
                    📸 Galería de Imágenes
                </button>
            </div>

            {{-- ── GRID unificado ── --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5" id="cortes-grid">

                {{-- Servicios desde la BD --}}
                @forelse($serviciosLanding as $s)
                    @php
                        $catSlug  = Str::slug($s->categoria);
                        $catColor = match($s->categoria) {
                            'Cortes'       => 'bg-[#00d4ff]/15 text-[#00d4ff] border-[#00d4ff]/30',
                            'Barba'        => 'bg-emerald-400/15 text-emerald-300 border-emerald-400/30',
                            'Combos VIP'   => 'bg-amber-400/15 text-amber-300 border-amber-400/30',
                            'Tratamientos' => 'bg-purple-400/15 text-purple-300 border-purple-400/30',
                            default        => 'bg-slate-700/40 text-slate-300 border-slate-600/30',
                        };
                    @endphp
                    <div class="corte-card card-dark overflow-hidden flex flex-col hover:border-[#00d4ff]/50 transition-all group"
                         data-filter="{{ $catSlug }}">

                        {{-- Imagen del servicio --}}
                        <div class="relative aspect-square overflow-hidden bg-[#070b16]">
                            @if($s->imagen && file_exists(public_path('images/servicios/'.$s->imagen)))
                                <img src="/images/servicios/{{ $s->imagen }}"
                                     alt="{{ $s->nombre }}"
                                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                                     loading="lazy">
                            @else
                                <div class="w-full h-full flex flex-col items-center justify-center gap-2 bg-gradient-to-br from-[#0d1526] to-[#080d19]">
                                    <span class="text-4xl opacity-40">✂</span>
                                    <span class="text-xs text-slate-600">Sin imagen</span>
                                </div>
                            @endif

                            {{-- Overlay hover --}}
                            <div class="absolute inset-0 bg-gradient-to-t from-[#0a0f1e]/90 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity flex items-end justify-center pb-3">
                                <button type="button" class="open-booking-btn px-3 py-1.5 rounded-lg bg-[#00d4ff] text-[#04121f] text-xs font-bold shadow-[0_0_12px_rgba(0,212,255,0.5)]">
                                    ✂ Reservar
                                </button>
                            </div>

                            {{-- Badges --}}
                            <div class="absolute top-2 left-2">
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold border {{ $catColor }} backdrop-blur-sm">
                                    {{ strtoupper($s->categoria) }}
                                </span>
                            </div>
                            <div class="absolute top-2 right-2">
                                <span class="px-2 py-0.5 rounded-full bg-[#0a0f1e]/80 border border-[#00d4ff]/40 text-[#00d4ff] text-[11px] font-bold backdrop-blur-sm">
                                    Bs. {{ $s->precio }}
                                </span>
                            </div>
                            @if($s->popular)
                                <div class="absolute bottom-2 left-2">
                                    <span class="px-2 py-0.5 rounded-full bg-amber-400/20 border border-amber-400/40 text-amber-300 text-[10px] font-bold backdrop-blur-sm">
                                        ⭐ POPULAR
                                    </span>
                                </div>
                            @endif
                        </div>

                        {{-- Info --}}
                        <div class="p-4 flex flex-col flex-1 justify-between">
                            <div>
                                <h3 class="text-sm font-bold text-white group-hover:text-[#00d4ff] transition-colors leading-tight">
                                    {{ $s->nombre }}
                                </h3>
                                @if($s->descripcion)
                                    <p class="text-[11px] text-slate-400 mt-1 leading-relaxed line-clamp-2">
                                        {{ $s->descripcion }}
                                    </p>
                                @endif
                            </div>
                            <div class="mt-3 pt-3 border-t border-[#00d4ff]/10 flex items-center justify-between">
                                <span class="text-xs text-slate-500">⏱ {{ $s->duracion }}</span>
                                <span class="text-lg font-extrabold text-[#00d4ff] glow-cyan">Bs. {{ $s->precio }}</span>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full text-center py-10 text-slate-500 text-sm">
                        Los servicios aparecerán aquí cuando el administrador los agregue.
                    </div>
                @endforelse

                {{-- Imágenes de la galería de cortes (carpeta cortes/) --}}
                @php
                    $cortesGaleria = [
                        ['nombre' => 'Corte Clásico',  'imagen' => 'cortes/clasico.jpg',   'emoji' => '💈'],
                        ['nombre' => 'Corte Escolar',   'imagen' => 'cortes/escolar.jpg',   'emoji' => '📚'],
                        ['nombre' => 'Corte Militar',   'imagen' => 'cortes/militar.jpg',   'emoji' => '🎖'],
                        ['nombre' => 'Degradado',       'imagen' => 'cortes/degradado.jpg', 'emoji' => '✨'],
                        ['nombre' => 'Low Fade',        'imagen' => 'cortes/lowfade.jpg',   'emoji' => '⚡'],
                        ['nombre' => 'Táper Fade',      'imagen' => 'cortes/taperfade.jpg', 'emoji' => '🔥'],
                        ['nombre' => 'Mullet',          'imagen' => 'cortes/mullet.jpg',    'emoji' => '🎸'],
                        ['nombre' => 'Mohicano',        'imagen' => 'cortes/mohicano.jpg',  'emoji' => '⚔'],
                        ['nombre' => 'Buzz Cut',        'imagen' => 'cortes/buzzcut.jpg',   'emoji' => '💡'],
                    ];
                @endphp

                @foreach($cortesGaleria as $corte)
                    @if(file_exists(public_path('images/'.$corte['imagen'])))
                        <div class="corte-card card-dark overflow-hidden group cursor-pointer hover:border-[#00d4ff]/50 transition-all"
                             data-filter="galeria"
                             onclick="document.getElementById('open-booking').click()">

                            <div class="relative aspect-square overflow-hidden bg-[#070b16]">
                                <img src="/images/{{ $corte['imagen'] }}"
                                     alt="{{ $corte['nombre'] }}"
                                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                                     loading="lazy">

                                <div class="absolute inset-0 bg-gradient-to-t from-[#0a0f1e]/90 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity flex items-end justify-center pb-3">
                                    <span class="px-3 py-1.5 rounded-lg bg-[#00d4ff] text-[#04121f] text-xs font-bold shadow-[0_0_12px_rgba(0,212,255,0.5)]">
                                        ✂ Reservar este estilo
                                    </span>
                                </div>

                                <div class="absolute top-2 left-2">
                                    <span class="px-2 py-0.5 rounded-full bg-[#0a0f1e]/80 border border-[#00d4ff]/25 text-[#00d4ff] text-[10px] font-bold backdrop-blur-sm">
                                        📸 GALERÍA
                                    </span>
                                </div>
                            </div>

                            <div class="p-3">
                                <h3 class="text-sm font-bold text-white group-hover:text-[#00d4ff] transition-colors">
                                    {{ $corte['nombre'] }}
                                </h3>
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>

            {{-- CTA inferior --}}
            <div class="text-center mt-12">
                <p class="text-slate-400 text-sm mb-4">¿No encontraste tu estilo? Nuestros barberos también hacen diseños personalizados.</p>
                <button type="button" class="btn-cyan open-booking-btn">
                    ✂ Agendar Mi Corte Ahora
                </button>
            </div>
        </div>
    </section>

            {{-- Grid de cortes --}}
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4" id="cortes-grid">

                @php
                    $cortes = [
                        [
                            'nombre'     => 'Corte Clásico',
                            'filtro'     => 'clasico',
                            'desc'       => 'Atemporal y elegante. Lados cortos, cima con volumen controlado.',
                            'imagen'     => 'cortes/clasico.jpg',
                            'precio'     => 'Bs. 60',
                            'emoji'      => '💈',
                        ],
                        [
                            'nombre'     => 'Corte Escolar',
                            'filtro'     => 'escolar',
                            'desc'       => 'Prolijo y formal. Perfecto para jóvenes estudiantes.',
                            'imagen'     => 'cortes/escolar.jpg',
                            'precio'     => 'Bs. 50',
                            'emoji'      => '📚',
                        ],
                        [
                            'nombre'     => 'Corte Militar',
                            'filtro'     => 'militar',
                            'desc'       => 'Limpio y estructurado. Lados rapados y cima muy corta.',
                            'imagen'     => 'cortes/militar.jpg',
                            'precio'     => 'Bs. 55',
                            'emoji'      => '🎖',
                        ],
                        [
                            'nombre'     => 'Degradado',
                            'filtro'     => 'degradado',
                            'desc'       => 'Transición suave de largo a corto. Clásico moderno.',
                            'imagen'     => 'cortes/degradado.jpg',
                            'precio'     => 'Bs. 65',
                            'emoji'      => '✨',
                        ],
                        [
                            'nombre'     => 'Low Fade',
                            'filtro'     => 'low-fade',
                            'desc'       => 'Degradado bajo que empieza cerca de la oreja.',
                            'imagen'     => 'cortes/lowfade.jpg',
                            'precio'     => 'Bs. 65',
                            'emoji'      => '⚡',
                        ],
                        [
                            'nombre'     => 'Táper Fade',
                            'filtro'     => 'taper-fade',
                            'desc'       => 'Degradado gradual con textura natural en la cima.',
                            'imagen'     => 'cortes/taperfade.jpg',
                            'precio'     => 'Bs. 70',
                            'emoji'      => '🔥',
                        ],
                        [
                            'nombre'     => 'Mullet',
                            'filtro'     => 'mullet',
                            'desc'       => 'Corto al frente, largo atrás. Tendencia retro-moderna.',
                            'imagen'     => 'cortes/mullet.jpg',
                            'precio'     => 'Bs. 75',
                            'emoji'      => '🎸',
                        ],
                        [
                            'nombre'     => 'Mohicano',
                            'filtro'     => 'mohicano',
                            'desc'       => 'Banda central de cabello. Atrevido y con personalidad.',
                            'imagen'     => 'cortes/mohicano.jpg',
                            'precio'     => 'Bs. 70',
                            'emoji'      => '⚔',
                        ],
                        [
                            'nombre'     => 'Buzz Cut',
                            'filtro'     => 'buzz-cut',
                            'desc'       => 'Corte de máquina uniforme. Minimalista y fresco.',
                            'imagen'     => 'cortes/buzzcut.jpg',
                            'precio'     => 'Bs. 45',
                            'emoji'      => '💡',
                        ],
                    ];
                @endphp

                @foreach($cortes as $corte)
                    <div class="corte-card card-dark overflow-hidden group cursor-pointer hover:border-[#00d4ff]/50 transition-all"
                         data-filter="{{ $corte['filtro'] }}"
                         onclick="document.getElementById('open-booking').click()">

                        {{-- Imagen del corte --}}
                        <div class="relative aspect-square overflow-hidden bg-[#070b16]">
                            @if(file_exists(public_path('images/' . $corte['imagen'])))
                                <img
                                    src="/images/{{ $corte['imagen'] }}"
                                    alt="{{ $corte['nombre'] }}"
                                    class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                                    loading="lazy"
                                >
                            @else
                                {{-- Placeholder hasta que el admin suba la imagen --}}
                                <div class="w-full h-full flex flex-col items-center justify-center gap-3 bg-gradient-to-br from-[#0d1526] to-[#080d19]">
                                    <span class="text-5xl opacity-60">{{ $corte['emoji'] }}</span>
                                    <span class="text-xs text-slate-500 text-center px-3 leading-snug">
                                        Imagen próximamente
                                    </span>
                                    {{-- Líneas decorativas de "foto pendiente" --}}
                                    <div class="absolute inset-0 pointer-events-none opacity-10">
                                        <div class="w-full h-full border-2 border-dashed border-[#00d4ff]"></div>
                                    </div>
                                </div>
                            @endif

                            {{-- Overlay hover con botón --}}
                            <div class="absolute inset-0 bg-gradient-to-t from-[#0a0f1e]/90 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity flex items-end justify-center pb-4">
                                <span class="px-3 py-1.5 rounded-lg bg-[#00d4ff] text-[#04121f] text-xs font-bold shadow-[0_0_12px_rgba(0,212,255,0.5)]">
                                    ✂ Reservar este estilo
                                </span>
                            </div>

                            {{-- Badge de precio --}}
                            <div class="absolute top-2 right-2">
                                <span class="px-2 py-0.5 rounded-full bg-[#0a0f1e]/80 border border-[#00d4ff]/40 text-[#00d4ff] text-[11px] font-bold backdrop-blur-sm">
                                    {{ $corte['precio'] }}
                                </span>
                            </div>
                        </div>

                        {{-- Info del corte --}}
                        <div class="p-3">
                            <h3 class="text-sm font-bold text-white group-hover:text-[#00d4ff] transition-colors leading-tight">
                                {{ $corte['nombre'] }}
                            </h3>
                            <p class="text-[11px] text-slate-400 mt-1 leading-relaxed">
                                {{ $corte['desc'] }}
                            </p>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- CTA inferior --}}
            <div class="text-center mt-12">
                <p class="text-slate-400 text-sm mb-4">¿No encontraste tu estilo? Nuestros barberos también hacen diseños personalizados.</p>
                <button type="button" id="open-booking-galeria" class="btn-cyan open-booking-btn">
                    ✂ Agendar Mi Corte Ahora
                </button>
            </div>
        </div>
    </section>

    {{-- Maestros Barberos Section --}}
    <section class="py-20 bg-[#070b16] border-t border-[#00d4ff]/15" id="barberos">
        <div class="max-w-7xl mx-auto px-4">
            <div class="text-center max-w-2xl mx-auto mb-14">
                <span class="badge mb-3">STAFF PROFESIONAL</span>
                <h2 class="text-3xl sm:text-4xl font-extrabold text-white leading-tight">
                    Nuestros <span class="text-[#00d4ff] glow-cyan">Maestros Barberos</span>
                </h2>
                <p class="mt-3 text-sm text-slate-400">
                    Conoce al equipo especializado que transformará tu imagen con precisión milimétrica y atención de primera clase.
                </p>
            </div>

            @php
                $gradientes = ['from-[#00d4ff] to-[#0096ff]', 'from-emerald-400 to-[#00d4ff]', 'from-amber-400 to-[#0096ff]', 'from-purple-400 to-[#00d4ff]'];
            @endphp

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @forelse($barberosLanding as $idx => $b)
                    @php
                        $inicial  = strtoupper(substr($b->nombre, 0, 1));
                        $grad     = $gradientes[$idx % count($gradientes)];
                        $totalCortes = \App\Models\Reserva::where('barbero', $b->nombre)->where('estado', 'Completada')->count();
                    @endphp
                    <div class="card-dark p-6 flex flex-col justify-between hover:border-[#00d4ff]/40 transition-all text-center group">
                        <div>
                            <div class="w-20 h-20 rounded-2xl bg-gradient-to-br {{ $grad }} text-[#04121f] font-extrabold text-3xl grid place-items-center shadow-[0_0_20px_rgba(0,212,255,0.3)] mx-auto mb-4 group-hover:scale-105 transition-transform">
                                {{ $inicial }}
                            </div>
                            <h3 class="text-xl font-bold text-white mb-1">{{ $b->nombre }}</h3>
                            <p class="text-xs text-[#00d4ff] font-semibold mb-3">{{ $b->rol }}</p>
                            @if($b->especialidad)
                                <p class="text-xs text-slate-300 bg-[#080d19]/80 p-3 rounded-lg border border-[#00d4ff]/10 mb-4">
                                    🎯 <strong class="text-white">Especialidad:</strong> {{ $b->especialidad }}
                                </p>
                            @endif
                            <div class="flex items-center justify-center gap-4 text-xs text-slate-400 mb-6">
                                <span class="text-amber-300 font-bold">{{ number_format($b->rating, 1) }} ★</span>
                                <span>•</span>
                                <span>{{ $totalCortes > 0 ? '+'.$totalCortes.' cortes' : 'Nuevo en staff' }}</span>
                            </div>
                        </div>
                        <button type="button" class="open-booking-btn btn-cyan w-full text-xs py-2.5">
                            Agendar con {{ explode(' ', $b->nombre)[0] }}
                        </button>
                    </div>
                @empty
                    <div class="md:col-span-3 text-center py-10 text-slate-500">
                        El equipo de barberos aparecerá aquí pronto.
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    {{-- ¿Cómo Funciona? Section --}}
    <section class="py-20 bg-[#0a0f1e] border-t border-[#00d4ff]/15" id="como-funciona">
        <div class="max-w-7xl mx-auto px-4">
            <div class="text-center max-w-2xl mx-auto mb-14">
                <span class="badge mb-3">PASO A PASO</span>
                <h2 class="text-3xl sm:text-4xl font-extrabold text-white leading-tight">
                    ¿Cómo <span class="text-[#00d4ff] glow-cyan">Funciona</span> el Sistema?
                </h2>
                <p class="mt-3 text-sm text-slate-400">
                    Tu cita en 3 sencillos pasos. Sin esperas y con confirmación instantánea.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="card-dark p-6 relative">
                    <span class="text-5xl font-extrabold text-[#00d4ff]/20 absolute top-4 right-4">01</span>
                    <div class="w-12 h-12 rounded-xl bg-[#00d4ff]/15 text-[#00d4ff] text-2xl grid place-items-center mb-4">
                        ✂️
                    </div>
                    <h3 class="text-lg font-bold text-white mb-2">1. Selecciona Servicio y Barbero</h3>
                    <p class="text-xs text-slate-300 leading-relaxed">
                        Escoge el servicio que necesitas (Corte, Barba, Combo VIP) y tu barbero de preferencia (Cristian, Rogher o Esteban).
                    </p>
                </div>

                <div class="card-dark p-6 relative">
                    <span class="text-5xl font-extrabold text-[#00d4ff]/20 absolute top-4 right-4">02</span>
                    <div class="w-12 h-12 rounded-xl bg-[#00d4ff]/15 text-[#00d4ff] text-2xl grid place-items-center mb-4">
                        📅
                    </div>
                    <h3 class="text-lg font-bold text-white mb-2">2. Elige Fecha y Horario</h3>
                    <p class="text-xs text-slate-300 leading-relaxed">
                        Revisa los turnos disponibles en tiempo real en nuestro calendario interactivo y escoge la hora exacta que mejor te convenga.
                    </p>
                </div>

                <div class="card-dark p-6 relative">
                    <span class="text-5xl font-extrabold text-[#00d4ff]/20 absolute top-4 right-4">03</span>
                    <div class="w-12 h-12 rounded-xl bg-[#00d4ff]/15 text-[#00d4ff] text-2xl grid place-items-center mb-4">
                        📱
                    </div>
                    <h3 class="text-lg font-bold text-white mb-2">3. Recibe tu Folio y ¡Listo!</h3>
                    <p class="text-xs text-slate-300 leading-relaxed">
                        Se generará tu código de folio único y recibirás confirmación para llegar directamente a tu sillón reservado sin hacer filas.
                    </p>
                </div>
            </div>
        </div>
    </section>

    {{-- 5. Statistics section --}}
    <section class="bg-[#070b16] py-16 border-t border-[#00d4ff]/10">
        <div class="max-w-7xl mx-auto px-4 grid grid-cols-2 lg:grid-cols-4 gap-8 text-center">
            <div>
                <p class="text-4xl sm:text-5xl font-extrabold text-[#00d4ff] glow-cyan">+18,400</p>
                <p class="mt-3 text-sm tracking-wider text-slate-400 uppercase">Cortes &amp; Barbas Realizados</p>
            </div>
            <div>
                <p class="text-4xl sm:text-5xl font-extrabold text-[#00d4ff] glow-cyan">3</p>
                <p class="mt-3 text-sm tracking-wider text-slate-400 uppercase">Maestros Barberos en Staff</p>
            </div>
            <div>
                <p class="text-4xl sm:text-5xl font-extrabold text-[#00d4ff] glow-cyan">4.9/5.0</p>
                <p class="mt-3 text-sm tracking-wider text-slate-400 uppercase">Satisfacción de Clientes</p>
            </div>
            <div>
                <p class="text-4xl sm:text-5xl font-extrabold text-[#00d4ff] glow-cyan">100%</p>
                <p class="mt-3 text-sm tracking-wider text-slate-400 uppercase">Puntualidad en Turnos</p>
            </div>
        </div>
    </section>

    {{-- Ayuda & FAQ Section --}}
    <section class="py-20 bg-[#080d19] border-t border-[#00d4ff]/15" id="ayuda">
        <div class="max-w-7xl mx-auto px-4">
            <div class="text-center max-w-2xl mx-auto mb-14">
                <span class="badge mb-3">CENTRO DE SOPORTE</span>
                <h2 class="text-3xl sm:text-4xl font-extrabold text-white leading-tight">
                    ¿Tienes Preguntas? <span class="text-[#00d4ff] glow-cyan">Te Ayudamos</span>
                </h2>
                <p class="mt-3 text-sm text-slate-400">
                    Respuestas rápidas a las consultas más frecuentes sobre citas, reservas y formas de pago.
                </p>
            </div>

            <div class="max-w-3xl mx-auto space-y-4">
                <details class="card-dark p-5 cursor-pointer group">
                    <summary class="font-bold text-white text-sm flex items-center justify-between list-none">
                        <span>¿Cómo consulto el estado de mi cita agendada?</span>
                        <span class="text-[#00d4ff] group-open:rotate-180 transition-transform">▼</span>
                    </summary>
                    <p class="mt-3 text-xs text-slate-300 leading-relaxed border-t border-[#00d4ff]/10 pt-3">
                        Puedes consultar tu cita en la sección <a href="#folio" class="text-[#00d4ff] underline font-semibold">Consultar Cita/Folio</a> ingresando el código de folio que recibiste al reservar o tu número de teléfono registrado.
                    </p>
                </details>

                <details class="card-dark p-5 cursor-pointer group">
                    <summary class="font-bold text-white text-sm flex items-center justify-between list-none">
                        <span>¿Qué métodos de pago aceptan en la barbería?</span>
                        <span class="text-[#00d4ff] group-open:rotate-180 transition-transform">▼</span>
                    </summary>
                    <p class="mt-3 text-xs text-slate-300 leading-relaxed border-t border-[#00d4ff]/10 pt-3">
                        Aceptamos efectivo en Bolivianos (Bs.), transferencias bancarias mediante <strong>QR Simple</strong> de cualquier banco boliviano, y tarjetas de débito o crédito Visa / Mastercard.
                    </p>
                </details>

                <details class="card-dark p-5 cursor-pointer group">
                    <summary class="font-bold text-white text-sm flex items-center justify-between list-none">
                        <span>¿Con cuánta anticipación debo llegar a mi turno?</span>
                        <span class="text-[#00d4ff] group-open:rotate-180 transition-transform">▼</span>
                    </summary>
                    <p class="mt-3 text-xs text-slate-300 leading-relaxed border-t border-[#00d4ff]/10 pt-3">
                        Recomendamos presentarte 5 a 10 minutos antes de la hora seleccionada para acomodarte y disfrutar de tu bebida de cortesía antes de pasar al sillón.
                    </p>
                </details>

                <details class="card-dark p-5 cursor-pointer group">
                    <summary class="font-bold text-white text-sm flex items-center justify-between list-none">
                        <span>¿Dónde están ubicados y cuál es el horario de atención?</span>
                        <span class="text-[#00d4ff] group-open:rotate-180 transition-transform">▼</span>
                    </summary>
                    <p class="mt-3 text-xs text-slate-300 leading-relaxed border-t border-[#00d4ff]/10 pt-3">
                        Estamos ubicados en <strong>{{ $biz['direccion'] }}, {{ $biz['ciudad'] }}</strong>. Atendemos de {{ $biz['dias_laborables'] }} de {{ $biz['hora_apertura'] }} AM a {{ $biz['hora_cierre'] }} PM.
                    </p>
                </details>
            </div>

            <div class="mt-12 text-center p-6 rounded-2xl bg-gradient-to-r from-[#0096ff]/10 via-[#00d4ff]/15 to-[#0096ff]/10 border border-[#00d4ff]/25 max-w-2xl mx-auto">
                <h4 class="font-bold text-white text-base mb-1">¿Necesitas ayuda personalizada?</h4>
                <p class="text-xs text-slate-400 mb-4">Escríbenos directamente por WhatsApp y nuestro equipo te asistirá al instante.</p>
                <a href="{{ $waLink }}" target="_blank" rel="noopener noreferrer" class="btn-cyan inline-flex items-center gap-2 text-sm">
                    <span>💬</span> Chatear por WhatsApp ({{ $biz['telefono'] }})
                </a>
            </div>
        </div>
    </section>

    {{-- Footer --}}
    <footer class="bg-[#0a0f1e] py-10 border-t border-[#00d4ff]/10">
        <div class="max-w-7xl mx-auto px-4 flex flex-col sm:flex-row items-center justify-between gap-4 text-sm text-slate-400">
            <div class="flex items-center gap-3">
                <img src="/images/logo.png" alt="{{ $biz['nombre_negocio'] }}" class="w-9 h-9 rounded-lg object-cover shadow-[0_0_12px_rgba(0,212,255,0.45)]">
                <span class="font-bold text-white tracking-wide">{{ strtoupper($biz['nombre_negocio']) }}</span>
            </div>
            <p>© {{ date('Y') }} {{ $biz['nombre_negocio'] }} · Club Oficial de Caballeros</p>
        </div>
    </footer>

    {{-- ===================== BOOKING MODAL ===================== --}}
    <div id="booking-modal" class="fixed inset-0 z-50 hidden" role="dialog" aria-modal="true" aria-labelledby="booking-title">
        <div id="booking-backdrop" class="absolute inset-0 bg-black/70 backdrop-blur-sm"></div>
        <div class="relative flex items-start justify-center min-h-screen px-2 py-6 overflow-y-auto">
            <div class="relative w-full max-w-5xl bg-[#0a1428] border border-[#00d4ff]/25 rounded-2xl shadow-[0_0_60px_rgba(0,212,255,0.12)] overflow-hidden">

                {{-- Modal header --}}
                <div class="flex items-center justify-between px-6 py-5 border-b border-[#00d4ff]/15 bg-[#080d19]">
                    <div class="flex items-center gap-4">
                        <span class="grid place-items-center w-12 h-12 rounded-xl overflow-hidden shadow-[0_0_18px_rgba(0,212,255,0.45)]">
                            <img src="/images/logo.png" alt="Barber Bless" class="w-full h-full object-cover">
                        </span>
                        <div>
                            <h2 id="booking-title" class="text-lg font-extrabold text-white">Agendar Cita en {{ $biz['nombre_negocio'] }}</h2>
                            <p class="text-xs text-slate-400">Elige tu fecha en el calendario y tu horario preferido</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <span class="hidden sm:flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-emerald-500/15 border border-emerald-500/30 text-emerald-300 text-xs font-semibold">
                            <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                            Turnos en Tiempo Real
                        </span>
                        <button id="close-booking" type="button" class="w-9 h-9 grid place-items-center rounded-xl border border-[#00d4ff]/25 text-slate-400 hover:text-white hover:border-[#00d4ff]/50 transition-colors" aria-label="Cerrar">✕</button>
                    </div>
                </div>

                {{-- Modal body --}}
                <div class="grid grid-cols-1 lg:grid-cols-[380px_1fr] gap-0">

                    {{-- LEFT: Client form --}}
                    <div class="px-6 py-6 border-b border-r-0 lg:border-b-0 lg:border-r border-[#00d4ff]/10 space-y-4">

                        <div>
                            <label class="flex items-center gap-1.5 text-sm font-semibold text-[#00d4ff] mb-2">
                                🏷 Servicio de Barbería
                            </label>
                            <div class="relative">
                                <select id="bk-service" class="input-dark pr-10 appearance-none">
                                    <option value="">— Selecciona un servicio —</option>
                                    <option value="Corte Fade & Degrafilado — Bs. 60">Corte Fade &amp; Degrafilado — Bs. 60</option>
                                    <option value="Afeitado Tradicional Spa — Bs. 45">Afeitado Tradicional Spa — Bs. 45</option>
                                    <option value="Experiencia VIP Bless — Bs. 120">Experiencia VIP Bless (Corte + Barba + Facial) — Bs. 120</option>
                                    <option value="Diseño Freestyle & Tribales — Bs. 70">Diseño Freestyle &amp; Tribales — Bs. 70</option>
                                    <option value="Perfilado de Barba Express — Bs. 30">Perfilado de Barba Express — Bs. 30</option>
                                    <option value="Exfoliación y Mascarilla — Bs. 40">Exfoliación y Mascarilla Capilar — Bs. 40</option>
                                </select>
                                <span class="absolute right-3 top-1/2 -translate-y-1/2 text-[#00d4ff] pointer-events-none text-xs">🔒</span>
                            </div>
                        </div>

                        <div>
                            <label class="flex items-center gap-1.5 text-sm font-semibold text-[#00d4ff] mb-2">
                                👤 Maestro Barbero
                            </label>
                            <div class="relative">
                                <select id="bk-barber" class="input-dark pr-10 appearance-none">
                                    <option value="Cualquier Barbero (Atención Inmediata)">⚡ Cualquier Barbero (Atención Inmediata)</option>
                                    <option value="Cristian Macha">Cristian Macha — Master Barber &amp; Estilista</option>
                                    <option value="Rogher Luara">Rogher Luara — Especialista en Barba</option>
                                    <option value="Esteban Apaza">Esteban Apaza — Barbero VIP &amp; Colorista</option>
                                </select>
                                <span class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none text-xs">▼</span>
                            </div>
                        </div>

                        <div>
                            <label for="bk-name" class="flex items-center gap-1.5 text-sm font-semibold text-[#00d4ff] mb-2">
                                👤 Nombre Completo <span class="text-rose-400">*</span>
                            </label>
                            <input id="bk-name" type="text" class="input-dark" placeholder="Tu nombre completo">
                        </div>

                        <div>
                            <label for="bk-phone" class="flex items-center gap-1.5 text-sm font-semibold text-[#00d4ff] mb-2">
                                📱 Teléfono Móvil (WhatsApp) <span class="text-rose-400">*</span>
                            </label>
                            <input id="bk-phone" type="tel" class="input-dark" placeholder="+591 76666668">
                        </div>

                        <div>
                            <label for="bk-email" class="flex items-center gap-1.5 text-sm font-semibold text-[#00d4ff] mb-2">
                                ✉ Correo Electrónico <span class="text-rose-400">*</span>
                            </label>
                            <input id="bk-email" type="email" class="input-dark" placeholder="cliente@correo.com">
                        </div>

                        <div>
                            <label for="bk-notes" class="flex items-center gap-1.5 text-sm font-semibold text-[#00d4ff] mb-2">
                                ✏ Detalles de Estilo o Notas <span class="text-slate-500 font-normal">(Opcional)</span>
                            </label>
                            <textarea id="bk-notes" rows="3" class="input-dark resize-y" placeholder="Ej. Low fade con navaja, toalla caliente extra, barba perfilada..."></textarea>
                        </div>

                        <div>
                            <label class="flex items-center gap-1.5 text-sm font-semibold text-[#00d4ff] mb-2">
                                📷 Foto de referencia de corte <span class="text-slate-500 font-normal">(opcional)</span>
                            </label>
                            <label for="bk-photo" class="flex flex-col items-center justify-center gap-2 w-full rounded-xl border-2 border-dashed border-[#00d4ff]/30 bg-[#00d4ff]/5 hover:bg-[#00d4ff]/10 transition-colors cursor-pointer py-5 text-center">
                                <span class="text-2xl">📷</span>
                                <span id="bk-photo-label" class="text-sm text-[#00d4ff] font-semibold">Subir Imagen de Ejemplo</span>
                                <span class="text-xs text-slate-500">JPG, PNG o WEBP · Máx 5 MB</span>
                            </label>
                            <input id="bk-photo" type="file" accept="image/*" class="hidden">
                        </div>
                    </div>

                    {{-- RIGHT: Calendar + Time slots --}}
                    <div class="px-6 py-6 space-y-6">

                        {{-- Calendar section --}}
                        <div class="card-dark p-5">
                            <div class="flex items-center justify-between mb-4 gap-3 flex-wrap">
                                <div class="flex items-center gap-2">
                                    <span class="text-[#00d4ff]">📅</span>
                                    <span class="font-bold text-white text-sm tracking-wider">1. ELIGE FECHA EN CALENDARIO</span>
                                </div>
                                <button type="button" id="bk-pick-other" class="flex items-center gap-1.5 text-xs px-3 py-1.5 rounded-lg border border-[#00d4ff]/30 text-[#00d4ff] hover:bg-[#00d4ff]/10 transition-colors">
                                    📅 Elegir Otra Fecha
                                </button>
                            </div>

                            <div id="quick-days" class="flex gap-2 mb-4 flex-wrap"></div>

                            <div class="border border-[#00d4ff]/15 rounded-xl overflow-hidden">
                                <div class="flex items-center justify-between px-4 py-3 bg-[#080d19]">
                                    <button type="button" id="cal-prev" class="w-8 h-8 grid place-items-center rounded-lg hover:bg-[#00d4ff]/15 text-[#00d4ff] transition-colors text-lg">&lt;</button>
                                    <div class="flex items-center gap-2">
                                        <span id="cal-month-label" class="font-bold text-[#00d4ff]"></span>
                                        <button type="button" id="cal-today-btn" class="text-xs px-2 py-0.5 rounded-full border border-[#00d4ff]/40 text-[#00d4ff] hover:bg-[#00d4ff]/10 transition-colors">Hoy</button>
                                    </div>
                                    <button type="button" id="cal-next" class="w-8 h-8 grid place-items-center rounded-lg hover:bg-[#00d4ff]/15 text-[#00d4ff] transition-colors text-lg">&gt;</button>
                                </div>
                                <div class="px-3 pb-3">
                                    <div class="grid grid-cols-7 text-center text-xs text-slate-400 py-2 border-b border-[#00d4ff]/10">
                                        <span>LU</span><span>MA</span><span>MI</span><span>JU</span><span>VI</span><span class="text-slate-300">SÁ</span><span class="text-rose-400">DO</span>
                                    </div>
                                    <div id="cal-grid" class="grid grid-cols-7 gap-y-1 mt-2"></div>
                                </div>
                            </div>

                            <div class="flex items-center justify-between mt-3 px-1">
                                <span class="flex items-center gap-1.5 text-sm text-slate-400">
                                    <span class="text-[#00d4ff]">📅</span>
                                    <span class="font-semibold text-white">Fecha elegida:</span>
                                </span>
                                <div class="flex items-center gap-2">
                                    <span id="bk-date-display" class="text-sm text-slate-400">--/--/----</span>
                                    <button type="button" id="bk-clear-date" class="text-xs text-[#00d4ff] hover:underline hidden">✎ Cambiar</button>
                                </div>
                            </div>
                        </div>

                        {{-- Time slot section --}}
                        <div class="card-dark p-5">
                            <div class="flex items-center justify-between mb-4 flex-wrap gap-2">
                                <div class="flex items-center gap-2">
                                    <span class="text-[#00d4ff]">🕐</span>
                                    <span class="font-bold text-white text-sm tracking-wider">2. SELECCIONA LA HORA DEL TURNO</span>
                                </div>
                                <span id="selected-time-badge" class="px-2 py-0.5 rounded-full bg-[#00d4ff]/20 border border-[#00d4ff]/40 text-[#00d4ff] text-xs font-bold hidden"></span>
                            </div>

                            <div class="flex gap-2 mb-4 flex-wrap">
                                <button type="button" class="time-filter active-filter px-4 py-1.5 rounded-full text-sm font-semibold transition-all" data-period="all">Todos</button>
                                <button type="button" class="time-filter px-4 py-1.5 rounded-full text-sm font-semibold transition-all border border-[#00d4ff]/25 text-slate-300 hover:border-[#00d4ff]/50" data-period="morning">Mañana</button>
                                <button type="button" class="time-filter px-4 py-1.5 rounded-full text-sm font-semibold transition-all border border-[#00d4ff]/25 text-slate-300 hover:border-[#00d4ff]/50" data-period="afternoon">Tarde</button>
                                <button type="button" class="time-filter px-4 py-1.5 rounded-full text-sm font-semibold transition-all border border-[#00d4ff]/25 text-slate-300 hover:border-[#00d4ff]/50" data-period="night">Noche</button>
                            </div>

                            <div id="time-grid" class="grid grid-cols-4 sm:grid-cols-5 gap-2 max-h-52 overflow-y-auto pr-1"></div>
                        </div>

                        <button type="button" id="bk-confirm" class="btn-cyan w-full text-base py-3 font-bold opacity-60 cursor-not-allowed" disabled>
                            💳 Continuar al Pago
                        </button>
                        <p class="text-center text-xs text-slate-500">Recibirás un folio de confirmación por WhatsApp y correo electrónico.</p>                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ===================== PAYMENT MODAL ===================== --}}
    <div id="payment-modal" class="fixed inset-0 z-50 hidden" role="dialog" aria-modal="true" aria-labelledby="payment-title">
        <div id="payment-backdrop" class="absolute inset-0 bg-black/75 backdrop-blur-sm"></div>
        <div class="relative flex items-start justify-center min-h-screen px-3 py-8 overflow-y-auto">
            <div class="relative w-full max-w-lg bg-[#0a1428] border border-[#00d4ff]/25 rounded-2xl shadow-[0_0_60px_rgba(0,212,255,0.14)] overflow-hidden">

                {{-- Header del pago --}}
                <div class="flex items-center justify-between px-5 py-4 border-b border-[#00d4ff]/15 bg-[#080d19]">
                    <div class="flex items-center gap-3">
                        <span class="grid place-items-center w-10 h-10 rounded-xl overflow-hidden shadow-[0_0_14px_rgba(0,212,255,0.4)]">
                            <img src="/images/logo.png" alt="Barber Bless" class="w-full h-full object-cover">
                        </span>
                        <div>
                            <h2 id="payment-title" class="text-base font-extrabold text-white">Selecciona tu Método de Pago</h2>
                            <p class="text-xs text-slate-400">Paso 3 de 3 · Confirma y paga tu reserva</p>
                        </div>
                    </div>
                    <button id="close-payment" type="button" class="w-9 h-9 grid place-items-center rounded-xl border border-[#00d4ff]/25 text-slate-400 hover:text-white hover:border-[#00d4ff]/50 transition-colors" aria-label="Cerrar">✕</button>
                </div>

                {{-- Resumen de la cita --}}
                <div class="mx-5 mt-5 p-4 rounded-xl bg-[#00d4ff]/8 border border-[#00d4ff]/20">
                    <p class="text-xs font-bold tracking-wider text-[#00d4ff] mb-2">📋 RESUMEN DE CITA</p>
                    <div class="grid grid-cols-2 gap-y-1.5 text-sm">
                        <span class="text-slate-400">Servicio:</span>
                        <span id="pay-service" class="text-white font-semibold text-right"></span>
                        <span class="text-slate-400">Barbero:</span>
                        <span id="pay-barber" class="text-white font-semibold text-right"></span>
                        <span class="text-slate-400">Fecha:</span>
                        <span id="pay-date" class="text-white font-semibold text-right"></span>
                        <span class="text-slate-400">Hora:</span>
                        <span id="pay-time" class="text-white font-semibold text-right"></span>
                        <span class="text-slate-400">Cliente:</span>
                        <span id="pay-client" class="text-white font-semibold text-right"></span>
                    </div>
                    <div class="mt-3 pt-3 border-t border-[#00d4ff]/20 flex items-center justify-between">
                        <span class="text-slate-400 text-sm">Total a pagar:</span>
                        <span id="pay-total" class="text-2xl font-extrabold text-[#00d4ff] glow-cyan"></span>
                    </div>
                </div>

                {{-- Selección de método de pago --}}
                <div class="px-5 mt-5">
                    <p class="text-xs font-bold tracking-wider text-slate-400 mb-3">ELIGE TU MÉTODO DE PAGO</p>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3" id="payment-methods">

                        {{-- QR Bancario --}}
                        <button type="button" class="pay-method-btn selected-pay flex flex-col items-center gap-2 p-4 rounded-xl border-2 transition-all" data-method="qr">
                            <span class="text-3xl">📱</span>
                            <span class="text-sm font-bold text-white">QR Simple</span>
                            <span class="text-xs text-slate-400 text-center leading-tight">Cualquier banco boliviano</span>
                            <span class="badge text-[10px] mt-1">Recomendado</span>
                        </button>

                        {{-- Efectivo --}}
                        <button type="button" class="pay-method-btn flex flex-col items-center gap-2 p-4 rounded-xl border transition-all border-[#00d4ff]/20 text-slate-300 hover:border-[#00d4ff]/50 hover:bg-[#00d4ff]/8" data-method="cash">
                            <span class="text-3xl">💵</span>
                            <span class="text-sm font-bold">Efectivo</span>
                            <span class="text-xs text-slate-400 text-center leading-tight">Pago en el local (Bs.)</span>
                            <span class="px-2 py-0.5 rounded-full bg-amber-400/15 border border-amber-400/30 text-amber-300 text-[10px] font-bold mt-1">En barbería</span>
                        </button>

                        {{-- Tarjeta --}}
                        <button type="button" class="pay-method-btn flex flex-col items-center gap-2 p-4 rounded-xl border transition-all border-[#00d4ff]/20 text-slate-300 hover:border-[#00d4ff]/50 hover:bg-[#00d4ff]/8" data-method="card">
                            <span class="text-3xl">💳</span>
                            <span class="text-sm font-bold">Tarjeta</span>
                            <span class="text-xs text-slate-400 text-center leading-tight">Débito / Crédito Visa · MC</span>
                            <span class="px-2 py-0.5 rounded-full bg-emerald-400/15 border border-emerald-400/30 text-emerald-300 text-[10px] font-bold mt-1">Pos en local</span>
                        </button>
                    </div>
                </div>

                {{-- Panel dinámico por método --}}
                <div class="px-5 mt-5" id="pay-panel">

                    {{-- QR Panel --}}
                    <div id="pay-panel-qr" class="pay-panel-content">
                        <div class="card-dark p-5 text-center">
                            <p class="text-xs font-bold tracking-wider text-[#00d4ff] mb-3">📲 ESCANEA EL CÓDIGO QR</p>

                            {{-- QR SVG generado dinámicamente --}}
                            <div class="flex justify-center mb-3">
                                <div class="relative w-48 h-48 bg-white rounded-xl p-2 shadow-[0_0_20px_rgba(0,212,255,0.3)]">
                                    <svg id="qr-svg" viewBox="0 0 100 100" class="w-full h-full">
                                        <!-- QR generado por JS -->
                                    </svg>
                                    {{-- Overlay logo en el centro del QR --}}
                                    <div class="absolute inset-0 flex items-center justify-center pointer-events-none">
                                        <div class="w-10 h-10 rounded-lg overflow-hidden border-2 border-[#00d4ff] shadow">
                                            <img src="/images/logo.png" alt="BB" class="w-full h-full object-cover">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="bg-[#00d4ff]/8 border border-[#00d4ff]/20 rounded-xl p-4 text-left space-y-2 text-sm">
                                <div class="flex items-center justify-between">
                                    <span class="text-slate-400">Banco:</span>
                                    <span class="text-white font-bold">BNB · Banco Nacional de Bolivia</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-slate-400">Titular:</span>
                                    <span class="text-white font-bold">{{ $biz['nombre_negocio'] }} S.R.L.</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-slate-400">Cuenta:</span>
                                    <span class="text-white font-bold font-mono">1000-4592-3847</span>
                                </div>
                                <div class="flex items-center justify-between border-t border-[#00d4ff]/15 pt-2 mt-2">
                                    <span class="text-slate-400">Monto exacto:</span>
                                    <span id="qr-amount" class="text-[#00d4ff] font-extrabold text-lg glow-cyan"></span>
                                </div>
                            </div>

                            <p class="mt-3 text-xs text-slate-500 leading-relaxed">
                                Abre tu app bancaria → Pagos → Escanear QR Simple.<br>
                                Envía el comprobante a <span class="text-[#00d4ff]">WhatsApp {{ $biz['telefono'] }}</span>
                            </p>

                            {{-- Otras opciones de QR --}}
                            <div class="mt-4 flex flex-wrap gap-2 justify-center">
                                <span class="px-2 py-1 rounded-lg bg-white/10 text-white text-xs font-semibold">🏦 BNB</span>
                                <span class="px-2 py-1 rounded-lg bg-white/10 text-white text-xs font-semibold">🏦 Bancosol</span>
                                <span class="px-2 py-1 rounded-lg bg-white/10 text-white text-xs font-semibold">🏦 Tigo Money</span>
                                <span class="px-2 py-1 rounded-lg bg-white/10 text-white text-xs font-semibold">🏦 Banco FIE</span>
                                <span class="px-2 py-1 rounded-lg bg-white/10 text-white text-xs font-semibold">🏦 BISA</span>
                                <span class="px-2 py-1 rounded-lg bg-white/10 text-white text-xs font-semibold">📲 Todos los bancos</span>
                            </div>
                        </div>
                    </div>

                    {{-- Efectivo Panel --}}
                    <div id="pay-panel-cash" class="pay-panel-content hidden">
                        <div class="card-dark p-5 text-center">
                            <div class="text-5xl mb-3">💵</div>
                            <h4 class="text-white font-bold text-lg mb-2">Pago en Efectivo</h4>
                            <p class="text-slate-400 text-sm leading-relaxed mb-4">
                                Reserva tu turno ahora sin costo previo. El pago se realiza directamente en la barbería al momento de tu servicio.
                            </p>
                            <div class="bg-amber-400/10 border border-amber-400/25 rounded-xl p-4 text-left space-y-2 text-sm">
                                <div class="flex items-center gap-2 text-amber-300 font-bold text-base mb-1">
                                    <span>ℹ️</span> Instrucciones
                                </div>
                                <div class="flex items-center gap-2 text-slate-300">
                                    <span class="text-amber-300">✓</span> Presenta tu folio de reserva al llegar
                                </div>
                                <div class="flex items-center gap-2 text-slate-300">
                                    <span class="text-amber-300">✓</span> Paga en Bolivianos (Bs.) al barbero
                                </div>
                                <div class="flex items-center gap-2 text-slate-300">
                                    <span class="text-amber-300">✓</span> Llega 5 min antes para tu bebida de cortesía
                                </div>
                                <div class="flex items-center gap-2 text-slate-300">
                                    <span class="text-amber-300">✓</span> No se cobra ningún costo de reserva online
                                </div>
                            </div>
                            <div class="mt-4 p-3 rounded-xl bg-[#00d4ff]/8 border border-[#00d4ff]/20 text-sm">
                                <span class="text-slate-400">Monto a preparar: </span>
                                <span id="cash-amount" class="text-[#00d4ff] font-extrabold text-lg glow-cyan"></span>
                            </div>
                        </div>
                    </div>

                    {{-- Tarjeta Panel --}}
                    <div id="pay-panel-card" class="pay-panel-content hidden">
                        <div class="card-dark p-5">
                            <div class="text-center mb-4">
                                <div class="text-4xl mb-2">💳</div>
                                <h4 class="text-white font-bold text-lg">Tarjeta Débito / Crédito</h4>
                                <p class="text-slate-400 text-sm mt-1">El cobro se realiza con POS en la barbería</p>
                            </div>
                            <div class="space-y-3">
                                <div class="flex items-center gap-3 p-3 rounded-xl bg-white/5 border border-white/10">
                                    <span class="text-2xl">💳</span>
                                    <div>
                                        <p class="text-white font-semibold text-sm">Visa / Mastercard</p>
                                        <p class="text-slate-400 text-xs">Débito y crédito nacionales e internacionales</p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-3 p-3 rounded-xl bg-white/5 border border-white/10">
                                    <span class="text-2xl">🔒</span>
                                    <div>
                                        <p class="text-white font-semibold text-sm">Pago seguro con chip y PIN</p>
                                        <p class="text-slate-400 text-xs">Terminal POS certificado, sin recargo</p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-3 p-3 rounded-xl bg-white/5 border border-white/10">
                                    <span class="text-2xl">🏦</span>
                                    <div>
                                        <p class="text-white font-semibold text-sm">Banco emisor en Bolivia</p>
                                        <p class="text-slate-400 text-xs">BNB, Banco Sol, BISA, FIE, y más</p>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-4 p-3 rounded-xl bg-[#00d4ff]/8 border border-[#00d4ff]/20 text-sm text-center">
                                <span class="text-slate-400">Monto a cobrar: </span>
                                <span id="card-amount" class="text-[#00d4ff] font-extrabold text-lg glow-cyan"></span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Footer del pago --}}
                <div class="px-5 pb-6 mt-5">
                    <button type="button" id="pay-confirm-btn" class="btn-cyan w-full text-base py-3.5 font-bold">
                        ✅ Confirmar Reserva y Pago
                    </button>
                    <button type="button" id="pay-back-btn" class="w-full mt-2 py-2.5 text-sm text-slate-400 hover:text-[#00d4ff] transition-colors">
                        ← Volver a editar la cita
                    </button>
                    <p class="text-center text-xs text-slate-600 mt-3 flex items-center justify-center gap-1.5">
                        <span>🔒</span> Tu información está protegida · SSL cifrado
                    </p>
                </div>
            </div>
        </div>
    </div>

    {{-- ===================== SUCCESS MODAL ===================== --}}
    <div id="success-modal" class="fixed inset-0 z-50 hidden" role="dialog" aria-modal="true">
        <div class="absolute inset-0 bg-black/70 backdrop-blur-sm"></div>
        <div class="relative flex items-center justify-center min-h-screen px-4">
            <div class="relative bg-[#0a1428] border border-emerald-500/30 rounded-2xl p-8 max-w-md w-full text-center shadow-[0_0_60px_rgba(0,212,255,0.1)]">
                <div class="grid place-items-center w-20 h-20 rounded-full bg-emerald-500/20 border border-emerald-500/40 text-5xl mx-auto mb-4">✅</div>
                <h3 class="text-2xl font-extrabold text-white mb-2">¡Cita Confirmada!</h3>
                <p class="text-slate-400 mb-4" id="success-detail">Tu turno ha sido agendado exitosamente.</p>
                <div id="folio-number" class="inline-block px-5 py-2 rounded-xl bg-[#00d4ff]/15 border border-[#00d4ff]/30 text-[#00d4ff] font-bold tracking-widest text-lg mb-6"></div>
                <p class="text-xs text-slate-500 mb-6">Guarda tu folio para consultar el estado de tu cita.</p>
                <button type="button" id="close-success" class="btn-cyan w-full">Perfecto, gracias</button>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
<script>
// ── Hamburger nav menu ────────────────────────────────────────────────────
const navMenuBtn  = document.getElementById('nav-menu-btn');
const navMobileMenu = document.getElementById('nav-mobile-menu');

if (navMenuBtn && navMobileMenu) {
    navMenuBtn.addEventListener('click', () => {
        const isOpen = !navMobileMenu.classList.contains('hidden');
        navMobileMenu.classList.toggle('hidden', isOpen);
        navMenuBtn.setAttribute('aria-expanded', String(!isOpen));
        // Animate hamburger → X
        const bars = navMenuBtn.querySelectorAll('.nav-hamburger-icon span');
        if (!isOpen) {
            bars[0].style.transform = 'rotate(45deg) translate(5px,5px)';
            bars[1].style.opacity  = '0';
            bars[2].style.transform = 'rotate(-45deg) translate(5px,-5px)';
        } else {
            bars[0].style.transform = '';
            bars[1].style.opacity  = '1';
            bars[2].style.transform = '';
        }
    });
    // Close mobile menu when a link is clicked
    navMobileMenu.querySelectorAll('.nav-mobile-link').forEach(link => {
        link.addEventListener('click', () => {
            navMobileMenu.classList.add('hidden');
            navMenuBtn.setAttribute('aria-expanded', 'false');
            const bars = navMenuBtn.querySelectorAll('.nav-hamburger-icon span');
            bars[0].style.transform = ''; bars[1].style.opacity = '1'; bars[2].style.transform = '';
        });
    });
}

// ── Open / close modals ───────────────────────────────────────────────────
const bookingModal = document.getElementById('booking-modal');
const paymentModal = document.getElementById('payment-modal');
const successModal = document.getElementById('success-modal');

function openModal()    { bookingModal.classList.remove('hidden'); document.body.style.overflow = 'hidden'; }
function closeModal()   { bookingModal.classList.add('hidden');    document.body.style.overflow = ''; }
function openPayment()  { paymentModal.classList.remove('hidden'); }
function closePayment() { paymentModal.classList.add('hidden'); }
function openSuccess()  { successModal.classList.remove('hidden'); }
function closeSuccess() { successModal.classList.add('hidden'); document.body.style.overflow = ''; }

document.getElementById('open-booking').addEventListener('click', openModal);
document.querySelectorAll('.open-booking-btn').forEach(b => b.addEventListener('click', openModal));
document.getElementById('close-booking').addEventListener('click', closeModal);
document.getElementById('booking-backdrop').addEventListener('click', closeModal);
document.getElementById('close-payment').addEventListener('click', closePayment);
document.getElementById('payment-backdrop').addEventListener('click', closePayment);
document.addEventListener('keydown', e => { if (e.key === 'Escape') { closeModal(); closePayment(); closeSuccess(); } });
document.getElementById('close-success').addEventListener('click', closeSuccess);

// ── Photo label ──────────────────────────────────────────────────────────
document.getElementById('bk-photo').addEventListener('change', function () {
    document.getElementById('bk-photo-label').textContent =
        this.files[0] ? this.files[0].name : 'Subir Imagen de Ejemplo';
});

// ── QR SVG generator (visual approximation) ──────────────────────────────
function generateQR(amount) {
    const svg = document.getElementById('qr-svg');
    if (!svg) { return; }
    // Build a deterministic-looking QR pattern seeded from the amount string
    const seed = amount.split('').reduce((acc, c) => acc + c.charCodeAt(0), 0);
    const size = 21; // 21×21 modules (version 1 QR approximation)
    const cell = 100 / size;
    let html = '';

    // Fixed finder patterns (top-left, top-right, bottom-left)
    const finderPositions = [
        [0,0],[1,0],[2,0],[3,0],[4,0],[5,0],[6,0],
        [0,1],[6,1],[0,2],[2,2],[3,2],[4,2],[6,2],
        [0,3],[2,3],[4,3],[6,3],[0,4],[2,4],[3,4],[4,4],[6,4],
        [0,5],[6,5],[0,6],[1,6],[2,6],[3,6],[4,6],[5,6],[6,6],
        [14,0],[15,0],[16,0],[17,0],[18,0],[19,0],[20,0],
        [14,1],[20,1],[14,2],[16,2],[17,2],[18,2],[20,2],
        [14,3],[16,3],[18,3],[20,3],[14,4],[16,4],[17,4],[18,4],[20,4],
        [14,5],[20,5],[14,6],[15,6],[16,6],[17,6],[18,6],[19,6],[20,6],
        [0,14],[1,14],[2,14],[3,14],[4,14],[5,14],[6,14],
        [0,15],[6,15],[0,16],[2,16],[3,16],[4,16],[6,16],
        [0,17],[2,17],[4,17],[6,17],[0,18],[2,18],[3,18],[4,18],[6,18],
        [0,19],[6,19],[0,20],[1,20],[2,20],[3,20],[4,20],[5,20],[6,20],
    ];
    const finderSet = new Set(finderPositions.map(([x,y]) => `${x},${y}`));

    // Data modules: pseudo-random based on seed, skip center (logo area 8-12,8-12)
    const rng = (x, y) => { const v = (seed * 31 + x * 17 + y * 13) % 97; return v > 42; };
    const centerZone = (x, y) => x >= 8 && x <= 12 && y >= 8 && y <= 12;

    for (let y = 0; y < size; y++) {
        for (let x = 0; x < size; x++) {
            let filled = false;
            if (finderSet.has(`${x},${y}`)) {
                filled = true;
            } else if (!centerZone(x, y)) {
                filled = rng(x, y);
            }
            if (filled) {
                html += `<rect x="${(x * cell).toFixed(1)}" y="${(y * cell).toFixed(1)}" width="${cell.toFixed(1)}" height="${cell.toFixed(1)}" fill="#000"/>`;
            }
        }
    }
    svg.innerHTML = html;
}

// ── Payment method switching ──────────────────────────────────────────────
let currentPayMethod = 'qr';

function switchPayMethod(method) {
    currentPayMethod = method;
    // Update button styles
    document.querySelectorAll('.pay-method-btn').forEach(btn => {
        const isActive = btn.dataset.method === method;
        btn.className = isActive
            ? 'pay-method-btn selected-pay flex flex-col items-center gap-2 p-4 rounded-xl border-2 transition-all'
            : 'pay-method-btn flex flex-col items-center gap-2 p-4 rounded-xl border transition-all border-[#00d4ff]/20 text-slate-300 hover:border-[#00d4ff]/50 hover:bg-[#00d4ff]/8';
    });
    // Show/hide panels
    document.querySelectorAll('.pay-panel-content').forEach(p => p.classList.add('hidden'));
    const panel = document.getElementById('pay-panel-' + method);
    if (panel) { panel.classList.remove('hidden'); }
}

document.querySelectorAll('.pay-method-btn').forEach(btn => {
    btn.addEventListener('click', () => switchPayMethod(btn.dataset.method));
});

// ── Open payment modal (from bk-confirm) ─────────────────────────────────
document.getElementById('bk-confirm').addEventListener('click', () => {
    const name    = document.getElementById('bk-name').value.trim();
    const phone   = document.getElementById('bk-phone').value.trim();
    const email   = document.getElementById('bk-email').value.trim();
    const svcRaw  = document.getElementById('bk-service').value;
    const barber  = document.getElementById('bk-barber').value;

    if (!name || !phone || !email) {
        alert('Por favor completa: Nombre, Teléfono y Correo antes de continuar.');
        return;
    }

    // Parse amount from service label (e.g. "Corte Fade — Bs. 60")
    const amountMatch = svcRaw.match(/Bs\.\s*(\d+)/);
    const amount = amountMatch ? 'Bs. ' + amountMatch[1] : 'Bs. —';
    const svcLabel = svcRaw.split('—')[0].trim() || 'Servicio por confirmar';
    const dateStr = selectedDate
        ? selectedDate.toLocaleDateString('es-ES', { weekday: 'long', day: 'numeric', month: 'long', year: 'numeric' })
        : '--';

    // Populate summary
    document.getElementById('pay-service').textContent = svcLabel;
    document.getElementById('pay-barber').textContent  = barber;
    document.getElementById('pay-date').textContent    = dateStr;
    document.getElementById('pay-time').textContent    = selectedTime || '--';
    document.getElementById('pay-client').textContent  = name;
    document.getElementById('pay-total').textContent   = amount;

    // Populate per-method amounts
    document.getElementById('qr-amount').textContent   = amount;
    document.getElementById('cash-amount').textContent = amount;
    document.getElementById('card-amount').textContent = amount;

    // Generate QR
    generateQR(amount);

    // Reset to QR method
    switchPayMethod('qr');

    // Open payment modal (booking stays open beneath)
    openPayment();
});

// ── Back button: close payment, keep booking open ─────────────────────────
document.getElementById('pay-back-btn').addEventListener('click', closePayment);

// ── Confirm payment → generate folio → success ────────────────────────────
document.getElementById('pay-confirm-btn').addEventListener('click', async () => {
    const name     = document.getElementById('bk-name').value.trim();
    const phone    = document.getElementById('bk-phone').value.trim();
    const email    = document.getElementById('bk-email').value.trim();
    const svcRaw   = document.getElementById('bk-service').value;
    const barber   = document.getElementById('bk-barber').value;
    const notes    = document.getElementById('bk-notes').value.trim();

    // Basic validation
    if (!name || !phone || !email) {
        alert('Por favor completa: Nombre, Teléfono y Correo antes de continuar.');
        return;
    }
    if (!selectedDate || !selectedTime) {
        alert('Por favor selecciona una fecha y hora.');
        return;
    }

    const svcLabel = svcRaw.split('—')[0].trim() || 'Servicio por confirmar';
    const dateStr  = selectedDate.toLocaleDateString('es-ES', {
        weekday: 'long', day: 'numeric', month: 'long', year: 'numeric',
    });

    // Map payment method: 'card' y 'cash' → 'local' (lo que acepta el backend)
    const metodoPagoMap = { qr: 'qr', cash: 'local', card: 'local' };
    const metodoPago    = metodoPagoMap[currentPayMethod] || 'local';

    // Disable button while sending
    const payBtn = document.getElementById('pay-confirm-btn');
    payBtn.disabled = true;
    payBtn.textContent = '⏳ Registrando cita…';

    try {
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content
            ?? document.cookie.split('; ').find(r => r.startsWith('XSRF-TOKEN='))?.split('=')[1]
            ?? '';

        const payload = {
            cliente:      name,
            telefono:     phone,
            email:        email,
            servicio:     svcLabel,
            barbero:      barber,
            fecha:        selectedDate.toLocaleDateString('es-ES', { day: '2-digit', month: '2-digit', year: 'numeric' }),
            hora:         selectedTime,
            metodo_pago:  metodoPago,
            detalles:     notes || null,
        };

        const response = await fetch('/reservas', {
            method:  'POST',
            headers: {
                'Content-Type':  'application/json',
                'Accept':        'application/json',
                'X-CSRF-TOKEN':  csrfToken,
            },
            body: JSON.stringify(payload),
        });

        const data = await response.json();

        if (!response.ok || !data.success) {
            const firstError = data.errors
                ? Object.values(data.errors)[0][0]
                : (data.mensaje || 'No se pudo registrar la cita. Intenta de nuevo.');
            alert('⚠ Error: ' + firstError);
            payBtn.disabled = false;
            payBtn.textContent = '✅ Confirmar Reserva y Pago';
            return;
        }

        // Success — populate success modal with server-generated folio
        const methodLabels = { qr: 'QR Simple', cash: 'Efectivo en local', card: 'Tarjeta POS' };
        const methodLabel  = methodLabels[currentPayMethod] || 'QR Simple';

        document.getElementById('success-detail').textContent =
            `${name} · ${svcLabel} · ${dateStr} a las ${selectedTime} · Pago: ${methodLabel}`;
        document.getElementById('folio-number').textContent = '📋 ' + data.folio;

        closePayment();
        closeModal();
        openSuccess();

    } catch (err) {
        alert('Error de conexión. Revisa tu internet e intenta de nuevo.');
        payBtn.disabled = false;
        payBtn.textContent = '✅ Confirmar Reserva y Pago';
    }
});

// ── Photo label ──────────────────────────────────────────────────────────
document.getElementById('bk-photo').addEventListener('change', function () {
    document.getElementById('bk-photo-label').textContent =
        this.files[0] ? this.files[0].name : 'Subir Imagen de Ejemplo';
});

// ── Calendar ─────────────────────────────────────────────────────────────
const MONTHS = ['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'];
let calDate = new Date(), selectedDate = null;

function renderCalendar() {
    const y = calDate.getFullYear(), m = calDate.getMonth();
    document.getElementById('cal-month-label').textContent = MONTHS[m] + ' ' + y;
    const grid = document.getElementById('cal-grid');
    grid.innerHTML = '';
    const first    = new Date(y, m, 1);
    const startDay = (first.getDay() + 6) % 7;
    const daysInMonth = new Date(y, m + 1, 0).getDate();
    const today = new Date(); today.setHours(0,0,0,0);

    for (let i = 0; i < startDay; i++) { grid.insertAdjacentHTML('beforeend','<div></div>'); }

    for (let d = 1; d <= daysInMonth; d++) {
        const date   = new Date(y, m, d);
        const isPast = date < today;
        const isToday = date.getTime() === today.getTime();
        const isSel   = selectedDate && date.getTime() === selectedDate.getTime();
        const isSun   = date.getDay() === 0;

        const btn = document.createElement('button');
        btn.type = 'button'; btn.textContent = d; btn.dataset.date = date.toISOString();

        let cls = 'relative w-full aspect-square rounded-lg text-sm font-semibold transition-all ';
        if (isPast)       { cls += 'text-slate-600 cursor-not-allowed'; btn.disabled = true; }
        else if (isSel)   { cls += 'bg-[#00d4ff] text-[#04121f] shadow-[0_0_12px_rgba(0,212,255,0.5)]'; }
        else if (isToday) { cls += 'border-2 border-[#00d4ff] text-[#00d4ff] hover:bg-[#00d4ff]/20'; }
        else if (isSun)   { cls += 'text-rose-400 hover:bg-rose-500/15'; }
        else              { cls += 'text-white hover:bg-[#00d4ff]/15'; }
        btn.className = cls;

        if (isToday && !isSel) {
            const dot = document.createElement('span');
            dot.className = 'absolute bottom-1 left-1/2 -translate-x-1/2 w-1 h-1 rounded-full bg-[#00d4ff]';
            btn.appendChild(dot);
        }

        btn.addEventListener('click', () => { selectedDate = date; renderCalendar(); renderQuickDays(); updateDateDisplay(); checkReady(); });
        grid.appendChild(btn);
    }
}

function updateDateDisplay() {
    const disp = document.getElementById('bk-date-display');
    const btn  = document.getElementById('bk-clear-date');
    if (selectedDate) {
        disp.textContent = selectedDate.toLocaleDateString('es-ES',{day:'2-digit',month:'2-digit',year:'numeric'});
        disp.classList.add('text-white'); btn.classList.remove('hidden');
    } else {
        disp.textContent = '--/--/----'; disp.classList.remove('text-white'); btn.classList.add('hidden');
    }
}

document.getElementById('cal-prev').addEventListener('click',      () => { calDate.setMonth(calDate.getMonth()-1); renderCalendar(); });
document.getElementById('cal-next').addEventListener('click',      () => { calDate.setMonth(calDate.getMonth()+1); renderCalendar(); });
document.getElementById('cal-today-btn').addEventListener('click', () => { calDate = new Date(); renderCalendar(); });
document.getElementById('bk-clear-date').addEventListener('click', () => { selectedDate = null; renderCalendar(); renderQuickDays(); updateDateDisplay(); checkReady(); });
document.getElementById('bk-pick-other').addEventListener('click', () => { document.getElementById('cal-grid').scrollIntoView({behavior:'smooth'}); });

// ── Quick day buttons ─────────────────────────────────────────────────────
function renderQuickDays() {
    const container = document.getElementById('quick-days');
    container.innerHTML = '';
    const today = new Date(); today.setHours(0,0,0,0);
    for (let i = 0; i < 4; i++) {
        const d = new Date(today); d.setDate(today.getDate() + i);
        const label = i === 0 ? 'Hoy ' + d.getDate()
                    : i === 1 ? 'Mañana ' + d.getDate()
                    : d.toLocaleDateString('es-ES',{weekday:'short',day:'numeric'});
        const isSel = selectedDate && d.getTime() === selectedDate.getTime();
        const btn = document.createElement('button');
        btn.type = 'button'; btn.textContent = label;
        btn.className = 'flex-1 min-w-[70px] px-2 py-2 rounded-xl text-sm transition-all ' +
            (isSel ? 'bg-[#00d4ff] text-[#04121f] shadow-[0_0_12px_rgba(0,212,255,0.4)] font-bold'
                   : 'border border-[#00d4ff]/25 text-slate-300 hover:bg-[#00d4ff]/15');
        btn.addEventListener('click', () => {
            selectedDate = d; calDate = new Date(d);
            renderCalendar(); renderQuickDays(); updateDateDisplay(); checkReady();
        });
        container.appendChild(btn);
    }
}

// ── Time slots ───────────────────────────────────────────────────────────
let selectedTime = null, currentFilter = 'all';
const allSlots = [];
for (let h = 9; h <= 19; h++) {
    for (let m = 0; m < 60; m += 30) {
        allSlots.push(`${String(h).padStart(2,'0')}:${String(m).padStart(2,'0')}`);
    }
}

function period(t) { const h = parseInt(t); return h < 12 ? 'morning' : h < 17 ? 'afternoon' : 'night'; }

function renderTimeGrid(filter) {
    const grid = document.getElementById('time-grid'); grid.innerHTML = '';
    const visible = filter === 'all' ? allSlots : allSlots.filter(t => period(t) === filter);
    visible.forEach(t => {
        const btn = document.createElement('button');
        btn.type = 'button'; btn.textContent = t;
        btn.className = 'py-2 rounded-lg text-sm font-semibold transition-all ' +
            (t === selectedTime
                ? 'bg-[#00d4ff] text-[#04121f] shadow-[0_0_10px_rgba(0,212,255,0.4)]'
                : 'border border-[#00d4ff]/20 text-slate-300 hover:border-[#00d4ff]/50 hover:bg-[#00d4ff]/15');
        btn.addEventListener('click', () => {
            selectedTime = t; renderTimeGrid(currentFilter);
            const badge = document.getElementById('selected-time-badge');
            badge.textContent = t; badge.classList.remove('hidden');
            checkReady();
        });
        grid.appendChild(btn);
    });
}

document.querySelectorAll('.time-filter').forEach(btn => {
    btn.addEventListener('click', function () {
        currentFilter = this.dataset.period;
        document.querySelectorAll('.time-filter').forEach(b => {
            b.className = 'time-filter px-4 py-1.5 rounded-full text-sm font-semibold transition-all border border-[#00d4ff]/25 text-slate-300 hover:border-[#00d4ff]/50';
        });
        this.className = 'time-filter active-filter px-4 py-1.5 rounded-full text-sm font-semibold transition-all';
        renderTimeGrid(currentFilter);
    });
});

// ── Ready check ──────────────────────────────────────────────────────────
function checkReady() {
    const btn = document.getElementById('bk-confirm');
    const ok = selectedDate && selectedTime;
    btn.disabled = !ok;
    btn.classList.toggle('opacity-60', !ok);
    btn.classList.toggle('cursor-not-allowed', !ok);
}

// ── Active filter style ──────────────────────────────────────────────────
document.head.insertAdjacentHTML('beforeend', '<style>' +
    '.active-filter{background:#00d4ff;color:#04121f;box-shadow:0 0 12px rgba(0,212,255,0.35);}' +
    '.selected-pay{border-color:#00d4ff;background:rgba(0,212,255,0.12);color:#fff;box-shadow:0 0 14px rgba(0,212,255,0.25);}' +
    '.selected-pay span:last-child{display:none;}' +
'</style>');

// ── Init ─────────────────────────────────────────────────────────────────
renderCalendar(); renderQuickDays(); renderTimeGrid('all');
switchPayMethod('qr');
</script>
@endpush
