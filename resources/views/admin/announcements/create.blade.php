@extends('layouts.admin')

@section('title', 'Nuevo Aviso')

@section('breadcrumbs')
    <span class="text-gray-400">Panel</span> / <a href="{{ route('announcements.index') }}" class="text-gray-400 hover:text-[var(--primary)]">Avisos</a> / <span class="text-[var(--primary)]">Crear</span>
@endsection

@section('content')
<div class="max-w-2xl mx-auto">
    
    <div class="mb-6">
        <h1 class="text-2xl font-extrabold text-[var(--primary-dark)] font-montserrat">
            Crear Nuevo Aviso
        </h1>
        <p class="text-xs text-gray-500 font-semibold mt-1">
            Publica un mensaje importante sobre el contenido o incidencias de los accesos.
        </p>
    </div>

    <div class="bg-white rounded-2xl border border-gray-100 p-8 shadow-sm">
        <form action="{{ route('announcements.store') }}" method="POST" class="space-y-6">
            @csrf

            <x-input 
                label="Título del Aviso" 
                name="title" 
                placeholder="Ej. ¡Cambio de grupo de voluntarios!" 
                required="true"
            />

            <x-textarea 
                label="Contenido del Mensaje" 
                name="content" 
                placeholder="Escribe el texto informativo del aviso..." 
                required="true"
            />

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <!-- Alert type -->
                <x-select label="Tipo de Aviso" name="type" required="true" help="Define el color de la tarjeta pública.">
                    <option value="info">Información (Azul Celeste)</option>
                    <option value="success">Éxito / Favorable (Verde)</option>
                    <option value="warning">Advertencia (Amarillo / Naranja)</option>
                    <option value="danger">Urgente (Rojo)</option>
                </x-select>

                <!-- Button Text -->
                <x-input 
                    label="Texto del Botón (Opcional)" 
                    name="button_text" 
                    placeholder="Ej. Saber más"
                />
            </div>

            <!-- Button URL -->
            <x-input 
                label="Enlace del Botón (URL Opcional)" 
                name="button_url" 
                placeholder="Ej. https://..."
            />

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <x-input 
                    label="Fecha de Inicio" 
                    name="starts_at" 
                    type="datetime-local" 
                />

                <x-input 
                    label="Fecha de Finalización" 
                    name="ends_at" 
                    type="datetime-local" 
                />
            </div>

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
                    Publicar aviso inmediatamente (Visibilidad Activa)
                </label>
            </div>

            <!-- Actions Buttons -->
            <div class="flex items-center justify-end space-x-3 border-t border-gray-100 pt-6">
                <x-button variant="outline" href="{{ route('announcements.index') }}" class="text-xs">
                    Cancelar
                </x-button>
                <x-button type="submit" variant="primary" class="text-xs">
                    Guardar Aviso
                    <i data-lucide="save" class="h-4 w-4 ml-1.5"></i>
                </x-button>
            </div>
        </form>
    </div>

</div>
@endsection
