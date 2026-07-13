@extends('layouts.admin')

@section('title', 'Nueva FAQ')

@section('breadcrumbs')
    <span class="text-gray-400">Panel</span> / <a href="{{ route('faqs.index') }}" class="text-gray-400 hover:text-[var(--primary)]">FAQs</a> / <span class="text-[var(--primary)]">Crear</span>
@endsection

@section('content')
<div class="max-w-2xl mx-auto">
    
    <div class="mb-6">
        <h1 class="text-2xl font-extrabold text-[var(--primary-dark)] font-montserrat">
            Crear Pregunta Frecuente
        </h1>
        <p class="text-xs text-gray-500 font-semibold mt-1">
            Agrega una duda común resuelta sobre los accesos o el evento.
        </p>
    </div>

    <div class="bg-white rounded-2xl border border-gray-100 p-8 shadow-sm">
        <form action="{{ route('faqs.store') }}" method="POST" class="space-y-6">
            @csrf

            <x-input 
                label="Pregunta" 
                name="question" 
                placeholder="Ej. ¿Cómo obtengo el código de acceso?" 
                required="true"
            />

            <x-textarea 
                label="Respuesta" 
                name="answer" 
                placeholder="Escribe la respuesta detallada a la pregunta..." 
                required="true"
            />

            <x-input 
                label="Categoría (Opcional)" 
                name="category" 
                placeholder="Ej. Seguridad, WhatsApp, General" 
                help="Ayuda a clasificar las preguntas en el panel administrativo."
            />

            <!-- Visibilidad Toggle -->
            <div class="flex items-center space-x-3.5 border-t border-gray-100 pt-6">
                <input 
                    type="checkbox" 
                    id="is_active" 
                    name="is_active" 
                    value="1" 
                    checked
                    class="rounded border-gray-300 text-[var(--primary)] focus:ring-[var(--primary)] h-4 w-4"
                >
                <label for="is_active" class="text-sm font-semibold text-gray-700 select-none cursor-pointer">
                    Publicar pregunta inmediatamente (Visibilidad Activa)
                </label>
            </div>

            <!-- Actions Buttons -->
            <div class="flex items-center justify-end space-x-3 border-t border-gray-100 pt-6">
                <x-button variant="outline" href="{{ route('faqs.index') }}" class="text-xs">
                    Cancelar
                </x-button>
                <x-button type="submit" variant="primary" class="text-xs">
                    Guardar FAQ
                    <i data-lucide="save" class="h-4 w-4 ml-1.5"></i>
                </x-button>
            </div>
        </form>
    </div>

</div>
@endsection
