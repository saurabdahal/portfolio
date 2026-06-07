<?php

namespace App\Services;

use App\Models\BlogPost;

class PortfolioBlogService
{
    public function __construct(protected HashnodeService $hashnode) {}

    public function hasVisiblePosts(): bool
    {
        if (BlogPost::exists()) {
            return BlogPost::where('is_visible', true)->exists();
        }

        return $this->hashnode->getPosts(1) !== [];
    }

    public function getVisiblePosts(int $limit): array
    {
        if (! BlogPost::exists()) {
            return $this->mapHashnodePosts($this->hashnode->getPosts($limit));
        }

        return BlogPost::query()
            ->where('is_visible', true)
            ->orderByDesc('published_at')
            ->limit($limit)
            ->get()
            ->map(fn (BlogPost $post) => $post->toApiArray())
            ->all();
    }

    public function getVisiblePost(string $slug): ?array
    {
        if (! BlogPost::exists()) {
            return $this->hashnode->getPost($slug);
        }

        $record = BlogPost::where('slug', $slug)->where('is_visible', true)->first();

        if (! $record) {
            return null;
        }

        $post = $this->hashnode->getPost($slug);

        if ($post) {
            return array_merge($post, ['url' => $record->url]);
        }

        return $record->toApiArray();
    }

    protected function mapHashnodePosts(array $posts): array
    {
        $host = config('services.hashnode.host');

        return collect($posts)->map(function (array $post) use ($host) {
            $slug = $post['slug'] ?? $post['id'] ?? '';

            return [
                'id' => $slug,
                'slug' => $slug,
                'title' => $post['title'] ?? '',
                'brief' => $post['brief'] ?? '',
                'readTimeInMinutes' => (int) ($post['readTimeInMinutes'] ?? 1),
                'publishedAt' => $post['publishedAt'] ?? null,
                'url' => $post['url'] ?? "https://{$host}/{$slug}",
            ];
        })->values()->all();
    }
}
