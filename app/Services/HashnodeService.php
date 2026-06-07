<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class HashnodeService
{
    protected string $host;

    protected string $endpoint;

    protected ?string $token;

    public function __construct(
        protected HashnodeRssService $rss,
        protected HashnodeCacheService $cache
    ) {
        $this->host = config('services.hashnode.host');
        $this->endpoint = config('services.hashnode.endpoint');
        $this->token = config('services.hashnode.token');
    }

    public function getPosts(int $first = 12): array
    {
        $cached = $this->cache->getPosts($first);
        if ($cached !== []) {
            return $cached;
        }

        if ($this->token) {
            try {
                return $this->getPostsViaGraphQL($first);
            } catch (\Throwable $e) {
                Log::info('Hashnode GraphQL unavailable, falling back to RSS', ['message' => $e->getMessage()]);
            }
        }

        try {
            return $this->rss->getPosts($first);
        } catch (\Throwable $e) {
            Log::info('Hashnode RSS unavailable, using cached posts', ['message' => $e->getMessage()]);
        }

        return $this->cache->getPosts($first);
    }

    public function getPost(string $slug): ?array
    {
        $cached = $this->cache->getPost($slug);
        if ($cached) {
            return $cached;
        }

        if ($this->token) {
            try {
                $post = $this->getPostViaGraphQL($slug);
                if ($post) {
                    return $post;
                }
            } catch (\Throwable $e) {
                Log::info('Hashnode GraphQL unavailable, falling back to RSS', ['message' => $e->getMessage()]);
            }
        }

        try {
            $post = $this->rss->getPost($slug);
            if ($post) {
                return $post;
            }
        } catch (\Throwable $e) {
            Log::info('Hashnode RSS unavailable, using cached post', ['message' => $e->getMessage()]);
        }

        return $this->cache->getPost($slug);
    }

    protected function getPostsViaGraphQL(int $first): array
    {
        $query = <<<GQL
            query Publication {
                publication(host: "{$this->host}") {
                    posts(first: {$first}) {
                        edges {
                            node {
                                title
                                brief
                                slug
                                id
                                readTimeInMinutes
                                publishedAt
                            }
                        }
                    }
                }
            }
        GQL;

        $data = $this->query($query);

        return collect($data['publication']['posts']['edges'] ?? [])
            ->map(fn ($edge) => $edge['node'])
            ->values()
            ->all();
    }

    protected function getPostViaGraphQL(string $slug): ?array
    {
        $escaped = addslashes($slug);

        $query = <<<GQL
            query Publication {
                publication(host: "{$this->host}") {
                    post(slug: "{$escaped}") {
                        id
                        title
                        brief
                        readTimeInMinutes
                        publishedAt
                        coverImage {
                            url
                        }
                        content {
                            html
                        }
                    }
                }
            }
        GQL;

        $data = $this->query($query);

        return $data['publication']['post'] ?? null;
    }

    protected function query(string $query): array
    {
        $response = Http::timeout(5)
            ->withOptions(['allow_redirects' => false])
            ->acceptJson()
            ->asJson()
            ->withHeaders($this->headers())
            ->post($this->endpoint, ['query' => $query]);

        if (in_array($response->status(), [301, 302], true)) {
            throw new \RuntimeException('Hashnode GraphQL requires Pro plan.');
        }

        if ($response->failed()) {
            throw new \RuntimeException('Hashnode GraphQL request failed.');
        }

        $body = $response->body();

        if (str_starts_with(ltrim($body), '<!DOCTYPE') || str_starts_with(ltrim($body), '<html')) {
            throw new \RuntimeException('Hashnode GraphQL requires Pro plan.');
        }

        $json = $response->json();

        if (! empty($json['errors'])) {
            throw new \RuntimeException($json['errors'][0]['message'] ?? 'Hashnode API error');
        }

        return $json['data'] ?? [];
    }

    protected function headers(): array
    {
        if (! $this->token) {
            return [];
        }

        return ['Authorization' => $this->token];
    }
}
