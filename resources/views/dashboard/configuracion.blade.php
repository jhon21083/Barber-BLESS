@extends('layouts.admin')

@section('title', 'Configuración | Barber Bless')
@section('page-title', 'Configuración del Negocio')
@section('page-subtitle', 'Parámetros de la barbería, horarios de apertura y políticas de reserva')

@section('admin-content')
    @if(session('success_config'))
        <div class="flex items-center gap-2 px-4 py-3 rounded-lg bg-emerald-500/15 border border-emerald-500/30 text-emerald-300 text-sm">
            <span>✔</span> {{ session('success_config') }}
        </div>
    @endif

    <form method="POST" action="{{ route('admin.configuracion.update') }}" class="space-y-6">
        @csrf

        {{-- Business general info --}}
        <section class="card-dark p-6">
            <h2 class="text-lg font-bold text-white mb-1">Información General de la Barbería</h2>
            <p class="text-xs text-slate-400 mb-6">Datos visibles en la página web pública y en confirmaciones de citas.</p>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label for="nombre_negocio" class="block text-xs tracking-wider text-slate-400 mb-2">NOMBRE COMERCIAL</label>
                    <input
                        id="nombre_negocio"
                        type="text"
                        name="nombre_negocio"
                        value="{{ old('nombre_negocio', $config['nombre_negocio']) }}"
                        class="input-dark"
                        required
                    >
                    @error('nombre_negocio')
                        <p class="text-rose-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="slogan" class="block text-xs tracking-wider text-slate-400 mb-2">SLOGAN / LEMA</label>
                    <input
                        id="slogan"
                        type="text"
                        name="slogan"
                        value="{{ old('slogan', $config['slogan']) }}"
                        class="input-dark"
                    >
                </div>

                <div>
                    <label for="telefono" class="block text-xs tracking-wider text-slate-400 mb-2">WHATSAPP / TELÉFONO DE CONTACTO</label>
                    <input
                        id="telefono"
                        type="text"
                        name="telefono"
                        value="{{ old('telefono', $config['telefono']) }}"
                        class="input-dark"
                        required
                    >
                    @error('telefono')
                        <p class="text-rose-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="email_contacto" class="block text-xs tracking-wider text-slate-400 mb-2">CORREO DE CONTACTO</label>
                    <input
                        id="email_contacto"
                        type="email"
                        name="email_contacto"
                        value="{{ old('email_contacto', $config['email_contacto']) }}"
                        class="input-dark"
                    >
                </div>

                <div class="md:col-span-2">
                    <label for="direccion" class="block text-xs tracking-wider text-slate-400 mb-2">DIRECCIÓN DEL ESTABLECIMIENTO</label>
                    <input
                        id="direccion"
                        type="text"
                        name="direccion"
                        value="{{ old('direccion', $config['direccion']) }}"
                        class="input-dark"
                        required
                    >
                    @error('direccion')
                        <p class="text-rose-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <div class="flex items-center justify-between mb-2">
                        <label for="codigo_postal" class="block text-xs tracking-wider text-slate-400">CÓDIGO POSTAL (BOLIVIA)</label>
                        <span class="text-[10px] text-[#00d4ff]">Clic para auto-completar</span>
                    </div>
                    <input
                        id="codigo_postal"
                        type="text"
                        name="codigo_postal"
                        value="{{ old('codigo_postal', $config['codigo_postal'] ?? '0000') }}"
                        class="input-dark"
                        placeholder="Ej. 0000, 0101, 0201"
                    >
                    <div class="mt-2 flex flex-wrap gap-1.5">
                        <button type="button" onclick="document.getElementById('codigo_postal').value='0000'" class="px-2 py-0.5 rounded text-[11px] bg-slate-800 text-slate-300 hover:text-white hover:bg-[#00d4ff]/20 border border-slate-700 transition-colors">
                            0000 (General)
                        </button>
                        <button type="button" onclick="document.getElementById('codigo_postal').value='0101'; document.getElementById('ciudad').value='La Paz, Bolivia'" class="px-2 py-0.5 rounded text-[11px] bg-slate-800 text-slate-300 hover:text-white hover:bg-[#00d4ff]/20 border border-slate-700 transition-colors">
                            0101 (La Paz)
                        </button>
                        <button type="button" onclick="document.getElementById('codigo_postal').value='0201'; document.getElementById('ciudad').value='Santa Cruz, Bolivia'" class="px-2 py-0.5 rounded text-[11px] bg-slate-800 text-slate-300 hover:text-white hover:bg-[#00d4ff]/20 border border-slate-700 transition-colors">
                            0201 (Santa Cruz)
                        </button>
                        <button type="button" onclick="document.getElementById('codigo_postal').value='0301'; document.getElementById('ciudad').value='Cochabamba, Bolivia'" class="px-2 py-0.5 rounded text-[11px] bg-slate-800 text-slate-300 hover:text-white hover:bg-[#00d4ff]/20 border border-slate-700 transition-colors">
                            0301 (Cochabamba)
                        </button>
                    </div>
                    <p class="text-[11px] text-slate-400 mt-1.5">Puedes escribir el código postal que prefieras o hacer clic en los accesos rápidos.</p>
                </div>

                <div>
                    <div class="flex items-center justify-between mb-2">
                        <label for="ciudad" class="block text-xs tracking-wider text-slate-400">CIUDAD Y PAÍS</label>
                        <span class="text-[10px] text-[#00d4ff]">Sugerencias de Bolivia</span>
                    </div>
                    <input
                        id="ciudad"
                        type="text"
                        name="ciudad"
                        list="ciudades-bolivia"
                        value="{{ old('ciudad', $config['ciudad'] ?? 'La Paz, Bolivia') }}"
                        class="input-dark"
                        placeholder="Ej. La Paz, Bolivia"
                    >
                    <datalist id="ciudades-bolivia">
                        <option value="La Paz, Bolivia">
                        <option value="Santa Cruz de la Sierra, Bolivia">
                        <option value="Cochabamba, Bolivia">
                        <option value="El Alto, Bolivia">
                        <option value="Sucre, Bolivia">
                        <option value="Tarija, Bolivia">
                        <option value="Oruro, Bolivia">
                        <option value="Potosí, Bolivia">
                        <option value="Trinidad (Beni), Bolivia">
                        <option value="Cobija (Pando), Bolivia">
                    </datalist>
                    <div class="mt-2 flex flex-wrap gap-1.5">
                        <button type="button" onclick="document.getElementById('ciudad').value='La Paz, Bolivia'" class="px-2 py-0.5 rounded text-[11px] bg-slate-800 text-slate-300 hover:text-white hover:bg-[#00d4ff]/20 border border-slate-700 transition-colors">
                            La Paz
                        </button>
                        <button type="button" onclick="document.getElementById('ciudad').value='Santa Cruz, Bolivia'" class="px-2 py-0.5 rounded text-[11px] bg-slate-800 text-slate-300 hover:text-white hover:bg-[#00d4ff]/20 border border-slate-700 transition-colors">
                            Santa Cruz
                        </button>
                        <button type="button" onclick="document.getElementById('ciudad').value='Cochabamba, Bolivia'" class="px-2 py-0.5 rounded text-[11px] bg-slate-800 text-slate-300 hover:text-white hover:bg-[#00d4ff]/20 border border-slate-700 transition-colors">
                            Cochabamba
                        </button>
                        <button type="button" onclick="document.getElementById('ciudad').value='El Alto, Bolivia'" class="px-2 py-0.5 rounded text-[11px] bg-slate-800 text-slate-300 hover:text-white hover:bg-[#00d4ff]/20 border border-slate-700 transition-colors">
                            El Alto
                        </button>
                    </div>
                </div>

                <div class="md:col-span-2">
                    <label for="moneda" class="block text-xs tracking-wider text-slate-400 mb-2">MONEDA PRINCIPAL DEL SISTEMA</label>
                    @php
                        $currentMoneda = old('moneda', $config['moneda'] ?? 'BOB');
                    @endphp
                    <select id="moneda" name="moneda" class="input-dark">
                        <option value="BOB" {{ in_array($currentMoneda, ['BOB', 'Bolivianos (BOB / Bs.)', 'Bs.']) ? 'selected' : '' }}>
                            🇧🇴 Bolivianos — BOB (Bs.) [Moneda oficial de Bolivia]
                        </option>
                        <option value="USD" {{ $currentMoneda === 'USD' ? 'selected' : '' }}>
                            🇺🇸 Dólares Estadounidenses — USD ($)
                        </option>
                        <option value="EUR" {{ $currentMoneda === 'EUR' ? 'selected' : '' }}>
                            🇪🇺 Euros — EUR (€)
                        </option>
                        <option value="ARS" {{ $currentMoneda === 'ARS' ? 'selected' : '' }}>
                            🇦🇷 Pesos Argentinos — ARS ($)
                        </option>
                        <option value="PEN" {{ $currentMoneda === 'PEN' ? 'selected' : '' }}>
                            🇵🇪 Soles Peruanos — PEN (S/)
                        </option>
                        <option value="CLP" {{ $currentMoneda === 'CLP' ? 'selected' : '' }}>
                            🇨🇱 Pesos Chilenos — CLP ($)
                        </option>
                    </select>
                    <p class="text-[11px] text-[#00d4ff] mt-1.5">Puedes cambiar la moneda libremente cuando desees; se guardará al presionar "Guardar Configuración".</p>
                </div>
            </div>
        </section>

        {{-- Operating hours & booking parameters --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <section class="card-dark p-6">
                <h2 class="text-lg font-bold text-white mb-1">Horario de Operación</h2>
                <p class="text-xs text-slate-400 mb-5">Horas de apertura para agendamiento de citas en línea.</p>

                <div class="space-y-4">
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs text-slate-400 mb-1">APERTURA</label>
                            <input type="time" name="hora_apertura" value="{{ $config['hora_apertura'] }}" class="input-dark text-sm">
                        </div>
                        <div>
                            <label class="block text-xs text-slate-400 mb-1">CIERRE</label>
                            <input type="time" name="hora_cierre" value="{{ $config['hora_cierre'] }}" class="input-dark text-sm">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs text-slate-400 mb-1">DÍAS LABORABLES</label>
                        <input type="text" name="dias_laborables" value="{{ $config['dias_laborables'] }}" class="input-dark text-sm">
                    </div>
                </div>
            </section>

            <section class="card-dark p-6">
                <h2 class="text-lg font-bold text-white mb-1">Políticas de Agenda</h2>
                <p class="text-xs text-slate-400 mb-5">Intervalos de atención y reglas del sistema.</p>

                <div class="space-y-4">
                    <div>
                        <label class="block text-xs text-slate-400 mb-1">INTERVALO ENTRE CITAS (MINUTOS)</label>
                        <select name="intervalo_citas" class="input-dark text-sm">
                            <option value="15" {{ $config['intervalo_citas'] == '15' ? 'selected' : '' }}>15 minutos</option>
                            <option value="30" {{ $config['intervalo_citas'] == '30' ? 'selected' : '' }}>30 minutos</option>
                            <option value="45" {{ $config['intervalo_citas'] == '45' ? 'selected' : '' }}>45 minutos</option>
                            <option value="60" {{ $config['intervalo_citas'] == '60' ? 'selected' : '' }}>60 minutos</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs text-slate-400 mb-1">ANTICIPACIÓN MÁXIMA PARA AGENDAR (DÍAS)</label>
                        <select name="anticipacion_maxima" class="input-dark text-sm">
                            <option value="7" {{ $config['anticipacion_maxima'] == '7' ? 'selected' : '' }}>7 días</option>
                            <option value="15" {{ $config['anticipacion_maxima'] == '15' ? 'selected' : '' }}>15 días</option>
                            <option value="30" {{ $config['anticipacion_maxima'] == '30' ? 'selected' : '' }}>30 días</option>
                        </select>
                    </div>
                </div>
            </section>
        </div>

        {{-- Notifications toggles --}}
        <section class="card-dark p-6">
            <h2 class="text-lg font-bold text-white mb-1">Notificaciones Automáticas</h2>
            <p class="text-xs text-slate-400 mb-5">Alertas a clientes para reducir inasistencias.</p>

            <div class="space-y-4">
                <label class="flex items-center gap-3 cursor-pointer">
                    <input type="checkbox" name="recordatorios_whatsapp" value="1" checked class="w-4 h-4 rounded text-[#00d4ff] bg-slate-900 border-slate-700 focus:ring-[#00d4ff]">
                    <div>
                        <p class="text-sm font-semibold text-white">Recordatorio por WhatsApp (2 horas antes)</p>
                        <p class="text-xs text-slate-400">Envía mensaje automático con folio y enlace de confirmación.</p>
                    </div>
                </label>

                <label class="flex items-center gap-3 cursor-pointer">
                    <input type="checkbox" name="notificaciones_email" value="1" checked class="w-4 h-4 rounded text-[#00d4ff] bg-slate-900 border-slate-700 focus:ring-[#00d4ff]">
                    <div>
                        <p class="text-sm font-semibold text-white">Confirmación por Correo Electrónico</p>
                        <p class="text-xs text-slate-400">Envía recibo de reserva al registrarse un nuevo turno.</p>
                    </div>
                </label>
            </div>
        </section>

        <div class="flex justify-end">
            <button type="submit" class="btn-cyan px-8 py-3">
                Guardar Configuración
            </button>
        </div>
    </form>
@endsection
