<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Seeder;

class DataEngineerServicesSeeder extends Seeder
{
    public function run(): void
    {
        Service::query()->delete();

        $services = [
            [
                'title'       => 'Data Pipeline Engineering',
                'description' => 'Design and build scalable ETL/ELT pipelines using Python, SQL, Apache Spark, and Airflow — from ingestion through transformation to delivery.',
                'icon'        => '<svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>',
                'order'       => 1,
                'is_visible'  => true,
            ],
            [
                'title'       => 'Cloud Data Migration',
                'description' => 'Plan and execute large-scale migrations from legacy platforms (e.g. Teradata) to modern cloud warehouses like Snowflake, with minimal downtime and validated data integrity.',
                'icon'        => '<svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/></svg>',
                'order'       => 2,
                'is_visible'  => true,
            ],
            [
                'title'       => 'Data Lake & Warehouse Architecture',
                'description' => 'Architect cloud-native data platforms on AWS — S3 data lakes, Snowflake warehouses, and medallion (Bronze/Silver/Gold) layering for reliable analytics.',
                'icon'        => '<svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4"/></svg>',
                'order'       => 3,
                'is_visible'  => true,
            ],
            [
                'title'       => 'Data Platform Consulting',
                'description' => 'Advise teams on tooling choices, pipeline design, cost optimization, and best practices for building maintainable, production-grade data infrastructure.',
                'icon'        => '<svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/></svg>',
                'order'       => 4,
                'is_visible'  => true,
            ],
        ];

        foreach ($services as $service) {
            Service::create($service);
        }
    }
}
