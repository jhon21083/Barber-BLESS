@extends('layouts.admin')

@section('title', 'Mi Perfil | Barber Bless')
@section('page-title', 'Mi Perfil')
@section('page-subtitle', 'Administra tu información y seguridad')

@section('admin-content')
    {{-- Avatar / identity hero --}}
    <section class="card-dark p-6 flex flex-col sm:flex-row items-center sm:items-start gap-6">
        <div class="relative shrink-0">
            <div class="grid place-items-center w-24 h-24 rounded-2xl bg-gradient-to-br from-[#00d4ff] to-[#0096ff] text-[#04121f] text-4xl font-extrabold shadow-[0_0_30px_rgba(0,212,255,0.4)]">
                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
            </div>
            <span class="absolute -bottom-1 -right-1 w-5 h-5 rounded-full bg-emerald-400 border-2 border-[#080d19] shadow"></span>
        </div>
        <div class="text-center sm:text-left">
            <h2 class="text-2xl font-extrabold text-white">{{ auth()->user()->name }}</h2>
            <p class="text-[#00d4ff] text-sm mt-0.5">{{ auth()->user()->email }}</p>
            <span class="inline-block mt-3 px-3 py-1 rounded-full text-xs font-semibold tracking-wider bg-[#00d4ff]/15 text-[#00d4ff] border border-[#00d4ff]/30">
                ✂ ADMINISTRADOR
            </span>
            <p class="mt-3 text-xs text-slate-500">
                Cuenta creada el {{ auth()->user()->created_at->format('d/m/Y') }}
            </p>
        </div>
    </section>

    <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">
        {{-- Update info form --}}
        <section class="card-dark p-6">
            <h2 class="text-lg font-bold text-white mb-1">Información Personal</h2>
            <p class="text-xs text-slate-400 mb-5">Actualiza tu nombre y correo electrónico.</p>

            @if(session('success_info'))
                <div class="mb-4 flex items-center gap-2 px-4 py-3 rounded-lg bg-emerald-500/15 border border-emerald-500/30 text-emerald-300 text-sm">
                    <span>✔</span> {{ session('success_info') }}
                </div>
            @endif

            <form method="POST" action="{{ route('admin.profile.info') }}" class="space-y-4">
                @csrf
                @method('PATCH')

                <div>
                    <label for="name" class="block text-xs tracking-wider text-slate-400 mb-2">NOMBRE COMPLETO</label>
                    <input
                        id="name"
                        type="text"
                        name="name"
                        value="{{ old('name', auth()->user()->name) }}"
                        class="input-dark"
                        required
                    >
                    @error('name')
                        <p class="text-rose-400 text-sm mt-1.5">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="email" class="block text-xs tracking-wider text-slate-400 mb-2">CORREO ELECTRÓNICO</label>
                    <input
                        id="email"
                        type="email"
                        name="email"
                        value="{{ old('email', auth()->user()->email) }}"
                        class="input-dark"
                        required
                    >
                    @error('email')
                        <p class="text-rose-400 text-sm mt-1.5">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit" class="btn-cyan w-full">Guardar cambios</button>
            </form>
        </section>

        {{-- Change password form --}}
        <section class="card-dark p-6">
            <h2 class="text-lg font-bold text-white mb-1">Cambiar Contraseña</h2>
            <p class="text-xs text-slate-400 mb-5">Usa una contraseña fuerte con letras, números y símbolos.</p>

            @if(session('success_password'))
                <div class="mb-4 flex items-center gap-2 px-4 py-3 rounded-lg bg-emerald-500/15 border border-emerald-500/30 text-emerald-300 text-sm">
                    <span>✔</span> {{ session('success_password') }}
                </div>
            @endif

            <form method="POST" action="{{ route('admin.profile.password') }}" class="space-y-4" id="password-form">
                @csrf
                @method('PATCH')

                <div>
                    <label for="current_password" class="block text-xs tracking-wider text-slate-400 mb-2">CONTRASEÑA ACTUAL</label>
                    <div class="relative">
                        <input id="current_password" type="password" name="current_password" class="input-dark pr-12" placeholder="••••••••" required>
                        <button type="button" data-toggle="current_password" class="toggle-eye absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-[#00d4ff] transition-colors text-lg">👁</button>
                    </div>
                    @error('current_password')
                        <p class="text-rose-400 text-sm mt-1.5">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="new_password" class="block text-xs tracking-wider text-slate-400 mb-2">NUEVA CONTRASEÑA</label>
                    <div class="relative">
                        <input id="new_password" type="password" name="password" class="input-dark pr-12" placeholder="Mínimo 8 caracteres" required>
                        <button type="button" data-toggle="new_password" class="toggle-eye absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-[#00d4ff] transition-colors text-lg">👁</button>
                    </div>
                    {{-- strength bars --}}
                    <div id="p-strength" class="hidden mt-2 space-y-1">
                        <div class="flex gap-1">
                            <div id="ps1" class="h-1 flex-1 rounded-full bg-slate-700 transition-all duration-300"></div>
                            <div id="ps2" class="h-1 flex-1 rounded-full bg-slate-700 transition-all duration-300"></div>
                            <div id="ps3" class="h-1 flex-1 rounded-full bg-slate-700 transition-all duration-300"></div>
                            <div id="ps4" class="h-1 flex-1 rounded-full bg-slate-700 transition-all duration-300"></div>
                        </div>
                        <p id="ps-label" class="text-xs text-slate-500"></p>
                    </div>
                    @error('password')
                        <p class="text-rose-400 text-sm mt-1.5">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password_confirmation" class="block text-xs tracking-wider text-slate-400 mb-2">CONFIRMAR CONTRASEÑA</label>
                    <div class="relative">
                        <input id="password_confirmation" type="password" name="password_confirmation" class="input-dark pr-12" placeholder="Repite la nueva contraseña" required>
                        <button type="button" data-toggle="password_confirmation" class="toggle-eye absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-[#00d4ff] transition-colors text-lg">👁</button>
                    </div>
                </div>

                <button type="submit" class="btn-cyan w-full">Actualizar contraseña</button>
            </form>
        </section>
    </div>

    {{-- Security info card --}}
    <section class="card-dark p-6">
        <h2 class="text-lg font-bold text-white mb-4">Actividad de Seguridad</h2>
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div class="flex items-center gap-4 p-4 rounded-xl bg-emerald-500/10 border border-emerald-500/20">
                <span class="text-3xl">🔒</span>
                <div>
                    <p class="text-sm font-semibold text-emerald-300">Sesión Activa</p>
                    <p class="text-xs text-slate-400 mt-0.5">Conexión segura HTTPS</p>
                </div>
            </div>
            <div class="flex items-center gap-4 p-4 rounded-xl bg-[#00d4ff]/10 border border-[#00d4ff]/20">
                <span class="text-3xl">🛡</span>
                <div>
                    <p class="text-sm font-semibold text-[#00d4ff]">Protección Activa</p>
                    <p class="text-xs text-slate-400 mt-0.5">Límite de intentos de login</p>
                </div>
            </div>
            <div class="flex items-center gap-4 p-4 rounded-xl bg-amber-500/10 border border-amber-500/20">
                <span class="text-3xl">📅</span>
                <div>
                    <p class="text-sm font-semibold text-amber-300">Último acceso</p>
                    <p class="text-xs text-slate-400 mt-0.5">{{ now()->format('d/m/Y H:i') }}</p>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
