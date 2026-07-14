<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Section;
use App\Models\Link;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class LinkController extends Controller
{
    public function index(Request $request)
    {
        $query = Link::with('section')->orderBy('section_id')->orderBy('sort_order');

        $query->when($request->filled('section_id'), function ($q) use ($request) {
            return $q->where('section_id', $request->section_id);
        });

        $query->when($request->filled('link_type'), function ($q) use ($request) {
            return $q->where('link_type', $request->link_type);
        });

        $query->when($request->filled('is_active'), function ($q) use ($request) {
            return $q->where('is_active', $request->boolean('is_active'));
        });

        $links = $query->paginate(20);
        $sections = Section::orderBy('sort_order')->get();
        $settings = $this->getSettings();

        return response()
            ->view('admin.links.index', compact('links', 'sections', 'settings'))
            ->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0, private')
            ->header('Pragma', 'no-cache');
    }

    public function create()
    {
        $sections = Section::orderBy('sort_order')->get();
        $settings = $this->getSettings();
        return view('admin.links.create', compact('sections', 'settings'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'section_id' => 'required|exists:sections,id',
            'title' => 'required|string|max:150',
            'slug' => 'nullable|string|max:150|unique:links,slug',
            'description' => 'nullable|string',
            'button_text' => 'required|string|max:100',
            'destination_url' => 'required|string',
            'link_type' => 'required|string|max:50',
            'redirect_mode' => 'required|string|in:direct,interstitial,automatic',
            'starts_at' => 'nullable|date',
            'ends_at' => 'nullable|date|after_or_equal:starts_at',
            'max_clicks' => 'nullable|integer|min:0',
            'access_code' => 'nullable|string|min:4',
            'status_label' => 'nullable|string|max:100',
            'alternative_url' => 'nullable|string',
            'contact_name' => 'nullable|string|max:150',
            'contact_phone' => 'nullable|string|max:100',
            'day' => 'nullable|string|max:50',
            'zone' => 'nullable|string|max:100',
            'open_new_tab' => 'boolean',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
            'require_confirmation' => 'boolean',
            'confirmation_title' => 'nullable|string|max:150',
            'confirmation_message' => 'nullable|string',
        ]);

        $slug = $request->slug ? Str::slug($request->slug) : Str::slug($request->title);
        
        $originalSlug = $slug;
        $counter = 1;
        while (Link::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }

        $maxOrder = Link::where('section_id', $request->section_id)->max('sort_order') ?? 0;

        $link = new Link([
            'section_id' => $request->section_id,
            'title' => $request->title,
            'slug' => $slug,
            'description' => $request->description,
            'button_text' => $request->button_text,
            'destination_url' => $request->destination_url,
            'link_type' => $request->link_type,
            'redirect_mode' => $request->redirect_mode,
            'starts_at' => $request->starts_at,
            'ends_at' => $request->ends_at,
            'max_clicks' => $request->max_clicks,
            'status_label' => $request->status_label,
            'alternative_url' => $request->alternative_url,
            'contact_name' => $request->contact_name,
            'contact_phone' => $request->contact_phone,
            'day' => $request->day ?: 'General',
            'zone' => $request->zone ?: '',
            'open_new_tab' => $request->has('open_new_tab'),
            'is_featured' => $request->has('is_featured'),
            'is_active' => $request->has('is_active'),
            'require_confirmation' => $request->has('require_confirmation'),
            'confirmation_title' => $request->confirmation_title,
            'confirmation_message' => $request->confirmation_message,
            'sort_order' => $maxOrder + 1,
        ]);

        if ($request->access_code) {
            $link->setAccessCode($request->access_code);
        }

        $link->save();

        ActivityLog::log('create_link', $link, "Enlace '{$link->title}' creado en sección ID {$link->section_id}.");

        return redirect()->route('links.index')->with('success', 'Enlace creado exitosamente.');
    }

    public function edit(Link $link)
    {
        $sections = Section::orderBy('sort_order')->get();
        $settings = $this->getSettings();
        return view('admin.links.edit', compact('link', 'sections', 'settings'));
    }

    public function update(Request $request, Link $link)
    {
        $request->validate([
            'section_id' => 'required|exists:sections,id',
            'title' => 'required|string|max:150',
            'slug' => 'required|string|max:150|unique:links,slug,' . $link->id,
            'description' => 'nullable|string',
            'button_text' => 'required|string|max:100',
            'destination_url' => 'required|string',
            'link_type' => 'required|string|max:50',
            'redirect_mode' => 'required|string|in:direct,interstitial,automatic',
            'starts_at' => 'nullable|date',
            'ends_at' => 'nullable|date|after_or_equal:starts_at',
            'max_clicks' => 'nullable|integer|min:0',
            'access_code' => 'nullable|string',
            'status_label' => 'nullable|string|max:100',
            'alternative_url' => 'nullable|string',
            'contact_name' => 'nullable|string|max:150',
            'contact_phone' => 'nullable|string|max:100',
            'day' => 'nullable|string|max:50',
            'zone' => 'nullable|string|max:100',
            'open_new_tab' => 'boolean',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
            'require_confirmation' => 'boolean',
            'confirmation_title' => 'nullable|string|max:150',
            'confirmation_message' => 'nullable|string',
        ]);

        $oldValues = $link->toArray();

        $link->section_id = $request->section_id;
        $link->title = $request->title;
        $link->slug = Str::slug($request->slug);
        $link->description = $request->description;
        $link->button_text = $request->button_text;
        $link->destination_url = $request->destination_url;
        $link->link_type = $request->link_type;
        $link->redirect_mode = $request->redirect_mode;
        $link->starts_at = $request->starts_at;
        $link->ends_at = $request->ends_at;
        $link->max_clicks = $request->max_clicks;
        $link->status_label = $request->status_label;
        $link->alternative_url = $request->alternative_url;
        $link->contact_name = $request->contact_name;
        $link->contact_phone = $request->contact_phone;
        $link->day = $request->day ?: 'General';
        $link->zone = $request->zone ?: '';
        $link->open_new_tab = $request->has('open_new_tab');
        $link->is_featured = $request->has('is_featured');
        $link->is_active = $request->has('is_active');
        $link->require_confirmation = $request->has('require_confirmation');
        $link->confirmation_title = $request->confirmation_title;
        $link->confirmation_message = $request->confirmation_message;

        if ($request->has('access_code') && $request->access_code !== null) {
            if ($request->access_code === '') {
                $link->access_code_hash = null;
            } else {
                $link->setAccessCode($request->access_code);
            }
        }

        $link->save();

        ActivityLog::log('update_link', $link, "Enlace '{$link->title}' actualizado.", $oldValues, $link->toArray());

        return redirect()->route('links.index')->with('success', 'Enlace actualizado exitosamente.');
    }

    public function destroy(Link $link)
    {
        $link->delete();
        ActivityLog::log('delete_link', $link, "Enlace '{$link->title}' eliminado (Soft Delete).");

        return redirect()->route('links.index')->with('success', 'Enlace eliminado exitosamente.');
    }

    public function toggle(Link $link)
    {
        $link->is_active = !$link->is_active;
        $link->save();

        ActivityLog::log('toggle_link', $link, "Enlace '{$link->title}' " . ($link->is_active ? 'activado' : 'desactivado'));

        return back()->with('success', 'Visibilidad de enlace cambiada.');
    }

    public function duplicate(Link $link)
    {
        $newLink = $link->replicate();
        $newLink->title = $link->title . ' (Copia)';
        $newLink->slug = $link->slug . '-copia-' . rand(100, 999);
        $newLink->sort_order = (Link::where('section_id', $link->section_id)->max('sort_order') ?? 0) + 1;
        $newLink->save();

        ActivityLog::log('duplicate_link', $newLink, "Enlace '{$link->title}' duplicado como '{$newLink->title}'.");

        return redirect()->route('links.index')->with('success', 'Enlace duplicado con éxito.');
    }

    public function moveUp(Link $link)
    {
        $previous = Link::where('section_id', $link->section_id)
            ->where('sort_order', '<', $link->sort_order)
            ->orderBy('sort_order', 'desc')
            ->first();

        if ($previous) {
            $prevOrder = $previous->sort_order;
            $previous->sort_order = $link->sort_order;
            $link->sort_order = $prevOrder;

            $previous->save();
            $link->save();
        }

        return back();
    }

    public function moveDown(Link $link)
    {
        $next = Link::where('section_id', $link->section_id)
            ->where('sort_order', '>', $link->sort_order)
            ->orderBy('sort_order', 'asc')
            ->first();

        if ($next) {
            $nextOrder = $next->sort_order;
            $next->sort_order = $link->sort_order;
            $link->sort_order = $nextOrder;

            $next->save();
            $link->save();
        }

        return back();
    }

    public function showQr(Link $link)
    {
        $settings = $this->getSettings();
        $publicUrl = route('public.access', $link->slug);
        
        return view('admin.links.qr', compact('link', 'publicUrl', 'settings'));
    }

    private function getSettings()
    {
        return [
            'logo' => \App\Models\Setting::get('logo'),
            'primary_color' => \App\Models\Setting::get('primary_color', '#062B63'),
        ];
    }
}
