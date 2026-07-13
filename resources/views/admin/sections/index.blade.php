@extends('layouts.admin')

@section('title', 'Secciones')

@section('breadcrumbs')
    <span class="text-gray-400">Panel</span> / <span class="text-[var(--primary)]">Secciones</span>
@endsection

@section('content')
<div class="space-y-6">
    
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-extrabold text-[var(--primary-dark)] font-montserrat">
                Gestión de Secciones
            </h1>
            <p class="text-xs text-gray-500 font-semibold mt-1">
                Crea y organiza las secciones principales que agrupan tus tarjetas de accesos.
            </p>
        </div>
        <x-button variant="primary" href="{{ route('sections.create') }}" class="text-xs">
            <i data-lucide="plus" class="h-4 w-4 mr-1.5"></i>
            Nueva Sección
        </x-button>
    </div>

    <!-- SECTIONS TABLE -->
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs font-semibold text-gray-600">
                <thead class="bg-gray-50 border-b border-gray-100 text-[10px] uppercase font-bold text-gray-500">
                    <tr>
                        <th class="px-6 py-3 w-16 text-center">Orden</th>
                        <th class="px-6 py-3">Sección</th>
                        <th class="px-6 py-3">Ícono</th>
                        <th class="px-6 py-3">Enlaces</th>
                        <th class="px-6 py-3">Fechas de Disponibilidad</th>
                        <th class="px-6 py-3">Estado</th>
                        <th class="px-6 py-3 text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($sections as $index => $section)
                        <tr class="hover:bg-gray-50/50">
                            <!-- Sort Actions -->
                            <td class="px-6 py-3.5 text-center">
                                <div class="flex flex-col items-center justify-center space-y-1">
                                    @if(!$loop->first)
                                        <form action="{{ route('sections.up', $section->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="p-0.5 text-gray-400 hover:text-[var(--primary)] transition-colors focus:outline-none">
                                                <i data-lucide="chevron-up" class="h-4 w-4"></i>
                                            </button>
                                        </form>
                                    @endif
                                    @if(!$loop->last)
                                        <form action="{{ route('sections.down', $section->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="p-0.5 text-gray-400 hover:text-[var(--primary)] transition-colors focus:outline-none">
                                                <i data-lucide="chevron-down" class="h-4 w-4"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                            <!-- Section Info -->
                            <td class="px-6 py-3.5">
                                <span class="font-bold text-[var(--primary-dark)] text-sm">{{ $section->title }}</span>
                                @if($section->subtitle)
                                    <p class="text-[9px] uppercase tracking-wider text-[var(--coral)] font-extrabold mt-0.5 font-montserrat">{{ $section->subtitle }}</p>
                                @endif
                                <p class="text-[10px] text-gray-400 mt-1 font-mono font-medium">{{ $section->slug }}</p>
                            </td>
                            <!-- Icon -->
                            <td class="px-6 py-3.5 text-gray-400">
                                @if($section->icon)
                                    <div class="inline-flex items-center space-x-1.5">
                                        <i data-lucide="{{ $section->icon }}" class="h-4.5 w-4.5 text-gray-500"></i>
                                        <span class="text-[10px] font-mono font-medium">{{ $section->icon }}</span>
                                    </div>
                                @else
                                    <span class="text-gray-300 font-semibold italic text-[10px]">Sin ícono</span>
                                @endif
                            </td>
                            <!-- Links count -->
                            <td class="px-6 py-3.5 text-gray-600">
                                <span class="font-bold">{{ $section->links()->count() }}</span> enlaces
                            </td>
                            <!-- Dates availability -->
                            <td class="px-6 py-3.5 text-gray-400">
                                @if($section->starts_at || $section->ends_at)
                                    <div class="space-y-0.5 text-[10px] font-semibold">
                                        @if($section->starts_at)
                                            <p>Desde: <span class="text-gray-600">{{ $section->starts_at->format('d/m/Y H:i') }}</span></p>
                                        @endif
                                        @if($section->ends_at)
                                            <p>Hasta: <span class="text-gray-600">{{ $section->ends_at->format('d/m/Y H:i') }}</span></p>
                                        @endif
                                    </div>
                                @else
                                    <span class="text-gray-300 font-semibold italic text-[10px]">Siempre visible</span>
                                @endif
                            </td>
                            <!-- Toggle Visibilidad -->
                            <td class="px-6 py-3.5">
                                <form action="{{ route('sections.toggle', $section->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="focus:outline-none">
                                        <x-badge variant="{{ $section->is_active ? 'success' : 'danger' }}">
                                            {{ $section->is_active ? 'Activo' : 'Inactivo' }}
                                        </x-badge>
                                    </button>
                                </form>
                            </td>
                            <!-- Actions -->
                            <td class="px-6 py-3.5 text-right">
                                <div class="flex items-center justify-end space-x-2.5">
                                    <!-- Duplicate -->
                                    <form action="{{ route('sections.duplicate', $section->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="p-1.5 bg-gray-50 text-gray-500 rounded-lg hover:bg-gray-100 hover:text-[var(--primary)] transition-all focus:outline-none" title="Duplicar">
                                            <i data-lucide="copy" class="h-4.5 w-4.5"></i>
                                        </button>
                                    </form>

                                    <!-- Edit -->
                                    <a href="{{ route('sections.edit', $section->id) }}" class="p-1.5 bg-blue-50 text-blue-600 rounded-lg hover:bg-blue-100 transition-all focus:outline-none" title="Editar">
                                        <i data-lucide="edit-3" class="h-4.5 w-4.5"></i>
                                    </a>

                                    <!-- Delete with confirmation -->
                                    @if(auth()->user()->role !== 'visualizer')
                                        <form action="{{ route('sections.destroy', $section->id) }}" method="POST" onsubmit="return confirm('¿Estás seguro de que deseas eliminar esta sección de forma permanente? Se marcará como borrada de forma segura (Soft Delete).')">
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
                                <p class="text-sm font-semibold">Aún no has creado ninguna sección.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
