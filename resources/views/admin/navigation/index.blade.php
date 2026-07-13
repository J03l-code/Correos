@extends('layouts.admin')

@section('title', 'Navegación')

@section('breadcrumbs')
    <span class="text-gray-400">Panel</span> / <span class="text-[var(--primary)]">Navegación</span>
@endsection

@section('content')
<div class="space-y-6">
    
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-extrabold text-[var(--primary-dark)] font-montserrat">
                Menús de Navegación
            </h1>
            <p class="text-xs text-gray-500 font-semibold mt-1">
                Administra los enlaces del menú del encabezado y del pie de página del portal.
            </p>
        </div>
        <x-button variant="primary" href="{{ route('navigation.create') }}" class="text-xs">
            <i data-lucide="plus" class="h-4 w-4 mr-1.5"></i>
            Nuevo Enlace de Menú
        </x-button>
    </div>

    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs font-semibold text-gray-600">
                <thead class="bg-gray-50 border-b border-gray-100 text-[10px] uppercase font-bold text-gray-500">
                    <tr>
                        <th class="px-6 py-3 w-16 text-center">Orden</th>
                        <th class="px-6 py-3">Etiqueta del Enlace</th>
                        <th class="px-6 py-3">Destino URL</th>
                        <th class="px-6 py-3">Ubicación</th>
                        <th class="px-6 py-3">Destino Ventana</th>
                        <th class="px-6 py-3">Estado</th>
                        <th class="px-6 py-3 text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($navigationItems as $nav)
                        <tr class="hover:bg-gray-50/50">
                            <!-- Order controls -->
                            <td class="px-6 py-3.5 text-center">
                                <div class="flex flex-col items-center justify-center space-y-1">
                                    <form action="{{ route('navigation.up', $nav->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="p-0.5 text-gray-400 hover:text-[var(--primary)] transition-colors focus:outline-none">
                                            <i data-lucide="chevron-up" class="h-4 w-4"></i>
                                        </button>
                                    </form>
                                    <form action="{{ route('navigation.down', $nav->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="p-0.5 text-gray-400 hover:text-[var(--primary)] transition-colors focus:outline-none">
                                            <i data-lucide="chevron-down" class="h-4 w-4"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                            <td class="px-6 py-3.5 font-bold text-[var(--primary-dark)] text-sm">
                                {{ $nav->label }}
                            </td>
                            <td class="px-6 py-3.5 font-mono text-gray-400 text-[10px]">
                                {{ $nav->url }}
                            </td>
                            <td class="px-6 py-3.5 capitalize text-gray-500">
                                {{ $nav->location === 'both' ? 'Encabezado y Pie' : ($nav->location === 'header' ? 'Encabezado' : 'Pie de página') }}
                            </td>
                            <td class="px-6 py-3.5 font-mono text-gray-400">
                                {{ $nav->target === '_blank' ? 'Nueva Pestaña' : 'Misma Pestaña' }}
                            </td>
                            <td class="px-6 py-3.5">
                                <form action="{{ route('navigation.toggle', $nav->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="focus:outline-none">
                                        <x-badge variant="{{ $nav->is_active ? 'success' : 'danger' }}">
                                            {{ $nav->is_active ? 'Activo' : 'Inactivo' }}
                                        </x-badge>
                                    </button>
                                </form>
                            </td>
                            <td class="px-6 py-3.5 text-right">
                                <div class="flex items-center justify-end space-x-2.5">
                                    <a href="{{ route('navigation.edit', $nav->id) }}" class="p-1.5 bg-blue-50 text-blue-600 rounded-lg hover:bg-blue-100 transition-all focus:outline-none" title="Editar">
                                        <i data-lucide="edit-3" class="h-4.5 w-4.5"></i>
                                    </a>
                                    @if(auth()->user()->role !== 'visualizer')
                                        <form action="{{ route('navigation.destroy', $nav->id) }}" method="POST" onsubmit="return confirm('¿Estás seguro de que deseas eliminar este enlace de navegación?')">
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
                            <td colspan="7" class="p-8 text-center text-gray-400">
                                <i data-lucide="alert-circle" class="h-10 w-10 text-gray-300 mx-auto mb-2"></i>
                                <p class="text-sm font-semibold">No se han configurado enlaces de navegación.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
