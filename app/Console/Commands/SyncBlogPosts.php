<?php

namespace App\Console\Commands;

use App\Models\BlogPost;
use App\Services\BlogPostSyncService;
use Illuminate\Console\Command;

class SyncBlogPosts extends Command
{
    protected $signature = 'blog:sync {--content-only : Only fetch missing content for existing posts}';

    protected $description = 'Sync blog posts from Hashnode (metadata and content when reachable)';

    public function handle(BlogPostSyncService $sync): int
    {
        $count = $sync->sync();
        $this->info("Synced {$count} post(s).");

        $withContent = BlogPost::whereNotNull('content_html')->where('content_html', '!=', '')->count();
        $total = BlogPost::count();
        $this->info("Posts with full content: {$withContent}/{$total}");

        if ($withContent < $total) {
            $this->warn('Some posts have no content_html. Open each post in the browser once to import via RSS, or run: php artisan blog:import-content');
        }

        return self::SUCCESS;
    }
}
