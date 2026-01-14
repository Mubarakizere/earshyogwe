<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seed roles and permissions first
        $this->call([
            RoleAndPermissionSeeder::class,
            // Comment out seeders you don't need to run every time
            // Uncomment only when you need to reset these specific data
            // GivingTypeSeeder::class,
            // ExpenseCategorySeeder::class,
            // ServiceTypeSeeder::class,
        ]);

        // Optional: Uncomment to create test users with roles
        // $this->call([
        //     TestUserSeeder::class,
        // ]);
    }
}
