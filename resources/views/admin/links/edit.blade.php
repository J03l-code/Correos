@extends('layouts.admin')

@section('title', 'Editar Enlace')

@section('breadcrumbs')
    <span class="text-gray-400">Panel</span> / <a href="{{ route('links.index') }}" class="text-gray-400 hover:text-[var(--primary)]">Enlaces</a> / <span class="text-[var(--primary)]">Editar</span>
@endsection

@section('content')
<div class="max-w-3xl mx-auto">
    
    <div class="mb-6">
        <h1 class="text-2xl font-extrabold text-[var(--primary-dark)] font-montserrat">
            Editar Enlace: {{ $link->title }}
        </h1>
        <p class="text-xs text-gray-500 font-semibold mt-1">
            Modifica la configuración de este enlace y su redirección.
        </p>
    </div>

    <div class="bg-white rounded-2xl border border-gray-100 p-8 shadow-sm">
        <form action="{{ route('links.update', $link->id) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <!-- Section ID -->
                <x-select label="Sección Perteneciente" name="section_id" required="true" help="La sección donde aparecerá el enlace.">
                    @foreach($sections as $sec)
                        <option value="{{ $sec->id }}" {{ $link->section_id === $sec->id ? 'selected' : '' }}>{{ $sec->title }}</option>
                    @endforeach
                </x-select>

                <!-- Link Type -->
                <x-select label="Tipo de Enlace" name="link_type" required="true" help="Define el ícono automático de la tarjeta.">
                    <option value="whatsapp" {{ $link->link_type === 'whatsapp' ? 'selected' : '' }}>Grupo o Canal de WhatsApp</option>
                    <option value="form" {{ $link->link_type === 'form' ? 'selected' : '' }}>Formulario (Google Forms, Typeform, etc.)</option>
                    <option value="doc" {{ $link->link_type === 'doc' ? 'selected' : '' }}>Documento / Carpeta (Drive, PDF, etc.)</option>
                    <option value="map" {{ $link->link_type === 'map' ? 'selected' : '' }}>Mapa / Ubicación (Google Maps)</option>
                    <option value="telegram" {{ $link->link_type === 'telegram' ? 'selected' : '' }}>Telegram</option>
                    <option value="mail" {{ $link->link_type === 'mail' ? 'selected' : '' }}>Correo Electrónico (mailto:)</option>
                    <option value="phone" {{ $link->link_type === 'phone' ? 'selected' : '' }}>Llamada Telefónica (tel:)</option>
                    <option value="video" {{ $link->link_type === 'video' ? 'selected' : '' }}>Video (YouTube, Vimeo, etc.)</option>
                    <option value="website" {{ $link->link_type === 'website' ? 'selected' : '' }}>Página Web Externa</option>
                </x-select>
            </div>

            <x-input 
                label="Título del Enlace" 
                name="title" 
                value="{{ $link->title }}"
                placeholder="Ej. Grupo de Coordinación de Voluntarios" 
                required="true"
            />

            <x-input 
                label="Slug (URL Amigable)" 
                name="slug" 
                value="{{ $link->slug }}"
                placeholder="ej-grupo-voluntarios"
                required="true"
                help="Define la URL de acceso directo."
            />

            <x-textarea 
                label="Descripción del Enlace" 
                name="description" 
                value="{{ $link->description }}"
                placeholder="Indica las instrucciones, normas o una descripción corta para este grupo..." 
                help="Opcional. Se mostrará en la tarjeta pública."
            />

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <x-input 
                    label="Texto del Botón" 
                    name="button_text" 
                    value="{{ $link->button_text }}"
                    placeholder="Ej. Unirse al Grupo" 
                    required="true"
                />

                <x-select label="Modo de Redirección" name="redirect_mode" required="true" help="Cómo accederá el usuario final.">
                    <option value="direct" {{ $link->redirect_mode === 'direct' ? 'selected' : '' }}>Redirección Directa (Sin pantalla intermedia)</option>
                    <option value="interstitial" {{ $link->redirect_mode === 'interstitial' ? 'selected' : '' }}>Página Intermedia (Con advertencia de seguridad)</option>
                    <option value="automatic" {{ $link->redirect_mode === 'automatic' ? 'selected' : '' }}>Redirección Automática (Espera 2-3s con loader)</option>
                </x-select>
            </div>

            <!-- Destination URL -->
            <x-input 
                label="URL de Destino Real (Grupo de WhatsApp)" 
                name="destination_url" 
                value="{{ $link->destination_url }}"
                placeholder="Ej. https://chat.whatsapp.com/..." 
                required="true"
                help="Enlace real al que se enviará al usuario. Protocolos permitidos: https, mailto, tel."
            />

            <!-- Contact Fields -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <x-input 
                    label="Persona Encargada (Responsable del Turno)" 
                    name="contact_name" 
                    value="{{ $link->contact_name }}"
                    placeholder="Ej. Hno. Juan Pérez" 
                    help="Opcional. Nombre del responsable de este turno."
                />

                <x-input 
                    label="Contacto del Encargado (Teléfono o Enlace)" 
                    name="contact_phone" 
                    value="{{ $link->contact_phone }}"
                    placeholder="Ej. +593 99 999 9999 o enlace" 
                    help="Opcional. Número de WhatsApp o teléfono de contacto."
                />
            </div>

            <!-- SECURITY / PROTECTION AND LIMITS -->
            <div class="border-t border-gray-100 pt-6">
                <h3 class="text-sm font-extrabold text-[var(--primary-dark)] font-montserrat mb-4">Límites y Control de Acceso</h3>
                
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                    <x-input 
                        label="Límite de Clics Máximos" 
                        name="max_clicks" 
                        type="number"
                        value="{{ $link->max_clicks }}"
                        placeholder="Ej. 150"
                        help="Opcional. El enlace se marcará como 'cupo completo' al llegar al límite."
                    />

                    <x-input 
                        label="Código de Acceso (Password)" 
                        name="access_code" 
                        type="text"
                        placeholder="Dejar vacío para mantener el actual / Escribir '' para quitarlo"
                        help="Contraseña para proteger el enlace. Dejar vacío si no se desea cambiar."
                    />

                    <x-input 
                        label="Etiqueta de Estado Personalizada" 
                        name="status_label" 
                        value="{{ $link->status_label }}"
                        placeholder="Ej. Cupo Completo"
                        help="Opcional. Mensaje para cuando no esté disponible."
                    />
                </div>

                <div class="mt-4">
                    <x-input 
                        label="Enlace Alternativo (URL)" 
                        name="alternative_url" 
                        value="{{ $link->alternative_url }}"
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
                        value="{{ $link->starts_at ? $link->starts_at->format('Y-m-d\TH:i') : '' }}"
                        help="Opcional. Cuándo se activará el enlace."
                    />

                    <x-input 
                        label="Fecha de Cierre" 
                        name="ends_at" 
                        type="datetime-local" 
                        value="{{ $link->ends_at ? $link->ends_at->format('Y-m-d\TH:i') : '' }}"
                        help="Opcional. Cuándo expirará el enlace automáticamente."
                    />
                </div>
            </div>

            <!-- BEHAVIOR & TOGGLES -->
            <div class="border-t border-gray-100 pt-6 space-y-4">
                <h3 class="text-sm font-extrabold text-[var(--primary-dark)] font-montserrat mb-4">Comportamiento y Visibilidad</h3>
                
                <div class="flex flex-wrap gap-6 text-xs font-semibold text-gray-700">
                    <label class="flex items-center cursor-pointer select-none">
                        <input type="checkbox" name="is_active" value="1" {{ $link->is_active ? 'checked' : '' }} class="rounded border-gray-300 text-[var(--primary)] focus:ring-[var(--primary)] h-4 w-4 mr-2">
                        Publicar inmediatamente (Activo)
                    </label>

                    <label class="flex items-center cursor-pointer select-none">
                        <input type="checkbox" name="is_featured" value="1" {{ $link->is_featured ? 'checked' : '' }} class="rounded border-gray-300 text-[var(--primary)] focus:ring-[var(--primary)] h-4 w-4 mr-2">
                        Destacar tarjeta (Borde coral)
                    </label>

                    <label class="flex items-center cursor-pointer select-none">
                        <input type="checkbox" name="open_new_tab" value="1" {{ $link->open_new_tab ? 'checked' : '' }} class="rounded border-gray-300 text-[var(--primary)] focus:ring-[var(--primary)] h-4 w-4 mr-2">
                        Abrir en pestaña nueva
                    </label>
                </div>
            </div>

            <!-- Confirmation message config -->
            <div x-data="{ requireConf: {{ $link->require_confirmation ? 'true' : 'false' }} }" class="border-t border-gray-100 pt-6 space-y-4">
                <label class="flex items-center cursor-pointer select-none text-xs font-bold text-gray-700">
                    <input type="checkbox" x-model="requireConf" name="require_confirmation" value="1" class="rounded border-gray-300 text-[var(--primary)] focus:ring-[var(--primary)] h-4 w-4 mr-2">
                    Requerir confirmación antes de abrir el enlace
                </label>

                <div x-show="requireConf" class="grid grid-cols-1 sm:grid-cols-2 gap-6" style="display: none;">
                    <x-input 
                        label="Título del Mensaje de Confirmación" 
                        name="confirmation_title" 
                        value="{{ $link->confirmation_title ?: '¿Deseas continuar?' }}"
                        placeholder="Ej. ¿Deseas unirte al grupo?"
                    />
                    <x-textarea 
                        label="Mensaje de Confirmación" 
                        name="confirmation_message" 
                        value="{{ $link->confirmation_message ?: 'Serás redirigido a un enlace externo oficial.' }}"
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
                    Actualizar Enlace
                    <i data-lucide="save" class="h-4 w-4 ml-1.5"></i>
                </x-button>
            </div>
        </form>
    </div>

</div>
@endsection
