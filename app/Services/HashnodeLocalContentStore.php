<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;

class HashnodeLocalContentStore
{
    protected string $disk = 'local';

    protected string $directory = 'hashnode/content';

    public function getForSlug(string $slug): ?array
    {
        $path = "{$this->directory}/{$slug}.json";

        if (! Storage::disk($this->disk)->exists($path)) {
            return null;
        }

        $data = json_decode(Storage::disk($this->disk)->get($path), true);

        if (! is_array($data)) {
            return null;
        }

        $html = $data['contentHtml'] ?? $data['content_html'] ?? null;

        if (! $html) {
            return null;
        }

        return [
            'content_html' => $html,
            'cover_image_url' => $data['coverImageUrl'] ?? $data['cover_image_url'] ?? null,
        ];
    }

    public function saveForSlug(string $slug, string $html, ?string $coverImageUrl = null): void
    {
        Storage::disk($this->disk)->put(
            "{$this->directory}/{$slug}.json",
            json_encode([
                'slug' => $slug,
                'contentHtml' => $html,
                'coverImageUrl' => $coverImageUrl,
            ], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT)
        );
    }
}
