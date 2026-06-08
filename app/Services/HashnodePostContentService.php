<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class HashnodePostContentService
{
    public function __construct(
        protected HashnodeRssService $rss
    ) {}

    public function fetchForSlug(string $slug, ?string $url = null): ?array
    {
        $local = app(HashnodeLocalContentStore::class)->getForSlug($slug);
        if ($local && $this->hasUsableContent($local['content_html'] ?? null)) {
            return $local;
        }

        $host = config('services.hashnode.host');
        $url ??= "https://{$host}/{$slug}";

        foreach ([
            fn () => $this->fromPublicationGraphql($slug),
            fn () => $this->fromRss($slug),
            fn () => $this->fromPage($url),
        ] as $fetcher) {
            try {
                $result = $fetcher();
                if ($this->hasUsableContent($result['content_html'] ?? null)) {
                    return $result;
                }
            } catch (\Throwable $e) {
                Log::info('Hashnode content fetch attempt failed', [
                    'slug' => $slug,
                    'message' => $e->getMessage(),
                ]);
            }
        }

        return null;
    }

    public function hasUsableContent(?string $html): bool
    {
        if ($html === null || $html === '') {
            return false;
        }

        if (str_contains($html, 'Read the full article on Hashnode')) {
            return false;
        }

        return strlen(strip_tags($html)) > 200;
    }

    protected function fromPublicationGraphql(string $slug): ?array
    {
        $host = config('services.hashnode.host');

        $query = <<<'GQL'
            query Post($host: String!, $slug: String!) {
                publication(host: $host) {
                    post(slug: $slug) {
                        content { html }
                        coverImage { url }
                    }
                }
            }
        GQL;

        $response = Http::timeout(20)
            ->withHeaders([
                'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
                'Origin' => "https://{$host}",
                'Referer' => "https://{$host}/",
            ])
            ->post("https://{$host}/api/graphql", [
                'query' => $query,
                'variables' => ['host' => $host, 'slug' => $slug],
            ]);

        if ($response->failed() || str_contains($response->body(), 'Just a moment')) {
            throw new \RuntimeException('Publication GraphQL blocked.');
        }

        $post = $response->json('data.publication.post');

        if (! $post) {
            return null;
        }

        return [
            'content_html' => $post['content']['html'] ?? null,
            'cover_image_url' => $post['coverImage']['url'] ?? null,
        ];
    }

    protected function fromRss(string $slug): ?array
    {
        $post = $this->rss->getPost($slug);

        if (! $post) {
            return null;
        }

        return [
            'content_html' => $post['content']['html'] ?? null,
            'cover_image_url' => $post['coverImage']['url'] ?? null,
        ];
    }

    protected function fromPage(string $url): ?array
    {
        $response = Http::timeout(20)
            ->withHeaders([
                'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
                'Accept' => 'text/html,application/xhtml+xml',
            ])
            ->get($url);

        if ($response->failed() || str_contains($response->body(), 'Just a moment')) {
            throw new \RuntimeException('Post page blocked.');
        }

        if (! preg_match('/<script id="__NEXT_DATA__" type="application\/json">(.+?)<\/script>/s', $response->body(), $matches)) {
            return null;
        }

        $data = json_decode($matches[1], true);
        $post = $data['props']['pageProps']['post'] ?? null;

        if (! $post) {
            return null;
        }

        return [
            'content_html' => $post['content']['html'] ?? null,
            'cover_image_url' => $post['coverImage']['url'] ?? null,
        ];
    }
}
