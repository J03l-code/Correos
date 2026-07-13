@extends('layouts.public')

@section('title', 'Inicio')

@section('content')

    <!-- HERO SECTION -->
    <section class="relative overflow-hidden py-16 lg:py-24 bg-gradient-to-br from-white via-[#FBF9F4] to-[#F2EFE8] border-b border-gray-100">
        <!-- Background Decorative Circles (reflecting logo shapes) -->
        <div class="absolute top-0 right-0 w-96 h-96 rounded-full bg-[var(--secondary)]/10 -mr-20 -mt-20 blur-3xl pointer-events-none"></div>
        <div class="absolute bottom-0 left-0 w-80 h-80 rounded-full bg-[var(--coral)]/5 -ml-20 -mb-20 blur-3xl pointer-events-none"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
                <!-- Hero Left Content -->
                <div class="lg:col-span-7 space-y-6 text-center lg:text-left">
                    <div class="inline-flex items-center space-x-2 bg-[var(--secondary)]/15 text-[var(--primary)] px-4 py-1.5 rounded-full text-xs font-extrabold uppercase tracking-widest font-montserrat">
                        <span class="w-2.5 h-2.5 rounded-full bg-[var(--coral)] inline-block"></span>
                        <span>BIENVENIDOS A QUITO 2026</span>
                    </div>

                    <h1 class="text-4xl sm:text-5xl lg:text-6xl font-black text-[var(--primary-dark)] leading-tight font-montserrat">
                        Encuentra aquí la <br class="hidden sm:inline">
                        <span class="text-transparent bg-clip-text bg-gradient-to-r from-[var(--primary)] to-[var(--coral)]">información oficial</span>
                    </h1>

                    <p class="text-base sm:text-lg text-gray-600 max-w-xl mx-auto lg:mx-0 leading-relaxed font-medium">
                        El portal centralizado para delegados, voluntarios e instituciones asociadas al gran evento. Accede directamente a los canales y grupos autorizados de forma segura.
                    </p>

                    <div class="pt-2 flex flex-col sm:flex-row items-center justify-center lg:justify-start gap-4">
                        <x-button variant="primary" href="#accesos" class="shadow-lg shadow-[var(--primary)]/20 min-h-[44px] w-full sm:w-auto">
                            Ver Grupos y Accesos
                            <i data-lucide="arrow-down" class="h-4 w-4 ml-2"></i>
                        </x-button>
                        <x-button variant="outline" href="#contacto" class="min-h-[44px] w-full sm:w-auto">
                            Contacto
                        </x-button>
                    </div>
                </div>

                <!-- Hero Right Illustration / Cards Preview (inspired by the logo) -->
                <div class="lg:col-span-5 relative flex justify-center">
                    <!-- Geometric Logo Wrapper -->
                    <div class="relative w-72 h-72 sm:w-80 sm:h-80 rounded-full border-8 border-[var(--primary)] p-4 flex items-center justify-center bg-white shadow-2xl">
                        <!-- Mountains and Flag lines inspired design -->
                        <div class="absolute inset-0 rounded-full border-4 border-dashed border-[var(--secondary)]/50 scale-105 pointer-events-none"></div>
                        
                        <!-- Logo Image centered with no distortion -->
                        @if($settings['logo'])
                            <img src="{{ $settings['logo'] }}" alt="Logo Quito 2026" class="w-10/12 h-auto object-contain">
                        @else
                            <div class="text-center">
                                <span class="text-4xl font-black text-[var(--primary)] tracking-tight">QUITO</span>
                                <div class="text-2xl font-black text-[var(--coral)] mt-1">2026</div>
                            </div>
                        @endif

                        <!-- Floating decorative elements -->
                        <div class="absolute -top-4 -right-4 bg-[var(--yellow)] text-[var(--primary-dark)] p-3.5 rounded-2xl shadow-lg hover:rotate-12 transition-transform">
                            <i data-lucide="award" class="h-6 w-6"></i>
                        </div>
                        <div class="absolute -bottom-4 -left-4 bg-[var(--coral)] text-white p-3.5 rounded-2xl shadow-lg hover:-rotate-12 transition-transform">
                            <i data-lucide="message-square" class="h-6 w-6"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ANNOUNCEMENTS SECTION -->
    @if(isset($announcements) && $announcements->count() > 0)
        <section class="py-8 bg-amber-50/50 border-b border-amber-100">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="space-y-4">
                    @foreach($announcements as $announcement)
                        <x-alert type="{{ $announcement->type }}" dismissible="true">
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                                <div>
                                    <h4 class="font-extrabold text-[var(--primary-dark)] font-montserrat text-sm">{{ $announcement->title }}</h4>
                                    <p class="text-sm mt-1 leading-relaxed font-semibold">{{ $announcement->content }}</p>
                                </div>
                                @if($announcement->button_url && $announcement->button_text)
                                    <x-button variant="primary" href="{{ $announcement->button_url }}" target="_blank" class="text-xs py-1.5 px-4 min-h-[38px] self-start sm:self-center">
                                        {{ $announcement->button_text }}
                                        <i data-lucide="external-link" class="h-3 w-3 ml-1.5"></i>
                                    </x-button>
                                @endif
                            </div>
                        </x-alert>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <!-- ACCESS SECTIONS & LINK CARDS -->
    <section id="accesos" class="py-16 lg:py-24">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="text-center max-w-2xl mx-auto mb-16">
                <h2 class="text-3xl sm:text-4xl font-extrabold text-[var(--primary-dark)] tracking-tight font-montserrat">
                    Canales de Acceso Oficiales
                </h2>
                <div class="w-16 h-1 bg-[var(--coral)] mx-auto mt-4 rounded-full"></div>
                <p class="text-gray-500 mt-4 font-semibold text-sm">
                    Selecciona la sección correspondiente para unirte a los grupos de coordinación autorizados o descargar documentos.
                </p>
            </div>

            @if(isset($sections) && $sections->count() > 0)
                <div class="space-y-20">
                    @foreach($sections as $section)
                        <div class="space-y-8">
                            <!-- Section Header -->
                            <div class="flex items-center space-x-3 pb-3 border-b-2 border-gray-200">
                                @if($section->icon)
                                    <div class="p-2.5 bg-[var(--secondary)]/15 text-[var(--primary)] rounded-xl">
                                        <i data-lucide="{{ $section->icon }}" class="h-6 w-6"></i>
                                    </div>
                                @endif
                                <div>
                                    <h3 class="text-2xl font-extrabold text-[var(--primary)] font-montserrat leading-none">
                                        {{ $section->title }}
                                    </h3>
                                    @if($section->subtitle)
                                        <p class="text-xs uppercase tracking-wider text-[var(--coral)] font-black mt-1 font-montserrat">{{ $section->subtitle }}</p>
                                    @endif
                                </div>
                            </div>

                            @if($section->description)
                                <p class="text-sm text-gray-500 font-semibold leading-relaxed -mt-4 max-w-3xl">
                                    {{ $section->description }}
                                </p>
                            @endif

                            <!-- Link Cards Grid -->
                            @if($section->links->count() > 0)
                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                                    @foreach($section->links as $link)
                                        @php
                                            $status = $link->getAvailabilityStatus();
                                            $isAvailable = $link->isAvailable();
                                            
                                            // Icon selector based on link type
                                            $icon = 'link';
                                            if($link->link_type === 'whatsapp') $icon = 'message-circle';
                                            elseif($link->link_type === 'form') $icon = 'clipboard-list';
                                            elseif($link->link_type === 'doc') $icon = 'file-text';
                                            elseif($link->link_type === 'map') $icon = 'map';
                                            elseif($link->link_type === 'telegram') $icon = 'send';
                                            elseif($link->link_type === 'mail') $icon = 'mail';
                                            elseif($link->link_type === 'phone') $icon = 'phone';
                                            elseif($link->link_type === 'video') $icon = 'video';
                                            
                                            // Badges colors
                                            $badgeColors = [
                                                'disponible' => 'success',
                                                'programado' => 'info',
                                                'finalizado' => 'danger',
                                                'completo' => 'danger',
                                                'protegido' => 'warning',
                                                'desactivado' => 'danger',
                                            ];
                                        @endphp
                                        
                                        <div class="relative bg-white rounded-2xl border border-gray-100 p-6 shadow-sm hover:shadow-md transition-all duration-200 flex flex-col h-full group border-l-4" style="border-left-color: {{ $link->is_featured ? 'var(--coral)' : 'var(--primary)' }}">
                                            <!-- Featured Tag -->
                                            @if($link->is_featured)
                                                <div class="absolute top-0 right-6 -translate-y-1/2 bg-[var(--coral)] text-white text-[10px] font-black uppercase tracking-widest px-3 py-1 rounded-full font-montserrat">
                                                    Destacado
                                                </div>
                                            @endif

                                            <!-- Card Header -->
                                            <div class="flex items-start justify-between gap-4 mb-4">
                                                <div class="p-3 rounded-xl {{ $link->is_featured ? 'bg-[var(--coral)]/10 text-[var(--coral)]' : 'bg-[var(--primary)]/10 text-[var(--primary)]' }}">
                                                    <i data-lucide="{{ $icon }}" class="h-6 w-6"></i>
                                                </div>
                                                <x-badge variant="{{ $badgeColors[$status] ?? 'info' }}">
                                                    {{ $status }}
                                                </x-badge>
                                            </div>

                                            <!-- Card Info -->
                                            <div class="flex-grow space-y-2">
                                                <h4 class="text-base font-extrabold text-[var(--primary-dark)] font-montserrat leading-snug">
                                                    {{ $link->title }}
                                                </h4>
                                                @if($link->description)
                                                    <p class="text-xs text-gray-500 font-semibold leading-relaxed line-clamp-3">
                                                        {{ $link->description }}
                                                    </p>
                                                @endif
                                            </div>

                                            <!-- Availability limits label -->
                                            @if($link->max_clicks && $status === 'disponible')
                                                <div class="text-[10px] font-bold text-gray-400 mt-3 flex items-center">
                                                    <i data-lucide="users" class="h-3 w-3 mr-1"></i>
                                                    Cupos restantes: {{ max(0, $link->max_clicks - $link->clicks_count) }}
                                                </div>
                                            @endif

                                            <!-- Card Footer Action -->
                                            <div class="pt-6 mt-auto">
                                                @if($isAvailable)
                                                    <x-button 
                                                        variant="{{ $link->is_featured ? 'secondary' : 'primary' }}" 
                                                        href="{{ route('public.access', $link->slug) }}" 
                                                        target="{{ $link->open_new_tab ? '_blank' : '_self' }}"
                                                        class="w-full text-xs"
                                                    >
                                                        <span>{{ $link->button_text }}</span>
                                                        <i data-lucide="arrow-right" class="h-3.5 w-3.5 ml-2 group-hover:translate-x-1 transition-transform"></i>
                                                    </x-button>
                                                @else
                                                    <button type="button" disabled class="w-full min-h-[44px] py-2.5 px-4 bg-gray-100 text-gray-400 font-bold text-xs rounded-xl cursor-not-allowed border border-gray-200">
                                                        {{ $link->status_label ?: 'Enlace No Disponible' }}
                                                    </button>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="p-8 text-center bg-white border border-gray-100 rounded-2xl">
                                    <i data-lucide="alert-circle" class="h-10 w-10 text-gray-300 mx-auto mb-2"></i>
                                    <p class="text-sm font-semibold text-gray-400">No hay enlaces activos en esta sección en este momento.</p>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            @else
                <!-- Empty State -->
                <div class="max-w-md mx-auto text-center py-16 bg-white border border-gray-100 rounded-3xl p-8 shadow-sm">
                    <div class="p-4 bg-gray-50 text-gray-400 rounded-full inline-block mb-4">
                        <i data-lucide="folder-open" class="h-12 w-12"></i>
                    </div>
                    <h3 class="text-lg font-bold text-[var(--primary)] font-montserrat">No se encontraron secciones</h3>
                    <p class="text-sm text-gray-400 mt-2 font-medium">El administrador no ha publicado ninguna sección pública todavía.</p>
                </div>
            @endif

        </div>
    </section>

    <!-- FAQS SECTION -->
    <section id="faqs" class="py-16 lg:py-24 bg-white/40 border-t border-b border-gray-100">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl font-extrabold text-[var(--primary-dark)] font-montserrat tracking-tight">
                    Preguntas Frecuentes
                </h2>
                <div class="w-12 h-1 bg-[var(--coral)] mx-auto mt-4 rounded-full"></div>
            </div>

            @if(isset($faqs) && $faqs->count() > 0)
                <div class="space-y-4">
                    @foreach($faqs as $faq)
                        <x-accordion id="faq-{{ $faq->id }}" title="{{ $faq->question }}">
                            {!! nl2br(e($faq->answer)) !!}
                        </x-accordion>
                    @endforeach
                </div>
            @else
                <p class="text-center font-semibold text-gray-400 text-sm py-8">Aún no se han configurado preguntas frecuentes.</p>
            @endif
        </div>
    </section>

    <!-- CONTACT SECTION -->
    <section id="contacto" class="py-16 lg:py-24">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="bg-gradient-to-br from-[var(--primary-dark)] to-[var(--primary)] rounded-3xl text-white shadow-xl overflow-hidden">
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 p-8 sm:p-12 lg:p-16 items-center">
                    
                    <div class="lg:col-span-7 space-y-6">
                        <span class="text-xs font-black uppercase tracking-widest text-[var(--secondary)] font-montserrat">Canales de Ayuda</span>
                        <h3 class="text-3xl sm:text-4xl font-extrabold font-montserrat leading-tight">
                            ¿Necesitas asistencia adicional para Quito 2026?
                        </h3>
                        <p class="text-gray-300 text-sm leading-relaxed max-w-lg font-medium">
                            Si tienes problemas de credenciales, no puedes acceder a un grupo de WhatsApp, o requieres soporte logístico inmediato, ponte en contacto directo con nuestro centro de control técnico.
                        </p>

                        <div class="pt-4 flex flex-wrap gap-4">
                            @if($settings['contact_whatsapp'])
                                <x-button variant="secondary" href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $settings['contact_whatsapp']) }}" target="_blank" class="min-h-[44px] w-full sm:w-auto">
                                    <i data-lucide="message-square" class="h-4 w-4 mr-2"></i>
                                    Soporte WhatsApp
                                </x-button>
                            @endif
                            @if($settings['contact_email'])
                                <a href="mailto:{{ $settings['contact_email'] }}" class="inline-flex items-center justify-center font-bold text-sm text-white hover:text-[var(--secondary)] transition-colors min-h-[44px] px-6">
                                    <i data-lucide="mail" class="h-4 w-4 mr-2 text-[var(--yellow)]"></i>
                                    Enviar Correo
                                </a>
                            @endif
                        </div>
                    </div>

                    <!-- Visual graphic wrapper (Quito architecture inspired) -->
                    <div class="lg:col-span-5 flex justify-center">
                        <div class="relative bg-white/5 border border-white/10 rounded-2xl p-6 w-full max-w-sm">
                            <div class="absolute top-2 right-2 flex space-x-1">
                                <span class="w-2.5 h-2.5 rounded-full bg-red-500"></span>
                                <span class="w-2.5 h-2.5 rounded-full bg-yellow-500"></span>
                                <span class="w-2.5 h-2.5 rounded-full bg-green-500"></span>
                            </div>
                            
                            <h4 class="font-extrabold font-montserrat text-sm mb-4 border-b border-white/10 pb-2 text-[var(--secondary)]">Centro de Operaciones</h4>
                            
                            <ul class="space-y-3.5 text-xs text-gray-300">
                                <li class="flex items-center justify-between border-b border-white/5 pb-2">
                                    <span class="font-bold">Horarios:</span>
                                    <span>{{ $settings['contact_hours'] ?: 'No especificado' }}</span>
                                </li>
                                <li class="flex items-start justify-between">
                                    <span class="font-bold mr-2">Sede:</span>
                                    <span class="text-right leading-relaxed max-w-[200px]">{{ $settings['contact_address'] ?: 'Quito, Ecuador' }}</span>
                                </li>
                            </ul>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </section>

@endsection
