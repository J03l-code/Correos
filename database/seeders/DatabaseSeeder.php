<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Section;
use App\Models\Link;
use App\Models\Announcement;
use App\Models\Faq;
use App\Models\NavigationItem;
use App\Models\Setting;
use App\Models\SocialLink;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Truncate existing demo data to avoid duplicates
        \App\Models\NavigationItem::query()->forceDelete();
        \App\Models\SocialLink::query()->delete();
        \App\Models\Announcement::query()->forceDelete();
        \App\Models\Faq::query()->forceDelete();
        \App\Models\LinkClick::query()->delete();
        \App\Models\Link::query()->forceDelete();
        \App\Models\Section::query()->forceDelete();

        // 1. Create Super Admin from env variables or fallback
        $adminName = env('ADMIN_NAME', 'Super Administrador');
        $adminEmail = env('ADMIN_EMAIL', 'admin@quito2026.com');
        $adminPassword = env('ADMIN_PASSWORD', 'admin12345');

        $superAdmin = User::updateOrCreate(
            ['email' => $adminEmail],
            [
                'name' => $adminName,
                'password' => Hash::make($adminPassword),
                'role' => 'superadmin',
                'is_active' => true,
            ]
        );

        // Create a secondary editor user for demo
        User::updateOrCreate(
            ['email' => 'editor@quito2026.com'],
            [
                'name' => 'Juan Editor',
                'password' => Hash::make('editor12345'),
                'role' => 'editor',
                'is_active' => true,
            ]
        );

        // Create a visualizer user for demo
        User::updateOrCreate(
            ['email' => 'stats@quito2026.com'],
            [
                'name' => 'Maria Stats',
                'password' => Hash::make('stats12345'),
                'role' => 'visualizer',
                'is_active' => true,
            ]
        );

        // 2. Default settings
        Setting::set('site_name', 'QUITO 2026');
        Setting::set('site_description', 'Portal oficial de accesos y grupos de QUITO 2026. Encuentra aquí toda la información.');
        Setting::set('footer_text', '© 2026 QUITO. Todos los derechos reservados. Desarrollado para la Municipalidad de Quito.');
        Setting::set('contact_email', 'contacto@quito2026.gob.ec');
        Setting::set('contact_phone', '+593 2 395 2300');
        Setting::set('contact_whatsapp', '+593900000000');
        Setting::set('contact_address', 'Plaza Grande, Venezuela entre Chile y Espejo, Quito, Ecuador');
        Setting::set('contact_hours', 'Lunes a Viernes de 8:00 AM a 5:00 PM');
        Setting::set('click_tracking_enabled', true, 'general', 'boolean');
        Setting::set('maintenance_mode', false, 'general', 'boolean');
        Setting::set('privacy_policy_text', 'En cumplimiento de la Ley de Protección de Datos Personales, informamos que este portal registra las estadísticas de acceso de forma totalmente anonimizada mediante el hash unidireccional de su dirección IP. No solicitamos ni guardamos información que permita su identificación personal al redirigirle a los enlaces.');

        // Default colors (official QUITO 2026)
        Setting::set('primary_color', '#062B63');
        Setting::set('primary_dark_color', '#031D46');
        Setting::set('secondary_color', '#6CCBF2');
        Setting::set('coral_color', '#FF5964');
        Setting::set('yellow_color', '#FFBE26');
        Setting::set('bg_style', 'default');
        Setting::set('button_style', 'rounded-xl');

        // 3. Navigation items
        NavigationItem::create(['label' => 'Inicio', 'url' => '#', 'location' => 'header', 'sort_order' => 1]);
        NavigationItem::create(['label' => 'Accesos', 'url' => '#accesos', 'location' => 'header', 'sort_order' => 2]);
        NavigationItem::create(['label' => 'Preguntas Frecuentes', 'url' => '#faqs', 'location' => 'header', 'sort_order' => 3]);
        NavigationItem::create(['label' => 'Contacto', 'url' => '#contacto', 'location' => 'header', 'sort_order' => 4]);
        
        NavigationItem::create(['label' => 'Términos de Uso', 'url' => '#', 'location' => 'footer', 'sort_order' => 1]);
        NavigationItem::create(['label' => 'Política de Privacidad', 'url' => '/politica-de-privacidad', 'location' => 'footer', 'sort_order' => 2]);

        // 4. Social Links
        SocialLink::create(['platform' => 'facebook', 'label' => 'Facebook', 'url' => 'https://facebook.com/municipioquito', 'icon' => 'facebook', 'sort_order' => 1]);
        SocialLink::create(['platform' => 'twitter', 'label' => 'Twitter/X', 'url' => 'https://x.com/municipioquito', 'icon' => 'twitter', 'sort_order' => 2]);
        SocialLink::create(['platform' => 'instagram', 'label' => 'Instagram', 'url' => 'https://instagram.com/municipioquito', 'icon' => 'instagram', 'sort_order' => 3]);

        // 5. Announcements
        Announcement::create([
            'title' => '¡Aviso Importante para Delegados!',
            'content' => 'Las credenciales oficiales se entregarán únicamente de forma presencial en el Centro de Convenciones Metropolitano de Quito a partir del 15 de Enero. Recuerden llevar su documento de identidad original.',
            'type' => 'warning',
            'button_text' => 'Ver ubicación',
            'button_url' => 'https://maps.google.com',
            'sort_order' => 1,
            'is_active' => true,
        ]);

        // 6. FAQs
        Faq::create([
            'category' => 'General',
            'question' => '¿Qué es el portal QUITO 2026?',
            'answer' => 'Es el portal oficial administrable diseñado para concentrar todos los enlaces oficiales, grupos de coordinación, documentos clave y accesos del evento institucional QUITO 2026. Permite una redirección segura e intermedia.',
            'sort_order' => 1,
            'is_active' => true
        ]);
        Faq::create([
            'category' => 'Seguridad',
            'question' => '¿Por qué algunos enlaces requieren código de acceso?',
            'answer' => 'Para garantizar la seguridad de la información institucional, ciertos grupos de trabajo y canales de comunicación y coordinación están restringidos mediante contraseñas que son asignadas por los directores de área.',
            'sort_order' => 2,
            'is_active' => true
        ]);
        Faq::create([
            'category' => 'WhatsApp',
            'question' => '¿Qué hago si un grupo de WhatsApp indica "Cupo completo"?',
            'answer' => 'El sistema detecta automáticamente cuando un enlace ha alcanzado su límite de clics y muestra un aviso. El administrador actualizará el enlace del grupo en las próximas horas. Puedes contactar a soporte si es urgente.',
            'sort_order' => 3,
            'is_active' => true
        ]);

        // 7. Demo Sections and Links

        // SECCIÓN 1: Accesos principales
        $s1 = Section::create([
            'title' => 'Accesos principales',
            'slug' => 'accesos-principales',
            'subtitle' => 'Contenido de Demostración',
            'description' => 'Encuentra rápidamente los enlaces e información más importante para el evento.',
            'icon' => 'link-2',
            'sort_order' => 1,
            'is_active' => true
        ]);

        Link::create([
            'section_id' => $s1->id,
            'title' => 'Grupo general de WhatsApp',
            'slug' => 'grupo-general',
            'description' => 'Canal oficial de coordinación general para los participantes. Mantente al día con los boletines de prensa y avisos rápidos.',
            'button_text' => 'Unirse al Grupo de WhatsApp',
            'destination_url' => 'https://chat.whatsapp.com/demo-grupo-general',
            'link_type' => 'whatsapp',
            'redirect_mode' => 'interstitial', // Interstitial redirect
            'is_featured' => true,
            'is_active' => true,
            'sort_order' => 1,
        ]);

        Link::create([
            'section_id' => $s1->id,
            'title' => 'Información para voluntarios',
            'slug' => 'info-voluntarios',
            'description' => 'Formulario de registro, requisitos y cronograma de capacitaciones técnicas para todos los voluntarios inscritos en Quito 2026.',
            'button_text' => 'Completar Registro',
            'destination_url' => 'https://forms.google.com/demo-voluntarios',
            'link_type' => 'form',
            'redirect_mode' => 'automatic', // Automatic redirect after 2-3 seconds
            'is_active' => true,
            'sort_order' => 2,
        ]);

        Link::create([
            'section_id' => $s1->id,
            'title' => 'Información para delegados',
            'slug' => 'info-delegados',
            'description' => 'Carpeta digital protegida con el dossier de bienvenida, hoteles recomendados y guías logísticas del evento internacional. (Contraseña de prueba: 1234)',
            'button_text' => 'Acceder a Documentos',
            'destination_url' => 'https://drive.google.com/demo-delegados',
            'link_type' => 'doc',
            'redirect_mode' => 'direct',
            'access_code_hash' => Hash::make('1234'), // Password protected
            'is_active' => true,
            'sort_order' => 3,
        ]);

        Link::create([
            'section_id' => $s1->id,
            'title' => 'Ubicaciones y mapas',
            'slug' => 'ubicaciones-mapas',
            'description' => 'Mapa interactivo con las sedes oficiales de Quito 2026, parqueaderos habilitados y rutas de transporte sugeridas.',
            'button_text' => 'Ver Mapa Interactivo',
            'destination_url' => 'https://maps.google.com/demo-quito-map',
            'link_type' => 'map',
            'redirect_mode' => 'direct', // Direct redirect
            'is_active' => true,
            'sort_order' => 4,
        ]);


        // SECCIÓN 2: Información importante
        $s2 = Section::create([
            'title' => 'Información importante',
            'slug' => 'informacion-importante',
            'subtitle' => 'Contenido de Demostración',
            'description' => 'Dossier de documentos, horarios oficiales de conferencias y preguntas adicionales.',
            'icon' => 'info',
            'sort_order' => 2,
            'is_active' => true
        ]);

        Link::create([
            'section_id' => $s2->id,
            'title' => 'Horarios Oficiales',
            'slug' => 'horarios-oficiales',
            'description' => 'Cronograma minuto a minuto de las ceremonias, ponencias y paneles técnicos de Quito 2026.',
            'button_text' => 'Descargar Cronograma PDF',
            'destination_url' => 'https:// Quito2026.gob.ec/horarios.pdf',
            'link_type' => 'doc',
            'redirect_mode' => 'direct',
            'is_active' => true,
            'sort_order' => 1,
        ]);

        Link::create([
            'section_id' => $s2->id,
            'title' => 'Guía de Recomendaciones',
            'slug' => 'guia-recomendaciones',
            'description' => 'Consejos de seguridad, vestimenta formal recomendada, clima de Quito y uso seguro del transporte municipal.',
            'button_text' => 'Leer Guía',
            'destination_url' => 'https:// Quito2026.gob.ec/recomendaciones',
            'link_type' => 'website',
            'redirect_mode' => 'direct',
            'is_active' => true,
            'sort_order' => 2,
        ]);


        // SECCIÓN 3: Necesitas ayuda
        $s3 = Section::create([
            'title' => 'Necesitas ayuda',
            'slug' => 'necesitas-ayuda',
            'subtitle' => 'Contenido de Demostración',
            'description' => 'Canales de soporte en vivo y reporte de incidentes técnicos o logísticos.',
            'icon' => 'help-circle',
            'sort_order' => 3,
            'is_active' => true
        ]);

        Link::create([
            'section_id' => $s3->id,
            'title' => 'Contactar Soporte Técnico',
            'slug' => 'contactar-soporte',
            'description' => 'Acceso directo a chat de ayuda atendido por nuestro equipo para resolver dudas de acreditación.',
            'button_text' => 'Chat de Soporte',
            'destination_url' => 'https://wa.me/593900000000?text=Hola,%20necesito%20soporte%20con%20Quito%202026',
            'link_type' => 'whatsapp',
            'redirect_mode' => 'direct',
            'is_active' => true,
            'sort_order' => 1,
        ]);
    }
}
