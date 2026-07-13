@extends('layouts.admin')

@section('title', 'Estadísticas')

@section('breadcrumbs')
    <span class="text-gray-400">Panel</span> / <span class="text-[var(--primary)]">Estadísticas</span>
@endsection

@section('content')
<div class="space-y-8">
    
    <!-- Title & Action -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-extrabold text-[var(--primary-dark)] font-montserrat">
                Estadísticas de Tráfico
            </h1>
            <p class="text-xs text-gray-500 font-semibold mt-1">
                Análisis de clics por enlace, sección y tipo de redirección.
            </p>
        </div>
        <div class="flex flex-wrap items-center gap-3">
            <!-- Filter by days -->
            <form method="GET" action="{{ route('admin.statistics') }}" class="flex items-center bg-white border border-gray-200 rounded-xl px-3 py-1.5 shadow-sm text-xs font-bold">
                <span class="text-gray-400 mr-2">Rango:</span>
                <select name="days" onchange="this.form.submit()" class="bg-transparent border-none focus:outline-none focus:ring-0 text-[var(--primary)] cursor-pointer pr-4 font-bold">
                    <option value="7" {{ $days == 7 ? 'selected' : '' }}>Últimos 7 días</option>
                    <option value="30" {{ $days == 30 ? 'selected' : '' }}>Últimos 30 días</option>
                    <option value="90" {{ $days == 90 ? 'selected' : '' }}>Últimos 90 días</option>
                </select>
            </form>

            <x-button variant="success" href="{{ route('admin.export') }}" class="text-xs">
                <i data-lucide="download" class="h-4 w-4 mr-1.5"></i>
                Exportar CSV
            </x-button>
        </div>
    </div>

    <!-- METRICS CHARTS BRICK (Lightweight visual bars) -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        
        <!-- Clicks by Link Type -->
        <div class="bg-white rounded-2xl border border-gray-100 p-6 shadow-sm space-y-4">
            <h3 class="text-sm font-extrabold text-[var(--primary-dark)] font-montserrat flex items-center">
                <i data-lucide="tag" class="h-4.5 w-4.5 mr-2 text-[var(--coral)]"></i>
                Clics por Tipo de Enlace
            </h3>
            
            <div class="space-y-3.5 pt-2">
                @php $maxTypeVal = max(1, $clicksByType->max('count') ?? 1); @endphp
                @forelse($clicksByType as $type)
                    @php $percentage = round(($type->count / $maxTypeVal) * 100); @endphp
                    <div class="text-xs font-semibold">
                        <div class="flex justify-between mb-1.5">
                            <span class="capitalize text-gray-600">{{ $type->link_type }}</span>
                            <span class="text-[var(--primary)] font-bold">{{ $type->count }} clics</span>
                        </div>
                        <div class="w-full bg-gray-100 h-2.5 rounded-full overflow-hidden">
                            <div class="bg-[var(--primary)] h-full rounded-full transition-all duration-500" style="width: {{ $percentage }}%"></div>
                        </div>
                    </div>
                @empty
                    <p class="text-center text-gray-400 text-xs py-8">No hay registros para mostrar.</p>
                @endforelse
            </div>
        </div>

        <!-- Clicks by Section -->
        <div class="bg-white rounded-2xl border border-gray-100 p-6 shadow-sm space-y-4">
            <h3 class="text-sm font-extrabold text-[var(--primary-dark)] font-montserrat flex items-center">
                <i data-lucide="folders" class="h-4.5 w-4.5 mr-2 text-[var(--coral)]"></i>
                Clics por Sección
            </h3>
            
            <div class="space-y-3.5 pt-2">
                @php $maxSecVal = max(1, $clicksBySection->max('count') ?? 1); @endphp
                @forelse($clicksBySection as $sec)
                    @php $percentage = round(($sec->count / $maxSecVal) * 100); @endphp
                    <div class="text-xs font-semibold">
                        <div class="flex justify-between mb-1.5">
                            <span class="text-gray-600 leading-none">{{ $sec->section_title }}</span>
                            <span class="text-[var(--primary)] font-bold">{{ $sec->count }} clics</span>
                        </div>
                        <div class="w-full bg-gray-100 h-2.5 rounded-full overflow-hidden">
                            <div class="bg-[var(--coral)] h-full rounded-full transition-all duration-500" style="width: {{ $percentage }}%"></div>
                        </div>
                    </div>
                @empty
                    <p class="text-center text-gray-400 text-xs py-8">No hay registros para mostrar.</p>
                @endforelse
            </div>
        </div>

    </div>

    <!-- DETAILED TABLE -->
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
            <h3 class="text-base font-extrabold text-[var(--primary-dark)] font-montserrat">
                Detalle del Rendimiento por Enlace
            </h3>
            <i data-lucide="list" class="h-5 w-5 text-gray-400"></i>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs font-semibold text-gray-600">
                <thead class="bg-gray-50 border-b border-gray-100 text-[10px] uppercase font-bold text-gray-500">
                    <tr>
                        <th class="px-6 py-3">Enlace</th>
                        <th class="px-6 py-3">Sección</th>
                        <th class="px-6 py-3">Tipo</th>
                        <th class="px-6 py-3">Redirección</th>
                        <th class="px-6 py-3">Estado</th>
                        <th class="px-6 py-3 text-right">Clics Registrados</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($linksStats as $link)
                        <tr class="hover:bg-gray-50/50">
                            <td class="px-6 py-3.5">
                                <span class="font-bold text-[var(--primary-dark)]">{{ $link->title }}</span>
                                <p class="text-[10px] text-gray-400 mt-0.5 leading-relaxed">{{ $link->slug }}</p>
                            </td>
                            <td class="px-6 py-3.5 text-gray-500">
                                {{ $link->section->title ?? 'N/A' }}
                            </td>
                            <td class="px-6 py-3.5">
                                <span class="px-2 py-0.5 rounded bg-gray-100 text-gray-600 text-[9px] uppercase font-bold">{{ $link->link_type }}</span>
                            </td>
                            <td class="px-6 py-3.5 text-gray-400 capitalize">
                                {{ $link->redirect_mode }}
                            </td>
                            <td class="px-6 py-3.5 capitalize text-gray-500">
                                {{ $link->getAvailabilityStatus() }}
                            </td>
                            <td class="px-6 py-3.5 text-right font-extrabold text-[var(--primary)] text-sm">
                                {{ $link->clicks_count }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="p-6 text-center text-gray-400">No hay enlaces configurados en la base de datos.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="px-6 py-4 border-t border-gray-100">
            {{ $linksStats->links() }}
        </div>
    </div>

    <!-- DANGER ZONE: Vaciar estadísticas -->
    @if(auth()->user()->role === 'superadmin')
        <div class="bg-rose-50 border border-rose-200 rounded-2xl p-6 space-y-4">
            <div class="flex items-start space-x-3.5 text-rose-800">
                <i data-lucide="shield-alert" class="h-6 w-6 text-rose-600 mt-0.5 flex-shrink-0"></i>
                <div>
                    <h4 class="font-extrabold text-sm font-montserrat">Zona de Peligro: Vaciar Historial de Clics</h4>
                    <p class="text-xs mt-1 leading-relaxed font-semibold">Esta acción eliminará de forma irreversible todas las métricas de clics acumuladas en el portal Quito 2026. Los enlaces permanecerán activos.</p>
                </div>
            </div>

            <form action="{{ route('admin.clear_stats') }}" method="POST" class="flex flex-wrap items-end gap-4 text-xs font-semibold">
                @csrf
                <div class="w-full sm:max-w-xs">
                    <x-input 
                        label="Escribe ELIMINAR para confirmar" 
                        name="confirm_text" 
                        placeholder="ELIMINAR" 
                        required="true"
                    />
                </div>
                <x-button type="submit" variant="danger" class="mb-4 min-h-[44px]">
                    Confirmar Borrado de Estadísticas
                </x-button>
            </form>
        </div>
    @endif

</div>
@endsection
