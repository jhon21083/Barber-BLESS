@extends('layouts.admin')

@section('title', 'Reservas | Barber Bless')
@section('page-title', 'Gestión de Reservas')
@section('page-subtitle', 'Control de citas, agenda de barberos y turnos en tiempo real')

@section('admin-content')

    {{-- Alerta de éxito --}}
    @if(session('success_reserva'))
        <div class="flex items-center gap-2 px-4 py-3 rounded-lg bg-emerald-500/15 border border-emerald-500/30 text-emerald-300 text-sm">
            ✔ {{ session('success_reserva') }}
        </div>
    @endif

    {{-- ── KPI stats ───────────────────────────────────────────────────── --}}
    <section class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4">
        <div class="card-dark p-5">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-xs tracking-wider text-slate-400 uppercase">Citas Hoy</p>
                    <p class="mt-2 text-3xl font-extrabold {{ $stats['total_hoy'] === 0 ? 'text-slate-500' : 'text-[#00d4ff] glow-cyan' }}">
                        {{ $stats['total_hoy'] }}
                    </p>
                    <p class="mt-1 text-xs text-slate-400">
                        {{ $stats['total_hoy'] === 0 ? 'Sin reservas hoy' : 'Registradas hoy' }}
                    </p>
                </div>
                <span class="grid place-items-center w-11 h-11 rounded-xl bg-[#00d4ff]/15 text-2xl">📅</span>
            </div>
        </div>

        <div class="card-dark p-5">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-xs tracking-wider text-slate-400 uppercase">Por Confirmar</p>
                    <p class="mt-2 text-3xl font-extrabold {{ $stats['pendientes'] === 0 ? 'text-slate-500' : 'text-amber-300' }}">
                        {{ $stats['pendientes'] }}
                    </p>
                    <p class="mt-1 text-xs text-slate-400">Esperando confirmación</p>
                </div>
                <span class="grid place-items-center w-11 h-11 rounded-xl bg-amber-400/15 text-2xl">⏳</span>
            </div>
        </div>

        <div class="card-dark p-5">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-xs tracking-wider text-slate-400 uppercase">En Sillón Ahora</p>
                    <p class="mt-2 text-3xl font-extrabold {{ $stats['en_proceso'] === 0 ? 'text-slate-500' : 'text-emerald-400' }}">
                        {{ $stats['en_proceso'] }}
                    </p>
                    <p class="mt-1 text-xs {{ $stats['en_proceso'] === 0 ? 'text-slate-500' : 'text-emerald-400' }}">
                        {{ $stats['en_proceso'] === 0 ? 'Ninguno en atención' : 'Atención en curso' }}
                    </p>
                </div>
                <span class="grid place-items-center w-11 h-11 rounded-xl bg-emerald-400/15 text-2xl">✂</span>
            </div>
        </div>

        <div class="card-dark p-5">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-xs tracking-wider text-slate-400 uppercase">Completadas Hoy</p>
                    <p class="mt-2 text-3xl font-extrabold {{ $stats['completadas_hoy'] === 0 ? 'text-slate-500' : 'text-[#0096ff]' }}">
                        {{ $stats['completadas_hoy'] }}
                    </p>
                    <p class="mt-1 text-xs text-slate-400">Servicios finalizados</p>
                </div>
                <span class="grid place-items-center w-11 h-11 rounded-xl bg-[#0096ff]/15 text-2xl">✅</span>
            </div>
        </div>
    </section>

    {{-- ── Filtros y búsqueda ──────────────────────────────────────────── --}}
    <section class="card-dark p-4">
        <form method="GET" action="{{ route('admin.reservas') }}" class="flex flex-col sm:flex-row items-center gap-3">
            <div class="flex flex-wrap items-center gap-2 flex-1">
                <a href="{{ route('admin.reservas') }}"
                   class="px-3 py-1.5 rounded-lg text-xs font-semibold transition-colors {{ !request('estado') && !request('buscar') ? 'bg-[#00d4ff]/20 text-[#00d4ff] border border-[#00d4ff]/40' : 'bg-slate-800 text-slate-400 hover:text-white hover:bg-slate-700' }}">
                   Todas ({{ \App\Models\Reserva::count() }})
                </a>
                @foreach(['Pendiente' => '⏳ Pendientes', 'Confirmada' => '✓ Confirmadas', 'En proceso' => '✂ En Sillón', 'Completada' => '✅ Completadas', 'Cancelada' => '✗ Canceladas'] as $val => $label)
                    <a href="{{ route('admin.reservas', ['estado' => $val]) }}"
                       class="px-3 py-1.5 rounded-lg text-xs font-semibold transition-colors {{ request('estado') === $val ? 'bg-[#00d4ff]/20 text-[#00d4ff] border border-[#00d4ff]/40' : 'bg-slate-800 text-slate-400 hover:text-white hover:bg-slate-700' }}">
                       {{ $label }}
                    </a>
                @endforeach
            </div>
            <div class="flex items-center gap-2 w-full sm:w-auto">
                <div class="relative flex-1 sm:w-56">
                    <input type="text" name="buscar" value="{{ request('buscar') }}"
                           placeholder="Buscar cliente, folio..."
                           class="input-dark py-1.5 text-xs pl-8 w-full">
                    <span class="absolute left-2.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs">🔍</span>
                </div>
                <button type="submit" class="btn-cyan text-xs py-2 px-3">Buscar</button>
            </div>
        </form>
    </section>

    {{-- ── Tabla de reservas ───────────────────────────────────────────── --}}
    <section class="card-dark overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-left text-xs tracking-wider text-slate-400 uppercase border-b border-[#00d4ff]/15 bg-[#080d19]">
                        <th class="px-4 py-3 font-semibold">Folio / Cliente</th>
                        <th class="px-4 py-3 font-semibold">Servicio</th>
                        <th class="px-4 py-3 font-semibold">Barbero</th>
                        <th class="px-4 py-3 font-semibold">Fecha &amp; Hora</th>
                        <th class="px-4 py-3 font-semibold">Total</th>
                        <th class="px-4 py-3 font-semibold">Pago</th>
                        <th class="px-4 py-3 font-semibold">Estado</th>
                        <th class="px-4 py-3 font-semibold text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#00d4ff]/10">
                    @forelse($reservas as $r)
                        @php
                            $estadoClass = match($r['estado']) {
                                'Confirmada'  => 'text-emerald-300 border-emerald-400/40 bg-emerald-400/10',
                                'En proceso'  => 'text-[#00d4ff] border-[#00d4ff]/40 bg-[#00d4ff]/10',
                                'Pendiente'   => 'text-amber-300 border-amber-400/40 bg-amber-400/10',
                                'Completada'  => 'text-slate-300 border-slate-500/40 bg-slate-500/10',
                                'Cancelada'   => 'text-rose-300 border-rose-400/40 bg-rose-400/10',
                                default       => 'text-slate-300 border-slate-500/40 bg-slate-500/10',
                            };
                            $rowBg = match($r['estado']) {
                                'En proceso' => 'bg-[#00d4ff]/3',
                                'Cancelada'  => 'opacity-60',
                                default      => '',
                            };
                        @endphp
                        <tr class="hover:bg-[#00d4ff]/5 transition-colors {{ $rowBg }}">

                            {{-- Folio / Cliente --}}
                            <td class="px-4 py-3.5">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-lg bg-gradient-to-br from-[#00d4ff]/20 to-[#0096ff]/20 border border-[#00d4ff]/30 text-[#00d4ff] font-bold grid place-items-center text-sm shrink-0">
                                        {{ strtoupper(substr($r['cliente'], 0, 1)) }}
                                    </div>
                                    <div>
                                        <p class="font-semibold text-white leading-tight">{{ $r['cliente'] }}</p>
                                        <p class="text-[11px] text-slate-400 font-mono">{{ $r['id'] }}</p>
                                        @if(!empty($r['telefono']))
                                            <p class="text-[11px] text-slate-500">📱 {{ $r['telefono'] }}</p>
                                        @endif
                                    </div>
                                </div>
                            </td>

                            {{-- Servicio --}}
                            <td class="px-4 py-3.5">
                                <p class="text-slate-200 font-medium leading-tight">{{ $r['servicio'] }}</p>
                                <p class="text-[11px] text-slate-500 mt-0.5">⏱ {{ $r['duracion'] ?? '45 min' }}</p>
                            </td>

                            {{-- Barbero --}}
                            <td class="px-4 py-3.5">
                                <div class="flex items-center gap-2">
                                    <span class="w-2 h-2 rounded-full {{ $r['estado'] === 'En proceso' ? 'bg-emerald-400 animate-pulse' : 'bg-[#00d4ff]' }}"></span>
                                    <span class="text-slate-300">{{ $r['barbero'] }}</span>
                                </div>
                            </td>

                            {{-- Fecha --}}
                            <td class="px-4 py-3.5">
                                <p class="text-white text-xs font-medium">{{ $r['fecha'] }}</p>
                                @if(!empty($r['hora']))
                                    <p class="text-[11px] text-slate-400">🕐 {{ $r['hora'] }}</p>
                                @endif
                            </td>

                            {{-- Total --}}
                            <td class="px-4 py-3.5 font-bold text-[#00d4ff] whitespace-nowrap">
                                {{ $r['precio'] }}
                            </td>

                            {{-- Pago --}}
                            <td class="px-4 py-3.5">
                                @if(($r['metodo_pago'] ?? '') === 'qr')
                                    <span class="badge text-[#00d4ff] border-[#00d4ff]/30 bg-[#00d4ff]/10">📱 QR</span>
                                    <p class="text-[11px] mt-1 {{ str_contains($r['estado_pago'] ?? '', 'Pagado') ? 'text-emerald-400 font-semibold' : 'text-amber-300' }}">
                                        {{ $r['estado_pago'] ?? 'Pendiente' }}
                                    </p>
                                @else
                                    <span class="badge text-slate-300 border-slate-600/40 bg-slate-700/40">💵 Local</span>
                                    <p class="text-[11px] mt-1 text-slate-500">Presencial</p>
                                @endif
                            </td>

                            {{-- Estado --}}
                            <td class="px-4 py-3.5">
                                <span class="badge {{ $estadoClass }} whitespace-nowrap">{{ $r['estado'] }}</span>
                            </td>

                            {{-- ── ACCIONES ── --}}
                            <td class="px-4 py-3.5">
                                @if(isset($r['db_id']))
                                    <div class="flex flex-wrap gap-1.5 justify-center">

                                        {{-- CONFIRMAR — solo si está Pendiente --}}
                                        @if($r['estado'] === 'Pendiente')
                                            <form method="POST" action="{{ route('admin.reservas.status', $r['db_id']) }}">
                                                @csrf @method('PATCH')
                                                <input type="hidden" name="estado" value="Confirmada">
                                                <button type="submit"
                                                    title="Confirmar reserva"
                                                    class="flex items-center gap-1 px-2.5 py-1.5 rounded-lg bg-emerald-500/15 text-emerald-300 border border-emerald-500/30 hover:bg-emerald-500/30 text-xs font-bold transition-all">
                                                    ✓ Confirmar
                                                </button>
                                            </form>

                                            {{-- RECHAZAR — solo si está Pendiente --}}
                                            <form method="POST" action="{{ route('admin.reservas.status', $r['db_id']) }}"
                                                  onsubmit="return confirm('¿Rechazar la reserva de {{ $r['cliente'] }}?')">
                                                @csrf @method('PATCH')
                                                <input type="hidden" name="estado" value="Cancelada">
                                                <button type="submit"
                                                    title="Rechazar reserva"
                                                    class="flex items-center gap-1 px-2.5 py-1.5 rounded-lg bg-rose-500/15 text-rose-300 border border-rose-500/30 hover:bg-rose-500/30 text-xs font-bold transition-all">
                                                    ✗ Rechazar
                                                </button>
                                            </form>
                                        @endif

                                        {{-- EN SILLÓN — si está Confirmada --}}
                                        @if($r['estado'] === 'Confirmada')
                                            <form method="POST" action="{{ route('admin.reservas.status', $r['db_id']) }}">
                                                @csrf @method('PATCH')
                                                <input type="hidden" name="estado" value="En proceso">
                                                <button type="submit"
                                                    title="Pasar al sillón"
                                                    class="flex items-center gap-1 px-2.5 py-1.5 rounded-lg bg-[#00d4ff]/15 text-[#00d4ff] border border-[#00d4ff]/30 hover:bg-[#00d4ff]/30 text-xs font-bold transition-all whitespace-nowrap">
                                                    ✂ En Sillón
                                                </button>
                                            </form>
                                        @endif

                                        {{-- COMPLETAR — si está En proceso --}}
                                        @if($r['estado'] === 'En proceso')
                                            <form method="POST" action="{{ route('admin.reservas.status', $r['db_id']) }}">
                                                @csrf @method('PATCH')
                                                <input type="hidden" name="estado" value="Completada">
                                                <input type="hidden" name="estado_pago" value="Pagado">
                                                <button type="submit"
                                                    title="Marcar como completada"
                                                    class="flex items-center gap-1 px-2.5 py-1.5 rounded-lg bg-[#0096ff]/15 text-[#0096ff] border border-[#0096ff]/30 hover:bg-[#0096ff]/30 text-xs font-bold transition-all">
                                                    ✅ Completar
                                                </button>
                                            </form>
                                        @endif

                                        {{-- COMPLETADA — badge final --}}
                                        @if($r['estado'] === 'Completada')
                                            <span class="flex items-center gap-1 px-2.5 py-1.5 rounded-lg bg-slate-700/40 text-slate-400 border border-slate-600/30 text-xs font-semibold">
                                                ✓ Listo
                                            </span>
                                        @endif

                                        {{-- CANCELADA — opción reactivar --}}
                                        @if($r['estado'] === 'Cancelada')
                                            <form method="POST" action="{{ route('admin.reservas.status', $r['db_id']) }}">
                                                @csrf @method('PATCH')
                                                <input type="hidden" name="estado" value="Pendiente">
                                                <button type="submit"
                                                    title="Reactivar reserva"
                                                    class="flex items-center gap-1 px-2.5 py-1.5 rounded-lg bg-amber-500/15 text-amber-300 border border-amber-500/30 hover:bg-amber-500/30 text-xs font-bold transition-all">
                                                    ↩ Reactivar
                                                </button>
                                            </form>
                                        @endif

                                    </div>
                                @else
                                    <span class="text-xs text-slate-500 text-center block">—</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8">
                                <div class="flex flex-col items-center justify-center py-16 gap-3 text-center">
                                    <span class="text-5xl opacity-20">📋</span>
                                    <p class="text-slate-400 font-semibold">No hay reservas registradas aún</p>
                                    <p class="text-slate-500 text-xs">Las reservas que hagan los clientes aparecerán aquí automáticamente.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>

    {{-- ── Leyenda de estados ──────────────────────────────────────────── --}}
    <section class="card-dark p-4">
        <p class="text-xs text-slate-500 font-semibold uppercase tracking-wider mb-3">Flujo de estados de una reserva:</p>
        <div class="flex flex-wrap items-center gap-2 text-xs">
            <span class="flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-amber-400/10 text-amber-300 border border-amber-400/25">⏳ Pendiente</span>
            <span class="text-slate-600">→</span>
            <span class="flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-emerald-400/10 text-emerald-300 border border-emerald-400/25">✓ Confirmada</span>
            <span class="text-slate-600">→</span>
            <span class="flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-[#00d4ff]/10 text-[#00d4ff] border border-[#00d4ff]/25">✂ En Sillón</span>
            <span class="text-slate-600">→</span>
            <span class="flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-slate-500/10 text-slate-300 border border-slate-500/25">✅ Completada</span>
            <span class="text-slate-600 mx-1">|</span>
            <span class="flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-rose-400/10 text-rose-300 border border-rose-400/25">✗ Rechazada</span>
            <span class="text-slate-600">→</span>
            <span class="text-xs text-slate-500 italic">Se puede reactivar como Pendiente</span>
        </div>
    </section>

@endsection
