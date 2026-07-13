@extends('layouts.admin')

@section('title', 'Nuevo Usuario')

@section('breadcrumbs')
    <span class="text-gray-400">Panel</span> / <a href="{{ route('users.index') }}" class="text-gray-400 hover:text-[var(--primary)]">Usuarios</a> / <span class="text-[var(--primary)]">Crear</span>
@endsection

@section('content')
<div class="max-w-2xl mx-auto">
    
    <div class="mb-6">
        <h1 class="text-2xl font-extrabold text-[var(--primary-dark)] font-montserrat">
            Crear Nuevo Usuario
        </h1>
        <p class="text-xs text-gray-500 font-semibold mt-1">
            Asigna un nuevo integrante para la administración de la plataforma.
        </p>
    </div>

    <div class="bg-white rounded-2xl border border-gray-100 p-8 shadow-sm">
        <form action="{{ route('users.store') }}" method="POST" class="space-y-6">
            @csrf

            <x-input 
                label="Nombre Completo" 
                name="name" 
                placeholder="Ej. María Pérez" 
                required="true"
            />

            <x-input 
                label="Correo Electrónico" 
                name="email" 
                type="email"
                placeholder="maria@quito2026.com" 
                required="true"
            />

            <x-select label="Rol Asignado" name="role" required="true" help="Define los permisos y alcances del usuario.">
                <option value="superadmin">Superadministrador (Acceso Total)</option>
                <option value="editor" selected>Editor (Gestiona Secciones y Enlaces)</option>
                <option value="visualizer">Visualizador (Solo consulta Estadísticas)</option>
            </x-select>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <x-input 
                    label="Contraseña" 
                    name="password" 
                    type="password" 
                    placeholder="••••••••" 
                    required="true"
                    help="Mínimo 8 caracteres."
                />

                <x-input 
                    label="Confirmar Contraseña" 
                    name="password_confirmation" 
                    type="password" 
                    placeholder="••••••••" 
                    required="true"
                />
            </div>

            <!-- Visibilidad Toggle -->
            <div class="flex items-center space-x-3.5 border-t border-gray-100 pt-6">
                <input 
                    type="checkbox" 
                    id="is_active" 
                    name="is_active" 
                    value="1" 
                    checked
                    class="rounded border-gray-300 text-[var(--primary)] focus:ring-[var(--primary)] h-4 w-4"
                >
                <label for="is_active" class="text-sm font-semibold text-gray-700 select-none cursor-pointer">
                    Habilitar usuario inmediatamente (Estado Activo)
                </label>
            </div>

            <!-- Actions Buttons -->
            <div class="flex items-center justify-end space-x-3 border-t border-gray-100 pt-6">
                <x-button variant="outline" href="{{ route('users.index') }}" class="text-xs">
                    Cancelar
                </x-button>
                <x-button type="submit" variant="primary" class="text-xs">
                    Guardar Usuario
                    <i data-lucide="save" class="h-4 w-4 ml-1.5"></i>
                </x-button>
            </div>
        </form>
    </div>

</div>
@endsection
