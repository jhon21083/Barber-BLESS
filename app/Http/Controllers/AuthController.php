<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class AuthController extends Controller
{
    /**
     * Max failed login attempts before lockout.
     */
    private const MAX_ATTEMPTS = 5;

    /**
     * Lockout duration in seconds (5 minutes).
     */
    private const DECAY_SECONDS = 300;

    /**
     * Show the login form.
     */
    public function showLogin(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an authentication attempt with rate limiting.
     *
     * @throws ValidationException
     */
    public function login(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $throttleKey = $this->throttleKey($request);

        // Check if the user is locked out
        if (RateLimiter::tooManyAttempts($throttleKey, self::MAX_ATTEMPTS)) {
            $seconds = RateLimiter::availableIn($throttleKey);

            throw ValidationException::withMessages([
                'email' => trans('auth.throttle', [
                    'seconds' => $seconds,
                    'minutes' => ceil($seconds / 60),
                ]),
            ]);
        }

        if (Auth::attempt($request->only('email', 'password'), $request->boolean('remember'))) {
            RateLimiter::clear($throttleKey);
            $request->session()->regenerate();

            return redirect()->intended(route('dashboard'));
        }

        // Increment the failed attempts counter
        RateLimiter::hit($throttleKey, self::DECAY_SECONDS);

        $remaining = self::MAX_ATTEMPTS - RateLimiter::attempts($throttleKey);

        throw ValidationException::withMessages([
            'email' => $remaining > 0
                ? "Las credenciales no coinciden. Te quedan {$remaining} intento(s)."
                : 'Demasiados intentos fallidos. Tu cuenta está bloqueada temporalmente.',
        ]);
    }

    /**
     * Log the user out of the application.
     */
    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    /**
     * Build the rate-limiter key from the email + IP.
     */
    private function throttleKey(Request $request): string
    {
        return Str::transliterate(Str::lower($request->string('email')).'|'.$request->ip());
    }
}
