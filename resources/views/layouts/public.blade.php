<!DOCTYPE html>
<html lang="es" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - {{ $settings['site_name'] }}</title>
    
    <!-- Meta SEO -->
    <meta name="description" content="{{ $settings['site_description'] }}">
    <meta name="robots" content="@yield('meta_robots', 'index, follow')">
    <link rel="canonical" href="{{ url()->current() }}">
    
    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="@yield('title') - {{ $settings['site_name'] }}">
    <meta property="og:description" content="{{ $settings['site_description'] }}">
    @if($settings['social_image'])
        <meta property="og:image" content="{{ $settings['social_image'] }}">
    @endif

    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="{{ url()->current() }}">
    <meta property="twitter:title" content="@yield('title') - {{ $settings['site_name'] }}">
    <meta property="twitter:description" content="{{ $settings['site_description'] }}">
    @if($settings['social_image'])
        <meta property="twitter:image" content="{{ $settings['social_image'] }}">
    @endif

    <link rel="icon" type="image/png" href="/logo.png">
    <link rel="shortcut icon" href="/logo.png" type="image/png">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,400&display=swap" rel="stylesheet">

    <!-- CSS / Tailwind compiled by Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Inline variables derived from settings -->
    <style>
        :root {
            --primary: {{ $settings['primary_color'] }};
            --primary-dark: {{ $settings['primary_dark_color'] }};
            --secondary: {{ $settings['secondary_color'] }};
            --coral: {{ $settings['coral_color'] }};
            --coral-dark: {{ $settings['primary_dark_color'] }};
            --yellow: {{ $settings['yellow_color'] }};
            --crema-bg: #F5F1E7;
            --white-bg: #FFFFFF;
        }

        body {
            font-family: 'Montserrat', sans-serif;
        }

        @media (prefers-reduced-motion: reduce) {
            * {
                animation-duration: 0.01ms !important;
                animation-iteration-count: 1 !important;
                transition-duration: 0.01ms !important;
                scroll-behavior: auto !important;
            }
        }
    </style>

    <!-- Lucide Icons CDN -->
    <script src="https://unpkg.com/lucide@latest"></script>
    <!-- Alpine.js CDN (Fallback if not imported by Vite) -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

    @yield('head')
