@extends('layouts.admin')

@section('title', 'Reportes | Barber Bless')
@section('page-title', 'Reportes y Métricas Financieras')
@section('page-subtitle', 'Facturación, rendimiento de barberos y métodos de cobro')

@section('admin-content')

    {{-- ── KPI cards ──────────────────────────────────────────────────────── --}}
    <section class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4">

        {{-- Ingresos del mes --}}
        <div class="card-dark p-5">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-xs tracking-wider text-slate-400 uppercase">Ingresos del Mes</p>
                    <p class="mt-2 text-3xl font-extrabold {{ $metrics['ingresos_mes'] === 'Bs. 0' ? 'text-slate-500' : 'text-[#00d4ff] glow-cyan' }}">
                        {{ $metrics['ingresos_mes'] }}
                    </p>
                    <p class="mt-1 text-xs {{ str_starts_with($metrics['crecimiento'], '+') ? 'text-emerald-400' : ($metrics['crecimiento'] === '—' ? 'text-slate-500' : 'text-rose-400') }}">
                        {{ $metrics['crecimiento'] === '—' ? 'Sin mes anterior aún' : $metrics['crecimiento'].' vs mes anterior' }}
                    </p>
                </div>
                <span class="grid place-items-center w-11 h-11 rounded-xl bg-[#00d4ff]/15 text-2xl">💰</span>
            </div>
        </div>

        {{-- Ticket promedio --}}
        <div class="card-dark p-5">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-xs tracking-wider text-slate-400 uppercase">Ticket Promedio</p>
                    <p class="mt-2 text-3xl font-extrabold {{ $metrics['ticket_promedio'] === 'Bs. 0' ? 'text-slate-500' : 'text-white' }}">
                        {{ $metrics['ticket_promedio'] }}
                    </p>
                    <p class="mt-1 text-xs text-slate-400">
                        {{ $metrics['citas_completadas'] === 0 ? 'Sin servicios aún' : 'Por servicio completado' }}
                    </p>
                </div>
                <span class="grid place-items-center w-11 h-11 rounded-xl bg-slate-800 text-2xl">🏷</span>
            </div>
        </div>

        {{-- Citas finalizadas --}}
        <div class="card-dark p-5">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-xs tracking-wider text-slate-400 uppercase">Citas Finalizadas</p>
                    <p class="mt-2 text-3xl font-extrabold {{ $metrics['citas_completadas'] === 0 ? 'text-slate-500' : 'text-emerald-400' }}">
                        {{ $metrics['citas_completadas'] }}
                    </p>
                    <p class="mt-1 text-xs text-slate-400">
                        {{ $metrics['citas_completadas'] === 0 ? 'Sin registros aún' : 'Reservas completadas' }}
                    </p>
                </div>
                <span class="grid place-items-center w-11 h-11 rounded-xl bg-emerald-400/15 text-2xl">✂</span>
            </div>
        </div>

        {{-- Ocupación sillón --}}
        <div class="card-dark p-5">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-xs tracking-wider text-slate-400 uppercase">Ocupación de Sillón</p>
                    <p class="mt-2 text-3xl font-extrabold {{ $metrics['ocupacion_sillon'] === '0%' ? 'text-slate-500' : 'text-[#0096ff]' }}">
                        {{ $metrics['ocupacion_sillon'] }}
                    </p>
                    <p class="mt-1 text-xs text-slate-400">
                        {{ $metrics['ocupacion_sillon'] === '0%' ? 'Sin reservas aún' : 'Completadas vs total reservas' }}
                    </p>
                </div>
                <span class="grid place-items-center w-11 h-11 rounded-xl bg-[#0096ff]/15 text-2xl">📊</span>
            </div>
        </div>
    </section>

    {{-- ── Gráfico + Métodos de pago ───────────────────────────────────────── --}}
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

        {{-- Evolución mensual --}}
        <section class="xl:col-span-2 card-dark p-6">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h2 class="text-lg font-bold text-white">Evolución de Ingresos Mensuales</h2>
                    <p class="text-xs text-slate-400">Últimos 6 meses · solo reservas completadas</p>
                </div>
                <span class="badge">BOB (Bs.)</span>
            </div>

            @php $maxMonto = max(array_column($ingresosMensuales, 'monto')); @endphp

            @if($maxMonto === 0)
                {{-- Sin datos --}}
                <div class="flex flex-col items-center justify-center h-40 gap-2 mb-4">
                    <span class="text-4xl opacity-20">📈</span>
                    <p class="text-slate-500 text-sm">El gráfico se formará con las primeras reservas completadas</p>
                </div>
                <div class="flex items-end justify-between gap-4 h-20 px-2">
                    @foreach ($ingresosMensuales as $item)
                        <div class="flex-1 flex flex-col items-center gap-2 justify-end">
                            <div class="w-full rounded-t-lg bg-[#00d4ff]/10 border border-dashed border-[#00d4ff]/20" style="height:40px"></div>
                            <span class="text-xs text-slate-600">{{ $item['mes'] }}</span>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="flex items-end justify-between gap-4 h-56 pt-4 px-2">
                    @foreach ($ingresosMensuales as $item)
                        <div class="flex-1 flex flex-col items-center gap-2 h-full justify-end group">
                            @if($item['monto'] > 0)
                                <span class="text-xs font-bold text-[#00d4ff] opacity-80 group-hover:opacity-100 transition-opacity">
                                    Bs. {{ $item['monto'] >= 1000 ? number_format($item['monto']/1000,1).'k' : $item['monto'] }}
                                </span>
                                <div class="w-full rounded-t-lg bg-gradient-to-t from-[#0096ff] to-[#00d4ff] group-hover:brightness-125 shadow-[0_0_12px_rgba(0,212,255,0.3)] transition-all duration-300"
                                     style="height: {{ round(($item['monto'] / $maxMonto) * 100) }}%"></div>
                            @else
                                <span class="text-xs font-bold text-slate-600">Bs. 0</span>
                                <div class="w-full rounded-t-lg bg-[#00d4ff]/10 border border-dashed border-[#00d4ff]/20" style="height:4px"></div>
                            @endif
                            <span class="text-xs text-slate-300 font-medium">{{ $item['mes'] }}</span>
                        </div>
                    @endforeach
                </div>
            @endif
        </section>

        {{-- Métodos de pago --}}
        <section class="card-dark p-6">
            <h2 class="text-lg font-bold text-white mb-1">Métodos de Pago</h2>
            <p class="text-xs text-slate-400 mb-6">Distribución de ingresos por canal</p>

            @if(count($metodosPago) === 0)
                <div class="flex flex-col items-center justify-center py-10 gap-3 text-center">
                    <span class="text-4xl opacity-20">💳</span>
                    <p class="text-slate-500 text-sm">Aparecerá aquí cuando haya<br>reservas completadas registradas</p>
                </div>
            @else
                <div class="space-y-5">
                    @foreach ($metodosPago as $m)
                        <div>
                            <div class="flex justify-between text-xs mb-1.5">
                                <span class="font-semibold text-slate-200">{{ $m['metodo'] }}</span>
                                <span class="text-[#00d4ff] font-bold">{{ $m['monto'] }} ({{ $m['porcentaje'] }}%)</span>
                            </div>
                            <div class="w-full h-2 rounded-full bg-slate-800 overflow-hidden">
                                <div class="h-full rounded-full bg-gradient-to-r from-[#0096ff] to-[#00d4ff]"
                                     style="width: {{ $m['porcentaje'] }}%"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="mt-6 p-4 rounded-xl bg-slate-800/60 border border-[#00d4ff]/15">
                    <p class="text-xs text-slate-400">
                        💡 <strong class="text-slate-200">Dato real:</strong>
                        Basado en {{ $metrics['citas_completadas'] }} reserva(s) completada(s) registradas en el sistema.
                    </p>
                </div>
            @endif
        </section>
    </div>

    {{-- ── Rendimiento por barbero ────────────────────────────────────────── --}}
    <section class="card-dark p-6">
        <h2 class="text-lg font-bold text-white mb-1">Rendimiento por Barbero</h2>
        <p class="text-xs text-slate-400 mb-5">Aporte a la facturación este mes · solo reservas completadas</p>

        @if(count($rendimientoBarberos) === 0)
            <div class="flex flex-col items-center justify-center py-14 gap-3 text-center">
                <span class="text-5xl opacity-20">✂</span>
                <p class="text-slate-400 font-semibold">Sin datos de barberos aún</p>
                <p class="text-slate-500 text-xs">
                    Cuando el primer cliente complete una reserva,<br>
                    verás aquí el rendimiento por barbero.
                </p>
            </div>
        @else
            <div class="overflow-x-auto -mx-1">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="text-left text-xs tracking-wider text-slate-400 uppercase border-b border-[#00d4ff]/15">
                            <th class="px-3 py-3 font-semibold">Barbero</th>
                            <th class="px-3 py-3 font-semibold">Citas Realizadas</th>
                            <th class="px-3 py-3 font-semibold">Ingresos Generados</th>
                            <th class="px-3 py-3 font-semibold">% Aporte</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#00d4ff]/10">
                        @foreach ($rendimientoBarberos as $rb)
                            <tr class="hover:bg-[#00d4ff]/5 transition-colors">
                                <td class="px-3 py-3.5 font-semibold text-white">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-[#00d4ff]/20 to-[#0096ff]/20 text-[#00d4ff] font-bold grid place-items-center text-xs shrink-0">
                                            {{ strtoupper(substr($rb['nombre'], 0, 1)) }}
                                        </div>
                                        {{ $rb['nombre'] }}
                                    </div>
                                </td>
                                <td class="px-3 py-3.5 text-slate-300">
                                    <span class="font-bold text-white">{{ $rb['citas'] }}</span> servicio(s)
                                </td>
                                <td class="px-3 py-3.5 font-bold text-[#00d4ff]">{{ $rb['ingresos'] }}</td>
                                <td class="px-3 py-3.5">
                                    <div class="flex items-center gap-3">
                                        <div class="w-28 h-2 rounded-full bg-slate-800 overflow-hidden">
                                            <div class="h-full rounded-full bg-[#00d4ff]"
                                                 style="width: {{ $rb['porcentaje'] }}%"></div>
                                        </div>
                                        <span class="text-xs font-semibold text-slate-300">{{ $rb['porcentaje'] }}%</span>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </section>

@endsection
