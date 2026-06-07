<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BlogPost extends Model
{
    protected $fillable = [
        'slug',
        'title',
        'brief',
        'url',
        'read_time_in_minutes',
        'published_at',
        'is_visible',
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'is_visible' => 'boolean',
    ];

    public function toApiArray(): array
    {
        return [
            'id' => $this->slug,
            'slug' => $this->slug,
            'title' => $this->title,
            'brief' => $this->brief ?? '',
            'readTimeInMinutes' => $this->read_time_in_minutes,
            'publishedAt' => $this->published_at?->toIso8601String(),
            'url' => $this->url,
        ];
    }
}