</head>
<body class="min-h-screen flex flex-col text-gray-800 antialiased font-medium {{ $settings['bg_style'] === 'creme' ? 'bg-[#FBF9F4]' : ($settings['bg_style'] === 'soft-blue' ? 'bg-[#F0F6FA]' : 'bg-[#F5F1E7]') }}">
    
    <!-- Fixed Header -->
    <header 
        x-data="{ mobileMenuOpen: false, scrolled: false }"
        x-init="window.addEventListener('scroll', () => { scrolled = window.scrollY > 20 })"
        class="sticky top-0 z-40 w-full transition-all duration-200"
        :class="scrolled ? 'bg-white/95 backdrop-blur-md shadow-sm border-b border-gray-100 py-3' : 'bg-transparent py-5'"
    >
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between">
                <!-- Logo -->
                <a href="{{ route('public.index') }}" class="flex items-center space-x-3 focus:outline-none focus:ring-2 focus:ring-[var(--primary)] rounded-lg">
                    <img src="/logo.png" alt="Logo Oficial QUITO 2026" class="h-10 w-auto object-contain transition-all duration-200">
                </a>

                <!-- Desktop Navigation -->
                <nav class="hidden md:flex items-center space-x-8">
                    @isset($navItems)
                        @foreach($navItems->where('location', '!=', 'footer') as $item)
                            <a 
                                href="{{ $item->url }}" 
                                target="{{ $item->target }}" 
                                class="text-sm font-bold text-gray-700 hover:text-[var(--primary)] transition-colors duration-150 focus:outline-none focus:ring-2 focus:ring-[var(--primary)] rounded-md px-2 py-1"
                            >
                                {{ $item->label }}
                            </a>
                        @endforeach
                    @endisset

                    <!-- Admin access helper -->
                    @auth
                        <a href="{{ route('admin.dashboard') }}" class="text-sm font-bold bg-[var(--primary)] text-white px-4 py-2 rounded-xl hover:bg-[var(--primary-dark)] transition-all">
                            Ir al Panel
                        </a>
                    @endauth
                </nav>

                <!-- Mobile Menu Button -->
                <div class="md:hidden">
                    <button 
                        @click="mobileMenuOpen = !mobileMenuOpen"
                        type="button" 
                        class="inline-flex items-center justify-center p-2 rounded-lg text-gray-500 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-[var(--primary)] min-h-[44px]"
                        aria-controls="mobile-menu"
                        :aria-expanded="mobileMenuOpen ? 'true' : 'false'"
                    >
                        <span class="sr-only">Abrir menú principal</span>
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path x-show="!mobileMenuOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            <path x-show="mobileMenuOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" style="display: none;" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Navigation Menu -->
        <div 
            x-show="mobileMenuOpen" 
            x-transition:enter="transition ease-out duration-150"
            x-transition:enter-start="opacity-0 -translate-y-2"
            x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="transition ease-in duration-100"
            x-transition:leave-start="opacity-100 translate-y-0"
            x-transition:leave-end="opacity-0 -translate-y-2"
            class="md:hidden bg-white/95 backdrop-blur-md shadow-lg border-b border-gray-100"
            id="mobile-menu"
            style="display: none;"
        >
            <div class="px-2 pt-2 pb-4 space-y-1 sm:px-3">
                @isset($navItems)
                    @foreach($navItems->where('location', '!=', 'footer') as $item)
                        <a 
                            href="{{ $item->url }}" 
                            target="{{ $item->target }}" 
                            class="block px-3 py-3 rounded-lg text-base font-bold text-gray-700 hover:bg-gray-50 hover:text-[var(--primary)] focus:outline-none"
                            @click="mobileMenuOpen = false"
                        >
                            {{ $item->label }}
                        </a>
                    @endforeach
                @endisset
                @auth
                    <a href="{{ route('admin.dashboard') }}" class="block px-3 py-3 rounded-lg text-base font-bold bg-[var(--primary)] text-white text-center">
                        Ir al Panel
                    </a>
                @endauth
            </div>
        </div>
    </header>

    <!-- Main Content Area -->
    <main class="flex-grow">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-[var(--primary-dark)] text-white pt-12 pb-8 mt-auto border-t-4 border-[var(--coral)]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-8">
                <!-- Branding Info -->
                <div class="space-y-4">
                    @if($settings['footer_logo'])
                        <img src="{{ $settings['footer_logo'] }}" alt="Logo Footer QUITO 2026" class="h-14 w-auto object-contain">
                    @elseif($settings['logo'])
                        <img src="{{ $settings['logo'] }}" alt="Logo Footer QUITO 2026" class="h-14 w-auto object-contain brightness-0 invert">
                    @else
                        <span class="text-2xl font-black tracking-wider text-white font-montserrat">QUITO <span class="text-[var(--coral)]">2026</span></span>
                    @endif
                    <p class="text-sm text-gray-300 max-w-xs font-medium leading-relaxed">
                        {{ $settings['site_description'] }}
                    </p>
                </div>

                <!-- Contact Info -->
                <div class="space-y-3">
                    <h3 class="text-lg font-bold text-[var(--secondary)] font-montserrat">Contacto Oficial</h3>
                    <ul class="space-y-2.5 text-sm text-gray-300">
                        @if($settings['contact_phone'])
                            <li class="flex items-center">
                                <i data-lucide="phone" class="h-4 w-4 mr-2 text-[var(--yellow)]"></i>
                                {{ $settings['contact_phone'] }}
                            </li>
                        @endif
                        @if($settings['contact_email'])
                            <li class="flex items-center">
                                <i data-lucide="mail" class="h-4 w-4 mr-2 text-[var(--yellow)]"></i>
                                <a href="mailto:{{ $settings['contact_email'] }}" class="hover:underline focus:outline-none">{{ $settings['contact_email'] }}</a>
                            </li>
                        @endif
                        @if($settings['contact_address'])
                            <li class="flex items-start">
                                <i data-lucide="map-pin" class="h-4 w-4 mr-2 mt-1 text-[var(--yellow)] flex-shrink-0"></i>
                                <span>{{ $settings['contact_address'] }}</span>
                            </li>
                        @endif
                    </ul>
                </div>

                <!-- Footer Navigation / Social -->
                <div class="space-y-4">
                    <h3 class="text-lg font-bold text-[var(--secondary)] font-montserrat">Enlaces Legales</h3>
                    <div class="flex flex-wrap gap-4 text-sm text-gray-300">
                        @isset($navItems)
                            @foreach($navItems->where('location', '!=', 'header') as $item)
                                <a href="{{ $item->url }}" target="{{ $item->target }}" class="hover:text-white hover:underline focus:outline-none">
                                    {{ $item->label }}
                                </a>
                            @endforeach
                        @endisset
                    </div>

                    <!-- Social Icons -->
                    @isset($socialLinks)
                        @if($socialLinks->count() > 0)
                            <div class="pt-2">
                                <h4 class="text-xs font-bold uppercase tracking-wider text-gray-400 mb-2 font-montserrat">Síguenos en Redes</h4>
                                <div class="flex space-x-3.5">
                                    @foreach($socialLinks as $link)
                                        <a href="{{ $link->url }}" target="_blank" class="p-2 bg-white/10 rounded-xl hover:bg-white/20 hover:scale-115 transition-all text-white focus:outline-none focus:ring-2 focus:ring-[var(--secondary)]" aria-label="{{ $link->label }}">
                                            <i data-lucide="{{ $link->icon ?: 'link' }}" class="h-5 w-5"></i>
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    @endisset
                </div>
            </div>

            <!-- Footer Bottom -->
            <div class="border-t border-white/10 pt-6 flex flex-col md:flex-row items-center justify-between text-xs text-gray-400 font-semibold">
                <p>{{ $settings['footer_text'] }}</p>
                <p class="mt-2 md:mt-0">Diseñado con la calidez y fuerza de Quito.</p>
            </div>
        </div>
    </footer>

    <!-- Initialize Lucide Icons -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            lucide.createIcons();
        });
    </script>
    
    @yield('scripts')
</body>
</html>
