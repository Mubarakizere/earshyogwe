<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class FixWorkerPermissionsSeeder extends Seeder
{
    public function run()
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = [
            'view all workers',
            'create worker',
            'edit worker',
            'delete worker',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Assign to Boss
        $boss = Role::where('name', 'boss')->first();
        if ($boss) {
            $boss->givePermissionTo($permissions);
        }
        
        // Assign to Archid (View/Create/Edit but maybe not delete?) 
        // Assuming Archid can do everything except maybe delete? 
        // For now giving all to archid based on typical flow, or restricting delete.
        // User asked to give permission to "boss and other in need".
        
        $archid = Role::where('name', 'archid')->first();
        if ($archid) {
            $archid->givePermissionTo(['view all workers', 'create worker', 'edit worker']);
        }

        $pastor = Role::where('name', 'pastor')->first();
        if ($pastor) {
             $pastor->givePermissionTo(['view all workers']); 
        }
    }
}
