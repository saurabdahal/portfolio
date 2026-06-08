<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;

class HashnodeCacheService
{
    protected string $path = 'hashnode/posts.json';

    public function getPosts(int $limit = 12): array
    {
        if (! Storage::disk('local')->exists($this->path)) {
            return [];
        }

        $posts = json_decode(Storage::disk('local')->get($this->path), true) ?? [];

        return array_slice($posts, 0, $limit);
    }

    public function getPost(string $slug): ?array
    {
        foreach ($this->getPosts(50) as $post) {
            if (($post['slug'] ?? '') === $slug) {
                $record = \App\Models\BlogPost::where('slug', $slug)->first();

                if ($record && app(HashnodePostContentService::class)->hasUsableContent($record->content_html)) {
                    return $record->toDetailApiArray();
                }

                return $post;
            }
        }

        return null;
    }
}
