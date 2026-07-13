<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Support\Facades\Storage;

#[Fillable(['user_id', 'filename', 'original_name', 'mime_type', 'path', 'size', 'alt_text'])]
class Media extends Model
{
    use HasFactory, SoftDeletes;

    protected function casts(): array
    {
        return [
            'user_id' => 'integer',
            'size' => 'integer',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getUrlAttribute()
    {
        return Storage::url($this->path);
    }
}
