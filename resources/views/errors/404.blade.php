<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Página No Encontrada (404) - QUITO 2026</title>
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
        <h1 class="text-6xl font-black text-[#FF5964] leading-none">404</h1>
        <h2 class="text-xl font-bold text-[#062B63]">Página No Encontrada</h2>
        <p class="text-sm text-gray-500 font-semibold leading-relaxed">
            La página que buscas no existe o ha sido movida temporalmente.
        </p>
        <div>
            <a href="{{ url('/') }}" class="inline-flex items-center justify-center font-bold text-xs text-white bg-[#062B63] hover:bg-[#031D46] px-6 py-3 rounded-xl transition-all cursor-pointer">
                Volver al inicio
            </a>
        </div>
    </div>
</body>
</html>
