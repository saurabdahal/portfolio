<?php

namespace App\Services;

use App\Models\BlogPost;

class PortfolioBlogService
{
    public function __construct(
        protected HashnodeService $hashnode,
        protected HashnodePostContentService $content,
        protected HashnodeLocalContentStore $localContent
    ) {}

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

    public function getVisiblePost(string $slug, ?string $previewToken = null): ?array
    {
        if (! BlogPost::exists()) {
            return $this->normalizePost($this->hashnode->getPost($slug));
        }

        $record = BlogPost::where('slug', $slug)->first();

        if (! $record) {
            return null;
        }

        if (! $record->is_visible && ! $record->matchesPreviewToken($previewToken)) {
            return null;
        }

        if (filled($record->content_html)) {
            return $record->toDetailApiArray();
        }

        $local = $this->localContent->getForSlug($slug);
        if ($local && $this->content->hasUsableContent($local['content_html'] ?? null)) {
            return $this->mergeContent($record->toDetailApiArray(), $local);
        }

        $post = $this->normalizePost($this->hashnode->getPost($slug));

        if ($post && $this->content->hasUsableContent($post['content']['html'] ?? null)) {
            return array_merge($post, ['url' => $record->url]);
        }

        return $record->toDetailApiArray();
    }

    protected function mergeContent(array $post, array $content): array
    {
        $post['content'] = ['html' => $content['content_html']];

        if (! empty($content['cover_image_url'])) {
            $post['coverImage'] = ['url' => $content['cover_image_url']];
        }

        return $post;
    }

    protected function normalizePost(?array $post): ?array
    {
        if (! $post) {
            return null;
        }

        $html = $post['content']['html'] ?? null;

        if ($this->content->hasUsableContent($html)) {
            return $post;
        }

        unset($post['content']);

        return $post;
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
