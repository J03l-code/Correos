@extends('layouts.admin')

@section('title', 'Editar Usuario')

@section('breadcrumbs')
    <span class="text-gray-400">Panel</span> / <a href="{{ route('users.index') }}" class="text-gray-400 hover:text-[var(--primary)]">Usuarios</a> / <span class="text-[var(--primary)]">Editar</span>
@endsection

@section('content')
<div class="max-w-2xl mx-auto">
    
    <div class="mb-6">
        <h1 class="text-2xl font-extrabold text-[var(--primary-dark)] font-montserrat">
            Editar Usuario: {{ $user->name }}
        </h1>
        <p class="text-xs text-gray-500 font-semibold mt-1">
            Modifica los detalles del usuario o cambia su contraseña si es necesario.
        </p>
    </div>

    <div class="bg-white rounded-2xl border border-gray-100 p-8 shadow-sm">
        <form action="{{ route('users.update', $user->id) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <x-input 
                label="Nombre Completo" 
                name="name" 
                value="{{ $user->name }}"
                placeholder="Ej. María Pérez" 
                required="true"
            />

            <x-input 
                label="Correo Electrónico" 
                name="email" 
                type="email"
                value="{{ $user->email }}"
                placeholder="maria@quito2026.com" 
                required="true"
            />

            <x-select label="Rol Asignado" name="role" required="true" help="Define los permisos y alcances del usuario.">
                <option value="superadmin" {{ $user->role === 'superadmin' ? 'selected' : '' }}>Superadministrador (Acceso Total)</option>
                <option value="editor" {{ $user->role === 'editor' ? 'selected' : '' }}>Editor (Gestiona Secciones y Enlaces)</option>
                <option value="visualizer" {{ $user->role === 'visualizer' ? 'selected' : '' }}>Visualizador (Solo consulta Estadísticas)</option>
            </x-select>

            <div class="bg-amber-50/50 border border-amber-200/50 rounded-2xl p-5 space-y-4">
                <h4 class="text-xs font-bold text-amber-800 uppercase tracking-wider font-montserrat">Cambiar Contraseña (Opcional)</h4>
                <p class="text-[10px] text-gray-500 font-semibold -mt-2 leading-relaxed">Dejar estos campos en blanco si no deseas modificar la contraseña actual.</p>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <x-input 
                        label="Nueva Contraseña" 
                        name="password" 
                        type="password" 
                        placeholder="••••••••" 
                        help="Mínimo 8 caracteres."
                    />

                    <x-input 
                        label="Confirmar Nueva Contraseña" 
                        name="password_confirmation" 
                        type="password" 
                        placeholder="••••••••" 
                    />
                </div>
            </div>

            <!-- Visibilidad Toggle -->
            @if($user->id !== auth()->id())
                <div class="flex items-center space-x-3.5 border-t border-gray-100 pt-6">
                    <input 
                        type="checkbox" 
                        id="is_active" 
                        name="is_active" 
                        value="1" 
                        {{ $user->is_active ? 'checked' : '' }}
                        class="rounded border-gray-300 text-[var(--primary)] focus:ring-[var(--primary)] h-4 w-4"
                    >
                    <label for="is_active" class="text-sm font-semibold text-gray-700 select-none cursor-pointer">
                        Habilitar usuario (Estado Activo)
                    </label>
                </div>
            @else
                <!-- Keep hidden input to avoid self-disabling -->
                <input type="hidden" name="is_active" value="1">
            @endif

            <!-- Actions Buttons -->
            <div class="flex items-center justify-end space-x-3 border-t border-gray-100 pt-6">
                <x-button variant="outline" href="{{ route('users.index') }}" class="text-xs">
                    Cancelar
                </x-button>
                <x-button type="submit" variant="primary" class="text-xs">
                    Actualizar Usuario
                    <i data-lucide="save" class="h-4 w-4 ml-1.5"></i>
                </x-button>
            </div>
        </form>
    </div>

</div>
@endsection
