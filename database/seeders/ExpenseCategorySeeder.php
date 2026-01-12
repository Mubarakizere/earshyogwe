<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ExpenseCategory;
use App\Models\User;

class ExpenseCategorySeeder extends Seeder
{
    public function run(): void
    {
        $boss = User::role('boss')->first();
        $createdBy = $boss ? $boss->id : null; // Handle case when boss doesn't exist yet

        $categories = [
            ['name' => 'Utilities', 'description' => 'Electricity, water, internet, phone bills', 'requires_approval' => false],
            ['name' => 'Salaries & Wages', 'description' => 'Staff salaries and wages', 'requires_approval' => true],
            ['name' => 'Building Maintenance', 'description' => 'Repairs, renovations, cleaning', 'requires_approval' => true],
            ['name' => 'Events & Programs', 'description' => 'Church events, programs, conferences', 'requires_approval' => false],
            ['name' => 'Office Supplies', 'description' => 'Stationery, printing, equipment', 'requires_approval' => false],
            ['name' => 'Transportation', 'description' => 'Fuel, vehicle maintenance, travel', 'requires_approval' => false],
            ['name' => 'Ministry Expenses', 'description' => 'Outreach, missions, charity', 'requires_approval' => true],
            ['name' => 'Insurance', 'description' => 'Property, liability, health insurance', 'requires_approval' => true],
        ];

        foreach ($categories as $category) {
            ExpenseCategory::create([
                ...$category,
                'created_by' => $createdBy,
            ]);
        }
    }
}
