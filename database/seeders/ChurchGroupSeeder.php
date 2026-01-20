<?php

namespace Database\Seeders;

use App\Models\ChurchGroup;
use Illuminate\Database\Seeder;

class ChurchGroupSeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        $groups = [
            ['name' => 'Men\'s Fellowship', 'description' => 'Fellowship group for men in the church'],
            ['name' => 'Women\'s Fellowship', 'description' => 'Fellowship group for women in the church'],
            ['name' => 'Youth Fellowship', 'description' => 'Fellowship group for youth and young adults'],
            ['name' => 'Children\'s Ministry', 'description' => 'Ministry focused on children\'s spiritual growth'],
            ['name' => 'Choir', 'description' => 'Church choir and music ministry'],
            ['name' => 'Ushers', 'description' => 'Ushering and hospitality team'],
            ['name' => 'Mothers\' Union (MU)', 'description' => 'Mothers\' Union fellowship'],
            ['name' => 'Prayer Team', 'description' => 'Dedicated prayer and intercession group'],
            ['name' => 'Sunday School Teachers', 'description' => 'Teachers serving in Sunday school'],
        ];

        foreach ($groups as $group) {
            ChurchGroup::firstOrCreate(
                ['name' => $group['name']],
                ['description' => $group['description']]
            );
        }
    }
}
