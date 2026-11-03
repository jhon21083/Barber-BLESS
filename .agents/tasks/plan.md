# Implementation Plan — Barber Bless (Landing + Login + Dashboard)

All commands run from the project root `c:/laragon/sistema-registro/sistema-barberia` (PowerShell). Use `--no-interaction` on every `php artisan make:` call per AGENTS.md.

## Key findings (from exploration)

- Stack: Laravel 12 / PHP 8.2. DB = sqlite (`DB_CONNECTION=sqlite`), `database/database.sqlite` exists, `APP_KEY` set, `APP_URL=http://localhost:8000`. Migrations present: users, cache, jobs.
- Tailwind 4 IS installed and wired: `@tailwindcss/vite` plugin in `vite.config.js`, `@import 'tailwindcss';` in `resources/css/app.css`. Vite entrypoints = `resources/css/app.css` + `resources/js/app.js`. So we use Tailwind utilities + a small custom CSS layer (the task's "Tailwind si ya está instalado" branch).
- `node_modules` is ABSENT (no `package-lock.json`). `npm install` MUST run before `npm run build`.
- The `auth` middleware alias is registered by default by the framework (`vendor/.../Foundation/Configuration/Middleware.php:782` → `Illuminate\Auth\Middleware\Authenticate`). No `bootstrap/app.php` change is needed — just put `->middleware('auth')` on the dashboard route.
- The default unauthenticated handler redirects via `route('login')` (`vendor/.../Foundation/Exceptions/Handler.php:763`). Therefore the `GET /login` route MUST be named `login`, or guests hitting `/dashboard` get a `RouteNotFoundException` instead of a redirect.
- `User` model: fillable `[name,email,password]`, `password` cast = `'hashed'`. That cast stores already-hashed values as-is (`if (! Hash::isHashed($value)) Hash::make($value)`), so `Hash::make('password')` in the seeder is safe (no double hash). `UserFactory` default password is `Hash::make('password')`.
- Pest is configured. `tests/Pest.php` has `RefreshDatabase` COMMENTED OUT for the Feature suite, and `phpunit.xml` sets the test DB to sqlite `:memory:`. So the new feature test must opt into `RefreshDatabase` itself. CSRF is skipped in tests via `VerifyCsrfToken::runningUnitTests()`.
- `.ai/rules` does not exist (AGENTS.md says continue without it). Pint runs `vendor/bin/pint --dirty --format agent` (note: `--format agent`, not `--test`).

## Design decisions (rationale)

- **D1 — Protect /dashboard with the framework `auth` alias** (not an `Auth::check()` guard in the controller). Rationale: the alias exists by default (verified in framework source) and is the idiomatic Laravel-12 approach; the controller check is only a documented fallback. Login route is named `login` so the default unauthenticated redirect resolves.
- **D2 — One shared layout `layouts/app.blade.php`** holding the `<head>` with `@vite([...])`, the dark-navy `<body>`, and `@yield('content')`. Landing, login, and dashboard all `@extend` it, centralizing CSS/JS includes and the base body styling.
- **D3 — Tailwind 4 + a `@layer components` of brand classes** in `app.css` (not pure CSS). Rationale: Tailwind is already wired. We add brand color tokens to the existing `@theme` block and reusable classes (`.btn-cyan`, `.btn-outline`, `.card-dark`, `.input-dark`, `.glow-cyan`, `.badge`) for the gradient/glow theme.
- **D4 — Seeder uses `User::firstOrCreate(['email'=>...], ['name'=>'Administrador','password'=>Hash::make('password'),'email_verified_at'=>now()])`** for idempotency. `Hash::make` is safe with the `hashed` cast. Registered in `DatabaseSeeder`, replacing the default `Test User` factory call.
- **D5 — Dashboard uses hardcoded demo arrays** (stats + últimas reservas) passed to the view. The task does not ask for Reserva/Cliente/Barbero models, so none are created; the dashboard stays within the User-only scope.
- **D6 — Add a Pest feature test** as the strongest end-to-end verification of landing→login→dashboard→logout (AGENTS.md mandates Pest; the system verification rule asks for tests on new features). The task's explicit checks (route:list, file existence, seeder, build) are kept as final steps.
- **D7 — `GET /` → `LandingController` → `landing` view**; `welcome.blade.php` is left in place (unused, non-destructive).

---

- [ ] 1. Create the three controllers with their methods (logic included below), extending the base `Controller`.
      Run from the project root:
      `php artisan make:controller LandingController --no-interaction`
      `php artisan make:controller AuthController --no-interaction`
      `php artisan make:controller DashboardController --no-interaction`
      Then implement:
      - `app/Http/Controllers/LandingController.php` → `public function index(): \Illuminate\View\View { return view('landing'); }` (import `Illuminate\View\View`).
      - `app/Http/Controllers/AuthController.php` → `showLogin(): View` returns `view('auth.login')`; `login(Request $request): RedirectResponse` validates `email` (required,email) + `password` (required), calls `Auth::attempt($credentials, $request->boolean('remember'))`, on success `regenerate()` the session and `return redirect()->intended(route('dashboard'))`, on failure `back()->withErrors(['email' => 'Las credenciales no coinciden.'])->onlyInput('email')`; `logout(Request $request): RedirectResponse` calls `Auth::logout()`, `invalidate()`, `regenerateToken()`, `return redirect()->route('login')`. Imports: `Illuminate\Http\Request`, `Illuminate\Support\Facades\Auth`, `Illuminate\Http\RedirectResponse`, `Illuminate\View\View`. Use constructor property promotion only if a dependency is injected (none needed here — leave the constructor out).
      - `app/Http/Controllers/DashboardController.php` → `index(): View` builds two hardcoded demo arrays: `$stats` (keys: `reservas_hoy`=>24, `clientes_activos`=>312, `ingresos_mes`=>'$48,900', `barberos_disponibles`=>8) and `$reservas` (array of 5 rows with `cliente`, `servicio`, `barbero`, `fecha`, `estado`), then `return view('dashboard.index', compact('stats', 'reservas'))`.
      Follow AGENTS.md PHP rules: curly braces on all control structures, explicit return types, type-hinted params, prefer PHPDoc over inline comments.
      Files: `app/Http/Controllers/LandingController.php`, `app/Http/Controllers/AuthController.php`, `app/Http/Controllers/DashboardController.php`
      Verify: `php -l app/Http/Controllers/LandingController.php`, `php -l app/Http/Controllers/AuthController.php`, `php -l app/Http/Controllers/DashboardController.php` — each prints "No syntax errors detected".

- [ ] 2. Replace `routes/web.php` with the five routes. Name `login` and `logout` and `dashboard` so the `auth` middleware redirect and named-route links resolve.
      Content:
      ```php
      <?php

      use App\Http\Controllers\AuthController;
      use App\Http\Controllers\DashboardController;
      use App\Http\Controllers\LandingController;
      use Illuminate\Support\Facades\Route;

      Route::get('/', [LandingController::class, 'index'])->name('home');

      Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
      Route::post('/login', [AuthController::class, 'login']);
      Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

      Route::get('/dashboard', [DashboardController::class, 'index'])->middleware('auth')->name('dashboard');
      ```
      Files: `routes/web.php`
      Verify: `php artisan route:list --path=/` — shows GET / → LandingController@index; `php artisan route:list --name=login` — shows GET login and (no POST name) ; `php artisan route:list --name=dashboard` — shows GET dashboard with the `auth` middleware. (No errors.)

- [ ] 3. Create and register the AdminUserSeeder.
      `php artisan make:seeder AdminUserSeeder --no-interaction`
      Implement `database/seeders/AdminUserSeeder.php` `run(): void` as:
      `User::firstOrCreate(['email' => 'admin@barberbless.com'], ['name' => 'Administrador', 'password' => Hash::make('password'), 'email_verified_at' => now()]);` (imports `App\Models\User`, `Illuminate\Database\Seeder`, `Illuminate\Support\Facades\Hash`).
      Edit `database/seeders/DatabaseSeeder.php`: replace the `User::factory()->create([...])` body with `$this->call(AdminUserSeeder::class);` (keep the `use App\Models\User;` import only if still referenced; otherwise drop unused imports). Keep `WithoutModelEvents` trait as-is.
      Files: `database/seeders/AdminUserSeeder.php`, `database/seeders/DatabaseSeeder.php`
      Verify: `php -l database/seeders/AdminUserSeeder.php` and `php -l database/seeders/DatabaseSeeder.php` — no syntax errors. (Seeding itself is verified in step 10.)

- [ ] 4. Extend the theme in `resources/css/app.css`: add brand color tokens to the existing `@theme { ... }` block and add a `@layer components` block with reusable brand classes.
      Add to `@theme` (keep the existing `--font-sans` line):
      `--color-navy: #0a0f1e;`, `--color-navy-800: #0d1526;`, `--color-cyan-glow: #00d4ff;`, `--color-cyan: #00bcd4;`, `--color-blue-electric: #0096ff;`
      Add `@layer components { ... }` defining: `.btn-cyan` (gradient `linear-gradient(135deg,#00d4ff,#0096ff)`, white text, rounded, `box-shadow:0 0 18px rgba(0,212,255,.45)` glow, hover brightens/lifts), `.btn-outline` (transparent bg, 1px `#00d4ff` border, cyan text, hover fills with subtle cyan tint), `.card-dark` (`background:rgba(13,21,38,.7); border:1px solid rgba(0,212,255,.18); border-radius:1rem; backdrop-filter:blur(6px)`), `.input-dark` (`background:rgba(10,15,30,.8); border:1px solid rgba(0,212,255,.25); color:#fff; :focus { border-color:#00d4ff; box-shadow:0 0 0 3px rgba(0,212,255,.2) }`), `.glow-cyan` (`text-shadow:0 0 12px rgba(0,212,255,.6)`), `.badge` (small rounded pill, semi-transparent cyan/navy bg, uppercase tracking). Keep classes framework-agnostic (plain CSS in the layer).
      Files: `resources/css/app.css`
      Verify: after step 11 build — `npm run build` compiles Tailwind with no error. (No standalone verify before build; this file is only consumed at build time.)

- [ ] 5. Create the shared layout `resources/views/layouts/app.blade.php`.
      `<!DOCTYPE html><html lang="es">` with `<head>`: meta charset + viewport, `<title>@yield('title', 'Barber Bless')</title>`, `@vite(['resources/css/app.css', 'resources/js/app.js'])`. `<body class="bg-[#0a0f1e] text-slate-200 min-h-screen antialiased">` containing `@yield('content')` and `@stack('scripts')`.
      Files: `resources/views/layouts/app.blade.php`
      Verify: `php -l` is not valid for Blade; rely on the render tests in step 9 (GET / and GET /login return 200) once views exist.

- [ ] 6. Create `resources/views/landing.blade.php` (`@extends('layouts.app')`, `@section('title','Barber Bless | Club VIP')`, `@section('content')`) rendering the five landing sections, using the brand component classes from step 4 and Tailwind utilities (arbitrary hex where needed: `bg-[#0a0f1e]`, `text-[#00d4ff]`, `border-[#00d4ff]`):
      1. Announcement top bar (`.announcement-bar`): dark cyan/navy bg, the 🏆 CLUB OFICIAL text with the close `×` button (`.announcement-close`).
      2. Navbar: logo "BARBER BLESS" + ✂ scissors icon, subtitle "ESTILO QUE HABLA POR TI"; links Destacados / Servicios & Cortes / Consultar Cita-Folio / Maestros Barberos / Administración / ¿Cómo Funciona? / Ayuda (anchors to `#`); a dark/light toggle button (`#theme-toggle`); "Acceder" outline button → `route('login')`; "Reservar Cita" cyan button → `#reservar`.
      3. Hero/slider (`.hero-slide` slides + `.hero-indicator` indicators, with an `02/04`-style counter): left = "Membresía Oficial de Caballeros" badge, big title "Únete al Club VIP", cyan subtitle "Cortes Ilimitados & Beneficios", description, "Registrarme en el Club VIP" (cyan) + "Planes y Membresías" (outline); right = dark card "CLUB DE FIDELIDAD / TARJETA BLACK BLESS" with flower/star icon, "Beneficio Exclusivo" + "Pase Anual" badges, and the 20% discount copy. Indicators row at the bottom.
      4. "Tu Estilo en Manos de Verdaderos Maestros": badge "PORTAL OFICIAL DE RESERVAS & REGISTRO 2026", title with "Verdaderos Maestros" in cyan, description, buttons "Agendar Mi Turno Ahora" + "Rastrear Reserva"; right card "Consulta tu Cita" with "Sillones Activos" badge, a `#folio-form` form (input `#folio-input` placeholder "EJ. BARBER-2026-9041" + `.btn-cyan` "Comprobar Estado de Cita") and a `#folio-result` target; mini footer "Barberos Certificados | Café & Trago Gratis | Higiene Sanitaria 100%".
      5. Statistics section (slightly darker bg): 4 columns, large cyan numbers — "+18,400 CORTES & BARBAS REALIZADOS", "12 MAESTROS BARBEROS EN STAFF", "4.9/5.0 SATISFACCIÓN DE CLIENTES", "100% PUNTUALIDAD EN TURNOS".
      Files: `resources/views/landing.blade.php`
      Verify: step 9 feature test `GET /` returns 200 (renders the view with no Blade error).

- [ ] 7. Create `resources/views/auth/login.blade.php` (`@extends('layouts.app')`, centered card) with:
      logo "BARBER BLESS" above, a `.card-dark` with subtle cyan border, title "Acceder al Sistema"; a `<form method="POST" action="{{ route('login') }}">` containing `@csrf`, email `.input-dark`, password `.input-dark`, "Recordarme" checkbox, full-width `.btn-cyan` "Iniciar Sesión", and a "¿Olvidaste tu contraseña?" link → `#`. Show validation errors via `@error('email') <p class="text-rose-400 text-sm">{{ $message }}</p> @enderror` and `@error('password')`. Add subtle barbershop background decoration (absolute-positioned scissors/striped-pole SVG or CSS shapes) behind the card.
      Files: `resources/views/auth/login.blade.php`
      Verify: step 9 feature test `GET /login` returns 200.

- [ ] 8. Create `resources/views/dashboard/index.blade.php` (`@extends('layouts.app')`, `@section('title','Dashboard | Barber Bless')`) with:
      left sidebar (dark) — logo + nav links Dashboard / Reservas / Clientes / Barberos / Servicios / Reportes / Configuración (anchors `#`), and a logout `<form method="POST" action="{{ route('logout') }}">@csrf<button>...</button></form>`;
      top header — `{{ auth()->user()->name }}` + avatar;
      main area — 4 `.card-dark` stat cards using `$stats` (Total Reservas Hoy, Clientes Activos, Ingresos del Mes, Barberos Disponibles);
      a "Últimas Reservas" table built from `@foreach($reservas as $r)` with columns cliente / servicio / barbero / fecha / estado (estado as a `.badge`);
      a simple HTML/CSS bar chart (a row of divs with inline heights) — no JS chart libs.
      Files: `resources/views/dashboard/index.blade.php`
      Verify: step 9 feature test — authenticated `GET /dashboard` returns 200 and the view renders without error.

- [ ] 9. Add the page JS to `resources/js/app.js` (keep `import './bootstrap';`), all vanilla JS, every selector null-checked so it is safe on every page:
      - Announcement bar: on `.announcement-close` click, hide `.announcement-bar` (add `hidden`/`display:none`) and persist closed state in `localStorage`.
      - Theme toggle: `#theme-toggle` click toggles a `theme-light` class on `document.documentElement`, persists in `localStorage`, and restores on load. Add a small `.theme-light` override block in `app.css` (step 4) adjusting body bg/text — best-effort, dark-first.
      - Hero slider: wire `.hero-indicator` buttons to show the matching `.hero-slide` (toggle an `active` class), update the `02/04` counter to the active index, and auto-advance every ~6s.
      - Folio form: on `#folio-form` `submit`, `preventDefault()`, read `#folio-input` (validate non-empty), and write a status message into `#folio-result` (e.g. "Consultando reserva <valor>…" then a mock result). Client-side only, no `fetch`.
      Files: `resources/js/app.js`, (and the `.theme-light` CSS lives in `resources/css/app.css` from step 4)
      Verify: `npm run build` (step 11) compiles with no error.

- [ ] 10. Set up the database: run pending migrations then seed the admin user.
      `php artisan migrate --no-interaction`
      `php artisan db:seed --no-interaction`
      Files: (no file edits; touches `database/database.sqlite`)
      Verify: `php artisan tinker --execute 'echo App\Models\User::where("email","admin@barberbless.com")->value("name");'` — prints `Administrador`. (Use the AGENTS.md single-quote/double-quote convention exactly as shown.)

- [ ] 11. Add a Pest feature test covering the auth flow (strongest verification; uses sqlite `:memory:` + `RefreshDatabase`).
      `php artisan make:test --pest AuthFlowTest --no-interaction`
      In `tests/Feature/AuthFlowTest.php`, at the top add `uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);`. Tests:
      - `GET /` returns 200.
      - `GET /login` returns 200.
      - `POST /login` with a user created via `User::factory()->create(['name'=>'Administrador','email'=>'admin@barberbless.com'])` (factory default password = `password`) and valid creds redirects to `/dashboard` (`->assertRedirect('/dashboard')`).
      - `GET /dashboard` while guest redirects to `/login` (`->assertRedirect('/login')`).
      - Acting as that user, `GET /dashboard` returns 200.
      - `POST /logout` redirects and the user becomes a guest (`->assertRedirect(); $this->assertGuest();`).
      Files: `tests/Feature/AuthFlowTest.php`
      Verify: `php artisan test --compact tests/Feature/AuthFlowTest.php` — all tests pass. (CSRF is auto-skipped in tests via `runningUnitTests()`.)

- [ ] 12. Install frontend deps and compile assets.
      `npm install`
      `npm run build`
      Files: (creates `node_modules/`, `public/build/manifest.json`, `package-lock.json`)
      Verify: `npm run build` prints a successful Vite build and `public/build/manifest.json` exists.

- [ ] 13. Format all changed PHP files.
      `vendor/bin/pint --dirty --format agent`
      (PowerShell fallback if the wrapper is not invoked directly: `php vendor/bin/pint --dirty --format agent`)
      Files: formats all dirty `.php` files from steps 1–3, 10.
      Verify: the command exits 0 and reports formatted files (or "no changes").

- [ ] 14. Final verification (the task's checklist):
      - `php artisan route:list` — shows `/` (GET), `/login` (GET+POST), `/logout` (POST), `/dashboard` (GET, with auth middleware).
      - `php artisan tinker --execute 'echo App\Models\User::where("email","admin@barberbless.com")->value("name");'` — prints `Administrador`.
      - Confirm the view/controller files exist:
        `Test-Path resources/views/landing.blade.php, resources/views/auth/login.blade.php, resources/views/dashboard/index.blade.php, resources/views/layouts/app.blade.php, app/Http/Controllers/LandingController.php, app/Http/Controllers/AuthController.php, app/Http/Controllers/DashboardController.php` — all True.
      - `php artisan test --compact` — full suite passes.
      - `npm run build` — completes without error.
      Files: (no edits; verification only)
      Verify: every command above produces the expected output.
