<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class AnnouncementController extends Controller
{
    public function index()
    {
        $announcements = Announcement::orderBy('sort_order')->get();
        $settings = $this->getSettings();
        return view('admin.announcements.index', compact('announcements', 'settings'));
    }

    public function create()
    {
        $settings = $this->getSettings();
        return view('admin.announcements.create', compact('settings'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:150',
            'content' => 'required|string',
            'type' => 'required|string|in:info,success,warning,danger',
            'button_text' => 'nullable|string|max:100',
            'button_url' => 'nullable|string|max:255',
            'starts_at' => 'nullable|date',
            'ends_at' => 'nullable|date|after_or_equal:starts_at',
            'is_active' => 'boolean',
        ]);

        $maxOrder = Announcement::max('sort_order') ?? 0;

        $announcement = Announcement::create([
            'title' => $request->title,
            'content' => $request->content,
            'type' => $request->type,
            'button_text' => $request->button_text,
            'button_url' => $request->button_url,
            'starts_at' => $request->starts_at,
            'ends_at' => $request->ends_at,
            'is_active' => $request->has('is_active'),
            'sort_order' => $maxOrder + 1,
        ]);

        ActivityLog::log('create_announcement', $announcement, "Aviso '{$announcement->title}' creado.");

        return redirect()->route('announcements.index')->with('success', 'Aviso creado con éxito.');
    }

    public function edit(Announcement $announcement)
    {
        $settings = $this->getSettings();
        return view('admin.announcements.edit', compact('announcement', 'settings'));
    }

    public function update(Request $request, Announcement $announcement)
    {
        $request->validate([
            'title' => 'required|string|max:150',
            'content' => 'required|string',
            'type' => 'required|string|in:info,success,warning,danger',
            'button_text' => 'nullable|string|max:100',
            'button_url' => 'nullable|string|max:255',
            'starts_at' => 'nullable|date',
            'ends_at' => 'nullable|date|after_or_equal:starts_at',
            'is_active' => 'boolean',
        ]);

        $oldValues = $announcement->toArray();

        $announcement->update([
            'title' => $request->title,
            'content' => $request->content,
            'type' => $request->type,
            'button_text' => $request->button_text,
            'button_url' => $request->button_url,
            'starts_at' => $request->starts_at,
            'ends_at' => $request->ends_at,
            'is_active' => $request->has('is_active'),
        ]);

        ActivityLog::log('update_announcement', $announcement, "Aviso '{$announcement->title}' actualizado.", $oldValues, $announcement->toArray());

        return redirect()->route('announcements.index')->with('success', 'Aviso actualizado con éxito.');
    }

    public function destroy(Announcement $announcement)
    {
        $announcement->delete();
        ActivityLog::log('delete_announcement', $announcement, "Aviso '{$announcement->title}' eliminado (Soft Delete).");

        return redirect()->route('announcements.index')->with('success', 'Aviso eliminado con éxito.');
    }

    public function toggle(Announcement $announcement)
    {
        $announcement->is_active = !$announcement->is_active;
        $announcement->save();

        ActivityLog::log('toggle_announcement', $announcement, "Aviso '{$announcement->title}' " . ($announcement->is_active ? 'activado' : 'desactivado'));

        return back()->with('success', 'Estado del aviso actualizado.');
    }

    public function moveUp(Announcement $announcement)
    {
        $previous = Announcement::where('sort_order', '<', $announcement->sort_order)
            ->orderBy('sort_order', 'desc')
            ->first();

        if ($previous) {
            $prevOrder = $previous->sort_order;
            $previous->sort_order = $announcement->sort_order;
            $announcement->sort_order = $prevOrder;

            $previous->save();
            $announcement->save();
        }

        return back();
    }

    public function moveDown(Announcement $announcement)
    {
        $next = Announcement::where('sort_order', '>', $announcement->sort_order)
            ->orderBy('sort_order', 'asc')
            ->first();

        if ($next) {
            $nextOrder = $next->sort_order;
            $next->sort_order = $announcement->sort_order;
            $announcement->sort_order = $nextOrder;

            $next->save();
            $announcement->save();
        }

        return back();
    }

    private function getSettings()
    {
        return [
            'logo' => \App\Models\Setting::get('logo'),
            'primary_color' => \App\Models\Setting::get('primary_color', '#062B63'),
        ];
    }
}
