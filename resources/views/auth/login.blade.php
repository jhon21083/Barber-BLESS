@extends('layouts.app')

@section('title', 'Acceder | Barber Bless')

@section('content')
    <div class="relative min-h-screen flex items-center justify-center px-4 py-12 overflow-hidden">
        {{-- Barbershop background decoration --}}
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

            {{-- Login card --}}
            <div class="card-dark p-7 sm:p-8 border-[#00d4ff]/30">
                <h1 class="text-2xl font-bold text-white text-center">Acceder al Sistema</h1>
                <p class="mt-1 text-sm text-slate-400 text-center">Ingresa con tus credenciales de administrador</p>

                {{-- Rate-limit / lockout alert --}}
                @if ($errors->has('email') && str_contains($errors->first('email'), 'segundo'))
                    <div class="mt-4 flex items-center gap-3 px-4 py-3 rounded-lg bg-rose-500/15 border border-rose-500/30 text-rose-300 text-sm">
                        <span class="text-xl">🔒</span>
                        <span>{{ $errors->first('email') }}</span>
                    </div>
                @elseif ($errors->has('email'))
                    <div class="mt-4 flex items-center gap-3 px-4 py-3 rounded-lg bg-rose-500/10 border border-rose-500/25 text-rose-300 text-sm">
                        <span class="text-xl">⚠</span>
                        <span>{{ $errors->first('email') }}</span>
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}" class="mt-5 space-y-4">
                    @csrf

                    <div>
                        <label for="email" class="block text-xs tracking-wider text-slate-400 mb-2">EMAIL</label>
                        <input
                            id="email"
                            type="email"
                            name="email"
                            value="{{ old('email') }}"
                            class="input-dark"
                            placeholder="admin@barberbless.com"
                            required
                            autocomplete="email"
                            autofocus
                        >
                    </div>

                    <div>
                        <label for="password" class="block text-xs tracking-wider text-slate-400 mb-2">CONTRASEÑA</label>
                        <div class="relative">
                            <input
                                id="password"
                                type="password"
                                name="password"
                                class="input-dark pr-12"
                                placeholder="••••••••"
                                required
                                autocomplete="current-password"
                            >
                            <button
                                type="button"
                                id="toggle-pwd"
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-[#00d4ff] transition-colors text-lg"
                                aria-label="Mostrar/ocultar contraseña"
                            >👁</button>
                        </div>
                        @error('password')
                            <p class="text-rose-400 text-sm mt-1.5">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-center justify-between">
                        <label class="flex items-center gap-2 text-sm text-slate-300 cursor-pointer select-none">
                            <input type="checkbox" name="remember" class="rounded border-[#00d4ff]/40 bg-[#0a0f1e] text-[#00d4ff] focus:ring-[#00d4ff]">
                            Recordarme
                        </label>
                    </div>

                    <button type="submit" class="btn-cyan w-full">Iniciar Sesión</button>
                </form>

                {{-- Security note --}}
                <p class="mt-4 text-center text-xs text-slate-600 flex items-center justify-center gap-1.5">
                    <span>🛡</span> Cuenta bloqueada tras 5 intentos fallidos
                </p>
            </div>

            <p class="mt-6 text-center text-sm text-slate-500">
                ¿No tienes cuenta?
                <a href="{{ route('register') }}" class="text-[#00bcd4] hover:text-[#00d4ff] transition-colors font-medium">Regístrate</a>
            </p>
            <p class="mt-2 text-center text-sm text-slate-500">
                <a href="{{ route('home') }}" class="hover:text-[#00d4ff] transition-colors">← Volver al inicio</a>
            </p>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    document.getElementById('toggle-pwd').addEventListener('click', function () {
        const input = document.getElementById('password');
        input.type = input.type === 'password' ? 'text' : 'password';
        this.textContent = input.type === 'password' ? '👁' : '🙈';
    });
</script>
@endpush
