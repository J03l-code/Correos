<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class UserController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware(function ($request, $next) {
                if (auth()->user()->role !== 'superadmin') {
                    abort(403, 'Acción no autorizada. Solo el Superadministrador puede gestionar usuarios.');
                }
                return $next($request);
            }),
        ];
    }

    public function index()
    {
        $users = User::orderBy('id')->paginate(15);
        $settings = $this->getSettings();
        return view('admin.users.index', compact('users', 'settings'));
    }

    public function create()
    {
        $settings = $this->getSettings();
        return view('admin.users.create', compact('settings'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:150',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|string|in:superadmin,editor,visualizer',
            'is_active' => 'boolean',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'is_active' => $request->has('is_active'),
        ]);

        ActivityLog::log('create_user', $user, "Usuario '{$user->name}' con rol '{$user->role}' creado.");

        return redirect()->route('users.index')->with('success', 'Usuario creado con éxito.');
    }

    public function edit(User $user)
    {
        $settings = $this->getSettings();
        return view('admin.users.edit', compact('user', 'settings'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:150',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'role' => 'required|string|in:superadmin,editor,visualizer',
            'is_active' => 'boolean',
        ]);

        // Prevent superadmin from disabling themselves or changing their own role to something else
        if ($user->id === auth()->id()) {
            if ($request->role !== 'superadmin' || !$request->has('is_active')) {
                return back()->with('error', 'No puedes cambiar tu propio rol ni desactivar tu propio usuario superadmin.');
            }
        }

        $oldValues = $user->toArray();

        $user->name = $request->name;
        $user->email = $request->email;
        $user->role = $request->role;
        $user->is_active = $request->has('is_active');

        if ($request->password) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        ActivityLog::log('update_user', $user, "Usuario '{$user->name}' actualizado.", $oldValues, $user->toArray());

        return redirect()->route('users.index')->with('success', 'Usuario actualizado con éxito.');
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'No puedes eliminarte a ti mismo.');
        }

        $user->delete();
        ActivityLog::log('delete_user', $user, "Usuario '{$user->name}' eliminado (Soft Delete).");

        return redirect()->route('users.index')->with('success', 'Usuario eliminado con éxito.');
    }

    public function toggle(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'No puedes desactivar tu propio usuario.');
        }

        $user->is_active = !$user->is_active;
        $user->save();

        ActivityLog::log('toggle_user', $user, "Usuario '{$user->name}' " . ($user->is_active ? 'activado' : 'desactivado'));

        return back()->with('success', 'Estado del usuario cambiado.');
    }

    private function getSettings()
    {
        return [
            'logo' => \App\Models\Setting::get('logo'),
            'primary_color' => \App\Models\Setting::get('primary_color', '#062B63'),
        ];
    }
}
