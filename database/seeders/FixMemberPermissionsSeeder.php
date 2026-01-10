<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class FixMemberPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Ensure 'delete members' permission exists
        $deletePermission = Permission::firstOrCreate(['name' => 'delete members']);
        $viewAll = Permission::firstOrCreate(['name' => 'view all members']);

        // 2. Assign to Archid
        $archid = Role::where('name', 'archid')->first();
        if ($archid) {
            $archid->givePermissionTo($deletePermission);
            $this->command->info('Granted "delete members" to Archid.');
        }

        // 3. Assign to Pastor
        $pastor = Role::where('name', 'pastor')->first();
        if ($pastor) {
            $pastor->givePermissionTo($deletePermission);
            $this->command->info('Granted "delete members" to Pastor.');
        }
        
        // 4. Ensure Boss has it (should already, but safe to check)
        $boss = Role::where('name', 'boss')->first();
        if ($boss) {
            $boss->givePermissionTo($deletePermission);
            $boss->givePermissionTo($viewAll);
        }

        $this->command->info('Member permissions fixed successfully.');
    }
}
