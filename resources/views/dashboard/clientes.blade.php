@extends('layouts.admin')

@section('title', 'Clientes | Barber Bless')
@section('page-title', 'Directorio de Clientes')
@section('page-subtitle', 'Base de datos de clientes, membresías VIP y frecuencia de visitas')

@section('admin-content')
    {{-- KPI stats --}}
    <section class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4">
        <div class="card-dark p-5">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-xs tracking-wider text-slate-400 uppercase">Total Registrados</p>
                    <p class="mt-2 text-3xl font-extrabold text-[#00d4ff] glow-cyan">{{ $stats['total_clientes'] }}</p>
                    <p class="mt-1 text-xs text-emerald-400">+18% en el último trimestre</p>
                </div>
                <span class="grid place-items-center w-11 h-11 rounded-xl bg-[#00d4ff]/15 text-2xl">👥</span>
            </div>
        </div>

        <div class="card-dark p-5">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-xs tracking-wider text-slate-400 uppercase">Miembros VIP</p>
                    <p class="mt-2 text-3xl font-extrabold text-amber-300">{{ $stats['clientes_vip'] }}</p>
                    <p class="mt-1 text-xs text-slate-400">Beneficios y cortes preferenciales</p>
                </div>
                <span class="grid place-items-center w-11 h-11 rounded-xl bg-amber-400/15 text-2xl">👑</span>
            </div>
        </div>

        <div class="card-dark p-5">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-xs tracking-wider text-slate-400 uppercase">Nuevos este mes</p>
                    <p class="mt-2 text-3xl font-extrabold text-emerald-400">{{ $stats['nuevos_mes'] }}</p>
                    <p class="mt-1 text-xs text-emerald-400">+5 esta semana</p>
                </div>
                <span class="grid place-items-center w-11 h-11 rounded-xl bg-emerald-400/15 text-2xl">✨</span>
            </div>
        </div>

        <div class="card-dark p-5">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-xs tracking-wider text-slate-400 uppercase">Frecuencia Promedio</p>
                    <p class="mt-2 text-3xl font-extrabold text-[#0096ff]">{{ $stats['frecuencia_promedio'] }}</p>
                    <p class="mt-1 text-xs text-slate-400">Retorno recurrente de clientes</p>
                </div>
                <span class="grid place-items-center w-11 h-11 rounded-xl bg-[#0096ff]/15 text-2xl">🔄</span>
            </div>
        </div>
    </section>

    {{-- Search and actions --}}
    <section class="card-dark p-4 flex flex-col sm:flex-row items-center justify-between gap-4">
        <div class="relative w-full sm:w-80">
            <input type="text" placeholder="Buscar por nombre, correo o celular..." class="input-dark py-2 text-xs pl-8">
            <span class="absolute left-2.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs">🔍</span>
        </div>

        <div class="flex items-center gap-3 w-full sm:w-auto justify-end">
            <button type="button" class="px-3 py-2 rounded-lg text-xs font-semibold bg-slate-800 text-slate-300 hover:text-white hover:bg-slate-700 transition-colors">
                Exportar CSV
            </button>
            <button type="button" class="btn-cyan text-xs py-2 px-3.5 whitespace-nowrap">
                <span>+</span> Registrar Cliente
            </button>
        </div>
    </section>

    {{-- Clients directory table --}}
    <section class="card-dark p-5">
        <div class="overflow-x-auto -mx-1">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-left text-xs tracking-wider text-slate-400 uppercase border-b border-[#00d4ff]/15">
                        <th class="px-3 py-3 font-semibold">Cliente</th>
                        <th class="px-3 py-3 font-semibold">Contacto</th>
                        <th class="px-3 py-3 font-semibold">Visitas</th>
                        <th class="px-3 py-3 font-semibold">Total Consumido</th>
                        <th class="px-3 py-3 font-semibold">Última Visita</th>
                        <th class="px-3 py-3 font-semibold">Barbero Preferido</th>
                        <th class="px-3 py-3 font-semibold text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#00d4ff]/10">
                    @foreach ($clientes as $c)
                        <tr class="hover:bg-[#00d4ff]/5 transition-colors">
                            <td class="px-3 py-3.5">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full {{ $c['es_vip'] ? 'bg-gradient-to-br from-amber-400 to-amber-600 text-black font-extrabold shadow-[0_0_12px_rgba(245,158,11,0.5)]' : 'bg-slate-800 text-[#00d4ff] font-bold border border-[#00d4ff]/20' }} grid place-items-center text-sm">
                                        {{ strtoupper(substr($c['nombre'], 0, 1)) }}
                                    </div>
                                    <div>
                                        <div class="flex items-center gap-2">
                                            <p class="font-semibold text-white leading-tight">{{ $c['nombre'] }}</p>
                                            @if($c['es_vip'])
                                                <span class="text-[10px] px-1.5 py-0.5 rounded font-bold bg-amber-400/20 text-amber-300 border border-amber-400/30">VIP</span>
                                            @endif
                                        </div>
                                        <p class="text-xs text-slate-500">{{ $c['id'] }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-3 py-3.5">
                                <p class="text-xs text-slate-300">{{ $c['telefono'] }}</p>
                                <p class="text-[11px] text-slate-500">{{ $c['email'] }}</p>
                            </td>
                            <td class="px-3 py-3.5">
                                <span class="font-bold text-white">{{ $c['visitas'] }}</span>
                                <span class="text-xs text-slate-400">citas</span>
                            </td>
                            <td class="px-3 py-3.5 font-bold text-[#00d4ff]">
                                {{ $c['total_gastado'] }}
                            </td>
                            <td class="px-3 py-3.5 text-xs text-slate-300">
                                {{ $c['ultima_visita'] }}
                            </td>
                            <td class="px-3 py-3.5">
                                <span class="text-xs text-slate-300 font-medium">✂ {{ $c['barbero_favorito'] }}</span>
                            </td>
                            <td class="px-3 py-3.5 text-right">
                                <div class="inline-flex items-center gap-1.5">
                                    <button title="Historial" class="p-1.5 rounded-lg bg-slate-800 text-slate-300 hover:text-[#00d4ff] hover:bg-slate-700 transition-colors text-xs">📋</button>
                                    <button title="Enviar WhatsApp" class="p-1.5 rounded-lg bg-slate-800 text-slate-300 hover:text-emerald-400 hover:bg-slate-700 transition-colors text-xs">💬</button>
                                    <button title="Editar" class="p-1.5 rounded-lg bg-slate-800 text-slate-300 hover:text-white hover:bg-slate-700 transition-colors text-xs">✏</button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </section>
@endsection
