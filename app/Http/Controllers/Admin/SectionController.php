<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Section;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SectionController extends Controller
{
    public function index()
    {
        $sections = Section::orderBy('sort_order')->get();
        $settings = $this->getSettings();
        return view('admin.sections.index', compact('sections', 'settings'));
    }

    public function create()
    {
        $settings = $this->getSettings();
        return view('admin.sections.create', compact('settings'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:150',
            'slug' => 'nullable|string|max:150|unique:sections,slug',
            'subtitle' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'icon' => 'nullable|string|max:50',
            'starts_at' => 'nullable|date',
            'ends_at' => 'nullable|date|after_or_equal:starts_at',
            'is_active' => 'boolean',
            'style_variant' => 'string|max:50',
        ]);

        $slug = $request->slug ? Str::slug($request->slug) : Str::slug($request->title);
        
        // Ensure slug is unique
        $originalSlug = $slug;
        $counter = 1;
        while (Section::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }

        // Get max order
        $maxOrder = Section::max('sort_order') ?? 0;

        $section = Section::create([
            'title' => $request->title,
            'slug' => $slug,
            'subtitle' => $request->subtitle,
            'description' => $request->description,
            'icon' => $request->icon,
            'starts_at' => $request->starts_at,
            'ends_at' => $request->ends_at,
            'is_active' => $request->has('is_active'),
            'style_variant' => $request->get('style_variant', 'default'),
            'sort_order' => $maxOrder + 1,
        ]);

        ActivityLog::log('create_section', $section, "Sección '{$section->title}' creada.");

        return redirect()->route('sections.index')->with('success', 'Sección creada exitosamente.');
    }

    public function edit(Section $section)
    {
        $settings = $this->getSettings();
        return view('admin.sections.edit', compact('section', 'settings'));
    }

    public function update(Request $request, Section $section)
    {
        $request->validate([
            'title' => 'required|string|max:150',
            'slug' => 'required|string|max:150|unique:sections,slug,' . $section->id,
            'subtitle' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'icon' => 'nullable|string|max:50',
            'starts_at' => 'nullable|date',
            'ends_at' => 'nullable|date|after_or_equal:starts_at',
            'is_active' => 'boolean',
            'style_variant' => 'string|max:50',
        ]);

        $oldValues = $section->toArray();
        
        $section->update([
            'title' => $request->title,
            'slug' => Str::slug($request->slug),
            'subtitle' => $request->subtitle,
            'description' => $request->description,
            'icon' => $request->icon,
            'starts_at' => $request->starts_at,
            'ends_at' => $request->ends_at,
            'is_active' => $request->has('is_active'),
            'style_variant' => $request->get('style_variant', 'default'),
        ]);

        ActivityLog::log('update_section', $section, "Sección '{$section->title}' actualizada.", $oldValues, $section->toArray());

        return redirect()->route('sections.index')->with('success', 'Sección actualizada exitosamente.');
    }

    public function destroy(Section $section)
    {
        if ($section->links()->count() > 0) {
            return back()->with('error', 'No se puede eliminar la sección porque contiene enlaces activos. Por favor, reasigna o elimina los enlaces primero.');
        }

        $section->delete();
        ActivityLog::log('delete_section', $section, "Sección '{$section->title}' eliminada (Soft Delete).");

        return redirect()->route('sections.index')->with('success', 'Sección eliminada exitosamente.');
    }

    public function toggle(Section $section)
    {
        $section->is_active = !$section->is_active;
        $section->save();

        ActivityLog::log('toggle_section', $section, "Sección '{$section->title}' " . ($section->is_active ? 'activada' : 'desactivada'));

        return back()->with('success', 'Visibilidad de sección cambiada.');
    }

    public function moveUp(Section $section)
    {
        $previous = Section::where('sort_order', '<', $section->sort_order)
            ->orderBy('sort_order', 'desc')
            ->first();

        if ($previous) {
            $prevOrder = $previous->sort_order;
            $previous->sort_order = $section->sort_order;
            $section->sort_order = $prevOrder;

            $previous->save();
            $section->save();
        }

        return back();
    }

    public function moveDown(Section $section)
    {
        $next = Section::where('sort_order', '>', $section->sort_order)
            ->orderBy('sort_order', 'asc')
            ->first();

        if ($next) {
            $nextOrder = $next->sort_order;
            $next->sort_order = $section->sort_order;
            $section->sort_order = $nextOrder;

            $next->save();
            $section->save();
        }

        return back();
    }

    public function duplicate(Section $section)
    {
        $newSection = $section->replicate();
        $newSection->title = $section->title . ' (Copia)';
        $newSection->slug = $section->slug . '-copia-' . rand(100, 999);
        $newSection->sort_order = (Section::max('sort_order') ?? 0) + 1;
        $newSection->save();

        ActivityLog::log('duplicate_section', $newSection, "Sección '{$section->title}' duplicada como '{$newSection->title}'.");

        return redirect()->route('sections.index')->with('success', 'Sección duplicada con éxito.');
    }

    private function getSettings()
    {
        return [
            'logo' => \App\Models\Setting::get('logo'),
            'primary_color' => \App\Models\Setting::get('primary_color', '#062B63'),
        ];
    }
}
