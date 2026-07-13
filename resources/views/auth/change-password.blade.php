@extends('layouts.public')

@section('title', 'Cambiar Contraseña')
@section('meta_robots', 'noindex, nofollow')

@section('content')
<div class="max-w-md mx-auto px-4 py-16">
    
    <div class="bg-white rounded-3xl border border-gray-100 p-8 shadow-xl space-y-6">
        
        <div class="text-center space-y-2">
            <div class="p-3 bg-amber-50 text-amber-600 rounded-full inline-block">
                <i data-lucide="key-round" class="h-8 w-8"></i>
            </div>
            <h1 class="text-xl font-extrabold text-[var(--primary-dark)] font-montserrat">
                Cambiar Contraseña
            </h1>
            <p class="text-xs text-gray-500 font-semibold">
                Actualiza tu contraseña para asegurar tu cuenta administrativa.
            </p>
        </div>

        <form action="{{ route('password.change') }}" method="POST" class="space-y-4">
            @csrf

            <x-input 
                label="Contraseña Actual" 
                name="current_password" 
                type="password" 
                placeholder="••••••••" 
                required="true"
            />

            <x-input 
                label="Nueva Contraseña" 
                name="password" 
                type="password" 
                placeholder="••••••••" 
                required="true"
                help="Debe contener al menos 8 caracteres."
            />

            <x-input 
                label="Confirmar Nueva Contraseña" 
                name="password_confirmation" 
                type="password" 
                placeholder="••••••••" 
                required="true"
            />

            <x-button type="submit" variant="primary" class="w-full text-xs">
                Actualizar Contraseña
                <i data-lucide="save" class="h-4 w-4 ml-1.5"></i>
            </x-button>
        </form>

        <div class="text-center pt-2">
            <a href="{{ route('admin.dashboard') }}" class="text-xs text-gray-500 font-bold hover:underline focus:outline-none">
                Cancelar y volver al panel
            </a>
        </div>

    </div>

</div>
@endsection
