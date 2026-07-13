<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) {
            return redirect()->route('admin.dashboard');
        }

        $settings = [
            'logo' => \App\Models\Setting::get('logo'),
            'primary_color' => \App\Models\Setting::get('primary_color', '#062B63'),
            'primary_dark_color' => \App\Models\Setting::get('primary_dark_color', '#031D46'),
            'secondary_color' => \App\Models\Setting::get('secondary_color', '#6CCBF2'),
            'coral_color' => \App\Models\Setting::get('coral_color', '#FF5964'),
        ];

        return view('auth.login', compact('settings'));
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $throttleKey = Str::lower($request->input('email')) . '|' . $request->ip();

        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            throw ValidationException::withMessages([
                'email' => trans('auth.throttle', [
                    'seconds' => $seconds,
                    'minutes' => ceil($seconds / 60),
                ]),
            ]);
        }

        $credentials = $request->only('email', 'password');
        $credentials['is_active'] = true; // Only active users can login

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            RateLimiter::clear($throttleKey);
            
            $request->session()->regenerate();

            $user = Auth::user();
            $user->last_login_at = now();
            $user->save();

            // Log activity
            ActivityLog::log('login', $user, "Usuario {$user->name} inició sesión exitosamente.");

            return redirect()->intended(route('admin.dashboard'));
        }

        RateLimiter::hit($throttleKey, 60);

        throw ValidationException::withMessages([
            'email' => __('auth.failed'),
        ]);
    }

    public function logout(Request $request)
    {
        $user = Auth::user();
        if ($user) {
            ActivityLog::log('logout', $user, "Usuario {$user->name} cerró sesión.");
        }

        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    public function showChangePassword()
    {
        $settings = [
            'logo' => \App\Models\Setting::get('logo'),
            'primary_color' => \App\Models\Setting::get('primary_color', '#062B63'),
        ];
        return view('auth.change-password', compact('settings'));
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'La contraseña actual es incorrecta.']);
        }

        $user->password = Hash::make($request->password);
        $user->save();

        ActivityLog::log('change_password', $user, "Usuario {$user->name} cambió su contraseña.");

        return redirect()->route('admin.dashboard')->with('success', 'Contraseña actualizada correctamente.');
    }
}
