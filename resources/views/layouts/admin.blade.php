<!DOCTYPE html>
<html lang="es" class="h-full bg-gray-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Administración QUITO 2026</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        :root {
            --primary: {{ $settings['primary_color'] ?? '#062B63' }};
            --primary-dark: {{ $settings['primary_dark_color'] ?? '#031D46' }};
            --secondary: {{ $settings['secondary_color'] ?? '#6CCBF2' }};
            --coral: {{ $settings['coral_color'] ?? '#FF5964' }};
        }
        body {
            font-family: 'Montserrat', sans-serif;
        }
    </style>

    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>
    <!-- Alpine JS -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="h-full flex flex-col md:flex-row text-gray-800 antialiased font-medium">

    <div x-data="{ sidebarOpen: false }" class="flex w-full min-h-screen">
        
        <!-- Mobile menu overlay -->
        <div 
            x-show="sidebarOpen"
            @click="sidebarOpen = false"
            class="fixed inset-0 z-30 bg-gray-900/50 backdrop-blur-sm md:hidden"
            style="display: none;"
        ></div>

        <!-- SIDEBAR -->
        <aside 
            :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
            class="fixed inset-y-0 left-0 z-40 w-64 bg-[var(--primary-dark)] text-white flex flex-col transition-transform duration-300 md:translate-x-0 md:static md:flex-shrink-0 border-r border-white/5"
        >
            <!-- Logo area -->
            <div class="h-16 px-6 border-b border-white/5 flex items-center justify-between">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center space-x-2">
                    @if($settings['logo'])
                        <img src="{{ $settings['logo'] }}" alt="Logo" class="h-9 w-auto object-contain brightness-0 invert">
                    @else
                        <span class="text-lg font-black tracking-wider text-white">QUITO <span class="text-[var(--coral)]">2026</span></span>
                    @endif
                </a>
                <button @click="sidebarOpen = false" class="md:hidden p-1 rounded-lg text-gray-400 hover:text-white">
                    <i data-lucide="x" class="h-6 w-6"></i>
                </button>
            </div>

            <!-- Navigation Links -->
            <nav class="flex-1 px-4 py-6 space-y-1.5 overflow-y-auto">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center px-4 py-2.5 rounded-xl text-xs font-bold transition-all {{ request()->routeIs('admin.dashboard') ? 'bg-[var(--coral)] text-white' : 'text-gray-300 hover:bg-white/5 hover:text-white' }}">
                    <i data-lucide="layout-dashboard" class="h-4.5 w-4.5 mr-3"></i>
                    Resumen
                </a>

                <a href="{{ route('sections.index') }}" class="flex items-center px-4 py-2.5 rounded-xl text-xs font-bold transition-all {{ request()->routeIs('sections.*') ? 'bg-[var(--coral)] text-white' : 'text-gray-300 hover:bg-white/5 hover:text-white' }}">
                    <i data-lucide="folders" class="h-4.5 w-4.5 mr-3"></i>
                    Secciones
                </a>

                <a href="{{ route('links.index') }}" class="flex items-center px-4 py-2.5 rounded-xl text-xs font-bold transition-all {{ request()->routeIs('links.*') ? 'bg-[var(--coral)] text-white' : 'text-gray-300 hover:bg-white/5 hover:text-white' }}">
                    <i data-lucide="link" class="h-4.5 w-4.5 mr-3"></i>
                    Enlaces y Tarjetas
                </a>

                <a href="{{ route('announcements.index') }}" class="flex items-center px-4 py-2.5 rounded-xl text-xs font-bold transition-all {{ request()->routeIs('announcements.*') ? 'bg-[var(--coral)] text-white' : 'text-gray-300 hover:bg-white/5 hover:text-white' }}">
                    <i data-lucide="megaphone" class="h-4.5 w-4.5 mr-3"></i>
                    Avisos Destacados
                </a>

                <a href="{{ route('faqs.index') }}" class="flex items-center px-4 py-2.5 rounded-xl text-xs font-bold transition-all {{ request()->routeIs('faqs.*') ? 'bg-[var(--coral)] text-white' : 'text-gray-300 hover:bg-white/5 hover:text-white' }}">
                    <i data-lucide="help-circle" class="h-4.5 w-4.5 mr-3"></i>
                    Preguntas Frecuentes
                </a>

                <a href="{{ route('navigation.index') }}" class="flex items-center px-4 py-2.5 rounded-xl text-xs font-bold transition-all {{ request()->routeIs('navigation.*') ? 'bg-[var(--coral)] text-white' : 'text-gray-300 hover:bg-white/5 hover:text-white' }}">
                    <i data-lucide="menu" class="h-4.5 w-4.5 mr-3"></i>
                    Navegación
                </a>

                <a href="{{ route('media.index') }}" class="flex items-center px-4 py-2.5 rounded-xl text-xs font-bold transition-all {{ request()->routeIs('media.*') ? 'bg-[var(--coral)] text-white' : 'text-gray-300 hover:bg-white/5 hover:text-white' }}">
                    <i data-lucide="image" class="h-4.5 w-4.5 mr-3"></i>
                    Gestor de Medios
                </a>

                <a href="{{ route('admin.statistics') }}" class="flex items-center px-4 py-2.5 rounded-xl text-xs font-bold transition-all {{ request()->routeIs('admin.statistics') ? 'bg-[var(--coral)] text-white' : 'text-gray-300 hover:bg-white/5 hover:text-white' }}">
                    <i data-lucide="bar-chart-2" class="h-4.5 w-4.5 mr-3"></i>
                    Estadísticas
                </a>

                @if(auth()->user()->role === 'superadmin')
                    <div class="pt-4 border-t border-white/5 mt-4">
                        <span class="px-4 text-[10px] font-extrabold uppercase text-gray-500 tracking-wider">Ajustes del Sistema</span>
                    </div>

                    <a href="{{ route('users.index') }}" class="flex items-center px-4 py-2.5 rounded-xl text-xs font-bold transition-all {{ request()->routeIs('users.*') ? 'bg-[var(--coral)] text-white' : 'text-gray-300 hover:bg-white/5 hover:text-white' }}">
                        <i data-lucide="users" class="h-4.5 w-4.5 mr-3"></i>
                        Usuarios y Permisos
                    </a>

                    <a href="{{ route('admin.settings') }}" class="flex items-center px-4 py-2.5 rounded-xl text-xs font-bold transition-all {{ request()->routeIs('admin.settings') ? 'bg-[var(--coral)] text-white' : 'text-gray-300 hover:bg-white/5 hover:text-white' }}">
                        <i data-lucide="settings" class="h-4.5 w-4.5 mr-3"></i>
                        Configuración General
                    </a>

                    <a href="{{ route('admin.activity') }}" class="flex items-center px-4 py-2.5 rounded-xl text-xs font-bold transition-all {{ request()->routeIs('admin.activity') ? 'bg-[var(--coral)] text-white' : 'text-gray-300 hover:bg-white/5 hover:text-white' }}">
                        <i data-lucide="scroll" class="h-4.5 w-4.5 mr-3"></i>
                        Bitácora de Cambios
                    </a>
                @endif
            </nav>

            <!-- Bottom sidebar profile -->
            <div class="p-4 border-t border-white/5 flex items-center justify-between text-xs text-gray-400">
                <div>
                    <p class="font-bold text-white leading-none mb-1">{{ auth()->user()->name }}</p>
                    <p class="font-semibold text-[9px] uppercase tracking-wider text-[var(--secondary)]">{{ auth()->user()->role }}</p>
                </div>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="p-2 hover:bg-white/5 rounded-xl hover:text-rose-400 focus:outline-none transition-colors" title="Cerrar Sesión">
                        <i data-lucide="log-out" class="h-4 w-4"></i>
                    </button>
                </form>
            </div>
        </aside>

        <!-- MAIN WINDOW -->
        <div class="flex-1 flex flex-col min-w-0">
            <!-- Header bar -->
            <header class="h-16 bg-white border-b border-gray-100 px-6 flex items-center justify-between z-20">
                <div class="flex items-center">
                    <button @click="sidebarOpen = true" class="md:hidden p-2 rounded-lg text-gray-500 hover:bg-gray-100 mr-4 focus:outline-none">
                        <i data-lucide="menu" class="h-6 w-6"></i>
                    </button>
                    <!-- Breadcrumbs / Action -->
                    <div class="text-sm font-bold text-[var(--primary-dark)]">
                        @yield('breadcrumbs')
                    </div>
                </div>

                <div class="flex items-center space-x-4">
                    <a href="{{ route('public.index') }}" target="_blank" class="text-xs font-bold text-gray-500 hover:text-[var(--primary)] transition-all flex items-center">
                        <i data-lucide="external-link" class="h-4 w-4 mr-1"></i>
                        Ver Sitio Público
                    </a>
                    <a href="{{ route('password.change') }}" class="text-xs font-bold text-gray-500 hover:text-[var(--primary)] transition-all flex items-center">
                        <i data-lucide="key" class="h-4 w-4 mr-1"></i>
                        Cambiar Contraseña
                    </a>
                </div>
            </header>

            <!-- Content Container -->
            <main class="flex-1 overflow-y-auto p-6 bg-gray-50/50">
                
                <!-- Alerts / Flashes -->
                @if(session('success'))
                    <x-alert type="success" dismissible="true" class="mb-6 font-semibold">
                        {{ session('success') }}
                    </x-alert>
                @endif
                
                @if(session('error'))
                    <x-alert type="danger" dismissible="true" class="mb-6 font-semibold">
                        {{ session('error') }}
                    </x-alert>
                @endif

                @yield('content')
            </main>
        </div>

    </div>

    <!-- Init icons -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            lucide.createIcons();
        });
    </script>
    
    @yield('scripts')
</body>
</html>
