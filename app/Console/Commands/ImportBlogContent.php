<?php

namespace App\Console\Commands;

use App\Models\BlogPost;
use App\Services\HashnodeLocalContentStore;
use App\Services\HashnodePostContentService;
use Illuminate\Console\Command;

class ImportBlogContent extends Command
{
    protected $signature = 'blog:import-content {--slug= : Import a single post slug}';

    protected $description = 'Import blog HTML from local hashnode/content JSON files into the database';

    public function handle(HashnodeLocalContentStore $store, HashnodePostContentService $content): int
    {
        $query = BlogPost::query();

        if ($slug = $this->option('slug')) {
            $query->where('slug', $slug);
        }

        $posts = $query->get();
        $imported = 0;

        foreach ($posts as $post) {
            $local = $store->getForSlug($post->slug);

            if (! $local || ! $content->hasUsableContent($local['content_html'] ?? null)) {
                $this->warn("No local content for {$post->slug}");
                continue;
            }

            $post->update([
                'content_html' => $local['content_html'],
                'cover_image_url' => $local['cover_image_url'] ?? $post->cover_image_url,
            ]);

            $imported++;
            $this->info("Imported {$post->slug}");
        }

        $this->info("Done. Imported {$imported} post(s).");

        return self::SUCCESS;
    }
}
