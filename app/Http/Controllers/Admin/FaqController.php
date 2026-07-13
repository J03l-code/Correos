<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Faq;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class FaqController extends Controller
{
    public function index()
    {
        $faqs = Faq::orderBy('sort_order')->get();
        $settings = $this->getSettings();
        return view('admin.faqs.index', compact('faqs', 'settings'));
    }

    public function create()
    {
        $settings = $this->getSettings();
        return view('admin.faqs.create', compact('settings'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'question' => 'required|string|max:255',
            'answer' => 'required|string',
            'category' => 'nullable|string|max:100',
            'is_active' => 'boolean',
        ]);

        $maxOrder = Faq::max('sort_order') ?? 0;

        $faq = Faq::create([
            'question' => $request->question,
            'answer' => $request->answer,
            'category' => $request->category,
            'is_active' => $request->has('is_active'),
            'sort_order' => $maxOrder + 1,
        ]);

        ActivityLog::log('create_faq', $faq, "Pregunta frecuente '{$faq->question}' creada.");

        return redirect()->route('faqs.index')->with('success', 'Pregunta frecuente creada con éxito.');
    }

    public function edit(Faq $faq)
    {
        $settings = $this->getSettings();
        return view('admin.faqs.edit', compact('faq', 'settings'));
    }

    public function update(Request $request, Faq $faq)
    {
        $request->validate([
            'question' => 'required|string|max:255',
            'answer' => 'required|string',
            'category' => 'nullable|string|max:100',
            'is_active' => 'boolean',
        ]);

        $oldValues = $faq->toArray();

        $faq->update([
            'question' => $request->question,
            'answer' => $request->answer,
            'category' => $request->category,
            'is_active' => $request->has('is_active'),
        ]);

        ActivityLog::log('update_faq', $faq, "Pregunta frecuente '{$faq->question}' actualizada.", $oldValues, $faq->toArray());

        return redirect()->route('faqs.index')->with('success', 'Pregunta frecuente actualizada con éxito.');
    }

    public function destroy(Faq $faq)
    {
        $faq->delete();
        ActivityLog::log('delete_faq', $faq, "Pregunta frecuente '{$faq->question}' eliminada.");

        return redirect()->route('faqs.index')->with('success', 'Pregunta frecuente eliminada con éxito.');
    }

    public function toggle(Faq $faq)
    {
        $faq->is_active = !$faq->is_active;
        $faq->save();

        ActivityLog::log('toggle_faq', $faq, "Pregunta frecuente '{$faq->question}' " . ($faq->is_active ? 'activada' : 'desactivada'));

        return back()->with('success', 'Visibilidad de la pregunta frecuente cambiada.');
    }

    public function moveUp(Faq $faq)
    {
        $previous = Faq::where('sort_order', '<', $faq->sort_order)
            ->orderBy('sort_order', 'desc')
            ->first();

        if ($previous) {
            $prevOrder = $previous->sort_order;
            $previous->sort_order = $faq->sort_order;
            $faq->sort_order = $prevOrder;

            $previous->save();
            $faq->save();
        }

        return back();
    }

    public function moveDown(Faq $faq)
    {
        $next = Faq::where('sort_order', '>', $faq->sort_order)
            ->orderBy('sort_order', 'asc')
            ->first();

        if ($next) {
            $nextOrder = $next->sort_order;
            $next->sort_order = $faq->sort_order;
            $faq->sort_order = $nextOrder;

            $next->save();
            $faq->save();
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
