<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Rename existing 'activities' permissions to 'objectives'
        $permissions = \Spatie\Permission\Models\Permission::where('name', 'like', '%activities%')->get();

        foreach ($permissions as $p) {
            $newName = str_replace(['activities', 'Activities'], ['objectives', 'objectives'], $p->name);
            
            // If the objective version already exists, just delete the activity one
            if (\Spatie\Permission\Models\Permission::where('name', $newName)->exists()) {
                $p->delete();
            } else {
                $p->update(['name' => $newName]);
            }
        }

        // 2. Ensure specific missing permissions are created (backwards safety)
        $requiredPerms = [
            'view development objectives',
            'view finance-daf objectives',
            'view family-life objectives',
            'view health objectives',
        ];

        foreach ($requiredPerms as $perm) {
            \Spatie\Permission\Models\Permission::firstOrCreate(['name' => $perm]);
        }

        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
    }

    public function down(): void
    {
        // Standard cleanup not required for this fix
    }
};
