<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

foreach (App\Models\BlogPost::orderBy('id')->get() as $p) {
    printf("%-60s html=%-6d cover=%s\n", $p->slug, strlen((string) $p->content_html), $p->cover_image_url ? 'yes' : 'no');
}
