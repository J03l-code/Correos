<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Media;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MediaController extends Controller
{
    public function index()
    {
        $mediaFiles = Media::orderBy('created_at', 'desc')->paginate(18);
        $settings = $this->getSettings();
        return view('admin.media.index', compact('mediaFiles', 'settings'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:jpg,jpeg,png,webp,svg|max:5120', // Max 5MB
            'alt_text' => 'nullable|string|max:255',
        ]);

        $file = $request->file('file');
        $originalName = $file->getClientOriginalName();
        $mimeType = $file->getClientMimeType();
        $extension = strtolower($file->getClientOriginalExtension());

        // Security check on real mime type
        $allowedMimeTypes = ['image/jpeg', 'image/png', 'image/webp', 'image/svg+xml'];
        if (!in_array($mimeType, $allowedMimeTypes)) {
            return back()->with('error', 'El archivo no tiene un tipo de imagen válido.');
        }

        $safeName = Str::slug(pathinfo($originalName, PATHINFO_FILENAME)) . '_' . time();
        $finalExtension = $extension;
        $tempPath = $file->getRealPath();

        // SVG Sanitization
        if ($extension === 'svg' || $mimeType === 'image/svg+xml') {
            $svgContent = file_get_contents($tempPath);
            // Simple sanitization to remove scripts/onload
            $svgContent = preg_replace('/<script\b[^>]*>(.*?)<\/script>/is', '', $svgContent);
            $svgContent = preg_replace('/on\w+\s*=\s*\"[^\"]*\"/i', '', $svgContent);
            $svgContent = preg_replace('/on\w+\s*=\s*\'[^\']*\'/i', '', $svgContent);
            file_put_contents($tempPath, $svgContent);
        }

        // WebP Conversion for JPG/PNG (if GD is supported)
        $shouldConvert = in_array($extension, ['jpg', 'jpeg', 'png']) && function_exists('imagewebp');
        if ($shouldConvert) {
            $safeName .= '.webp';
            $finalExtension = 'webp';
            $mimeType = 'image/webp';
            
            $targetPath = storage_path('app/public/media/' . $safeName);
            if (!file_exists(storage_path('app/public/media'))) {
                mkdir(storage_path('app/public/media'), 0755, true);
            }

            if ($extension === 'png') {
                $image = imagecreatefrompng($file->getRealPath());
                // preserve transparency
                imagealphablending($image, false);
                imagesavealpha($image, true);
            } else {
                $image = imagecreatefromjpeg($file->getRealPath());
            }

            if ($image) {
                imagewebp($image, $targetPath, 85); // 85% quality
                imagedestroy($image);
                $path = 'media/' . $safeName;
                $size = filesize($targetPath);
            } else {
                // Fallback to direct upload if GD fails
                $path = $file->storeAs('public/media', $safeName . '.' . $extension);
                $path = str_replace('public/', '', $path);
                $size = $file->getSize();
                $finalExtension = $extension;
            }
        } else {
            // Standard direct upload
            $fullName = $safeName . '.' . $extension;
            $path = $file->storeAs('public/media', $fullName);
            $path = str_replace('public/', '', $path);
            $size = $file->getSize();
        }

        $media = Media::create([
            'user_id' => auth()->id(),
            'filename' => $safeName . ($shouldConvert ? '' : '.' . $finalExtension),
            'original_name' => $originalName,
            'mime_type' => $mimeType,
            'path' => $path,
            'size' => $size,
            'alt_text' => $request->alt_text ?: pathinfo($originalName, PATHINFO_FILENAME),
        ]);

        ActivityLog::log('upload_media', $media, "Imagen '{$media->original_name}' subida y guardada como '{$media->filename}'.");

        return back()->with('success', 'Archivo subido y procesado correctamente.');
    }

    public function destroy(Media $medium)
    {
        // Delete file from disk
        if (Storage::disk('public')->exists($medium->path)) {
            Storage::disk('public')->delete($medium->path);
        }

        $medium->delete();
        ActivityLog::log('delete_media', $medium, "Archivo de medios '{$medium->filename}' eliminado.");

        return back()->with('success', 'Archivo de medios eliminado correctamente.');
    }

    public function updateAlt(Request $request, Media $medium)
    {
        $request->validate([
            'alt_text' => 'required|string|max:255',
        ]);

        $medium->alt_text = $request->alt_text;
        $medium->save();

        return back()->with('success', 'Texto alternativo actualizado.');
    }

    private function getSettings()
    {
        return [
            'logo' => \App\Models\Setting::get('logo'),
            'primary_color' => \App\Models\Setting::get('primary_color', '#062B63'),
        ];
    }
}
