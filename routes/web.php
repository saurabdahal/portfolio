<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Models\Hero;
use App\Models\About;
use App\Models\Skill;
use App\Models\Experience;
use App\Models\Project;
use App\Models\Service;
use App\Models\ContactInfo;
use App\Models\Social;
use App\Models\Setting;
use App\Services\PortfolioBlogService;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/blogs/{slug?}', function () {
    return view('welcome');
})->where('slug', '.*');

Route::get('/api/portfolio', function (PortfolioBlogService $blog) {
    $maintenance_mode = Setting::get('maintenance_mode', '0') === '1';
    $blog_visible = $blog->hasVisiblePosts();

    $hero        = Hero::where('is_visible', true)->first();
    $about       = About::where('is_visible', true)->first();
    $skills      = Skill::where('is_visible', true)->orderBy('order')->get();
    $experiences = Experience::where('is_visible', true)->orderBy('order')->get();
    $projects    = Project::where('is_visible', true)->orderBy('order')->get();
    $services    = Service::where('is_visible', true)->orderBy('order')->get();
    $contact     = ContactInfo::where('is_visible', true)->first();
    $socials     = Social::where('is_visible', true)->orderBy('order')->get();

    return response()->json(compact(
        'maintenance_mode',
        'blog_visible',
        'hero', 'about', 'skills', 'experiences', 'projects', 'services', 'contact', 'socials'
    ));
});

Route::get('/api/hashnode/feed', function () {
    $host = config('services.hashnode.host');

    try {
        $response = \Illuminate\Support\Facades\Http::timeout(20)
            ->withHeaders([
                'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
                'Accept' => 'application/rss+xml, application/xml, text/xml, */*',
            ])
            ->get("https://{$host}/rss.xml");

        if ($response->failed() || str_contains($response->body(), 'Just a moment')) {
            return response()->json(['message' => 'RSS feed blocked'], 502);
        }

        return response($response->body(), 200)->header('Content-Type', 'application/xml');
    } catch (\Throwable $e) {
        return response()->json(['message' => $e->getMessage()], 502);
    }
});

Route::get('/api/hashnode/posts', function (Request $request, PortfolioBlogService $blog) {
    try {
        $first = min((int) $request->query('first', 12), 50);
        $posts = $blog->getVisiblePosts($first);

        return response()->json(['posts' => $posts]);
    } catch (\Throwable $e) {
        return response()->json(['message' => $e->getMessage(), 'posts' => []], 502);
    }
});

Route::get('/api/hashnode/posts/{slug}', function (string $slug, Request $request, PortfolioBlogService $blog) {
    try {
        $post = $blog->getVisiblePost($slug, $request->query('preview'));
        if (! $post) {
            return response()->json(['message' => 'Post not found'], 404);
        }
        return response()->json(['post' => $post]);
    } catch (\Throwable $e) {
        return response()->json(['message' => $e->getMessage()], 502);
    }
});

Route::post('/api/hashnode/graphql', function (Request $request) {
    $host = config('services.hashnode.host');
    $token = config('services.hashnode.token');
    $payload = $request->only(['query', 'variables', 'operationName']);

    $endpoints = array_unique([
        config('services.hashnode.endpoint', 'https://gql.hashnode.com/'),
        "https://{$host}/api/graphql",
    ]);

    foreach ($endpoints as $endpoint) {
        $headers = [
            'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
            'Origin' => "https://{$host}",
            'Referer' => "https://{$host}/",
        ];

        if ($token) {
            $headers['Authorization'] = $token;
        }

        $response = \Illuminate\Support\Facades\Http::timeout(25)
            ->withHeaders($headers)
            ->post($endpoint, $payload);

        if ($response->failed()) {
            continue;
        }

        $body = $response->body();
        if (str_starts_with(ltrim($body), '<!DOCTYPE') || str_starts_with(ltrim($body), '<html')) {
            continue;
        }

        $json = $response->json();
        if (is_array($json) && (isset($json['data']) || isset($json['errors']))) {
            return response()->json($json);
        }
    }

    return response()->json(['message' => 'Could not load article content'], 502);
});

Route::post('/api/hashnode/posts/{slug}/content', function (string $slug, Request $request) {
    $validated = $request->validate([
        'content_html' => 'required|string',
        'cover_image_url' => 'nullable|string|max:2048',
    ]);

    $post = \App\Models\BlogPost::where('slug', $slug)->first();
    if (! $post) {
        return response()->json(['message' => 'Post not found'], 404);
    }

    $post->update([
        'content_html' => $validated['content_html'],
        'cover_image_url' => $validated['cover_image_url'] ?? $post->cover_image_url,
    ]);

    app(\App\Services\HashnodeLocalContentStore::class)->saveForSlug(
        $slug,
        $validated['content_html'],
        $validated['cover_image_url'] ?? $post->cover_image_url
    );

    return response()->json(['message' => 'Content cached']);
});

Route::post('/contact', function (Request $request) {
    $validated = $request->validate([
        'name'    => 'required|string|max:255',
        'email'   => 'required|email',
        'subject' => 'nullable|string|max:255',
        'message' => 'required|string',
    ]);

    // Mail sending will be wired up when CMS is built
    // For now just return success
    return response()->json(['message' => 'Message received.']);
});
