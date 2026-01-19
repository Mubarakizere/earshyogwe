<?php

use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$permissions = Permission::where('name', 'like', '%activities%')->get();

echo "Found " . $permissions->count() . " permissions to process.\n";

foreach ($permissions as $p) {
    $oldName = $p->name;
    $newName = str_replace('activities', 'objectives', $oldName);
    
    if (Permission::where('name', $newName)->exists()) {
        echo "Duplicate found: $newName. Deleting old permission: $oldName\n";
        $p->delete();
    } else {
        echo "Renaming: $oldName -> $newName\n";
        $p->update(['name' => $newName]);
    }
}

app(PermissionRegistrar::class)->forgetCachedPermissions();
echo "Done! Cache cleared.\n";
