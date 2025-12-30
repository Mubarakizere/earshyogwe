<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleAndPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions
        $permissions = [
            // Church Management
            'view all churches',
            'view assigned churches',
            'view own church',
            'create church',
            'edit church',
            'delete church',
            
            // Giving Management
            'create giving types',
            'edit giving types',
            'delete giving types',
            'enter givings',
            'view all givings',
            'view assigned givings',
            'view own givings',
            'mark diocese transfer',
            
            // Expense Management
            'create expense categories',
            'enter expenses',
            'view all expenses',
            'view assigned expenses',
            'view own expenses',
            'approve expenses',
            
            // Evangelism
            'submit evangelism reports',
            'view all evangelism',
            'view assigned evangelism',
            'view own evangelism',
            
            // Activities
            'create activities',
            'edit activities',
            'view all activities',
            'view assigned activities',
            'view own activities',
            'view department activities',
            
            // HR Management
            'manage all workers',
            'manage assigned workers',
            'manage own workers',
            'manage contracts',
            'view retirement plans',
            
            // User & Permission Management
            'manage users',
            'assign roles',
            'grant permissions',
            'revoke permissions',
            'assign archid to churches',
            
            // Department Management
            'create departments',
            'edit departments',
            'view all departments',
            'assign users to departments',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Create roles and assign permissions

        // BOSS Role (Diocese Administrator) - Full Access
        $boss = Role::firstOrCreate(['name' => 'boss']);
        $boss->givePermissionTo(Permission::all());

        // ARCHID Role (Regional Supervisor) - Manages assigned churches
        $archid = Role::firstOrCreate(['name' => 'archid']);
        $archid->syncPermissions([
            'view assigned churches',
            'edit church',
            'enter givings',
            'view assigned givings',
            'mark diocese transfer',
            'enter expenses',
            'view assigned expenses',
            'approve expenses',
            'submit evangelism reports',
            'view assigned evangelism',
            'create activities',
            'edit activities',
            'view assigned activities',
            'manage assigned workers',
            'manage contracts',
            'view retirement plans',
            'create departments',
            'edit departments',
            'assign users to departments',
        ]);

        // PASTOR Role (Church Leader) - Manages own church
        $pastor = Role::firstOrCreate(['name' => 'pastor']);
        $pastor->syncPermissions([
            'view own church',
            'edit church',
            'enter givings',
            'view own givings',
            'enter expenses',
            'view own expenses',
            'submit evangelism reports',
            'view own evangelism',
            'create activities',
            'edit activities',
            'view own activities',
            'view department activities',
            'manage own workers',
            'manage contracts',
            'view retirement plans',
            'create departments',
            'edit departments',
            'assign users to departments',
        ]);

        // FINANCE Role (Diocese Level)
        $finance = Role::firstOrCreate(['name' => 'finance']);
        $finance->syncPermissions([
            'view all givings',
            'view all expenses',
            'approve expenses',
            'mark diocese transfer',
            'create expense categories', // Finance should manage categories
            'create giving types',        // Finance should manage giving types
        ]);

        // HR Role (Diocese Level)
        $hr = Role::firstOrCreate(['name' => 'hr']);
        $hr->syncPermissions([
            'manage all workers',
            'manage contracts',
            'view retirement plans',
        ]);

        // EVANGELISM Role (Diocese Level)
        $evangelism = Role::firstOrCreate(['name' => 'evangelism']);
        $evangelism->syncPermissions([
            'view all evangelism',
            'submit evangelism reports', // Maybe they want to submit too?
        ]);

        $this->command->info('Roles and permissions created successfully!');
    }
}
