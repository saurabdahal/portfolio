<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class HashnodeRssService
{
    protected string $host;

    public function __construct()
    {
        $this->host = config('services.hashnode.host');
    }

    public function getPosts(int $limit = 12): array
    {
        $items = $this->fetchFeedItems();

        return collect($items)
            ->take($limit)
            ->map(fn ($item) => $this->normalizeListItem($item))
            ->values()
            ->all();
    }

    public function getPost(string $slug): ?array
    {
        $items = $this->fetchFeedItems();

        foreach ($items as $item) {
            if ($this->extractSlug($item) === $slug) {
                return $this->normalizeDetailItem($item);
            }
        }

        return null;
    }

    protected function fetchFeedItems(): array
    {
        return Cache::remember("hashnode.rss.{$this->host}", 900, function () {
            $url = "https://{$this->host}/rss.xml";

            $response = Http::timeout(20)
                ->withHeaders([
                    'User-Agent' => 'Mozilla/5.0 (compatible; PortfolioBot/1.0; +https://saurabdahal.com.np)',
                    'Accept' => 'application/rss+xml, application/xml, text/xml, */*',
                ])
                ->get($url);

            if ($response->failed() || str_contains($response->body(), 'Just a moment')) {
                Log::warning('Hashnode RSS fetch failed', ['status' => $response->status()]);
                throw new \RuntimeException('Could not fetch Hashnode RSS feed.');
            }

            $xml = @simplexml_load_string($response->body(), 'SimpleXMLElement', LIBXML_NOCDATA);

            if ($xml === false || ! isset($xml->channel->item)) {
                throw new \RuntimeException('Invalid Hashnode RSS feed.');
            }

            return iterator_to_array($xml->channel->item, false);
        });
    }

    protected function normalizeListItem(\SimpleXMLElement $item): array
    {
        $link = (string) $item->link;
        $slug = $this->extractSlug($item);
        $description = strip_tags((string) ($item->description ?? ''));

        return [
            'id' => $slug,
            'title' => (string) $item->title,
            'brief' => $description,
            'slug' => $slug,
            'readTimeInMinutes' => $this->estimateReadTime($this->getContentHtml($item)),
            'publishedAt' => $this->formatDate((string) $item->pubDate),
            'url' => $link,
        ];
    }

    protected function normalizeDetailItem(\SimpleXMLElement $item): array
    {
        $html = $this->getContentHtml($item);
        $slug = $this->extractSlug($item);

        return [
            'id' => $slug,
            'title' => (string) $item->title,
            'brief' => strip_tags((string) ($item->description ?? '')),
            'slug' => $slug,
            'readTimeInMinutes' => $this->estimateReadTime($html),
            'publishedAt' => $this->formatDate((string) $item->pubDate),
            'coverImage' => ['url' => $this->extractCoverImage($item)],
            'content' => ['html' => $html],
            'url' => (string) $item->link,
        ];
    }

    protected function getContentHtml(\SimpleXMLElement $item): string
    {
        $namespaces = $item->getNamespaces(true);

        if (isset($namespaces['content'])) {
            $encoded = $item->children($namespaces['content'])->encoded ?? null;
            if ($encoded) {
                return (string) $encoded;
            }
        }

        return (string) ($item->description ?? '');
    }

    protected function extractCoverImage(\SimpleXMLElement $item): ?string
    {
        $html = $this->getContentHtml($item);
        if (preg_match('/<img[^>]+src=["\']([^"\']+)["\']/', $html, $matches)) {
            return $matches[1];
        }

        return null;
    }

    protected function extractSlug(\SimpleXMLElement $item): string
    {
        $link = (string) $item->link;
        $path = parse_url($link, PHP_URL_PATH) ?? '';
        $parts = array_values(array_filter(explode('/', trim($path, '/'))));

        return end($parts) ?: md5((string) $item->title);
    }

    protected function formatDate(string $pubDate): string
    {
        if ($pubDate === '') {
            return now()->toIso8601String();
        }

        try {
            return (new \DateTime($pubDate))->format(\DateTimeInterface::ATOM);
        } catch (\Exception) {
            return now()->toIso8601String();
        }
    }

    protected function estimateReadTime(string $html): int
    {
        $words = str_word_count(strip_tags($html));

        return max(1, (int) ceil($words / 200));
    }
}
