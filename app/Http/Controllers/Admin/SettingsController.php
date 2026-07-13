<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Models\SocialLink;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function index()
    {
        $settings = [
            'site_name' => Setting::get('site_name', 'QUITO 2026'),
            'site_description' => Setting::get('site_description', 'Portal oficial de accesos y grupos de QUITO 2026'),
            'logo' => Setting::get('logo'),
            'favicon' => Setting::get('favicon'),
            'footer_logo' => Setting::get('footer_logo'),
            'social_image' => Setting::get('social_image'),
            
            'primary_color' => Setting::get('primary_color', '#062B63'),
            'primary_dark_color' => Setting::get('primary_dark_color', '#031D46'),
            'secondary_color' => Setting::get('secondary_color', '#6CCBF2'),
            'coral_color' => Setting::get('coral_color', '#FF5964'),
            'yellow_color' => Setting::get('yellow_color', '#FFBE26'),
            'bg_style' => Setting::get('bg_style', 'default'),
            'button_style' => Setting::get('button_style', 'rounded-xl'),
            
            'contact_email' => Setting::get('contact_email'),
            'contact_phone' => Setting::get('contact_phone'),
            'contact_whatsapp' => Setting::get('contact_whatsapp'),
            'contact_address' => Setting::get('contact_address'),
            'contact_hours' => Setting::get('contact_hours'),
            
            'footer_text' => Setting::get('footer_text', '© 2026 QUITO. Todos los derechos reservados.'),
            'privacy_policy_text' => Setting::get('privacy_policy_text'),
            'click_tracking_enabled' => Setting::get('click_tracking_enabled', true),
            'maintenance_mode' => Setting::get('maintenance_mode', false),
        ];

        $socialLinks = SocialLink::orderBy('sort_order')->get();
        $sidebarSettings = $this->getSettings();

        return view('admin.settings.index', compact('settings', 'socialLinks', 'sidebarSettings'));
    }

    public function update(Request $request)
    {
        // Only superadmins or editors can change settings (limit sensitive parts to superadmin)
        $isSuper = auth()->user()->role === 'superadmin';

        $rules = [
            'site_name' => 'required|string|max:100',
            'site_description' => 'nullable|string|max:255',
            'contact_email' => 'nullable|email|max:100',
            'contact_phone' => 'nullable|string|max:50',
            'contact_whatsapp' => 'nullable|string|max:50',
            'contact_address' => 'nullable|string|max:200',
            'contact_hours' => 'nullable|string|max:200',
            'footer_text' => 'required|string|max:200',
            'privacy_policy_text' => 'nullable|string',
            'logo' => 'nullable|string',
            'favicon' => 'nullable|string',
            'footer_logo' => 'nullable|string',
            'social_image' => 'nullable|string',
        ];

        if ($isSuper) {
            $rules = array_merge($rules, [
                'primary_color' => 'required|string|size:7',
                'primary_dark_color' => 'required|string|size:7',
                'secondary_color' => 'required|string|size:7',
                'coral_color' => 'required|string|size:7',
                'yellow_color' => 'required|string|size:7',
                'bg_style' => 'required|string|in:default,soft-blue,creme',
                'button_style' => 'required|string|in:rounded-none,rounded,rounded-xl,rounded-full',
                'click_tracking_enabled' => 'boolean',
                'maintenance_mode' => 'boolean',
            ]);
        }

        $request->validate($rules);

        // Update settings
        Setting::set('site_name', $request->site_name);
        Setting::set('site_description', $request->site_description);
        Setting::set('contact_email', $request->contact_email);
        Setting::set('contact_phone', $request->contact_phone);
        Setting::set('contact_whatsapp', $request->contact_whatsapp);
        Setting::set('contact_address', $request->contact_address);
        Setting::set('contact_hours', $request->contact_hours);
        Setting::set('footer_text', $request->footer_text);
        Setting::set('privacy_policy_text', $request->privacy_policy_text);
        
        Setting::set('logo', $request->logo);
        Setting::set('favicon', $request->favicon);
        Setting::set('footer_logo', $request->footer_logo);
        Setting::set('social_image', $request->social_image);

        if ($isSuper) {
            Setting::set('primary_color', $request->primary_color);
            Setting::set('primary_dark_color', $request->primary_dark_color);
            Setting::set('secondary_color', $request->secondary_color);
            Setting::set('coral_color', $request->coral_color);
            Setting::set('yellow_color', $request->yellow_color);
            Setting::set('bg_style', $request->bg_style);
            Setting::set('button_style', $request->button_style);
            Setting::set('click_tracking_enabled', $request->has('click_tracking_enabled'), 'general', 'boolean');
            Setting::set('maintenance_mode', $request->has('maintenance_mode'), 'general', 'boolean');
        }

        ActivityLog::log('update_settings', null, "Ajustes de configuración del sitio actualizados.");

        return redirect()->route('admin.settings')->with('success', 'Configuración actualizada correctamente.');
    }

    public function restoreColors()
    {
        if (auth()->user()->role !== 'superadmin') {
            abort(403);
        }

        Setting::set('primary_color', '#062B63');
        Setting::set('primary_dark_color', '#031D46');
        Setting::set('secondary_color', '#6CCBF2');
        Setting::set('coral_color', '#FF5964');
        Setting::set('yellow_color', '#FFBE26');
        Setting::set('bg_style', 'default');
        Setting::set('button_style', 'rounded-xl');

        ActivityLog::log('restore_settings_colors', null, "Colores oficiales de QUITO 2026 restaurados.");

        return redirect()->route('admin.settings')->with('success', 'Colores oficiales de QUITO 2026 restaurados correctamente.');
    }

    public function storeSocialLink(Request $request)
    {
        $request->validate([
            'platform' => 'required|string|max:50',
            'label' => 'required|string|max:100',
            'url' => 'required|url|max:255',
            'icon' => 'nullable|string|max:50',
        ]);

        $maxOrder = SocialLink::max('sort_order') ?? 0;

        $link = SocialLink::create([
            'platform' => $request->platform,
            'label' => $request->label,
            'url' => $request->url,
            'icon' => $request->icon,
            'sort_order' => $maxOrder + 1,
            'is_active' => true,
        ]);

        ActivityLog::log('create_social_link', $link, "Enlace social para '{$link->platform}' creado.");

        return redirect()->route('admin.settings')->with('success', 'Enlace de red social añadido.');
    }

    public function destroySocialLink(SocialLink $socialLink)
    {
        $socialLink->delete();
        ActivityLog::log('delete_social_link', $socialLink, "Enlace social de '{$socialLink->platform}' eliminado.");

        return redirect()->route('admin.settings')->with('success', 'Enlace de red social eliminado.');
    }

    private function getSettings()
    {
        return [
            'logo' => \App\Models\Setting::get('logo'),
            'primary_color' => \App\Models\Setting::get('primary_color', '#062B63'),
        ];
    }
}
