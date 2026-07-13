<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión - QUITO 2026</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700;800;900&display=swap" rel="stylesheet">
    
    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        :root {
            --primary: {{ $settings['primary_color'] }};
            --primary-dark: {{ $settings['primary_dark_color'] }};
            --secondary: {{ $settings['secondary_color'] }};
            --coral: {{ $settings['coral_color'] }};
        }
        body {
            font-family: 'Montserrat', sans-serif;
        }
    </style>
    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>
</head>
<body class="bg-[#F5F1E7] min-h-screen flex items-center justify-center p-4">

    <div class="w-full max-w-md bg-white rounded-3xl border border-gray-100 p-8 shadow-xl space-y-6 relative overflow-hidden">
        <!-- Top graphic bar matching logo style -->
        <div class="absolute top-0 left-0 right-0 h-2 bg-gradient-to-r from-[var(--primary)] via-[var(--secondary)] to-[var(--coral)]"></div>

        <div class="text-center space-y-2">
            <!-- Logo container -->
            <div class="flex justify-center mb-4">
                @if($settings['logo'])
                    <img src="{{ $settings['logo'] }}" alt="Logo Oficial" class="h-16 w-auto object-contain">
                @else
                    <span class="text-2xl font-black tracking-wider text-[var(--primary)] font-montserrat">QUITO <span class="text-[var(--coral)]">2026</span></span>
                @endif
            </div>
            
            <h1 class="text-xl font-extrabold text-[var(--primary-dark)] font-montserrat">
                Panel Administrativo
            </h1>
            <p class="text-xs text-gray-500 font-semibold">
                Introduce tus credenciales para acceder a la gestión de accesos.
            </p>
        </div>

        <form action="{{ route('login') }}" method="POST" class="space-y-4">
            @csrf

            <x-input 
                label="Correo Electrónico" 
                name="email" 
                type="email" 
                placeholder="nombre@ejemplo.com" 
                required="true"
            />

            <x-input 
                label="Contraseña" 
                name="password" 
                type="password" 
                placeholder="••••••••" 
                required="true"
            />

            <!-- Remember Me -->
            <div class="flex items-center justify-between text-xs font-bold text-gray-500">
                <label class="flex items-center cursor-pointer">
                    <input type="checkbox" name="remember" class="rounded border-gray-300 text-[var(--primary)] focus:ring-[var(--primary)] h-4 w-4 mr-2">
                    Recordar sesión
                </label>
            </div>

            <x-button type="submit" variant="primary" class="w-full text-xs">
                Acceder al Panel
                <i data-lucide="log-in" class="h-4 w-4 ml-1.5"></i>
            </x-button>
        </form>

        <div class="text-center pt-2">
            <a href="{{ route('public.index') }}" class="text-xs text-gray-500 font-bold hover:underline focus:outline-none flex items-center justify-center">
                <i data-lucide="arrow-left" class="h-3 w-3 mr-1"></i>
                Volver a la página pública
            </a>
        </div>

    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            lucide.createIcons();
        });
    </script>
</body>
</html>
