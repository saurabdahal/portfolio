<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class BlogPost extends Model
{
    protected $fillable = [
        'slug',
        'title',
        'brief',
        'content_html',
        'cover_image_url',
        'tags',
        'url',
        'read_time_in_minutes',
        'published_at',
        'is_visible',
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'is_visible' => 'boolean',
        'tags' => 'array',
    ];

    protected static function booted(): void
    {
        static::saving(function (BlogPost $post): void {
            if (blank($post->slug) && filled($post->title)) {
                $post->slug = static::uniqueSlug(Str::slug($post->title), $post->id);
            }

            if (filled($post->content_html)) {
                $words = str_word_count(strip_tags($post->content_html));
                $post->read_time_in_minutes = max(1, (int) ceil($words / 200));
            }

            if (blank($post->published_at) && $post->is_visible) {
                $post->published_at = now();
            }
        });
    }

    protected static function uniqueSlug(string $base, ?int $ignoreId = null): string
    {
        $base = $base !== '' ? $base : 'post';
        $slug = $base;
        $i = 2;

        while (static::where('slug', $slug)->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))->exists()) {
            $slug = "{$base}-{$i}";
            $i++;
        }

        return $slug;
    }

    public function resolvedCoverImageUrl(): ?string
    {
        if (blank($this->cover_image_url)) {
            return null;
        }

        if (Str::startsWith($this->cover_image_url, ['http://', 'https://', '//'])) {
            return $this->cover_image_url;
        }

        return Storage::disk('public')->url($this->cover_image_url);
    }

    public function publicUrl(): string
    {
        return $this->url ?: url("/blogs/{$this->slug}");
    }

    public function toApiArray(): array
    {
        return [
            'id' => $this->slug,
            'slug' => $this->slug,
            'title' => $this->title,
            'brief' => $this->brief ?? '',
            'tags' => $this->tags ?? [],
            'readTimeInMinutes' => $this->read_time_in_minutes,
            'publishedAt' => $this->published_at?->toIso8601String(),
            'url' => $this->publicUrl(),
            'coverImage' => $this->resolvedCoverImageUrl() ? ['url' => $this->resolvedCoverImageUrl()] : null,
        ];
    }

    public function toDetailApiArray(): array
    {
        $data = $this->toApiArray();

        if ($this->content_html) {
            $data['content'] = ['html' => $this->content_html];
        }

        return $data;
    }
}
