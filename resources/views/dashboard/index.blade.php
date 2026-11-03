@extends('layouts.admin')

@section('title', 'Dashboard | Barber Bless')
@section('page-title', 'Panel de Administración')
@section('page-subtitle', 'Bienvenido de vuelta al Club VIP')

@section('admin-content')

    {{-- ── Stat cards ──────────────────────────────────────────────────── --}}
    <section class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4">
        @php
            $cards = [
                ['TOTAL RESERVAS HOY',    $stats['reservas_hoy'],         '📅', $stats['reservas_hoy'] === 0 ? 'Sin reservas aún'   : 'Registradas hoy'],
                ['CLIENTES ÚNICOS',       $stats['clientes_activos'],      '👥', $stats['clientes_activos'] === 0 ? 'Sin clientes aún' : 'Teléfonos distintos'],
                ['INGRESOS DEL MES',      $stats['ingresos_mes'],          '💵', 'Reservas completadas'],
                ['BARBEROS EN RESERVAS',  $stats['barberos_disponibles'],  '✂',  $stats['barberos_disponibles'] === 0 ? 'Sin registros aún' : 'Barberos activos'],
            ];
        @endphp

        @foreach ($cards as [$label, $value, $icon, $note])
            <div class="card-dark p-5">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-xs tracking-wider text-slate-400 uppercase">{{ $label }}</p>
                        <p class="mt-2 text-3xl font-extrabold {{ $value == 0 || $value === 'Bs. 0' ? 'text-slate-500' : 'text-[#00d4ff] glow-cyan' }}">
                            {{ $value }}
                        </p>
                        <p class="mt-1 text-xs text-slate-500">{{ $note }}</p>
                    </div>
                    <span class="grid place-items-center w-11 h-11 rounded-xl bg-[#00d4ff]/15 text-2xl">{{ $icon }}</span>
                </div>
            </div>
        @endforeach
    </section>

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

        {{-- ── Tabla de últimas reservas ────────────────────────────────── --}}
        <section class="xl:col-span-2 card-dark p-5">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-bold text-white">Últimas Reservas</h2>
                <a href="{{ route('admin.reservas') }}" class="badge hover:bg-[#00d4ff]/20 transition-colors">Ver todas →</a>
            </div>

            @if(count($reservas) === 0)
                {{-- Estado vacío --}}
                <div class="flex flex-col items-center justify-center py-16 text-center gap-3">
                    <span class="text-5xl opacity-30">📋</span>
                    <p class="text-slate-400 font-semibold">Aún no hay reservas registradas</p>
                    <p class="text-slate-500 text-xs">Las reservas que hagan los clientes aparecerán aquí automáticamente.</p>
                </div>
            @else
                <div class="overflow-x-auto -mx-1">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="text-left text-xs tracking-wider text-slate-400 uppercase border-b border-[#00d4ff]/15">
                                <th class="px-2 py-3 font-semibold">Cliente</th>
                                <th class="px-2 py-3 font-semibold">Servicio</th>
                                <th class="px-2 py-3 font-semibold">Barbero</th>
                                <th class="px-2 py-3 font-semibold">Fecha</th>
                                <th class="px-2 py-3 font-semibold">Estado</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[#00d4ff]/10">
                            @foreach ($reservas as $r)
                                <tr class="hover:bg-[#00d4ff]/5 transition-colors">
                                    <td class="px-2 py-3 text-white font-medium">{{ $r['cliente'] }}</td>
                                    <td class="px-2 py-3 text-slate-300">{{ $r['servicio'] }}</td>
                                    <td class="px-2 py-3 text-slate-300">{{ $r['barbero'] }}</td>
                                    <td class="px-2 py-3 text-slate-400 whitespace-nowrap">{{ $r['fecha'] }}</td>
                                    <td class="px-2 py-3">
                                        @php
                                            $estadoClass = match ($r['estado']) {
                                                'Confirmada'  => 'text-emerald-300 border-emerald-400/40 bg-emerald-400/10',
                                                'En proceso'  => 'text-[#00d4ff] border-[#00d4ff]/40 bg-[#00d4ff]/10',
                                                'Pendiente'   => 'text-amber-300 border-amber-400/40 bg-amber-400/10',
                                                'Completada'  => 'text-slate-300 border-slate-500/40 bg-slate-500/10',
                                                'Cancelada'   => 'text-rose-300 border-rose-400/40 bg-rose-400/10',
                                                default       => 'text-slate-300 border-slate-500/40 bg-slate-500/10',
                                            };
                                        @endphp
                                        <span class="badge {{ $estadoClass }}">{{ $r['estado'] }}</span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </section>

        {{-- ── Gráfico de barras (últimos 7 días reales) ───────────────── --}}
        <section class="card-dark p-5">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-bold text-white">Reservas por Día</h2>
                <span class="badge">7 días</span>
            </div>

            @php
                $maxVal = max(array_column($chart, 1));
            @endphp

            @if($maxVal === 0)
                {{-- Sin datos aún --}}
                <div class="flex flex-col items-center justify-center h-44 gap-3 text-center">
                    <span class="text-4xl opacity-30">📊</span>
                    <p class="text-slate-500 text-xs">Las barras aparecerán aquí<br>cuando haya reservas registradas.</p>
                </div>
                {{-- Ejes vacíos con etiquetas de día --}}
                <div class="flex items-end justify-between gap-3 mt-4">
                    @foreach ($chart as [$dia, $valor])
                        <div class="flex-1 flex flex-col items-center gap-1">
                            <div class="w-full rounded-t-md bg-[#00d4ff]/10 border border-dashed border-[#00d4ff]/20" style="height: 80px"></div>
                            <span class="text-xs text-slate-600">{{ $dia }}</span>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="flex items-end justify-between gap-3 h-44 pt-2">
                    @foreach ($chart as [$dia, $valor])
                        <div class="flex-1 flex flex-col items-center gap-2 h-full justify-end">
                            @if($valor > 0)
                                <span class="text-xs font-semibold text-[#00d4ff]">{{ $valor }}</span>
                                <div class="w-full rounded-t-md bg-gradient-to-t from-[#0096ff] to-[#00d4ff] shadow-[0_0_10px_rgba(0,212,255,0.35)] transition-all"
                                     style="height: {{ round(($valor / $maxVal) * 100) }}%"></div>
                            @else
                                <span class="text-xs font-semibold text-slate-600">0</span>
                                <div class="w-full rounded-t-md bg-[#00d4ff]/10 border border-dashed border-[#00d4ff]/20"
                                     style="height: 4px"></div>
                            @endif
                            <span class="text-xs text-slate-400">{{ $dia }}</span>
                        </div>
                    @endforeach
                </div>
            @endif
        </section>

    </div>

    {{-- ── Resumen rápido ────────────────────────────────────────────────── --}}
    <section class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        @php
            $pendientesCount  = \App\Models\Reserva::whereIn('estado', ['Pendiente','Confirmada'])->count();
            $enProcesoCount   = \App\Models\Reserva::where('estado', 'En proceso')->count();
            $completadasCount = \App\Models\Reserva::where('estado', 'Completada')->count();
        @endphp
        <div class="card-dark p-4 flex items-center gap-4">
            <span class="grid place-items-center w-12 h-12 rounded-xl bg-amber-400/15 text-2xl shrink-0">⏳</span>
            <div>
                <p class="text-xs text-slate-400 uppercase tracking-wider">Pendientes</p>
                <p class="text-2xl font-extrabold {{ $pendientesCount === 0 ? 'text-slate-500' : 'text-amber-300' }}">{{ $pendientesCount }}</p>
            </div>
        </div>
        <div class="card-dark p-4 flex items-center gap-4">
            <span class="grid place-items-center w-12 h-12 rounded-xl bg-[#00d4ff]/15 text-2xl shrink-0">⚡</span>
            <div>
                <p class="text-xs text-slate-400 uppercase tracking-wider">En Proceso</p>
                <p class="text-2xl font-extrabold {{ $enProcesoCount === 0 ? 'text-slate-500' : 'text-[#00d4ff]' }}">{{ $enProcesoCount }}</p>
            </div>
        </div>
        <div class="card-dark p-4 flex items-center gap-4">
            <span class="grid place-items-center w-12 h-12 rounded-xl bg-emerald-400/15 text-2xl shrink-0">✅</span>
            <div>
                <p class="text-xs text-slate-400 uppercase tracking-wider">Completadas</p>
                <p class="text-2xl font-extrabold {{ $completadasCount === 0 ? 'text-slate-500' : 'text-emerald-300' }}">{{ $completadasCount }}</p>
            </div>
        </div>
    </section>

@endsection
