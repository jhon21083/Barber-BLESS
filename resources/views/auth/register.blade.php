@extends('layouts.app')

@section('title', 'Crear cuenta | Barber Bless')

@section('content')
    <div class="relative min-h-screen flex items-center justify-center px-4 py-12 overflow-hidden">
        {{-- Background decoration --}}
        <div class="absolute inset-0 pointer-events-none bg-[radial-gradient(circle_at_15%_20%,rgba(0,150,255,0.16),transparent_55%),radial-gradient(circle_at_85%_85%,rgba(0,212,255,0.14),transparent_50%)]"></div>

        {{-- Striped pole decoration (left) --}}
        <div class="absolute -left-10 top-1/2 -translate-y-1/2 w-6 h-72 rounded-full opacity-20 hidden md:block" style="background: repeating-linear-gradient(45deg, #00d4ff 0 12px, #ffffff 12px 24px, #0096ff 24px 36px);"></div>
        {{-- Striped pole decoration (right) --}}
        <div class="absolute -right-10 top-1/2 -translate-y-1/2 w-6 h-72 rounded-full opacity-20 hidden md:block" style="background: repeating-linear-gradient(135deg, #00d4ff 0 12px, #ffffff 12px 24px, #0096ff 24px 36px);"></div>

        <div class="relative w-full max-w-md">
            {{-- Logo --}}
            <div class="flex flex-col items-center mb-8">
                <img src="/images/logo.png" alt="Barber Bless" class="w-16 h-16 rounded-2xl object-cover shadow-[0_0_24px_rgba(0,212,255,0.5)]">
                <span class="mt-4 font-extrabold tracking-wide text-white text-2xl">BARBER BLESS</span>
                <span class="text-[10px] tracking-[0.3em] text-[#00d4ff]">ESTILO QUE HABLA POR TI</span>
            </div>

            {{-- Register card --}}
            <div class="card-dark p-7 sm:p-8 border-[#00d4ff]/30">
                <h1 class="text-2xl font-bold text-white text-center">Crear Cuenta</h1>
                <p class="mt-1 text-sm text-slate-400 text-center">Completa los datos para registrarte como administrador</p>

                <form method="POST" action="{{ route('register') }}" class="mt-6 space-y-4">
                    @csrf

                    {{-- Name --}}
                    <div>
                        <label for="name" class="block text-xs tracking-wider text-slate-400 mb-2">NOMBRE COMPLETO</label>
                        <input
                            id="name"
                            type="text"
                            name="name"
                            value="{{ old('name') }}"
                            class="input-dark"
                            placeholder="Ej. Juan Pérez"
                            required
                            autocomplete="name"
                            autofocus
                        >
                        @error('name')
                            <p class="text-rose-400 text-sm mt-1.5">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Email --}}
                    <div>
                        <label for="email" class="block text-xs tracking-wider text-slate-400 mb-2">CORREO ELECTRÓNICO</label>
                        <input
                            id="email"
                            type="email"
                            name="email"
                            value="{{ old('email') }}"
                            class="input-dark"
                            placeholder="correo@ejemplo.com"
                            required
                            autocomplete="email"
                        >
                        @error('email')
                            <p class="text-rose-400 text-sm mt-1.5">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Password --}}
                    <div>
                        <label for="password" class="block text-xs tracking-wider text-slate-400 mb-2">CONTRASEÑA</label>
                        <div class="relative">
                            <input
                                id="password"
                                type="password"
                                name="password"
                                class="input-dark pr-12"
                                placeholder="Mínimo 8 caracteres"
                                required
                                autocomplete="new-password"
                            >
                            <button
                                type="button"
                                id="toggle-password"
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-[#00d4ff] transition-colors text-lg"
                                aria-label="Mostrar/ocultar contraseña"
                            >👁</button>
                        </div>
                        @error('password')
                            <p class="text-rose-400 text-sm mt-1.5">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Password Confirmation --}}
                    <div>
                        <label for="password_confirmation" class="block text-xs tracking-wider text-slate-400 mb-2">CONFIRMAR CONTRASEÑA</label>
                        <div class="relative">
                            <input
                                id="password_confirmation"
                                type="password"
                                name="password_confirmation"
                                class="input-dark pr-12"
                                placeholder="Repite tu contraseña"
                                required
                                autocomplete="new-password"
                            >
                            <button
                                type="button"
                                id="toggle-password-confirm"
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-[#00d4ff] transition-colors text-lg"
                                aria-label="Mostrar/ocultar confirmación"
                            >👁</button>
                        </div>
                        @error('password_confirmation')
                            <p class="text-rose-400 text-sm mt-1.5">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Password strength indicator --}}
                    <div id="strength-container" class="hidden space-y-1.5">
                        <div class="flex gap-1.5">
                            <div id="s1" class="h-1 flex-1 rounded-full bg-slate-700 transition-all duration-300"></div>
                            <div id="s2" class="h-1 flex-1 rounded-full bg-slate-700 transition-all duration-300"></div>
                            <div id="s3" class="h-1 flex-1 rounded-full bg-slate-700 transition-all duration-300"></div>
                            <div id="s4" class="h-1 flex-1 rounded-full bg-slate-700 transition-all duration-300"></div>
                        </div>
                        <p id="strength-label" class="text-xs text-slate-500"></p>
                    </div>

                    <button type="submit" class="btn-cyan w-full mt-2">Crear cuenta</button>
                </form>
            </div>

            <p class="mt-6 text-center text-sm text-slate-500">
                ¿Ya tienes cuenta?
                <a href="{{ route('login') }}" class="text-[#00bcd4] hover:text-[#00d4ff] transition-colors font-medium">Inicia sesión</a>
            </p>
            <p class="mt-2 text-center text-sm text-slate-500">
                <a href="{{ route('home') }}" class="hover:text-[#00d4ff] transition-colors">← Volver al inicio</a>
            </p>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    // Toggle password visibility
    document.getElementById('toggle-password').addEventListener('click', function () {
        const input = document.getElementById('password');
        input.type = input.type === 'password' ? 'text' : 'password';
        this.textContent = input.type === 'password' ? '👁' : '🙈';
    });

    document.getElementById('toggle-password-confirm').addEventListener('click', function () {
        const input = document.getElementById('password_confirmation');
        input.type = input.type === 'password' ? 'text' : 'password';
        this.textContent = input.type === 'password' ? '👁' : '🙈';
    });

    // Password strength meter
    const passwordInput = document.getElementById('password');
    const strengthContainer = document.getElementById('strength-container');
    const strengthLabel = document.getElementById('strength-label');
    const bars = [document.getElementById('s1'), document.getElementById('s2'), document.getElementById('s3'), document.getElementById('s4')];

    const levels = [
        { color: 'bg-rose-500',   label: 'Muy débil'  },
        { color: 'bg-orange-400', label: 'Débil'       },
        { color: 'bg-yellow-400', label: 'Moderada'    },
        { color: 'bg-emerald-400',label: 'Fuerte'      },
    ];

    function getStrength(val) {
        let score = 0;
        if (val.length >= 8)  score++;
        if (/[A-Z]/.test(val)) score++;
        if (/[0-9]/.test(val)) score++;
        if (/[^A-Za-z0-9]/.test(val)) score++;
        return score;
    }

    passwordInput.addEventListener('input', function () {
        const val = this.value;
        if (!val) { strengthContainer.classList.add('hidden'); return; }
        strengthContainer.classList.remove('hidden');

        const score = getStrength(val);
        bars.forEach((bar, i) => {
            bar.className = 'h-1 flex-1 rounded-full transition-all duration-300 ' +
                (i < score ? levels[score - 1].color : 'bg-slate-700');
        });
        strengthLabel.textContent = 'Seguridad: ' + levels[score - 1].label;
        strengthLabel.className = 'text-xs ' + (score <= 1 ? 'text-rose-400' : score === 2 ? 'text-orange-400' : score === 3 ? 'text-yellow-400' : 'text-emerald-400');
    });
</script>
@endpush
