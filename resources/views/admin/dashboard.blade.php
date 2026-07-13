@extends('layouts.admin')

@section('title', 'Resumen')

@section('breadcrumbs')
    <span class="text-gray-400">Panel</span> / <span class="text-[var(--primary)]">Resumen</span>
@endsection

@section('content')
<div class="space-y-8">
    
    <!-- Title & Welcoming -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-extrabold text-[var(--primary-dark)] font-montserrat">
                ¡Bienvenido, {{ auth()->user()->name }}!
            </h1>
            <p class="text-xs text-gray-500 font-semibold mt-1">
                Este es el estado del portal y los enlaces de QUITO 2026.
            </p>
        </div>
        <div class="flex gap-3">
            <x-button variant="secondary" href="{{ route('links.create') }}" class="text-xs">
                <i data-lucide="plus" class="h-4 w-4 mr-1.5"></i>
                Nuevo Enlace
            </x-button>
            <x-button variant="outline" href="{{ route('admin.statistics') }}" class="text-xs">
                <i data-lucide="bar-chart-2" class="h-4 w-4 mr-1.5"></i>
                Estadísticas Completas
            </x-button>
        </div>
    </div>

    <!-- Warnings / Alerts of expired or full links -->
    @if(count($warnings) > 0)
        <div class="space-y-3">
            @foreach($warnings as $w)
                <x-alert type="{{ $w['type'] }}" dismissible="true" class="font-bold text-xs">
                    <div class="flex items-center justify-between">
                        <span>{{ $w['message'] }}</span>
                        <a href="{{ $w['link'] }}" class="text-xs underline hover:text-black ml-4">Editar Enlace</a>
                    </div>
                </x-alert>
            @endforeach
        </div>
    @endif

    <!-- COUNTER CARDS -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Sections Card -->
        <div class="bg-white rounded-2xl border border-gray-100 p-6 shadow-sm flex items-center justify-between">
            <div>
                <span class="text-[10px] font-black text-gray-400 uppercase tracking-wider font-montserrat">Secciones</span>
                <h3 class="text-2xl font-black text-[var(--primary-dark)] mt-1 font-montserrat">{{ $totalSections }}</h3>
            </div>
            <div class="p-3 bg-[var(--primary)]/10 text-[var(--primary)] rounded-xl">
                <i data-lucide="folders" class="h-6 w-6"></i>
            </div>
        </div>

        <!-- Links Card -->
        <div class="bg-white rounded-2xl border border-gray-100 p-6 shadow-sm flex items-center justify-between">
            <div>
                <span class="text-[10px] font-black text-gray-400 uppercase tracking-wider font-montserrat">Enlaces Totales</span>
                <h3 class="text-2xl font-black text-[var(--primary-dark)] mt-1 font-montserrat">{{ $totalLinks }}</h3>
                <span class="text-[9px] text-gray-400 font-bold">{{ $activeLinks }} activos / {{ $inactiveLinks }} inactivos</span>
            </div>
            <div class="p-3 bg-[var(--secondary)]/20 text-[var(--primary)] rounded-xl">
                <i data-lucide="link" class="h-6 w-6"></i>
            </div>
        </div>

        <!-- Clicks Today Card -->
        <div class="bg-white rounded-2xl border border-gray-100 p-6 shadow-sm flex items-center justify-between">
            <div>
                <span class="text-[10px] font-black text-gray-400 uppercase tracking-wider font-montserrat">Clics Hoy</span>
                <h3 class="text-2xl font-black text-[var(--coral)] mt-1 font-montserrat">{{ $clicksToday }}</h3>
            </div>
            <div class="p-3 bg-[var(--coral)]/10 text-[var(--coral)] rounded-xl">
                <i data-lucide="mouse-pointer-click" class="h-6 w-6"></i>
            </div>
        </div>

        <!-- Total Clicks Card -->
        <div class="bg-white rounded-2xl border border-gray-100 p-6 shadow-sm flex items-center justify-between">
            <div>
                <span class="text-[10px] font-black text-gray-400 uppercase tracking-wider font-montserrat">Clics Totales</span>
                <h3 class="text-2xl font-black text-emerald-600 mt-1 font-montserrat">{{ $totalClicks }}</h3>
                <span class="text-[9px] text-gray-400 font-bold">Últimos 7 días: {{ $clicks7Days }}</span>
            </div>
            <div class="p-3 bg-emerald-100 text-emerald-600 rounded-xl">
                <i data-lucide="activity" class="h-6 w-6"></i>
            </div>
        </div>
    </div>

    <!-- MAIN GRIDS -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        
        <!-- Left Column: Top Links and Recent Clicks -->
        <div class="lg:col-span-8 space-y-8">
            <!-- Top Links Table -->
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                    <h3 class="text-base font-extrabold text-[var(--primary-dark)] font-montserrat">
                        Enlaces Más Visitados
                    </h3>
                    <i data-lucide="trending-up" class="h-5 w-5 text-gray-400"></i>
                </div>
                
                @if($topLinks->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-xs font-semibold text-gray-600">
                            <thead class="bg-gray-50 border-b border-gray-100 text-[10px] uppercase font-bold text-gray-500">
                                <tr>
                                    <th class="px-6 py-3">Título</th>
                                    <th class="px-6 py-3">Tipo</th>
                                    <th class="px-6 py-3">Redirección</th>
                                    <th class="px-6 py-3 text-right">Clics</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @foreach($topLinks as $link)
                                    <tr class="hover:bg-gray-50/50">
                                        <td class="px-6 py-3.5">
                                            <a href="{{ route('links.edit', $link->id) }}" class="font-extrabold text-[var(--primary)] hover:underline">{{ $link->title }}</a>
                                            <p class="text-[10px] text-gray-400 mt-0.5 leading-relaxed">{{ $link->slug }}</p>
                                        </td>
                                        <td class="px-6 py-3.5">
                                            <span class="px-2 py-0.5 rounded bg-gray-100 text-gray-600 text-[9px] uppercase font-bold">{{ $link->link_type }}</span>
                                        </td>
                                        <td class="px-6 py-3.5 text-gray-400 capitalize">
                                            {{ $link->redirect_mode }}
                                        </td>
                                        <td class="px-6 py-3.5 text-right font-bold text-[var(--primary-dark)]">
                                            {{ $link->clicks_count }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="p-6 text-center text-gray-400 text-xs">No hay datos de visitas registrados todavía.</p>
                @endif
            </div>

            <!-- Recent Clicks -->
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                    <h3 class="text-base font-extrabold text-[var(--primary-dark)] font-montserrat">
                        Actividad de Clics Reciente
                    </h3>
                    <i data-lucide="history" class="h-5 w-5 text-gray-400"></i>
                </div>

                @if($recentClicks->count() > 0)
                    <div class="divide-y divide-gray-100 px-6">
                        @foreach($recentClicks as $click)
                            <div class="py-3.5 flex items-center justify-between gap-4 text-xs font-semibold">
                                <div class="space-y-1">
                                    <p class="text-[var(--primary-dark)] font-bold">
                                        Acceso a: <span class="text-[var(--primary)]">{{ $click->link->title ?? 'Enlace Eliminado' }}</span>
                                    </p>
                                    <div class="flex items-center space-x-3 text-[10px] text-gray-400">
                                        <span>IP: {{ substr($click->anonymized_ip, 0, 15) }}...</span>
                                        <span>•</span>
                                        <span>Dispositivo: <span class="capitalize">{{ $click->device_type }}</span></span>
                                    </div>
                                </div>
                                <div class="text-[10px] text-gray-400 font-bold">
                                    {{ $click->clicked_at->diffForHumans() }}
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="p-6 text-center text-gray-400 text-xs">No hay accesos de usuarios registrados todavía.</p>
                @endif
            </div>

        </div>

        <!-- Right Column: Recent Admin Actions / Fast Links -->
        <div class="lg:col-span-4 space-y-8">
            <!-- Admin Logs -->
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                    <h3 class="text-base font-extrabold text-[var(--primary-dark)] font-montserrat">
                        Bitácora de Cambios
                    </h3>
                    <i data-lucide="scroll" class="h-5 w-5 text-gray-400"></i>
                </div>

                @if($recentActions->count() > 0)
                    <div class="p-6 space-y-4">
                        @foreach($recentActions as $action)
                            <div class="text-xs space-y-1">
                                <p class="text-gray-600 font-medium">
                                    <span class="font-bold text-[var(--primary-dark)]">{{ $action->user->name ?? 'Sistema' }}</span>
                                    {{ $action->description }}
                                </p>
                                <span class="text-[9px] text-gray-400 font-bold">{{ $action->created_at->format('d/m/Y H:i') }}</span>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="p-6 text-center text-gray-400 text-xs">No hay acciones de administración registradas.</p>
                @endif
            </div>

            <!-- Fast access links -->
            <div class="bg-gradient-to-br from-[var(--primary)] to-[var(--primary-dark)] text-white rounded-2xl p-6 space-y-4 shadow-md">
                <h3 class="text-sm font-extrabold font-montserrat uppercase tracking-wider text-[var(--secondary)]">Accesos Rápidos</h3>
                <div class="grid grid-cols-2 gap-3 text-xs">
                    <a href="{{ route('sections.create') }}" class="p-3 bg-white/5 border border-white/10 rounded-xl hover:bg-white/10 hover:scale-102 transition-all flex flex-col items-center text-center">
                        <i data-lucide="folder-plus" class="h-5 w-5 mb-1.5 text-[var(--yellow)]"></i>
                        <span>Nueva Sección</span>
                    </a>
                    <a href="{{ route('links.create') }}" class="p-3 bg-white/5 border border-white/10 rounded-xl hover:bg-white/10 hover:scale-102 transition-all flex flex-col items-center text-center">
                        <i data-lucide="plus-circle" class="h-5 w-5 mb-1.5 text-[var(--yellow)]"></i>
                        <span>Nuevo Enlace</span>
                    </a>
                    <a href="{{ route('announcements.create') }}" class="p-3 bg-white/5 border border-white/10 rounded-xl hover:bg-white/10 hover:scale-102 transition-all flex flex-col items-center text-center">
                        <i data-lucide="megaphone" class="h-5 w-5 mb-1.5 text-[var(--yellow)]"></i>
                        <span>Crear Aviso</span>
                    </a>
                    <a href="{{ route('admin.settings') }}" class="p-3 bg-white/5 border border-white/10 rounded-xl hover:bg-white/10 hover:scale-102 transition-all flex flex-col items-center text-center">
                        <i data-lucide="settings" class="h-5 w-5 mb-1.5 text-[var(--yellow)]"></i>
                        <span>Ajustes Generales</span>
                    </a>
                </div>
            </div>
        </div>

    </div>

</div>
@endsection
