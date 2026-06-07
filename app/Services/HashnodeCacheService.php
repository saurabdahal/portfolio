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
                return $post + [
                    'content' => [
                        'html' => '<p>' . e($post['brief'] ?? '') . '</p>'
                            . '<p><a href="' . e($post['url'] ?? '') . '" target="_blank" rel="noopener noreferrer">Read the full article on Hashnode →</a></p>',
                    ],
                ];
            }
        }

        return null;
    }
}
