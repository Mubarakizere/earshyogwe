<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\GivingType;
use App\Models\User;

class GivingTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $boss = User::role('boss')->first();
        $createdBy = $boss ? $boss->id : null; // Handle case when boss doesn't exist yet

        // Tithes (no sub-types)
        GivingType::create([
            'name' => 'Tithes',
            'description' => 'Regular tithes - 10% of income',
            'has_sub_types' => false,
            'created_by' => $createdBy,
        ]);

        // Offerings (with sub-types)
        $offerings = GivingType::create([
            'name' => 'Offerings',
            'description' => 'Various church offerings',
            'has_sub_types' => true,
            'created_by' => $createdBy,
        ]);

        $offerings->subTypes()->createMany([
            ['name' => 'Sunday Offering', 'description' => 'Regular Sunday service offering'],
            ['name' => 'Thanksgiving Offering', 'description' => 'Special thanksgiving offerings'],
            ['name' => 'Harvest Offering', 'description' => 'Annual harvest celebration offering'],
            ['name' => 'Midweek Offering', 'description' => 'Wednesday/Thursday service offering'],
        ]);

        // Special Collections (with sub-types)
        $special = GivingType::create([
            'name' => 'Special Collections',
            'description' => 'Special purpose collections',
            'has_sub_types' => true,
            'created_by' => $createdBy,
        ]);

        $special->subTypes()->createMany([
            ['name' => 'Building Fund', 'description' => 'Church building and renovation'],
            ['name' => 'Mission Fund', 'description' => 'Missionary work and outreach'],
            ['name' => 'Charity Fund', 'description' => 'Helping the needy'],
            ['name' => 'Youth Fund', 'description' => 'Youth programs and activities'],
        ]);

        // First Fruits
        GivingType::create([
            'name' => 'First Fruits',
            'description' => 'First fruits offerings',
            'has_sub_types' => false,
            'created_by' => $createdBy,
        ]);

        // Pledges (with sub-types)
        $pledges = GivingType::create([
            'name' => 'Pledges',
            'description' => 'Committed pledges',
            'has_sub_types' => true,
            'created_by' => $createdBy,
        ]);

        $pledges->subTypes()->createMany([
            ['name' => 'Annual Pledge', 'description' => 'Yearly commitment'],
            ['name' => 'Project Pledge', 'description' => 'Specific project commitments'],
        ]);
    }
}
