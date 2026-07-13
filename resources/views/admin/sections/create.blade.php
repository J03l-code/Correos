@extends('layouts.admin')

@section('title', 'Nueva Sección')

@section('breadcrumbs')
    <span class="text-gray-400">Panel</span> / <a href="{{ route('sections.index') }}" class="text-gray-400 hover:text-[var(--primary)]">Secciones</a> / <span class="text-[var(--primary)]">Crear</span>
@endsection

@section('content')
<div class="max-w-2xl mx-auto">
    
    <div class="mb-6">
        <h1 class="text-2xl font-extrabold text-[var(--primary-dark)] font-montserrat">
            Crear Nueva Sección
        </h1>
        <p class="text-xs text-gray-500 font-semibold mt-1">
            Define una sección para agrupar tus enlaces.
        </p>
    </div>

    <div class="bg-white rounded-2xl border border-gray-100 p-8 shadow-sm">
        <form action="{{ route('sections.store') }}" method="POST" class="space-y-6">
            @csrf

            <x-input 
                label="Título de la Sección" 
                name="title" 
                placeholder="Ej. Grupos de Coordinación" 
                required="true"
                help="El nombre público que verán los usuarios."
            />

            <x-input 
                label="Slug (URL Amigable)" 
                name="slug" 
                placeholder="ej-grupos-coordinacion"
                help="Se genera automáticamente a partir del título si se deja vacío."
            />

            <x-input 
                label="Subtítulo / Etiqueta Superior" 
                name="subtitle" 
                placeholder="Ej. Coordinadores y Delegados" 
                help="Opcional. Se mostrará en letras pequeñas sobre el título."
            />

            <x-textarea 
                label="Descripción" 
                name="description" 
                placeholder="Describe brevemente el contenido de esta sección..." 
                help="Opcional. Explicación corta debajo del título de la sección."
            />

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <!-- Icon picker -->
                <x-input 
                    label="Ícono de Lucide" 
                    name="icon" 
                    placeholder="link-2" 
                    value="link-2"
                    help="Ej: message-circle, file-text, map, info, help-circle."
                />

                <!-- Style variants selector -->
                <x-select 
                    label="Variante de Estilo" 
                    name="style_variant"
                    help="El aspecto visual de la sección pública."
                >
                    <option value="default">Por defecto (Limpio)</option>
                    <option value="boxed">Enmarcado (Borde suave)</option>
                    <option value="featured">Destacado (Fondo color sutil)</option>
                </x-select>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <x-input 
                    label="Fecha de Inicio" 
                    name="starts_at" 
                    type="datetime-local" 
                    help="Opcional. Cuándo comenzará a mostrarse la sección."
                />

                <x-input 
                    label="Fecha de Expiración" 
                    name="ends_at" 
                    type="datetime-local" 
                    help="Opcional. Cuándo se ocultará automáticamente."
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
                    Publicar sección inmediatamente (Visibilidad Activa)
                </label>
            </div>

            <!-- Actions Buttons -->
            <div class="flex items-center justify-end space-x-3 border-t border-gray-100 pt-6">
                <x-button variant="outline" href="{{ route('sections.index') }}" class="text-xs">
                    Cancelar
                </x-button>
                <x-button type="submit" variant="primary" class="text-xs">
                    Guardar Sección
                    <i data-lucide="save" class="h-4 w-4 ml-1.5"></i>
                </x-button>
            </div>
        </form>
    </div>

</div>
@endsection
