@extends('layouts.public')

@section('title', 'Acceso Protegido')
@section('meta_robots', 'noindex, nofollow')

@section('content')
<div class="max-w-md mx-auto px-4 py-20">
    
    <div class="bg-white rounded-3xl border border-gray-100 p-8 shadow-xl text-center space-y-6">
        
        <!-- Icon lock -->
        <div class="p-4 bg-amber-50 text-[var(--yellow)] rounded-full inline-block">
            <svg class="h-10 w-10 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
            </svg>
        </div>

        <div class="space-y-2">
            <h1 class="text-xl font-extrabold text-[var(--primary-dark)] font-montserrat">
                Acceso Protegido
            </h1>
            <p class="text-xs text-gray-500 font-semibold leading-relaxed">
                El enlace a <span class="font-bold text-[var(--primary)]">{{ $link->title }}</span> está restringido. Por favor, introduce el código de acceso proporcionado por tu coordinador.
            </p>
        </div>

        <!-- Verification Form -->
        <form action="{{ route('public.verify', $link->slug) }}" method="POST" class="space-y-4 text-left">
            @csrf
            
            <x-input 
                label="Código de Acceso" 
                name="code" 
                type="password" 
                placeholder="••••••••" 
                required="true"
                help="El código distingue entre mayúsculas y minúsculas."
            />

            <x-button type="submit" variant="primary" class="w-full text-xs">
                Verificar Código
                <i data-lucide="shield-check" class="h-4 w-4 ml-1.5"></i>
            </x-button>
        </form>

        <div class="pt-2">
            <a href="{{ route('public.index') }}" class="text-xs text-gray-500 font-bold hover:underline focus:outline-none">
                Volver al inicio
            </a>
        </div>

    </div>

</div>
@endsection
