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
use App\Services\HashnodeService;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/blogs/{slug?}', function () {
    return view('welcome');
})->where('slug', '.*');

Route::get('/api/portfolio', function () {
    $maintenance_mode = Setting::get('maintenance_mode', '0') === '1';

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

Route::get('/api/hashnode/posts', function (Request $request, HashnodeService $hashnode) {
    try {
        $first = min((int) $request->query('first', 12), 50);
        $posts = $hashnode->getPosts($first);

        return response()->json(['posts' => $posts]);
    } catch (\Throwable $e) {
        return response()->json(['message' => $e->getMessage(), 'posts' => []], 502);
    }
});

Route::get('/api/hashnode/posts/{slug}', function (string $slug, HashnodeService $hashnode) {
    try {
        $post = $hashnode->getPost($slug);
        if (! $post) {
            return response()->json(['message' => 'Post not found'], 404);
        }
        return response()->json(['post' => $post]);
    } catch (\Throwable $e) {
        return response()->json(['message' => $e->getMessage()], 502);
    }
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
