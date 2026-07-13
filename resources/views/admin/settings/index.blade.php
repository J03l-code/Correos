@extends('layouts.admin')

@section('title', 'Configuración General')

@section('breadcrumbs')
    <span class="text-gray-400">Panel</span> / <span class="text-[var(--primary)]">Configuración</span>
@endsection

@section('content')
<div class="space-y-8">
    
    <div>
        <h1 class="text-2xl font-extrabold text-[var(--primary-dark)] font-montserrat">
            Configuración del Sitio
        </h1>
        <p class="text-xs text-gray-500 font-semibold mt-1">
            Personaliza el aspecto visual, datos de contacto e información institucional del portal.
        </p>
    </div>

    <!-- MAIN SETTINGS FORM -->
    <div class="bg-white rounded-2xl border border-gray-100 p-8 shadow-sm">
        <form action="{{ route('admin.settings') }}" method="POST" class="space-y-8">
            @csrf

            <!-- Section 1: General Info -->
            <div class="space-y-6">
                <h3 class="text-base font-extrabold text-[var(--primary-dark)] font-montserrat border-b border-gray-100 pb-3 flex items-center">
                    <i data-lucide="info" class="h-5 w-5 mr-2 text-[var(--coral)]"></i>
                    Información General
                </h3>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <x-input 
                        label="Nombre del Portal" 
                        name="site_name" 
                        value="{{ $settings['site_name'] }}"
                        placeholder="Ej. QUITO 2026" 
                        required="true"
                    />

                    <x-input 
                        label="Texto del Pie de Página" 
                        name="footer_text" 
                        value="{{ $settings['footer_text'] }}"
                        placeholder="Ej. © 2026 QUITO. Todos los derechos reservados." 
                        required="true"
                    />
                </div>

                <x-textarea 
                    label="Descripción del Portal (Metadescripción SEO)" 
                    name="site_description" 
                    value="{{ $settings['site_description'] }}"
                    placeholder="Portal oficial de accesos y grupos de QUITO 2026..." 
                />

                <x-textarea 
                    label="Texto de la Política de Privacidad (Publica)" 
                    name="privacy_policy_text" 
                    value="{{ $settings['privacy_policy_text'] }}"
                    placeholder="Escribe la política de privacidad oficial..." 
                    rows="6"
                />
            </div>

            <!-- Section 2: Contact Info -->
            <div class="space-y-6">
                <h3 class="text-base font-extrabold text-[var(--primary-dark)] font-montserrat border-b border-gray-100 pb-3 flex items-center">
                    <i data-lucide="phone" class="h-5 w-5 mr-2 text-[var(--coral)]"></i>
                    Datos de Contacto
                </h3>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                    <x-input 
                        label="Correo Electrónico de Contacto" 
                        name="contact_email" 
                        type="email"
                        value="{{ $settings['contact_email'] }}"
                        placeholder="contacto@quito2026.com" 
                    />

                    <x-input 
                        label="Teléfono de Contacto" 
                        name="contact_phone" 
                        value="{{ $settings['contact_phone'] }}"
                        placeholder="+593 2 395 2300" 
                    />

                    <x-input 
                        label="WhatsApp de Soporte" 
                        name="contact_whatsapp" 
                        value="{{ $settings['contact_whatsapp'] }}"
                        placeholder="Ej. +593900000000" 
                    />
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <x-input 
                        label="Dirección Oficial Sede" 
                        name="contact_address" 
                        value="{{ $settings['contact_address'] }}"
                        placeholder="Plaza Grande, Quito, Ecuador" 
                    />

                    <x-input 
                        label="Horario de Atención" 
                        name="contact_hours" 
                        value="{{ $settings['contact_hours'] }}"
                        placeholder="Lunes a Viernes de 8:00 AM a 5:00 PM" 
                    />
                </div>
            </div>

            <!-- Section 3: Logos & Media Links (Text fields for storage paths) -->
            <div class="space-y-6">
                <h3 class="text-base font-extrabold text-[var(--primary-dark)] font-montserrat border-b border-gray-100 pb-3 flex items-center">
                    <i data-lucide="image" class="h-5 w-5 mr-2 text-[var(--coral)]"></i>
                    Logos e Imágenes de Marca
                </h3>
                <p class="text-[10px] text-gray-500 font-semibold -mt-4">Introduce la URL de los archivos subidos mediante el Gestor de Medios.</p>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <x-input 
                        label="URL Logo Principal" 
                        name="logo" 
                        value="{{ $settings['logo'] }}"
                        placeholder="Ej. /storage/media/logo.png" 
                    />

                    <x-input 
                        label="URL Logo del Pie (Footer)" 
                        name="footer_logo" 
                        value="{{ $settings['footer_logo'] }}"
                        placeholder="Ej. /storage/media/logo_footer.png" 
                    />
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <x-input 
                        label="URL Favicon" 
                        name="favicon" 
                        value="{{ $settings['favicon'] }}"
                        placeholder="Ej. /storage/media/favicon.ico" 
                    />

                    <x-input 
                        label="URL Imagen Social (Open Graph)" 
                        name="social_image" 
                        value="{{ $settings['social_image'] }}"
                        placeholder="Ej. /storage/media/social.jpg" 
                    />
                </div>
            </div>

            <!-- Section 4: Identity & Colors (Only Superadmin) -->
            @if(auth()->user()->role === 'superadmin')
                <div class="space-y-6">
                    <div class="flex items-center justify-between border-b border-gray-100 pb-3">
                        <h3 class="text-base font-extrabold text-[var(--primary-dark)] font-montserrat flex items-center">
                            <i data-lucide="palette" class="h-5 w-5 mr-2 text-[var(--coral)]"></i>
                            Identidad Visual y Colores (Avanzado)
                        </h3>
                        <!-- Restore official colors button -->
                        <button 
                            type="submit" 
                            formaction="{{ route('admin.settings.restore_colors') }}"
                            class="text-xs font-bold text-[var(--coral)] hover:underline focus:outline-none flex items-center cursor-pointer"
                        >
                            <i data-lucide="rotate-ccw" class="h-4 w-4 mr-1"></i>
                            Restaurar Colores de Quito 2026
                        </button>
                    </div>

                    <div class="grid grid-cols-2 sm:grid-cols-5 gap-4">
                        <x-input 
                            label="Color Primario" 
                            name="primary_color" 
                            type="color"
                            value="{{ $settings['primary_color'] }}"
                            required="true"
                        />

                        <x-input 
                            label="Primario Oscuro" 
                            name="primary_dark_color" 
                            type="color"
                            value="{{ $settings['primary_dark_color'] }}"
                            required="true"
                        />

                        <x-input 
                            label="Color Secundario" 
                            name="secondary_color" 
                            type="color"
                            value="{{ $settings['secondary_color'] }}"
                            required="true"
                        />

                        <x-input 
                            label="Color Coral" 
                            name="coral_color" 
                            type="color"
                            value="{{ $settings['coral_color'] }}"
                            required="true"
                        />

                        <x-input 
                            label="Color Amarillo" 
                            name="yellow_color" 
                            type="color"
                            value="{{ $settings['yellow_color'] }}"
                            required="true"
                        />
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <x-select label="Estilo del Fondo" name="bg_style" required="true">
                            <option value="default" {{ $settings['bg_style'] === 'default' ? 'selected' : '' }}>Crema Mate (Oficial - #F5F1E7)</option>
                            <option value="creme" {{ $settings['bg_style'] === 'creme' ? 'selected' : '' }}>Crema Claro (#FBF9F4)</option>
                            <option value="soft-blue" {{ $settings['bg_style'] === 'soft-blue' ? 'selected' : '' }}>Azul Sutil (#F0F6FA)</option>
                        </x-select>

                        <x-select label="Estilo de Bordes y Botones" name="button_style" required="true">
                            <option value="rounded-none" {{ $settings['button_style'] === 'rounded-none' ? 'selected' : '' }}>Rectos (Sin bordes)</option>
                            <option value="rounded" {{ $settings['button_style'] === 'rounded' ? 'selected' : '' }}>Suave (Bordes estándar)</option>
                            <option value="rounded-xl" {{ $settings['button_style'] === 'rounded-xl' ? 'selected' : '' }}>Redondeado Premium (Quito 2026)</option>
                            <option value="rounded-full" {{ $settings['button_style'] === 'rounded-full' ? 'selected' : '' }}>Cápsula (Completamente redondo)</option>
                        </x-select>
                    </div>
                </div>

                <!-- Toggles -->
                <div class="border-t border-gray-100 pt-6 space-y-4">
                    <h3 class="text-sm font-extrabold text-[var(--primary-dark)] font-montserrat">Estados del Portal</h3>
                    
                    <div class="flex flex-wrap gap-8 text-xs font-bold text-gray-700">
                        <label class="flex items-center cursor-pointer select-none">
                            <input type="checkbox" name="click_tracking_enabled" value="1" {{ $settings['click_tracking_enabled'] ? 'checked' : '' }} class="rounded border-gray-300 text-[var(--primary)] focus:ring-[var(--primary)] h-4 w-4 mr-2">
                            Habilitar el seguimiento de clics y estadísticas
                        </label>

                        <label class="flex items-center cursor-pointer select-none text-rose-600">
                            <input type="checkbox" name="maintenance_mode" value="1" {{ $settings['maintenance_mode'] ? 'checked' : '' }} class="rounded border-gray-300 text-rose-500 focus:ring-rose-500 h-4 w-4 mr-2">
                            Habilitar Modo de Mantenimiento
                        </label>
                    </div>
                </div>
            @endif

            <!-- Save Buttons -->
            <div class="flex items-center justify-end space-x-3 border-t border-gray-100 pt-6">
                <x-button type="submit" variant="primary" class="text-xs">
                    Guardar Configuración
                    <i data-lucide="save" class="h-4 w-4 ml-1.5"></i>
                </x-button>
            </div>
        </form>
    </div>

    <!-- SOCIAL LINKS MANAGEMENT SUB-BOX (Only visible if sections and general settings saved) -->
    <div class="bg-white rounded-2xl border border-gray-100 p-8 shadow-sm space-y-6">
        <h3 class="text-base font-extrabold text-[var(--primary-dark)] font-montserrat border-b border-gray-100 pb-3 flex items-center">
            <i data-lucide="share-2" class="h-5 w-5 mr-2 text-[var(--coral)]"></i>
            Redes Sociales Oficiales (Footer)
        </h3>

        <!-- Form to Add -->
        <form action="{{ route('admin.settings.social') }}" method="POST" class="grid grid-cols-1 sm:grid-cols-4 gap-4 items-end text-xs font-semibold">
            @csrf
            <div>
                <x-select label="Plataforma" name="platform" required="true">
                    <option value="facebook">Facebook</option>
                    <option value="twitter">Twitter / X</option>
                    <option value="instagram">Instagram</option>
                    <option value="youtube">YouTube</option>
                    <option value="linkedin">LinkedIn</option>
                    <option value="tiktok">TikTok</option>
                </x-select>
            </div>
            <div>
                <x-input label="Etiqueta" name="label" placeholder="Ej. Facebook Oficial" required="true" />
            </div>
            <div>
                <x-input label="URL" name="url" placeholder="https://..." required="true" />
            </div>
            <div>
                <x-select label="Ícono" name="icon" required="true">
                    <option value="facebook">Facebook</option>
                    <option value="twitter">Twitter</option>
                    <option value="instagram">Instagram</option>
                    <option value="youtube">YouTube</option>
                    <option value="linkedin">LinkedIn</option>
                    <option value="link">Otro (Enlace general)</option>
                </x-select>
            </div>
            <div class="sm:col-span-4 flex justify-end pt-2">
                <x-button type="submit" variant="secondary" class="text-xs">
                    <i data-lucide="plus" class="h-4 w-4 mr-1.5"></i>
                    Añadir Red Social
                </x-button>
            </div>
        </form>

        <!-- Social links list -->
        @if($socialLinks->count() > 0)
            <div class="overflow-x-auto border border-gray-100 rounded-xl mt-6">
                <table class="w-full text-left text-xs font-semibold text-gray-600">
                    <thead class="bg-gray-50 border-b border-gray-100 text-[10px] uppercase font-bold text-gray-500">
                        <tr>
                            <th class="px-6 py-3">Plataforma</th>
                            <th class="px-6 py-3">Etiqueta</th>
                            <th class="px-6 py-3">URL</th>
                            <th class="px-6 py-3 text-right">Acción</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($socialLinks as $link)
                            <tr>
                                <td class="px-6 py-3 capitalize font-bold text-[var(--primary-dark)]">
                                    {{ $link->platform }}
                                </td>
                                <td class="px-6 py-3">
                                    {{ $link->label }}
                                </td>
                                <td class="px-6 py-3 font-mono text-gray-400 text-[10px]">
                                    {{ $link->url }}
                                </td>
                                <td class="px-6 py-3 text-right">
                                    <form action="{{ route('admin.settings.social.destroy', $link->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-rose-600 hover:text-rose-800 font-bold focus:outline-none">Eliminar</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

</div>
@endsection
