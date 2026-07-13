<?php

namespace App\Http\Controllers;

use App\Models\Section;
use App\Models\Link;
use App\Models\LinkClick;
use App\Models\Announcement;
use App\Models\Faq;
use App\Models\NavigationItem;
use App\Models\Setting;
use App\Models\SocialLink;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class PublicController extends Controller
{
    public function index()
    {
        $settings = $this->getPublicSettings();

        // If in maintenance mode and not logged as admin
        if ($settings['maintenance_mode'] && !auth()->check()) {
            return response()->view('errors.maintenance', compact('settings'), 503);
        }

        $navItems = NavigationItem::active()->orderBy('sort_order')->get();
        $announcements = Announcement::active()->orderBy('sort_order')->get();
        $sections = Section::active()
            ->with(['links' => function ($query) {
                $query->active();
            }])
            ->orderBy('sort_order')
            ->get();
        $faqs = Faq::active()->orderBy('sort_order')->get();
        $socialLinks = SocialLink::active()->orderBy('sort_order')->get();

        return view('public.index', compact('navItems', 'announcements', 'sections', 'faqs', 'socialLinks', 'settings'));
    }

    public function access(Request $request, $slug)
    {
        $link = Link::where('slug', $slug)->firstOrFail();
        $settings = $this->getPublicSettings();

        // Check if available
        if (!$link->isAvailable()) {
            $status = $link->getAvailabilityStatus();
            $message = $link->status_label ?: 'Este enlace ya no está disponible o ha alcanzado su cupo límite.';
            return response()->view('public.unavailable', compact('link', 'status', 'message', 'settings'), 403);
        }

        // Check if password protected
        if ($link->access_code_hash && !$request->session()->has('verified_link_' . $link->id)) {
            return view('public.protected', compact('link', 'settings'));
        }

        // Process based on redirect mode
        if ($link->redirect_mode === 'direct') {
            $this->registerClick($link, $request);
            return redirect()->away($link->destination_url);
        }

        return view('public.interstitial', compact('link', 'settings'));
    }

    public function verifyPassword(Request $request, $slug)
    {
        $link = Link::where('slug', $slug)->firstOrFail();

        $request->validate([
            'code' => 'required|string',
        ]);

        if ($link->verifyAccessCode($request->code)) {
            $request->session()->put('verified_link_' . $link->id, true);
            return redirect()->route('public.access', $link->slug);
        }

        return back()->withErrors(['code' => 'El código de acceso ingresado es incorrecto.']);
    }

    public function redirect(Request $request, $slug)
    {
        $link = Link::where('slug', $slug)->firstOrFail();

        if (!$link->isAvailable()) {
            return abort(403, 'Enlace no disponible');
        }

        if ($link->access_code_hash && !$request->session()->has('verified_link_' . $link->id)) {
            return abort(403, 'Acceso protegido');
        }

        $this->registerClick($link, $request);

        return response()->json([
            'url' => $link->destination_url
        ]);
    }

    public function privacy()
    {
        $settings = $this->getPublicSettings();
        $navItems = NavigationItem::active()->orderBy('sort_order')->get();
        $privacyText = Setting::get('privacy_policy_text', 'Esta es la política de privacidad por defecto para QUITO 2026. Recopilamos estadísticas de clics de forma completamente anónima.');

        return view('public.privacy', compact('settings', 'navItems', 'privacyText'));
    }

    public function sitemap()
    {
        $links = Link::active()->get();
        $sections = Section::active()->get();

        $content = view('public.sitemap', compact('links', 'sections'))->render();

        return response($content, 200)
            ->header('Content-Type', 'text/xml');
    }

    public function robots()
    {
        $content = "User-agent: *\n";
        $content .= "Disallow: /admin\n";
        $content .= "Disallow: /acceso/\n";
        $content .= "Allow: /\n";
        $content .= "\nSitemap: " . url('/sitemap.xml');

        return response($content, 200)
            ->header('Content-Type', 'text/plain');
    }

    private function getPublicSettings()
    {
        return [
            'site_name' => Setting::get('site_name', 'QUITO 2026'),
            'site_description' => Setting::get('site_description', 'Portal oficial de accesos y grupos de QUITO 2026'),
            'logo' => Setting::get('logo'),
            'favicon' => Setting::get('favicon'),
            'footer_logo' => Setting::get('footer_logo'),
            'contact_email' => Setting::get('contact_email'),
            'contact_phone' => Setting::get('contact_phone'),
            'contact_whatsapp' => Setting::get('contact_whatsapp'),
            'contact_address' => Setting::get('contact_address'),
            'contact_hours' => Setting::get('contact_hours'),
            'footer_text' => Setting::get('footer_text', '© 2026 QUITO. Todos los derechos reservados.'),
            'social_image' => Setting::get('social_image'),
            'maintenance_mode' => Setting::get('maintenance_mode', false),
            'primary_color' => Setting::get('primary_color', '#062B63'),
            'primary_dark_color' => Setting::get('primary_dark_color', '#031D46'),
            'secondary_color' => Setting::get('secondary_color', '#6CCBF2'),
            'coral_color' => Setting::get('coral_color', '#FF5964'),
            'yellow_color' => Setting::get('yellow_color', '#FFBE26'),
            'bg_style' => Setting::get('bg_style', 'default'),
            'button_style' => Setting::get('button_style', 'rounded-xl'),
        ];
    }

    private function registerClick(Link $link, Request $request)
    {
        if (!Setting::get('click_tracking_enabled', true)) {
            return;
        }

        $ip = $request->ip();
        $salt = date('Y-m-d') . config('app.key');
        $anonymizedIp = hash_hmac('sha256', $ip, $salt);

        $ua = $request->userAgent() ?? '';
        $deviceType = 'desktop';
        if (Str::contains($ua, ['Mobi', 'Android', 'iPhone', 'iPad'])) {
            $deviceType = 'mobile';
        }

        LinkClick::create([
            'link_id' => $link->id,
            'clicked_at' => now(),
            'anonymized_ip' => $anonymizedIp,
            'user_agent_summary' => substr($ua, 0, 255),
            'referrer' => substr($request->headers->get('referer', ''), 0, 255),
            'device_type' => $deviceType,
        ]);
    }
}
