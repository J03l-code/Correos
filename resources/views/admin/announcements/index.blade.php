@extends('layouts.admin')

@section('title', 'Avisos Destacados')

@section('breadcrumbs')
    <span class="text-gray-400">Panel</span> / <span class="text-[var(--primary)]">Avisos</span>
@endsection

@section('content')
<div class="space-y-6">
    
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-extrabold text-[var(--primary-dark)] font-montserrat">
                Gestión de Avisos Destacados
            </h1>
            <p class="text-xs text-gray-500 font-semibold mt-1">
                Publica alertas importantes u otros avisos generales arriba del contenido.
            </p>
        </div>
        <x-button variant="primary" href="{{ route('announcements.create') }}" class="text-xs">
            <i data-lucide="plus" class="h-4 w-4 mr-1.5"></i>
            Nuevo Aviso
        </x-button>
    </div>

    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs font-semibold text-gray-600">
                <thead class="bg-gray-50 border-b border-gray-100 text-[10px] uppercase font-bold text-gray-500">
                    <tr>
                        <th class="px-6 py-3 w-16 text-center">Orden</th>
                        <th class="px-6 py-3">Aviso</th>
                        <th class="px-6 py-3">Tipo</th>
                        <th class="px-6 py-3">Fechas de Publicación</th>
                        <th class="px-6 py-3">Estado</th>
                        <th class="px-6 py-3 text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($announcements as $ann)
                        <tr class="hover:bg-gray-50/50">
                            <!-- Order controls -->
                            <td class="px-6 py-3.5 text-center">
                                <div class="flex flex-col items-center justify-center space-y-1">
                                    <form action="{{ route('announcements.up', $ann->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="p-0.5 text-gray-400 hover:text-[var(--primary)] transition-colors focus:outline-none">
                                            <i data-lucide="chevron-up" class="h-4 w-4"></i>
                                        </button>
                                    </form>
                                    <form action="{{ route('announcements.down', $ann->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="p-0.5 text-gray-400 hover:text-[var(--primary)] transition-colors focus:outline-none">
                                            <i data-lucide="chevron-down" class="h-4 w-4"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                            <td class="px-6 py-3.5">
                                <span class="font-bold text-[var(--primary-dark)] text-sm">{{ $ann->title }}</span>
                                <p class="text-[10px] text-gray-400 mt-1 leading-relaxed max-w-sm line-clamp-2">{{ $ann->content }}</p>
                            </td>
                            <td class="px-6 py-3.5 capitalize">
                                <span class="px-2.5 py-0.5 rounded bg-gray-100 text-gray-600 text-[9px] uppercase font-bold">{{ $ann->type }}</span>
                            </td>
                            <td class="px-6 py-3.5 text-gray-400">
                                @if($ann->starts_at || $ann->ends_at)
                                    <div class="space-y-0.5 text-[10px] font-semibold">
                                        @if($ann->starts_at)
                                            <p>Desde: <span class="text-gray-600">{{ $ann->starts_at->format('d/m/Y H:i') }}</span></p>
                                        @endif
                                        @if($ann->ends_at)
                                            <p>Hasta: <span class="text-gray-600">{{ $ann->ends_at->format('d/m/Y H:i') }}</span></p>
                                        @endif
                                    </div>
                                @else
                                    <span class="text-gray-300 font-semibold italic text-[10px]">Siempre visible</span>
                                @endif
                            </td>
                            <td class="px-6 py-3.5">
                                <form action="{{ route('announcements.toggle', $ann->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="focus:outline-none">
                                        <x-badge variant="{{ $ann->is_active ? 'success' : 'danger' }}">
                                            {{ $ann->is_active ? 'Activo' : 'Inactivo' }}
                                        </x-badge>
                                    </button>
                                </form>
                            </td>
                            <td class="px-6 py-3.5 text-right">
                                <div class="flex items-center justify-end space-x-2.5">
                                    <a href="{{ route('announcements.edit', $ann->id) }}" class="p-1.5 bg-blue-50 text-blue-600 rounded-lg hover:bg-blue-100 transition-all focus:outline-none" title="Editar">
                                        <i data-lucide="edit-3" class="h-4.5 w-4.5"></i>
                                    </a>
                                    @if(auth()->user()->role !== 'visualizer')
                                        <form action="{{ route('announcements.destroy', $ann->id) }}" method="POST" onsubmit="return confirm('¿Estás seguro de que deseas eliminar este aviso?')">
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
                            <td colspan="6" class="p-8 text-center text-gray-400">
                                <i data-lucide="alert-circle" class="h-10 w-10 text-gray-300 mx-auto mb-2"></i>
                                <p class="text-sm font-semibold">No se han creado avisos destacados todavía.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
