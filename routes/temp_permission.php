<?php

use Illuminate\Support\Facades\Route;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

Route::get('/add-custom-field-permission', function () {
    // Create the permission
    $permission = Permission::firstOrCreate(['name' => 'manage all custom fields']);

    // Assign to boss role
    $boss = Role::where('name', 'boss')->first();
    if ($boss) {
        $boss->givePermissionTo($permission);
    }
    
    // Assign to admin role if it exists
    $admin = Role::where('name', 'admin')->first();
    if ($admin) {
        $admin->givePermissionTo($permission);
    }

    return 'Permission [manage all custom fields] created and assigned to Boss/Admin roles.';
});
