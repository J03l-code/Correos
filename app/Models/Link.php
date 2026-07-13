<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Support\Facades\Hash;

#[Fillable([
    'section_id', 'title', 'slug', 'description', 'button_text',
    'destination_url', 'link_type', 'redirect_mode', 'icon', 'image_path',
    'style_variant', 'background_color', 'text_color', 'sort_order',
    'is_featured', 'is_active', 'open_new_tab', 'require_confirmation',
    'confirmation_title', 'confirmation_message', 'access_code_hash',
    'max_clicks', 'starts_at', 'ends_at', 'status_label', 'alternative_url'
])]
class Link extends Model
{
    use HasFactory, SoftDeletes;

    protected function casts(): array
    {
        return [
            'section_id' => 'integer',
            'sort_order' => 'integer',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
            'open_new_tab' => 'boolean',
            'require_confirmation' => 'boolean',
            'max_clicks' => 'integer',
            'starts_at' => 'datetime',
            'ends_at' => 'datetime',
        ];
    }

    public function section()
    {
        return $this->belongsTo(Section::class);
    }

    public function clicks()
    {
        return $this->hasMany(LinkClick::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true)
            ->where(function ($q) {
                $q->whereNull('starts_at')->orWhere('starts_at', '<=', now());
            })
            ->where(function ($q) {
                $q->whereNull('ends_at')->orWhere('ends_at', '>=', now());
            });
    }

    public function getClicksCountAttribute()
    {
        return $this->clicks()->count();
    }

    public function isAvailable(): bool
    {
        if (!$this->is_active) {
            return false;
        }

        $now = now();
        if ($this->starts_at && $this->starts_at->isFuture()) {
            return false;
        }

        if ($this->ends_at && $this->ends_at->isPast()) {
            return false;
        }

        if ($this->max_clicks !== null && $this->clicks()->count() >= $this->max_clicks) {
            return false;
        }

        return true;
    }

    public function getAvailabilityStatus(): string
    {
        if (!$this->is_active) {
            return 'desactivado';
        }

        $now = now();
        if ($this->starts_at && $this->starts_at->isFuture()) {
            return 'programado';
        }

        if ($this->ends_at && $this->ends_at->isPast()) {
            return 'finalizado';
        }

        if ($this->max_clicks !== null && $this->clicks()->count() >= $this->max_clicks) {
            return 'completo';
        }

        if ($this->access_code_hash) {
            return 'protegido';
        }

        return 'disponible';
    }

    public function setAccessCode($code)
    {
        $this->access_code_hash = $code ? Hash::make($code) : null;
    }

    public function verifyAccessCode($code): bool
    {
        if (!$this->access_code_hash) {
            return true;
        }
        return Hash::check($code, $this->access_code_hash);
    }
}
