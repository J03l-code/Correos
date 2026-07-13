<?php

namespace App\Http\Controllers;

use App\Models\Section;
use App\Models\Link;
use App\Models\LinkClick;
use App\Models\Announcement;
use App\Models\Faq;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function dashboard()
    {
        // 1. Core counters
        $totalSections = Section::count();
        $totalLinks = Link::count();
        $activeLinks = Link::where('is_active', true)->count();
        $inactiveLinks = $totalLinks - $activeLinks;

        // 2. Click stats
        $totalClicks = LinkClick::count();
        $clicksToday = LinkClick::whereDate('clicked_at', today())->count();
        $clicks7Days = LinkClick::where('clicked_at', '>=', now()->subDays(7))->count();

        // 3. Top links
        $topLinks = Link::withCount('clicks')
            ->orderBy('clicks_count', 'desc')
            ->limit(5)
            ->get();

        // 4. Recent clicks
        $recentClicks = LinkClick::with('link')
            ->orderBy('clicked_at', 'desc')
            ->limit(5)
            ->get();

        // 5. Recent admin actions
        $recentActions = ActivityLog::with('user')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // 6. Warnings (links close to capacity or expired)
        $warnings = [];
        
        // Expired links
        $expiredLinks = Link::where('ends_at', '<', now())->where('is_active', true)->get();
        foreach ($expiredLinks as $l) {
            $warnings[] = [
                'type' => 'danger',
                'message' => "El enlace '{$l->title}' ha vencido el " . $l->ends_at->format('d/m/Y H:i') . ".",
                'link' => route('links.edit', $l->id)
            ];
        }

        // Full links (max clicks reached)
        $fullLinks = Link::whereNotNull('max_clicks')->get()->filter(function ($l) {
            return $l->clicks()->count() >= $l->max_clicks;
        });
        foreach ($fullLinks as $l) {
            $warnings[] = [
                'type' => 'warning',
                'message' => "El enlace '{$l->title}' ha completado su cupo máximo ({$l->max_clicks} clics).",
                'link' => route('links.edit', $l->id)
            ];
        }

        $settings = $this->getSettings();

        return view('admin.dashboard', compact(
            'totalSections', 'totalLinks', 'activeLinks', 'inactiveLinks',
            'totalClicks', 'clicksToday', 'clicks7Days', 'topLinks',
            'recentClicks', 'recentActions', 'warnings', 'settings'
        ));
    }

    public function statistics(Request $request)
    {
        $days = (int)$request->get('days', 7);
        $startDate = now()->subDays($days);

        // Clicks over time (grouped by date)
        $clicksOverTime = LinkClick::select(DB::raw('DATE(clicked_at) as date'), DB::raw('count(*) as count'))
            ->where('clicked_at', '>=', $startDate)
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get();

        // Clicks by link type
        $clicksByType = LinkClick::join('links', 'link_clicks.link_id', '=', 'links.id')
            ->select('links.link_type', DB::raw('count(*) as count'))
            ->groupBy('links.link_type')
            ->get();

        // Clicks by section
        $clicksBySection = LinkClick::join('links', 'link_clicks.link_id', '=', 'links.id')
            ->join('sections', 'links.section_id', '=', 'sections.id')
            ->select('sections.title as section_title', DB::raw('count(*) as count'))
            ->groupBy('section_title')
            ->get();

        // Detailed table of all links with their click counts
        $linksStats = Link::with('section')
            ->withCount('clicks')
            ->orderBy('clicks_count', 'desc')
            ->paginate(15);

        $settings = $this->getSettings();

        return view('admin.statistics', compact('clicksOverTime', 'clicksByType', 'clicksBySection', 'linksStats', 'days', 'settings'));
    }

    public function exportCsv(Request $request)
    {
        $links = Link::with('section')->withCount('clicks')->orderBy('clicks_count', 'desc')->get();

        $callback = function() use ($links) {
            $file = fopen('php://output', 'w');
            // UTF-8 BOM for Excel Spanish compatibility
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            fputcsv($file, ['ID', 'Título', 'Slug', 'Sección', 'Tipo de Enlace', 'URL de Destino', 'Modo de Redirección', 'Total Clics', 'Cupo Máximo', 'Estado']);

            foreach ($links as $link) {
                fputcsv($file, [
                    $link->id,
                    $link->title,
                    $link->slug,
                    $link->section->title ?? 'N/A',
                    $link->link_type,
                    $link->destination_url,
                    $link->redirect_mode,
                    $link->clicks_count,
                    $link->max_clicks ?? 'Ilimitado',
                    $link->getAvailabilityStatus()
                ]);
            }

            fclose($file);
        };

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="estadisticas_quito2026_' . date('Ymd_His') . '.csv"',
        ];

        return response()->stream($callback, 200, $headers);
    }

    public function clearStats(Request $request)
    {
        $request->validate([
            'confirm_text' => 'required|string|in:ELIMINAR'
        ]);

        if (auth()->user()->role !== 'superadmin') {
            abort(403);
        }

        LinkClick::truncate();

        \App\Models\ActivityLog::log('clear_statistics', null, "El administrador eliminó todo el historial de estadísticas.");

        return back()->with('success', 'Historial de estadísticas vaciado correctamente.');
    }

    public function activityLog()
    {
        $logs = ActivityLog::with('user')->orderBy('created_at', 'desc')->paginate(30);
        $settings = $this->getSettings();
        return view('admin.activity', compact('logs', 'settings'));
    }

    private function getSettings()
    {
        return [
            'logo' => \App\Models\Setting::get('logo'),
            'site_name' => \App\Models\Setting::get('site_name', 'QUITO 2026'),
            'primary_color' => \App\Models\Setting::get('primary_color', '#062B63'),
            'primary_dark_color' => \App\Models\Setting::get('primary_dark_color', '#031D46'),
            'secondary_color' => \App\Models\Setting::get('secondary_color', '#6CCBF2'),
            'coral_color' => \App\Models\Setting::get('coral_color', '#FF5964'),
        ];
    }
}
