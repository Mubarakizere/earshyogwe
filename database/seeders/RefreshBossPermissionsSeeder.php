<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RefreshBossPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $boss = Role::findByName('boss');
        
        // Give boss ALL permissions
        $boss->syncPermissions(Permission::all());
        
        // Remove the one permission boss shouldn't have
        $boss->revokePermissionTo('mark diocese transfer');
        
        $this->command->info('Boss permissions refreshed successfully!');
        $this->command->info('Total permissions: ' . $boss->permissions()->count());
    }
}
