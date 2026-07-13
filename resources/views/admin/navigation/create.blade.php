@extends('layouts.admin')

@section('title', 'Nuevo Enlace de Menú')

@section('breadcrumbs')
    <span class="text-gray-400">Panel</span> / <a href="{{ route('navigation.index') }}" class="text-gray-400 hover:text-[var(--primary)]">Navegación</a> / <span class="text-[var(--primary)]">Crear</span>
@endsection

@section('content')
<div class="max-w-2xl mx-auto">
    
    <div class="mb-6">
        <h1 class="text-2xl font-extrabold text-[var(--primary-dark)] font-montserrat">
            Crear Enlace de Navegación
        </h1>
        <p class="text-xs text-gray-500 font-semibold mt-1">
            Agrega un enlace personalizado al menú de cabecera o de pie de página.
        </p>
    </div>

    <div class="bg-white rounded-2xl border border-gray-100 p-8 shadow-sm">
        <form action="{{ route('navigation.store') }}" method="POST" class="space-y-6">
            @csrf

            <x-input 
                label="Etiqueta del Menú" 
                name="label" 
                placeholder="Ej. Voluntariado" 
                required="true"
                help="El texto visible en el menú público."
            />

            <x-input 
                label="URL de Destino" 
                name="url" 
                placeholder="Ej. #seccion-voluntarios o https://..." 
                required="true"
                help="Puedes usar anclas (ej. #seccion) o enlaces absolutos."
            />

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <!-- Location -->
                <x-select label="Ubicación del Enlace" name="location" required="true" help="Dónde se renderizará el enlace.">
                    <option value="header">Solo Encabezado (Header)</option>
                    <option value="footer">Solo Pie de Página (Footer)</option>
                    <option value="both">Ambas Ubicaciones</option>
                </x-select>

                <!-- Target window -->
                <x-select label="Destino de Apertura" name="target" required="true" help="Cómo abrirá el navegador el enlace.">
                    <option value="_self">Misma Ventana / Pestaña</option>
                    <option value="_blank">Nueva Pestaña / Ventana</option>
                </x-select>
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
                    Publicar enlace inmediatamente (Visibilidad Activa)
                </label>
            </div>

            <!-- Actions Buttons -->
            <div class="flex items-center justify-end space-x-3 border-t border-gray-100 pt-6">
                <x-button variant="outline" href="{{ route('navigation.index') }}" class="text-xs">
                    Cancelar
                </x-button>
                <x-button type="submit" variant="primary" class="text-xs">
                    Guardar Enlace
                    <i data-lucide="save" class="h-4 w-4 ml-1.5"></i>
                </x-button>
            </div>
        </form>
    </div>

</div>
@endsection
