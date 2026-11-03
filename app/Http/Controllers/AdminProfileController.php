<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class AdminProfileController extends Controller
{
    /**
     * Show the admin profile page.
     */
    public function show(): View
    {
        return view('dashboard.profile', [
            'user' => Auth::user(),
        ]);
    }

    /**
     * Update the admin's name and email.
     */
    public function updateInfo(Request $request): RedirectResponse
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,'.$user->id],
        ]);

        $user->fill($validated)->save();

        return back()->with('success_info', 'Información actualizada correctamente.');
    }

    /**
     * Update the admin's password.
     */
    public function updatePassword(Request $request): RedirectResponse
    {
        $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', Password::min(8)],
            'password_confirmation' => ['required'],
        ]);

        Auth::user()->update([
            'password' => Hash::make($request->string('password')),
        ]);

        return back()->with('success_password', 'Contraseña actualizada correctamente.');
    }
}
