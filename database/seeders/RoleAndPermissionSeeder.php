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

        // Permissions organized by category
        $permissionsByCategory = [
            'Church' => [
                'view all churches', 'view assigned churches', 'view own church',
                'create church', 'edit church', 'delete church',
            ],
            'Giving' => [
                'manage giving types', 'create giving types', 'edit giving types', 'delete giving types',
                'enter givings', 'view all givings', 'view assigned givings', 'view own givings',
                'mark diocese transfer', 'verify diocese receipt',
            ],
            'Expense' => [
                'manage expense categories', 'create expense categories',
                'enter expenses', 'view all expenses', 'view assigned expenses', 'view own expenses',
                'approve expenses',
            ],
            'Objective' => [
                'create objectives', 'edit objectives', 'delete objectives', 'approve objectives',
                'submit objective reports', 'view all objectives', 'view assigned objectives', 'view own objectives',
                'view department objectives',
            ],
            'Evangelism' => [
                'submit evangelism reports', 'view all evangelism', 'view assigned evangelism', 'view own evangelism',
            ],
            'HR' => [
                'manage all workers', 'manage assigned workers', 'manage own workers',
                'create worker', 'edit worker', 'delete worker', 'manage contracts',
                'view retirement plans', 'manage institutions',
            ],
            'Department' => [
                'create departments', 'edit departments', 'view all departments', 'assign users to departments',
            ],
            'Attendance & Census' => [
                'create attendance', 'edit attendance', 'delete attendance',
                'view all attendance', 'view assigned attendance', 'view own attendance',
                'create census', 'edit census', 'delete census',
                'view all census', 'view assigned census', 'view own census',
                'manage service types',
            ],
            'Member' => [
                'create members', 'edit members', 'delete members',
                'view all members', 'view assigned members', 'view own members',
            ],
            'System' => [
                'manage users', 'manage roles', 'assign roles',
                'grant permissions', 'revoke permissions',
                'assign archid to churches', 'view activity logs', 'manage custom fields',
            ],
        ];

        // 1. Create all permissions
        foreach ($permissionsByCategory as $category => $perms) {
            foreach ($perms as $permission) {
                Permission::firstOrCreate(['name' => $permission]);
            }
        }

        // 2. Setup standard roles
        $rolesData = [
            'boss' => [
                'all' => true,
                'exclude' => ['mark diocese transfer'],
            ],
            'archid' => [
                'view assigned churches', 'edit church', 'enter givings', 'view assigned givings',
                'mark diocese transfer', 'enter expenses', 'view assigned expenses', 'approve expenses',
                'submit evangelism reports', 'view assigned evangelism', 'create objectives', 'edit objectives',
                'delete objectives', 'approve objectives', 'submit objective reports', 'view assigned objectives',
                'manage assigned workers', 'manage contracts', 'view retirement plans', 'create departments',
                'edit departments', 'assign users to departments', 'create members', 'edit members',
                'view assigned members', 'create attendance', 'edit attendance', 'delete attendance',
                'view assigned attendance', 'create census', 'edit census', 'delete census', 'view assigned census',
            ],
            'pastor' => [
                'view own church', 'edit church', 'enter givings', 'view own givings', 'enter expenses',
                'view own expenses', 'submit evangelism reports', 'mark diocese transfer', 'view own evangelism',
                'create objectives', 'edit objectives', 'delete objectives', 'submit objective reports',
                'view own objectives', 'view department objectives', 'manage own workers', 'manage contracts',
                'view retirement plans', 'create departments', 'edit departments', 'assign users to departments',
                'create members', 'edit members', 'view own members', 'create attendance', 'edit attendance',
                'view own attendance', 'create census', 'edit census', 'view own census',
            ],
            'finance' => [
                'view all givings', 'view all expenses', 'approve expenses', 'verify diocese receipt',
                'manage expense categories', 'create expense categories', 'manage giving types',
                'create giving types', 'manage service types',
            ],
            'hr' => [
                'manage all workers', 'manage institutions', 'create worker', 'edit worker', 'delete worker',
                'manage contracts', 'view retirement plans',
            ],
            'evangelism' => [
                'view all evangelism', 'submit evangelism reports',
            ],
        ];

        foreach ($rolesData as $roleName => $data) {
            $role = Role::firstOrCreate(['name' => $roleName]);
            
            if (isset($data['all']) && $data['all']) {
                $perms = Permission::all();
                if (isset($data['exclude'])) {
                    $perms = $perms->whereNotIn('name', $data['exclude']);
                }
                // use syncPermissions only for boss or when we want to be strict, 
                // but for live updates, givePermissionTo is safer for others.
                $role->syncPermissions($perms);
            } else {
                // For other roles, we ADD permissions rather than replacing them, 
                // UNLESS you want to stay strictly strictly in sync.
                // Re-syncing is better to ENSURE specific rules, but keep it clear.
                $role->givePermissionTo($data);
            }
        }
    }
}
