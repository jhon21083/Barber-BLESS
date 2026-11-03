@extends('layouts.app')

@section('content')
    <div class="flex min-h-screen">
        {{-- Sidebar --}}
        <aside class="w-16 lg:w-64 shrink-0 bg-[#080d19] border-r border-[#00d4ff]/15 flex flex-col">
            <div class="flex items-center gap-3 px-4 py-5 border-b border-[#00d4ff]/15">
                <img src="/images/logo.png" alt="Barber Bless" class="w-10 h-10 rounded-xl object-cover shadow-[0_0_18px_rgba(0,212,255,0.45)] shrink-0">
                <a href="{{ route('dashboard') }}" class="hidden lg:block leading-tight">
                    <span class="block font-extrabold tracking-wide text-white hover:text-[#00d4ff] transition-colors">BARBER BLESS</span>
                    <span class="block text-[10px] tracking-[0.2em] text-[#00d4ff]">ADMIN</span>
                </a>
            </div>

            <nav class="flex-1 px-2 lg:px-3 py-5 space-y-1 text-slate-300">
                @php
                    $nav = [
                        ['Dashboard',     route('dashboard'),           request()->routeIs('dashboard')],
                        ['Reservas',      route('admin.reservas'),      request()->routeIs('admin.reservas*')],
                        ['Clientes',      route('admin.clientes'),      request()->routeIs('admin.clientes*')],
                        ['Barberos',      route('admin.barberos'),      request()->routeIs('admin.barberos*')],
                        ['Servicios',     route('admin.servicios'),     request()->routeIs('admin.servicios*')],
                        ['Reportes',      route('admin.reportes'),      request()->routeIs('admin.reportes*')],
                        ['Mi Perfil',     route('admin.profile'),       request()->routeIs('admin.profile*')],
                        ['Configuración', route('admin.configuracion'), request()->routeIs('admin.configuracion*')],
                    ];
                @endphp
                @foreach ($nav as [$label, $href, $active])
                    <a href="{{ $href }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm transition-colors {{ $active ? 'bg-[#00d4ff]/15 text-[#00d4ff] font-semibold' : 'hover:bg-[#00d4ff]/10 hover:text-white' }}">
                        <span class="w-1.5 h-1.5 rounded-full {{ $active ? 'bg-[#00d4ff] shadow-[0_0_6px_rgba(0,212,255,0.7)]' : 'bg-slate-600' }}"></span>
                        <span class="hidden lg:inline">{{ $label }}</span>
                    </a>
                @endforeach
            </nav>

            <div class="p-2 lg:p-3 border-t border-[#00d4ff]/15">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm text-slate-300 hover:bg-rose-500/15 hover:text-rose-300 transition-colors">
                        <span class="w-1.5 h-1.5 rounded-full bg-slate-600"></span>
                        <span class="hidden lg:inline">Cerrar Sesión</span>
                    </button>
                </form>
            </div>
        </aside>

        {{-- Main column --}}
        <div class="flex-1 flex flex-col min-w-0">
            {{-- Header --}}
            <header class="bg-[#0a0f1e]/90 backdrop-blur border-b border-[#00d4ff]/15 px-4 sm:px-6 py-4 flex items-center justify-between gap-4">
                <div>
                    <h1 class="text-lg font-bold text-white">@yield('page-title', 'Panel de Administración')</h1>
                    <p class="text-xs text-slate-400">@yield('page-subtitle', 'Bienvenido de vuelta al Club VIP')</p>
                </div>
                <div class="flex items-center gap-3">
                    <span class="text-right hidden sm:block">
                        <span class="block text-sm font-semibold text-white">{{ auth()->user()->name }}</span>
                        <span class="block text-xs text-slate-400">{{ auth()->user()->email }}</span>
                    </span>
                    <a href="{{ route('admin.profile') }}" class="grid place-items-center w-10 h-10 rounded-full bg-gradient-to-br from-[#00d4ff] to-[#0096ff] text-[#04121f] font-bold hover:shadow-[0_0_18px_rgba(0,212,255,0.5)] transition-all" title="Mi Perfil">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </a>
                </div>
            </header>

            {{-- Main content --}}
            <main class="flex-1 p-4 sm:p-6 space-y-6 bg-[#0a0f1e]">
                @yield('admin-content')
            </main>
        </div>
    </div>
@endsection
