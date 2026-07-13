@extends('layouts.public')

@section('title', 'Acceso No Disponible')
@section('meta_robots', 'noindex, nofollow')

@section('content')
<div class="max-w-md mx-auto px-4 py-20">
    
    <div class="bg-white rounded-3xl border border-gray-100 p-8 shadow-xl text-center space-y-6">
        
        <!-- Icon Error -->
        <div class="p-4 bg-rose-50 text-rose-600 rounded-full inline-block">
            <svg class="h-10 w-10 text-rose-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
        </div>

        <div class="space-y-2">
            <h1 class="text-xl font-extrabold text-[var(--primary-dark)] font-montserrat">
                Acceso No Disponible
            </h1>
            <p class="text-xs uppercase tracking-wider font-extrabold text-rose-600 font-montserrat">
                Estado: {{ $status }}
            </p>
            <p class="text-sm text-gray-500 font-semibold leading-relaxed mt-2 bg-gray-50 p-4 rounded-xl border border-gray-100">
                {{ $message }}
            </p>
        </div>

        <!-- Help Info -->
        <div class="text-left text-xs text-gray-500 bg-gray-50/50 p-4 rounded-xl border border-gray-100 space-y-2">
            <p class="font-bold text-[var(--primary)] font-montserrat">¿Qué significa esto?</p>
            @if($status === 'completo')
                <p class="leading-relaxed">Este grupo ha alcanzado su capacidad máxima permitida de accesos. El equipo de administración configurará un nuevo grupo a la brevedad.</p>
            @elseif($status === 'finalizado')
                <p class="leading-relaxed">El período de disponibilidad programado para este enlace ha concluido.</p>
            @elseif($status === 'programado')
                <p class="leading-relaxed">Este enlace está programado para abrirse en una fecha futura. Por favor, regresa más tarde.</p>
            @else
                <p class="leading-relaxed">Este enlace ha sido desactivado temporalmente por los administradores de la plataforma.</p>
            @endif
        </div>

        <div class="pt-4 flex flex-col gap-3">
            @if($link->alternative_url)
                <x-button variant="primary" href="{{ $link->alternative_url }}" class="w-full text-xs">
                    Ir al Enlace Alternativo
                    <i data-lucide="external-link" class="h-4 w-4 ml-1.5"></i>
                </x-button>
            @endif
            <x-button variant="outline" href="{{ route('public.index') }}" class="w-full text-xs">
                Volver al inicio
            </x-button>
        </div>

    </div>

</div>
@endsection
