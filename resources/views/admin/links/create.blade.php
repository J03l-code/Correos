@extends('layouts.admin')

@section('title', 'Nuevo Enlace')

@section('breadcrumbs')
    <span class="text-gray-400">Panel</span> / <a href="{{ route('links.index') }}" class="text-gray-400 hover:text-[var(--primary)]">Enlaces</a> / <span class="text-[var(--primary)]">Crear</span>
@endsection

@section('content')
<div class="max-w-3xl mx-auto">
    
    <div class="mb-6">
        <h1 class="text-2xl font-extrabold text-[var(--primary-dark)] font-montserrat">
            Crear Nuevo Enlace
        </h1>
        <p class="text-xs text-gray-500 font-semibold mt-1">
            Agrega una tarjeta de enlace y configura su redirección intermedia.
        </p>
    </div>

    <div class="bg-white rounded-2xl border border-gray-100 p-8 shadow-sm">
        <form action="{{ route('links.store') }}" method="POST" class="space-y-6">
            @csrf

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <!-- Section ID -->
                <x-select label="Sección Perteneciente" name="section_id" required="true" help="La sección donde aparecerá el enlace.">
                    @foreach($sections as $sec)
                        <option value="{{ $sec->id }}">{{ $sec->title }}</option>
                    @endforeach
                </x-select>

                <!-- Link Type -->
                <x-select label="Tipo de Enlace" name="link_type" required="true" help="Define el ícono automático de la tarjeta.">
                    <option value="whatsapp">Grupo o Canal de WhatsApp</option>
                    <option value="form">Formulario (Google Forms, Typeform, etc.)</option>
                    <option value="doc">Documento / Carpeta (Drive, PDF, etc.)</option>
                    <option value="map">Mapa / Ubicación (Google Maps)</option>
                    <option value="telegram">Telegram</option>
                    <option value="mail">Correo Electrónico (mailto:)</option>
                    <option value="phone">Llamada Telefónica (tel:)</option>
                    <option value="video">Video (YouTube, Vimeo, etc.)</option>
                    <option value="website">Página Web Externa</option>
                </x-select>
            </div>

            <x-input 
                label="Título del Enlace" 
                name="title" 
                placeholder="Ej. Grupo de Coordinación de Voluntarios" 
                required="true"
            />

            <x-input 
                label="Slug (URL Amigable)" 
                name="slug" 
                placeholder="ej-grupo-voluntarios"
                help="Se genera automáticamente a partir del título si se deja vacío."
            />

            <x-textarea 
                label="Descripción del Enlace" 
                name="description" 
                placeholder="Indica las instrucciones, normas o una descripción corta para este grupo..." 
                help="Opcional. Se mostrará en la tarjeta pública."
            />

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <x-input 
                    label="Texto del Botón" 
                    name="button_text" 
                    value="Unirse al Grupo"
                    placeholder="Ej. Unirse al Grupo" 
                    required="true"
                />

                <x-select label="Modo de Redirección" name="redirect_mode" required="true" help="Cómo accederá el usuario final.">
                    <option value="direct">Redirección Directa (Sin pantalla intermedia)</option>
                    <option value="interstitial" selected>Página Intermedia (Con advertencia de seguridad)</option>
                    <option value="automatic">Redirección Automática (Espera 2-3s con loader)</option>
                </x-select>
            </div>

            <!-- Destination URL -->
            <x-input 
                label="URL de Destino Real" 
                name="destination_url" 
                placeholder="Ej. https://chat.whatsapp.com/..." 
                required="true"
                help="Enlace real al que se enviará al usuario. Protocolos permitidos: https, mailto, tel."
            />

            <!-- SECURITY / PROTECTION AND LIMITS -->
            <div class="border-t border-gray-100 pt-6">
                <h3 class="text-sm font-extrabold text-[var(--primary-dark)] font-montserrat mb-4">Límites y Control de Acceso</h3>
                
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                    <x-input 
                        label="Límite de Clics Máximos" 
                        name="max_clicks" 
                        type="number"
                        placeholder="Ej. 150"
                        help="Opcional. El enlace se marcará como 'cupo completo' al llegar al límite."
                    />

                    <x-input 
                        label="Código de Acceso (Password)" 
                        name="access_code" 
                        type="text"
                        placeholder="Ej. 1234"
                        help="Opcional. Contraseña para proteger el enlace."
                    />

                    <x-input 
                        label="Etiqueta de Estado Personalizada" 
                        name="status_label" 
                        placeholder="Ej. Cupo Completo"
                        help="Opcional. Mensaje para cuando no esté disponible."
                    />
                </div>

                <div class="mt-4">
                    <x-input 
                        label="Enlace Alternativo (URL)" 
                        name="alternative_url" 
                        placeholder="Ej. https://chat.whatsapp.com/grupo-backup"
                        help="Opcional. Enlace al que se podrá redirigir cuando el cupo se llene."
                    />
                </div>
            </div>

            <!-- SCHEDULING DATE -->
            <div class="border-t border-gray-100 pt-6">
                <h3 class="text-sm font-extrabold text-[var(--primary-dark)] font-montserrat mb-4">Programación de Disponibilidad</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <x-input 
                        label="Fecha de Apertura" 
                        name="starts_at" 
                        type="datetime-local" 
                        help="Opcional. Cuándo se activará el enlace."
                    />

                    <x-input 
                        label="Fecha de Cierre" 
                        name="ends_at" 
                        type="datetime-local" 
                        help="Opcional. Cuándo expirará el enlace automáticamente."
                    />
                </div>
            </div>

            <!-- BEHAVIOR & TOGGLES -->
            <div class="border-t border-gray-100 pt-6 space-y-4">
                <h3 class="text-sm font-extrabold text-[var(--primary-dark)] font-montserrat mb-4">Comportamiento y Visibilidad</h3>
                
                <div class="flex flex-wrap gap-6 text-xs font-semibold text-gray-700">
                    <label class="flex items-center cursor-pointer select-none">
                        <input type="checkbox" name="is_active" value="1" checked class="rounded border-gray-300 text-[var(--primary)] focus:ring-[var(--primary)] h-4 w-4 mr-2">
                        Publicar inmediatamente (Activo)
                    </label>

                    <label class="flex items-center cursor-pointer select-none">
                        <input type="checkbox" name="is_featured" value="1" class="rounded border-gray-300 text-[var(--primary)] focus:ring-[var(--primary)] h-4 w-4 mr-2">
                        Destacar tarjeta (Borde coral)
                    </label>

                    <label class="flex items-center cursor-pointer select-none">
                        <input type="checkbox" name="open_new_tab" value="1" checked class="rounded border-gray-300 text-[var(--primary)] focus:ring-[var(--primary)] h-4 w-4 mr-2">
                        Abrir en pestaña nueva
                    </label>
                </div>
            </div>

            <!-- Confirmation message config -->
            <div x-data="{ requireConf: false }" class="border-t border-gray-100 pt-6 space-y-4">
                <label class="flex items-center cursor-pointer select-none text-xs font-bold text-gray-700">
                    <input type="checkbox" x-model="requireConf" name="require_confirmation" value="1" class="rounded border-gray-300 text-[var(--primary)] focus:ring-[var(--primary)] h-4 w-4 mr-2">
                    Requerir confirmación antes de abrir el enlace
                </label>

                <div x-show="requireConf" class="grid grid-cols-1 sm:grid-cols-2 gap-6" style="display: none;">
                    <x-input 
                        label="Título del Mensaje de Confirmación" 
                        name="confirmation_title" 
                        value="¿Deseas continuar?"
                        placeholder="Ej. ¿Deseas unirte al grupo?"
                    />
                    <x-textarea 
                        label="Mensaje de Confirmación" 
                        name="confirmation_message" 
                        value="Serás redirigido a un enlace externo oficial."
                        placeholder="Ej. Asegúrate de tener instalado WhatsApp en tu dispositivo..."
                    />
                </div>
            </div>

            <!-- Actions Buttons -->
            <div class="flex items-center justify-end space-x-3 border-t border-gray-100 pt-6">
                <x-button variant="outline" href="{{ route('links.index') }}" class="text-xs">
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
