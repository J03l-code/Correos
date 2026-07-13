<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable([
    'link_id', 'clicked_at', 'anonymized_ip', 'user_agent_summary',
    'referrer', 'device_type'
])]
class LinkClick extends Model
{
    use HasFactory;

    protected function casts(): array
    {
        return [
            'link_id' => 'integer',
            'clicked_at' => 'datetime',
        ];
    }

    public function link()
    {
        return $this->belongsTo(Link::class);
    }
}
