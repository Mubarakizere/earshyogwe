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
            'manage giving types',
            'create giving types',
            'edit giving types',
            'delete giving types',
            'enter givings',
            'view all givings',
            'view assigned givings',
            'view own givings',
            'mark diocese transfer',
            'verify diocese receipt',
            
            // Expense Management
            'manage expense categories',
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
            'delete activities', // NEW
            'approve activities', // NEW
            'log activity progress', // Phase 1
            'view all activities',
            'view assigned activities',
            'view own activities',
            'view department activities',
            
            // HR Management
            'manage all workers',
            'manage assigned workers',
            'manage own workers',
            'create worker',
            'edit worker',
            'delete worker',
            'manage contracts',
            'view retirement plans',
            
            // User & Permission Management
            'manage users',
            'manage roles',
            'assign roles',
            'grant permissions',
            'revoke permissions',
            'assign archid to churches',
            
            // Department Management
            'create departments',
            'edit departments',
            'view all departments',
            'assign users to departments',
            
            // Population / Member Management
            'create members',
            'edit members',
            'delete members',
            'view all members',
            'view assigned members',
            'view own members',
            
            // Attendance Management
            'create attendance',
            'edit attendance',
            'delete attendance',
            'view all attendance',
            'view assigned attendance',
            'view own attendance',
            
            // Population Census
            'create census',
            'edit census',
            'delete census',
            'view all census',
            'view assigned census',
            'view own census',
            
            // Audit Logs
            'view activity logs',
            
            // Service Types
            'manage service types',
            
            // Institutions
            'manage institutions',
            
            // Custom Fields (Phase 2)
            'manage custom fields',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Create roles and assign permissions

        $boss = Role::firstOrCreate(['name' => 'boss']);
        // Grant everything
        $boss->syncPermissions(Permission::all());
        // Remove 'mark diocese transfer' so Boss acts as Receiver only (like Finance)
        $boss->revokePermissionTo('mark diocese transfer');

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
            'delete activities', // NEW
            'approve activities', // NEW
            'log activity progress', // Phase 1
            'view assigned activities',
            'manage assigned workers',
            'manage contracts',
            'view retirement plans',
            'create departments',
            'edit departments',
            'assign users to departments',
            'create members',
            'edit members',
            'view assigned members',
            'create attendance',
            'edit attendance',
            'delete attendance',
            'view assigned attendance',
            'create census',
            'edit census',
            'delete census',
            'view assigned census',
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
            'mark diocese transfer',
            'view own evangelism',
            'create activities',
            'edit activities',
            'delete activities', // NEW
            'log activity progress', // Phase 1
            'view own activities',
            'view department activities',
            'manage own workers',
            'manage contracts',
            'view retirement plans',
            'create departments',
            'edit departments',
            'assign users to departments',
            'create members',
            'edit members',
            'view own members',
            'create attendance',
            'edit attendance',
            'view own attendance',
            'create census',
            'edit census',
            'view own census',
        ]);

        // FINANCE Role (Diocese Level)
        $finance = Role::firstOrCreate(['name' => 'finance']);
        $finance->syncPermissions([
            'view all givings',
            'view all expenses',
            'approve expenses',
            'verify diocese receipt',
            'manage expense categories',
            'create expense categories', // Finance should manage categories
            'manage giving types',
            'create giving types',        // Finance should manage giving types
            'manage service types',
        ]);

        // HR Role (Diocese Level)
        $hr = Role::firstOrCreate(['name' => 'hr']);
        $hr->syncPermissions([
            'manage all workers',
            'manage institutions',
            'create worker',
            'edit worker',
            'delete worker',
            'manage contracts',
            'view retirement plans',
        ]);

        // EVANGELISM Role (Diocese Level)
        $evangelism = Role::firstOrCreate(['name' => 'evangelism']);
        $evangelism->syncPermissions([
            'view all evangelism',
            'submit evangelism reports', // Maybe they want to submit too?
        ]);
    }
}
