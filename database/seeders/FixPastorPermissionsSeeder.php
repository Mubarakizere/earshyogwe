<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class FixPastorPermissionsSeeder extends Seeder
{
    /**
     * Restore the correct permissions for the pastor role.
     *
     * This is needed when the pastor role's permissions were wiped
     * by a syncPermissions() call via the Roles UI, which replaces
     * all permissions rather than adding to them.
     *
     * Run with: php artisan db:seed --class=FixPastorPermissionsSeeder
     */
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $pastor = Role::where('name', 'pastor')->first();

        if (!$pastor) {
            $this->command->error('Pastor role not found!');
            return;
        }

        $permissions = [
            'view own church',
            'edit church',
            'enter givings',
            'view own givings',
            'enter expenses',
            'view own expenses',
            'submit evangelism reports',
            'mark diocese transfer',
            'view own evangelism',
            'create objectives',
            'edit objectives',
            'delete objectives',
            'submit objective reports',
            'view own objectives',
            'view department objectives',
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
            'create parish transfers',
            'view own transfers',
        ];

        // Use givePermissionTo to ADD (not replace) — safe to run multiple times
        foreach ($permissions as $permName) {
            $perm = Permission::where('name', $permName)->first();
            if ($perm && !$pastor->hasPermissionTo($permName)) {
                $pastor->givePermissionTo($perm);
                $this->command->line("  ✅ Added: {$permName}");
            } else {
                $this->command->line("  ⚪ Already has: {$permName}");
            }
        }

        // Re-clear cache after changes
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $this->command->info('Pastor permissions restored. Total: ' . $pastor->permissions()->count());
    }
}
