@extends('layouts.admin')

@section('title', 'Enlaces y Tarjetas')

@section('breadcrumbs')
    <span class="text-gray-400">Panel</span> / <span class="text-[var(--primary)]">Enlaces</span>
@endsection

@section('content')
<div class="space-y-6">
    
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-extrabold text-[var(--primary-dark)] font-montserrat">
                Gestión de Enlaces y Tarjetas
            </h1>
            <p class="text-xs text-gray-500 font-semibold mt-1">
                Crea, administra y configura la redirección segura de tus enlaces y grupos de WhatsApp.
            </p>
        </div>
        <x-button variant="primary" href="{{ route('links.create') }}" class="text-xs">
            <i data-lucide="plus" class="h-4 w-4 mr-1.5"></i>
            Nuevo Enlace
        </x-button>
    </div>

    <!-- FILTERS BAR -->
    <div class="bg-white rounded-2xl border border-gray-100 p-6 shadow-sm">
        <form method="GET" action="{{ route('links.index') }}" class="grid grid-cols-1 sm:grid-cols-4 gap-4 items-end text-xs font-semibold">
            <!-- Filter by Section -->
            <div>
                <label for="section_id" class="block text-gray-500 mb-1.5">Sección</label>
                <select name="section_id" id="section_id" onchange="this.form.submit()" class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-1 focus:ring-[var(--primary)] focus:border-[var(--primary)] pr-8 cursor-pointer">
                    <option value="">Todas las secciones</option>
                    @foreach($sections as $sec)
                        <option value="{{ $sec->id }}" {{ request('section_id') == $sec->id ? 'selected' : '' }}>{{ $sec->title }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Filter by Type -->
            <div>
                <label for="link_type" class="block text-gray-500 mb-1.5">Tipo de Enlace</label>
                <select name="link_type" id="link_type" onchange="this.form.submit()" class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-1 focus:ring-[var(--primary)] focus:border-[var(--primary)] pr-8 cursor-pointer">
                    <option value="">Todos los tipos</option>
                    <option value="whatsapp" {{ request('link_type') == 'whatsapp' ? 'selected' : '' }}>WhatsApp (Grupo o Canal)</option>
                    <option value="form" {{ request('link_type') == 'form' ? 'selected' : '' }}>Formulario</option>
                    <option value="doc" {{ request('link_type') == 'doc' ? 'selected' : '' }}>Documento / Carpeta</option>
                    <option value="map" {{ request('link_type') == 'map' ? 'selected' : '' }}>Mapa / Ubicación</option>
                    <option value="website" {{ request('link_type') == 'website' ? 'selected' : '' }}>Sitio Web Externo</option>
                </select>
            </div>

            <!-- Filter by Visibilidad -->
            <div>
                <label for="is_active" class="block text-gray-500 mb-1.5">Visibilidad</label>
                <select name="is_active" id="is_active" onchange="this.form.submit()" class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-1 focus:ring-[var(--primary)] focus:border-[var(--primary)] pr-8 cursor-pointer">
                    <option value="">Todos los estados</option>
                    <option value="1" {{ request('is_active') === '1' ? 'selected' : '' }}>Activos</option>
                    <option value="0" {{ request('is_active') === '0' ? 'selected' : '' }}>Inactivos</option>
                </select>
            </div>

            <!-- Clean Filters -->
            <div>
                <a href="{{ route('links.index') }}" class="w-full inline-flex items-center justify-center border-2 border-gray-200 text-gray-500 font-bold hover:bg-gray-50 rounded-xl py-2 min-h-[38px] transition-all">
                    <i data-lucide="refresh-cw" class="h-4 w-4 mr-1.5"></i>
                    Limpiar Filtros
                </a>
            </div>
        </form>
    </div>

    <!-- LINKS LIST TABLE -->
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs font-semibold text-gray-600">
                <thead class="bg-gray-50 border-b border-gray-100 text-[10px] uppercase font-bold text-gray-500">
                    <tr>
                        <th class="px-6 py-3 w-16 text-center">Orden</th>
                        <th class="px-6 py-3">Enlace</th>
                        <th class="px-6 py-3">Sección</th>
                        <th class="px-6 py-3">Tipo y Modo</th>
                        <th class="px-6 py-3">Visitas</th>
                        <th class="px-6 py-3">Disponibilidad</th>
                        <th class="px-6 py-3 text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($links as $link)
                        @php
                            $status = $link->getAvailabilityStatus();
                            $statusColors = [
                                'disponible' => 'success',
                                'programado' => 'info',
                                'finalizado' => 'danger',
                                'completo' => 'danger',
                                'protegido' => 'warning',
                                'desactivado' => 'danger',
                            ];
                        @endphp
                        <tr class="hover:bg-gray-50/50">
                            <!-- Sort controls -->
                            <td class="px-6 py-3.5 text-center">
                                <div class="flex flex-col items-center justify-center space-y-1">
                                    <form action="{{ route('links.up', $link->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="p-0.5 text-gray-400 hover:text-[var(--primary)] transition-colors focus:outline-none">
                                            <i data-lucide="chevron-up" class="h-4 w-4"></i>
                                        </button>
                                    </form>
                                    <form action="{{ route('links.down', $link->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="p-0.5 text-gray-400 hover:text-[var(--primary)] transition-colors focus:outline-none">
                                            <i data-lucide="chevron-down" class="h-4 w-4"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                            <!-- Link Info -->
                            <td class="px-6 py-3.5">
                                <div class="flex items-center space-x-2">
                                    <span class="font-bold text-[var(--primary-dark)] text-sm">{{ $link->title }}</span>
                                    @if($link->is_featured)
                                        <span class="bg-[var(--coral)]/10 text-[var(--coral)] text-[8px] font-black uppercase px-2 py-0.5 rounded">Destacado</span>
                                    @endif
                                </div>
                                <p class="text-[10px] text-gray-400 mt-1 font-mono font-medium max-w-xs truncate" title="{{ $link->destination_url }}">{{ $link->destination_url }}</p>
                            </td>
                            <!-- Section -->
                            <td class="px-6 py-3.5 text-gray-500 font-bold">
                                {{ $link->section->title ?? 'Sin sección' }}
                            </td>
                            <!-- Type & Mode -->
                            <td class="px-6 py-3.5 space-y-1">
                                <p><span class="px-2 py-0.5 rounded bg-gray-100 text-gray-600 text-[9px] uppercase font-bold">{{ $link->link_type }}</span></p>
                                <p class="text-[10px] text-gray-400 capitalize">Redirección: {{ $link->redirect_mode }}</p>
                            </td>
                            <!-- Clicks stats -->
                            <td class="px-6 py-3.5 font-extrabold text-[var(--primary-dark)] text-sm">
                                {{ $link->clicks_count }} clics
                                @if($link->max_clicks)
                                    <p class="text-[9px] text-gray-400 font-medium mt-0.5">Límite: {{ $link->max_clicks }}</p>
                                @endif
                            </td>
                            <!-- Status Badges -->
                            <td class="px-6 py-3.5">
                                <form action="{{ route('links.toggle', $link->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="focus:outline-none">
                                        <x-badge variant="{{ $statusColors[$status] ?? 'info' }}">
                                            {{ $status }}
                                        </x-badge>
                                    </button>
                                </form>
                            </td>
                            <!-- Actions -->
                            <td class="px-6 py-3.5 text-right">
                                <div class="flex items-center justify-end space-x-2">
                                    <!-- QR Code -->
                                    <a href="{{ route('links.qr', $link->id) }}" class="p-1.5 bg-gray-50 text-gray-500 rounded-lg hover:bg-gray-100 hover:text-[var(--primary)] transition-all focus:outline-none" title="Generar código QR">
                                        <i data-lucide="qr-code" class="h-4.5 w-4.5"></i>
                                    </a>

                                    <!-- Copy Public Link -->
                                    <button 
                                        type="button" 
                                        onclick="navigator.clipboard.writeText('{{ route('public.access', $link->slug) }}'); alert('Enlace público copiado al portapapeles: \n{{ route('public.access', $link->slug) }}')"
                                        class="p-1.5 bg-gray-50 text-gray-500 rounded-lg hover:bg-gray-100 hover:text-[var(--primary)] transition-all focus:outline-none" 
                                        title="Copiar URL pública"
                                    >
                                        <i data-lucide="copy" class="h-4.5 w-4.5"></i>
                                    </button>

                                    <!-- Duplicate -->
                                    <form action="{{ route('links.duplicate', $link->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="p-1.5 bg-gray-50 text-gray-500 rounded-lg hover:bg-gray-100 hover:text-[var(--primary)] transition-all focus:outline-none" title="Duplicar enlace">
                                            <i data-lucide="copy-check" class="h-4.5 w-4.5"></i>
                                        </button>
                                    </form>

                                    <!-- Edit -->
                                    <a href="{{ route('links.edit', $link->id) }}" class="p-1.5 bg-blue-50 text-blue-600 rounded-lg hover:bg-blue-100 transition-all focus:outline-none" title="Editar">
                                        <i data-lucide="edit-3" class="h-4.5 w-4.5"></i>
                                    </a>

                                    <!-- Delete -->
                                    @if(auth()->user()->role !== 'visualizer')
                                        <form action="{{ route('links.destroy', $link->id) }}" method="POST" onsubmit="return confirm('¿Estás seguro de que deseas eliminar este enlace? Se eliminará de forma segura (Soft Delete).')">
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
                                <p class="text-sm font-semibold">No se encontraron enlaces con los filtros seleccionados.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="px-6 py-4 border-t border-gray-100">
            {{ $links->links() }}
        </div>
    </div>

</div>
@endsection
