<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NavigationItem;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class NavigationController extends Controller
{
    public function index()
    {
        $navigationItems = NavigationItem::orderBy('sort_order')->get();
        $settings = $this->getSettings();
        return view('admin.navigation.index', compact('navigationItems', 'settings'));
    }

    public function create()
    {
        $settings = $this->getSettings();
        return view('admin.navigation.create', compact('settings'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'label' => 'required|string|max:100',
            'url' => 'required|string|max:255',
            'location' => 'required|string|in:header,footer,both',
            'target' => 'required|string|in:_self,_blank',
            'is_active' => 'boolean',
        ]);

        $maxOrder = NavigationItem::max('sort_order') ?? 0;

        $navigationItem = NavigationItem::create([
            'label' => $request->label,
            'url' => $request->url,
            'location' => $request->location,
            'target' => $request->target,
            'is_active' => $request->has('is_active'),
            'sort_order' => $maxOrder + 1,
        ]);

        ActivityLog::log('create_navigation', $navigationItem, "Enlace de navegación '{$navigationItem->label}' creado.");

        return redirect()->route('navigation.index')->with('success', 'Enlace de navegación creado con éxito.');
    }

    public function edit(NavigationItem $navigation)
    {
        $settings = $this->getSettings();
        return view('admin.navigation.edit', compact('navigation', 'settings'));
    }

    public function update(Request $request, NavigationItem $navigation)
    {
        $request->validate([
            'label' => 'required|string|max:100',
            'url' => 'required|string|max:255',
            'location' => 'required|string|in:header,footer,both',
            'target' => 'required|string|in:_self,_blank',
            'is_active' => 'boolean',
        ]);

        $oldValues = $navigation->toArray();

        $navigation->update([
            'label' => $request->label,
            'url' => $request->url,
            'location' => $request->location,
            'target' => $request->target,
            'is_active' => $request->has('is_active'),
        ]);

        ActivityLog::log('update_navigation', $navigation, "Enlace de navegación '{$navigation->label}' actualizado.", $oldValues, $navigation->toArray());

        return redirect()->route('navigation.index')->with('success', 'Enlace de navegación actualizado con éxito.');
    }

    public function destroy(NavigationItem $navigation)
    {
        $navigation->delete();
        ActivityLog::log('delete_navigation', $navigation, "Enlace de navegación '{$navigation->label}' eliminado.");

        return redirect()->route('navigation.index')->with('success', 'Enlace de navegación eliminado con éxito.');
    }

    public function toggle(NavigationItem $navigation)
    {
        $navigation->is_active = !$navigation->is_active;
        $navigation->save();

        ActivityLog::log('toggle_navigation', $navigation, "Enlace de navegación '{$navigation->label}' " . ($navigation->is_active ? 'activado' : 'desactivado'));

        return back()->with('success', 'Visibilidad del enlace de navegación cambiada.');
    }

    public function moveUp(NavigationItem $navigation)
    {
        $previous = NavigationItem::where('sort_order', '<', $navigation->sort_order)
            ->orderBy('sort_order', 'desc')
            ->first();

        if ($previous) {
            $prevOrder = $previous->sort_order;
            $previous->sort_order = $navigation->sort_order;
            $navigation->sort_order = $prevOrder;

            $previous->save();
            $navigation->save();
        }

        return back();
    }

    public function moveDown(NavigationItem $navigation)
    {
        $next = NavigationItem::where('sort_order', '>', $navigation->sort_order)
            ->orderBy('sort_order', 'asc')
            ->first();

        if ($next) {
            $nextOrder = $next->sort_order;
            $next->sort_order = $navigation->sort_order;
            $navigation->sort_order = $nextOrder;

            $next->save();
            $navigation->save();
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
