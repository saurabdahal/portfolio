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

Route::get('/', function () {
    return view('welcome');
});

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