<script>
    // Toggle password visibility
    document.querySelectorAll('.toggle-eye').forEach(btn => {
        btn.addEventListener('click', function () {
            const input = document.getElementById(this.dataset.toggle);
            input.type = input.type === 'password' ? 'text' : 'password';
            this.textContent = input.type === 'password' ? '👁' : '🙈';
        });
    });

    // Password strength meter
    const newPwd = document.getElementById('new_password');
    const pStrength = document.getElementById('p-strength');
    const psLabel = document.getElementById('ps-label');
    const psBars = [document.getElementById('ps1'), document.getElementById('ps2'), document.getElementById('ps3'), document.getElementById('ps4')];
    const levels = [
        { color: 'bg-rose-500',    label: 'Muy débil'  },
        { color: 'bg-orange-400',  label: 'Débil'      },
        { color: 'bg-yellow-400',  label: 'Moderada'   },
        { color: 'bg-emerald-400', label: 'Fuerte'     },
    ];

    function getStrength(v) {
        let s = 0;
        if (v.length >= 8) s++;
        if (/[A-Z]/.test(v)) s++;
        if (/[0-9]/.test(v)) s++;
        if (/[^A-Za-z0-9]/.test(v)) s++;
        return s;
    }

    if (newPwd) {
        newPwd.addEventListener('input', function () {
            if (!this.value) { pStrength.classList.add('hidden'); return; }
            pStrength.classList.remove('hidden');
            const score = getStrength(this.value);
            psBars.forEach((bar, i) => {
                bar.className = 'h-1 flex-1 rounded-full transition-all duration-300 ' + (i < score ? levels[score - 1].color : 'bg-slate-700');
            });
            psLabel.textContent = 'Seguridad: ' + levels[score - 1].label;
            psLabel.className = 'text-xs ' + (score <= 1 ? 'text-rose-400' : score === 2 ? 'text-orange-400' : score === 3 ? 'text-yellow-400' : 'text-emerald-400');
        });
    }
</script>
@endpush
