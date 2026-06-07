<?php

namespace Database\Seeders;

use App\Services\BlogPostSyncService;
use Illuminate\Database\Seeder;

class BlogPostSeeder extends Seeder
{
    public function run(): void
    {
        app(BlogPostSyncService::class)->sync();
    }
}
