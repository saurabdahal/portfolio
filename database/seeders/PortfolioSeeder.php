<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Hero;
use App\Models\About;
use App\Models\Skill;
use App\Models\Experience;
use App\Models\Project;
use App\Models\Service;
use App\Models\ContactInfo;
use App\Models\Social;

class PortfolioSeeder extends Seeder
{
    public function run(): void
    {
        Hero::updateOrCreate(['id' => 1], [
            'name'       => 'Your Name',
            'title'      => 'Full Stack Developer',
            'tagline'    => 'I build elegant web applications with a focus on performance and user experience.',
            'photo'      => null,
            'is_visible' => true,
        ]);

        About::updateOrCreate(['id' => 1], [
            'bio'        => 'I am a passionate developer with a love for creating beautiful and functional web applications. With years of experience in both frontend and backend development, I bring ideas to life through clean, efficient code.',
            'cv_path'    => null,
            'is_visible' => true,
        ]);

        $skills = [
            ['name' => 'Laravel / PHP', 'percentage' => 90, 'order' => 1],
            ['name' => 'Vue.js',        'percentage' => 85, 'order' => 2],
            ['name' => 'HTML & CSS',    'percentage' => 95, 'order' => 3],
            ['name' => 'MySQL',         'percentage' => 80, 'order' => 4],
            ['name' => 'UI / UX Design','percentage' => 75, 'order' => 5],
        ];
        foreach ($skills as $skill) {
            Skill::updateOrCreate(['name' => $skill['name']], $skill + ['is_visible' => true]);
        }

        $experiences = [
            ['title' => 'Senior Full Stack Developer', 'company' => 'Tech Company',    'year' => '2023 — Present', 'description' => 'Leading development of scalable web applications using Laravel and Vue.js.',   'order' => 1],
            ['title' => 'Full Stack Developer',        'company' => 'Digital Agency',   'year' => '2021 — 2023',   'description' => 'Built and maintained client projects across various industries.',              'order' => 2],
            ['title' => 'Junior Web Developer',        'company' => 'Startup',          'year' => '2019 — 2021',   'description' => 'Developed frontend interfaces and RESTful APIs.',                            'order' => 3],
        ];
        foreach ($experiences as $exp) {
            Experience::updateOrCreate(['title' => $exp['title'], 'company' => $exp['company']], $exp + ['is_visible' => true]);
        }

        $projects = [
            ['title' => 'E-Commerce Platform',  'category' => 'Web',     'description' => 'Full-stack e-commerce built with Laravel and Vue.js',      'order' => 1],
            ['title' => 'Portfolio CMS',         'category' => 'Web',     'description' => 'Content management system for creative professionals',      'order' => 2],
            ['title' => 'Mobile App UI',         'category' => 'Design',  'description' => 'UI/UX design for a fitness tracking app',                  'order' => 3],
            ['title' => 'Analytics Dashboard',   'category' => 'Web',     'description' => 'Real-time analytics with interactive charts',               'order' => 4],
            ['title' => 'Brand Identity',        'category' => 'Design',  'description' => 'Complete brand identity for a tech startup',                'order' => 5],
            ['title' => 'REST API',              'category' => 'Backend', 'description' => 'Scalable RESTful API built with Laravel',                   'order' => 6],
        ];
        foreach ($projects as $proj) {
            Project::updateOrCreate(['title' => $proj['title']], $proj + ['is_visible' => true]);
        }

        $services = [
            ['title' => 'Web Development', 'description' => 'Building fast, scalable and modern web applications tailored to your needs.',    'order' => 1],
            ['title' => 'UI / UX Design',  'description' => 'Crafting intuitive and elegant user interfaces with a focus on user experience.','order' => 2],
            ['title' => 'API Development', 'description' => 'Designing and building robust RESTful APIs to power your applications.',          'order' => 3],
            ['title' => 'Consulting',      'description' => 'Technical consulting to help you make the right architectural decisions.',        'order' => 4],
        ];
        foreach ($services as $svc) {
            Service::updateOrCreate(['title' => $svc['title']], $svc + ['is_visible' => true]);
        }

        ContactInfo::updateOrCreate(['id' => 1], [
            'email'      => 'hello@example.com',
            'phone'      => '+1 234 567 890',
            'location'   => 'New York, USA',
            'is_visible' => true,
        ]);

        $socials = [
            ['name' => 'GitHub',   'url' => 'https://github.com',   'order' => 1],
            ['name' => 'LinkedIn', 'url' => 'https://linkedin.com', 'order' => 2],
            ['name' => 'Twitter',  'url' => 'https://twitter.com',  'order' => 3],
        ];
        foreach ($socials as $s) {
            Social::updateOrCreate(['name' => $s['name']], $s + ['is_visible' => true]);
        }
    }
}
