@extends('layouts.admin')

@section('title', 'Usuarios y Permisos')

@section('breadcrumbs')
    <span class="text-gray-400">Panel</span> / <span class="text-[var(--primary)]">Usuarios</span>
@endsection

@section('content')
<div class="space-y-6">
    
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-extrabold text-[var(--primary-dark)] font-montserrat">
                Gestión de Usuarios
            </h1>
            <p class="text-xs text-gray-500 font-semibold mt-1">
                Administra los accesos al panel administrativo, roles y contraseñas de tu equipo.
            </p>
        </div>
        <x-button variant="primary" href="{{ route('users.create') }}" class="text-xs">
            <i data-lucide="user-plus" class="h-4 w-4 mr-1.5"></i>
            Nuevo Usuario
        </x-button>
    </div>

    <!-- USERS LIST TABLE -->
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs font-semibold text-gray-600">
                <thead class="bg-gray-50 border-b border-gray-100 text-[10px] uppercase font-bold text-gray-500">
                    <tr>
                        <th class="px-6 py-3">Nombre</th>
                        <th class="px-6 py-3">Correo Electrónico</th>
                        <th class="px-6 py-3">Rol</th>
                        <th class="px-6 py-3">Último Acceso</th>
                        <th class="px-6 py-3">Estado</th>
                        <th class="px-6 py-3 text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($users as $user)
                        <tr class="hover:bg-gray-50/50">
                            <td class="px-6 py-3.5 font-bold text-[var(--primary-dark)] text-sm">
                                {{ $user->name }}
                                @if($user->id === auth()->id())
                                    <span class="ml-1.5 bg-sky-100 text-sky-800 text-[8px] font-black uppercase px-2 py-0.5 rounded">Tú</span>
                                @endif
                            </td>
                            <td class="px-6 py-3.5 text-gray-500">
                                {{ $user->email }}
                            </td>
                            <td class="px-6 py-3.5">
                                @php
                                    $roleColors = [
                                        'superadmin' => 'danger',
                                        'editor' => 'primary',
                                        'visualizer' => 'info',
                                    ];
                                @endphp
                                <x-badge variant="{{ $roleColors[$user->role] ?? 'info' }}">
                                    {{ $user->role }}
                                </x-badge>
                            </td>
                            <td class="px-6 py-3.5 text-gray-400">
                                {{ $user->last_login_at ? $user->last_login_at->format('d/m/Y H:i') : 'Nunca ha ingresado' }}
                            </td>
                            <td class="px-6 py-3.5">
                                <form action="{{ route('users.toggle', $user->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="focus:outline-none" {{ $user->id === auth()->id() ? 'disabled' : '' }}>
                                        <x-badge variant="{{ $user->is_active ? 'success' : 'danger' }}">
                                            {{ $user->is_active ? 'Activo' : 'Inactivo' }}
                                        </x-badge>
                                    </button>
                                </form>
                            </td>
                            <td class="px-6 py-3.5 text-right">
                                <div class="flex items-center justify-end space-x-2.5">
                                    <a href="{{ route('users.edit', $user->id) }}" class="p-1.5 bg-blue-50 text-blue-600 rounded-lg hover:bg-blue-100 transition-all focus:outline-none" title="Editar / Cambiar contraseña">
                                        <i data-lucide="edit-3" class="h-4.5 w-4.5"></i>
                                    </a>
                                    
                                    @if($user->id !== auth()->id())
                                        <form action="{{ route('users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('¿Estás seguro de que deseas eliminar este usuario de forma segura?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-1.5 bg-rose-50 text-rose-600 rounded-lg hover:bg-rose-100 transition-all focus:outline-none" title="Eliminar">
                                                <i data-lucide="trash-2" class="h-4.5 w-4.5"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="p-8 text-center text-gray-400">No hay usuarios configurados.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="px-6 py-4 border-t border-gray-100">
            {{ $users->links() }}
        </div>
    </div>

</div>
@endsection
