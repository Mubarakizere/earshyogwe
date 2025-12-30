<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ServiceTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $types = [
            [
                'name' => 'Sunday Service',
                'description' => 'Main weekly worship service.',
            ],
            [
                'name' => 'Prayer Meeting',
                'description' => 'Mid-week prayer gathering.',
            ],
            [
                'name' => 'Bible Study',
                'description' => 'Group bible study session.',
            ],
            [
                'name' => 'Youth Service',
                'description' => 'Service dedicated to youth ministry.',
            ],
            [
                'name' => 'Special Event',
                'description' => 'Conferences, guest speakers, or holidays.',
            ],
            [
                'name' => 'Other',
                'description' => 'Any other undefined service type.',
            ],
        ];

        foreach ($types as $type) {
            \App\Models\ServiceType::create($type);
        }
    }
}
