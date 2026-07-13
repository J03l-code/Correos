<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sitio en Mantenimiento - QUITO 2026</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;800;900&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Montserrat', sans-serif; }
    </style>
</head>
<body class="bg-[#F5F1E7] min-h-screen flex items-center justify-center p-6 text-center">
    <div class="max-w-md bg-white border border-gray-100 rounded-3xl p-8 shadow-xl space-y-6">
        <div class="flex justify-center">
            <svg class="h-16 w-16 text-[#FFBE26]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
            </svg>
        </div>
        <h2 class="text-xl font-bold text-[#062B63]">Sitio en Mantenimiento</h2>
        <p class="text-sm text-gray-500 font-semibold leading-relaxed">
            Estamos realizando labores de mantenimiento para ofrecerte una mejor experiencia. El portal estará de vuelta en unos minutos.
        </p>
        @if(isset($settings['contact_email']))
            <div class="pt-4 border-t border-gray-100 text-xs text-gray-400 font-bold">
                Contacto: <a href="mailto:{{ $settings['contact_email'] }}" class="text-[#062B63] hover:underline">{{ $settings['contact_email'] }}</a>
            </div>
        @endif
    </div>
</body>
</html>
