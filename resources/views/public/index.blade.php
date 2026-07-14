@extends('layouts.public')

@section('title', 'Inicio')

@section('content')

    <!-- SIMPLE HERO SECTION -->
    <section class="relative overflow-hidden py-12 bg-gradient-to-b from-white to-[#F5F1E7]/30 border-b border-gray-100 text-center">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6 flex flex-col items-center">
            <!-- Big official logo centered -->
            <img src="/logo.png" alt="Logo Quito 2026" class="h-28 sm:h-36 w-auto object-contain mb-2 drop-shadow-sm">

            <div class="inline-flex items-center space-x-2 bg-[var(--secondary)]/15 text-[var(--primary)] px-4 py-1 rounded-full text-[10px] font-black uppercase tracking-widest font-montserrat">
                <span>QUITO 2026</span>
            </div>

            <h1 class="text-3xl sm:text-4xl font-black text-[var(--primary-dark)] font-montserrat">
                Portal de Asignaciones y Turnos
            </h1>

            <p class="text-sm text-gray-500 max-w-xl mx-auto leading-relaxed font-semibold">
                Busca tu asignación, selecciona el día y encuentra tu turno para unirte al grupo de WhatsApp correspondiente de forma segura.
            </p>
        </div>
    </section>

    <!-- ANNOUNCEMENTS SECTION -->
    @if(isset($announcements) && $announcements->count() > 0)
        <section class="py-4 bg-amber-50/50 border-b border-amber-100">
            <div class="max-w-4xl mx-auto px-4 sm:px-6">
                <div class="space-y-3">
                    @foreach($announcements as $announcement)
                        <x-alert type="{{ $announcement->type }}" dismissible="true">
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 text-xs">
                                <div>
                                    <h4 class="font-extrabold text-[var(--primary-dark)] font-montserrat">{{ $announcement->title }}</h4>
                                    <p class="mt-0.5 leading-relaxed font-semibold">{{ $announcement->content }}</p>
                                </div>
                                @if($announcement->button_url && $announcement->button_text)
                                    <x-button variant="primary" href="{{ $announcement->button_url }}" target="_blank" class="text-[10px] py-1 px-3 min-h-[34px] self-start sm:self-center">
                                        {{ $announcement->button_text }}
                                        <i data-lucide="external-link" class="h-3 w-3 ml-1"></i>
                                    </x-button>
                                @endif
                            </div>
                        </x-alert>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <!-- ACCESS SECTIONS & LINK CARDS (Collapsible Nested Accordions) -->
    <section id="accesos" class="py-12">
        <div class="max-w-3xl mx-auto px-4 sm:px-6">
            
            @if(isset($sections) && $sections->count() > 0)
                <div class="space-y-6" x-data="{ activeSection: null }">
                    @foreach($sections as $section)
                        @php
                            $groupedLinks = $section->links->groupBy('day');
                            $dayOrder = ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo', 'General'];
                            $sortedDays = $groupedLinks->keys()->sortBy(fn($day) => array_search($day, $dayOrder) !== false ? array_search($day, $dayOrder) : 99);
                        @endphp
                        
                        <div class="bg-white rounded-3xl border border-gray-100 overflow-hidden shadow-sm transition-all duration-300">
                            
                            <!-- Level 1: Click to toggle Section (Assignment) -->
                            <button 
                                type="button" 
                                @click="activeSection = (activeSection === {{ $section->id }} ? null : {{ $section->id }})"
                                class="w-full flex items-center justify-between p-6 sm:p-8 bg-white hover:bg-gray-50/30 transition-colors focus:outline-none text-left"
                            >
                                <div class="flex items-center space-x-4">
                                    @if($section->icon)
                                        <div class="p-3 bg-[var(--secondary)]/15 text-[var(--primary)] rounded-2xl">
                                            <i data-lucide="{{ $section->icon }}" class="h-6 w-6"></i>
                                        </div>
                                    @endif
                                    <div>
                                        <h3 class="text-lg sm:text-xl font-extrabold text-[var(--primary-dark)] font-montserrat leading-none">
                                            {{ $section->title }}
                                        </h3>
                                        @if($section->subtitle)
                                            <p class="text-[9px] uppercase tracking-wider text-[var(--coral)] font-black font-montserrat mt-1.5">{{ $section->subtitle }}</p>
                                        @endif
                                    </div>
                                </div>
                                <i data-lucide="chevron-down" class="h-5 w-5 text-gray-400 transition-transform duration-300" :class="activeSection === {{ $section->id }} ? 'rotate-180 text-[var(--primary)]' : ''"></i>
                            </button>

                            <!-- Level 2: Days inside the Section -->
                            <div x-show="activeSection === {{ $section->id }}" x-collapse style="display: none;">
                                <div class="p-6 sm:p-8 border-t border-gray-100 bg-gray-50/15 space-y-4" x-data="{ activeDay: null }">
                                    
                                    @if($section->description)
                                        <p class="text-xs text-gray-500 font-semibold leading-relaxed mb-4">
                                            {{ $section->description }}
                                        </p>
                                    @endif

                                    @forelse($sortedDays as $dayName)
                                        @php $linksForDay = $groupedLinks->get($dayName); @endphp
                                        
                                        <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden shadow-sm transition-all duration-300">
                                            
                                            <!-- Click to toggle Day -->
                                            <button 
                                                type="button" 
                                                @click="activeDay = (activeDay === '{{ $dayName }}' ? null : '{{ $dayName }}')"
                                                class="w-full flex items-center justify-between p-4 sm:p-5 bg-white hover:bg-gray-50/30 transition-colors focus:outline-none text-left"
                                            >
                                                <span class="font-extrabold text-sm text-gray-700 flex items-center font-montserrat">
                                                    <i data-lucide="calendar" class="h-4 w-4 mr-2 text-[var(--coral)]"></i>
                                                    Día: {{ $dayName }}
                                                </span>
                                                <i data-lucide="chevron-down" class="h-4 w-4 text-gray-400 transition-transform duration-300" :class="activeDay === '{{ $dayName }}' ? 'rotate-180 text-[var(--primary)]' : ''"></i>
                                            </button>

                                            <!-- Level 3: Shifts inside the Day -->
                                            <div x-show="activeDay === '{{ $dayName }}'" x-collapse style="display: none;">
                                                <div class="p-4 sm:p-5 border-t border-gray-100 bg-gray-50/5 divide-y divide-gray-100">
                                                    @foreach($linksForDay as $link)
                                                        @php
                                                            $status = $link->getAvailabilityStatus();
                                                            $isAvailable = $link->isAvailable();
                                                        @endphp
                                                        <div class="py-4 first:pt-0 last:pb-0 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                                                            
                                                            <!-- Shift Details -->
                                                            <div class="space-y-1 sm:max-w-md">
                                                                <div class="flex items-center space-x-2">
                                                                    <span class="font-bold text-[var(--primary-dark)] text-sm">{{ $link->title }}</span>
                                                                    @if($link->is_featured)
                                                                        <span class="bg-[var(--coral)]/10 text-[var(--coral)] text-[8px] font-black uppercase px-2 py-0.5 rounded">Destacado</span>
                                                                    @endif
                                                                </div>
                                                                @if($link->description)
                                                                    <p class="text-xs text-gray-400 font-semibold leading-relaxed">{{ $link->description }}</p>
                                                                @endif

                                                                <!-- Contact info -->
                                                                @if($link->contact_name || $link->contact_phone)
                                                                    <div class="flex flex-wrap gap-x-4 gap-y-1 text-[11px] text-gray-500 font-bold pt-1">
                                                                        @if($link->contact_name)
                                                                            <span class="flex items-center">
                                                                                <i data-lucide="user" class="h-3 w-3 mr-1 text-gray-400"></i>
                                                                                Encargado: <strong class="text-gray-700 ml-1">{{ $link->contact_name }}</strong>
                                                                            </span>
                                                                        @endif
                                                                        @if($link->contact_phone)
                                                                            <span class="flex items-center">
                                                                                <i data-lucide="phone" class="h-3 w-3 mr-1 text-gray-400"></i>
                                                                                Contacto: <span class="text-gray-700 ml-1 font-mono">{{ $link->contact_phone }}</span>
                                                                            </span>
                                                                        @endif
                                                                    </div>
                                                                @endif
                                                            </div>

                                                            <!-- Join button -->
                                                            <div class="self-start sm:self-center min-w-[140px] text-right">
                                                                @if($isAvailable)
                                                                    <x-button 
                                                                        variant="{{ $link->is_featured ? 'secondary' : 'primary' }}" 
                                                                        href="{{ route('public.access', $link->slug) }}" 
                                                                        target="{{ $link->open_new_tab ? '_blank' : '_self' }}"
                                                                        class="text-xs w-full py-2 min-h-[38px] justify-center"
                                                                    >
                                                                        <i data-lucide="message-circle" class="h-4 w-4 mr-1.5"></i>
                                                                        {{ $link->button_text ?: 'Unirse al Grupo' }}
                                                                    </x-button>
                                                                @else
                                                                    <span class="inline-block w-full text-center py-2 px-3 bg-gray-100 text-gray-400 font-bold text-xs rounded-xl border border-gray-200 cursor-not-allowed">
                                                                        {{ $link->status_label ?: 'Cupo Completo' }}
                                                                    </span>
                                                                @endif
                                                            </div>

                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>

                                        </div>
                                    @empty
                                        <p class="text-xs text-gray-400 font-semibold italic p-4 text-center">No hay turnos ni días programados en esta sección.</p>
                                    @endforelse

                                </div>
                            </div>

                        </div>
                    @endforeach
                </div>
            @else
                <!-- Empty State -->
                <div class="text-center py-12 bg-white border border-gray-100 rounded-3xl p-8 shadow-sm">
                    <i data-lucide="folder-open" class="h-10 w-10 text-gray-300 mx-auto mb-2"></i>
                    <h3 class="text-base font-bold text-[var(--primary)] font-montserrat">No se encontraron secciones</h3>
                    <p class="text-xs text-gray-400 mt-1">El administrador aún no ha publicado ninguna sección pública.</p>
                </div>
            @endif

        </div>
    </section>

    <!-- FAQS SECTION -->
    <section id="faqs" class="py-12 bg-white/40 border-t border-b border-gray-100">
        <div class="max-w-3xl mx-auto px-4 sm:px-6">
            <div class="text-center mb-8">
                <h2 class="text-2xl font-extrabold text-[var(--primary-dark)] font-montserrat tracking-tight">
                    Preguntas Frecuentes
                </h2>
                <div class="w-12 h-1 bg-[var(--coral)] mx-auto mt-3 rounded-full"></div>
            </div>

            @if(isset($faqs) && $faqs->count() > 0)
                <div class="space-y-3">
                    @foreach($faqs as $faq)
                        <x-accordion id="faq-{{ $faq->id }}" title="{{ $faq->question }}">
                            <p class="text-xs leading-relaxed font-semibold text-gray-600">
                                {!! nl2br(e($faq->answer)) !!}
                            </p>
                        </x-accordion>
                    @endforeach
                </div>
            @else
                <p class="text-center font-semibold text-gray-400 text-xs py-8">Aún no se han configurado preguntas frecuentes.</p>
            @endif
        </div>
    </section>

@endsection
