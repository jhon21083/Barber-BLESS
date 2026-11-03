@extends('layouts.admin')

@section('title', 'Reservas | Barber Bless')
@section('page-title', 'Gestión de Reservas')
@section('page-subtitle', 'Control de citas, agenda de barberos y turnos en tiempo real')

@section('admin-content')
    {{-- Quick KPI metrics --}}
    <section class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4">
        <div class="card-dark p-5">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-xs tracking-wider text-slate-400 uppercase">Citas Hoy</p>
                    <p class="mt-2 text-3xl font-extrabold text-[#00d4ff] glow-cyan">{{ $stats['total_hoy'] }}</p>
                    <p class="mt-1 text-xs text-emerald-400">● 100% capacidad cubierta</p>
                </div>
                <span class="grid place-items-center w-11 h-11 rounded-xl bg-[#00d4ff]/15 text-2xl">📅</span>
            </div>
        </div>

        <div class="card-dark p-5">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-xs tracking-wider text-slate-400 uppercase">Por Confirmar</p>
                    <p class="mt-2 text-3xl font-extrabold text-amber-300">{{ $stats['pendientes'] }}</p>
                    <p class="mt-1 text-xs text-slate-400">Esperando anticipo o confirmación</p>
                </div>
                <span class="grid place-items-center w-11 h-11 rounded-xl bg-amber-400/15 text-2xl">⏳</span>
            </div>
        </div>

        <div class="card-dark p-5">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-xs tracking-wider text-slate-400 uppercase">En Sillón Ahora</p>
                    <p class="mt-2 text-3xl font-extrabold text-emerald-400">{{ $stats['en_proceso'] }}</p>
                    <p class="mt-1 text-xs text-emerald-400">Atención en curso</p>
                </div>
                <span class="grid place-items-center w-11 h-11 rounded-xl bg-emerald-400/15 text-2xl">✂</span>
            </div>
        </div>

        <div class="card-dark p-5">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-xs tracking-wider text-slate-400 uppercase">Completadas Hoy</p>
                    <p class="mt-2 text-3xl font-extrabold text-[#0096ff]">{{ $stats['completadas_hoy'] }}</p>
                    <p class="mt-1 text-xs text-slate-400">Servicios finalizados con éxito</p>
                </div>
                <span class="grid place-items-center w-11 h-11 rounded-xl bg-[#0096ff]/15 text-2xl">✅</span>
            </div>
        </div>
    </section>

    {{-- Filter bar and actions --}}
    <section class="card-dark p-4 flex flex-col sm:flex-row items-center justify-between gap-4">
        <div class="flex flex-wrap items-center gap-2 w-full sm:w-auto">
            <button class="px-3 py-1.5 rounded-lg text-xs font-semibold bg-[#00d4ff]/20 text-[#00d4ff] border border-[#00d4ff]/40">Todas ({{ count($reservas) }})</button>
            <button class="px-3 py-1.5 rounded-lg text-xs font-semibold bg-slate-800 text-slate-400 hover:text-white hover:bg-slate-700 transition-colors">Hoy</button>
            <button class="px-3 py-1.5 rounded-lg text-xs font-semibold bg-slate-800 text-slate-400 hover:text-white hover:bg-slate-700 transition-colors">Mañana</button>
            <button class="px-3 py-1.5 rounded-lg text-xs font-semibold bg-slate-800 text-slate-400 hover:text-white hover:bg-slate-700 transition-colors">Pendientes</button>
        </div>

        <div class="flex items-center gap-3 w-full sm:w-auto justify-end">
            <div class="relative w-full sm:w-64">
                <input type="text" placeholder="Buscar cliente, folio..." class="input-dark py-1.5 text-xs pl-8">
                <span class="absolute left-2.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs">🔍</span>
            </div>
            <button type="button" class="btn-cyan text-xs py-2 px-3.5 whitespace-nowrap">
                <span>+</span> Nueva Cita
            </button>
        </div>
    </section>

    {{-- Reservations table --}}
    <section class="card-dark p-5">
        <div class="overflow-x-auto -mx-1">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-left text-xs tracking-wider text-slate-400 uppercase border-b border-[#00d4ff]/15">
                        <th class="px-3 py-3 font-semibold">Folio / Cliente</th>
                        <th class="px-3 py-3 font-semibold">Servicio Solicitado</th>
                        <th class="px-3 py-3 font-semibold">Barbero</th>
                        <th class="px-3 py-3 font-semibold">Horario &amp; Duración</th>
                        <th class="px-3 py-3 font-semibold">Total</th>
                        <th class="px-3 py-3 font-semibold">Pago / QR</th>
                        <th class="px-3 py-3 font-semibold">Estado Cita</th>
                        <th class="px-3 py-3 font-semibold text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#00d4ff]/10">
                    @forelse ($reservas as $r)
                        <tr class="hover:bg-[#00d4ff]/5 transition-colors">
                            <td class="px-3 py-3.5">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-lg bg-gradient-to-br from-[#00d4ff]/20 to-[#0096ff]/20 border border-[#00d4ff]/30 text-[#00d4ff] font-bold grid place-items-center text-sm">
                                        {{ strtoupper(substr($r['cliente'], 0, 1)) }}
                                    </div>
                                    <div>
                                        <p class="font-semibold text-white leading-tight">{{ $r['cliente'] }}</p>
                                        <p class="text-xs text-slate-400 font-mono">{{ $r['id'] }} • {{ $r['telefono'] }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-3 py-3.5">
                                <span class="font-medium text-slate-200">{{ $r['servicio'] }}</span>
                            </td>
                            <td class="px-3 py-3.5">
                                <div class="flex items-center gap-2">
                                    <span class="w-2 h-2 rounded-full bg-[#00d4ff]"></span>
                                    <span class="text-slate-300 font-medium">{{ $r['barbero'] }}</span>
                                </div>
                            </td>
                            <td class="px-3 py-3.5">
                                <p class="text-white font-medium text-xs">{{ $r['fecha'] }}</p>
                                <p class="text-[11px] text-slate-400">⏱ {{ $r['duracion'] }}</p>
                            </td>
                            <td class="px-3 py-3.5 font-bold text-[#00d4ff]">
                                {{ $r['precio'] }}
                            </td>
                            <td class="px-3 py-3.5">
                                @if(($r['metodo_pago'] ?? '') === 'qr')
                                    <div class="flex flex-col gap-1 items-start">
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-[10px] font-bold bg-[#00d4ff]/15 text-[#00d4ff] border border-[#00d4ff]/30">
                                            📱 QR Simple
                                        </span>
                                        <span class="text-[11px] {{ str_contains($r['estado_pago'] ?? '', 'Pagado') ? 'text-emerald-400 font-semibold' : 'text-amber-300 font-semibold' }}">
                                            {{ $r['estado_pago'] ?? 'Pendiente' }}
                                        </span>
                                        @if(!empty($r['comprobante']))
                                            <span class="text-[10px] text-slate-400 font-mono">Ref: {{ $r['comprobante'] }}</span>
                                        @endif
                                    </div>
                                @else
                                    <div class="flex flex-col gap-1 items-start">
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-[10px] font-bold bg-slate-800 text-slate-300 border border-slate-700">
                                            💵 En Local
                                        </span>
                                        <span class="text-[11px] text-slate-400">Pago presencial</span>
                                    </div>
                                @endif
                            </td>
                            <td class="px-3 py-3.5">
                                @php
                                    $estadoClass = match ($r['estado']) {
                                        'Confirmada' => 'text-emerald-300 border-emerald-400/40 bg-emerald-400/10',
                                        'En proceso' => 'text-[#00d4ff] border-[#00d4ff]/40 bg-[#00d4ff]/10',
                                        'Pendiente'  => 'text-amber-300 border-amber-400/40 bg-amber-400/10',
                                        'Completada' => 'text-slate-300 border-slate-500/40 bg-slate-500/10',
                                        default      => 'text-slate-300 border-slate-500/40 bg-slate-500/10',
                                    };
                                @endphp
                                <span class="badge {{ $estadoClass }}">{{ $r['estado'] }}</span>
                            </td>
                            <td class="px-3 py-3.5 text-right">
                                @if(isset($r['db_id']))
                                    <div class="inline-flex items-center gap-1.5">
                                        @if($r['estado'] !== 'Completada')
                                            <form method="POST" action="{{ route('admin.reservas.status', $r['db_id']) }}" class="inline">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="estado" value="Completada">
                                                <input type="hidden" name="estado_pago" value="Pagado">
                                                <button type="submit" title="Marcar como Completada y Pagada" class="px-2 py-1 rounded bg-emerald-500/20 text-emerald-300 hover:bg-emerald-500/30 text-xs font-semibold transition-colors">
                                                    ✓ Completar
                                                </button>
                                            </form>
                                        @else
                                            <span class="text-xs text-emerald-400 font-semibold">Listo</span>
                                        @endif
                                    </div>
                                @else
                                    <span class="text-xs text-slate-500">—</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-8 text-slate-400 text-sm">
                                No se encontraron reservas registradas.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
@endsection
