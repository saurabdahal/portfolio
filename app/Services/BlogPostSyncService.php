<?php

namespace App\Services;

use App\Models\BlogPost;
use Carbon\Carbon;

class BlogPostSyncService
{
    public function __construct(
        protected HashnodeService $hashnode,
        protected HashnodePostContentService $content
    ) {}

    public function sync(): int
    {
        $host = config('services.hashnode.host');
        $posts = $this->hashnode->getPosts(50);
        $synced = 0;

        foreach ($posts as $post) {
            $slug = $post['slug'] ?? null;
            if (! $slug) {
                continue;
            }

            $record = BlogPost::firstOrNew(['slug' => $slug]);
            $record->fill([
                'title' => $post['title'] ?? $slug,
                'brief' => $post['brief'] ?? '',
                'url' => $post['url'] ?? "https://{$host}/{$slug}",
                'read_time_in_minutes' => (int) ($post['readTimeInMinutes'] ?? 1),
                'published_at' => $this->parseDate($post['publishedAt'] ?? null),
            ]);

            if (! $record->exists) {
                $record->is_visible = true;
            }

            if (! $this->content->hasUsableContent($record->content_html)) {
                $fetched = $this->content->fetchForSlug($slug, $record->url);
                if ($fetched) {
                    $record->content_html = $fetched['content_html'];
                    $record->cover_image_url = $fetched['cover_image_url'];
                }
            }

            $record->save();
            $synced++;
        }

        return $synced;
    }

    protected function parseDate(?string $value): ?Carbon
    {
        if (! $value) {
            return null;
        }

        try {
            return Carbon::parse($value);
        } catch (\Throwable) {
            return null;
        }
    }
}
