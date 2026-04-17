<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$user = App\Models\User::role('pastor')->first();
echo "Pastor: " . $user->name . " (ID: " . $user->id . ")\n";
echo "Church ID: " . $user->church_id . "\n";
echo "Permission 'view own members': " . ($user->hasPermissionTo('view own members') ? 'Yes' : 'No') . "\n";

$members = App\Models\Member::where('church_id', $user->church_id)->count();
echo "Total Members in Church ID {$user->church_id}: $members\n";

// Now, test the scopes
$query = App\Models\Member::query();

if ($user->can('view own members') && $user->church_id) {
    echo "Hit scope: view own members\n";
    $query->where('church_id', $user->church_id);
} elseif ($user->church_id) {
    echo "Hit scope: fallback to church_id\n";
    $query->where('church_id', $user->church_id);
} else {
    $managedIds = \App\Models\Church::where('pastor_id', $user->id)->pluck('id');
    if ($managedIds->isNotEmpty()) {
        echo "Hit scope: managedIds\n";
        $query->whereIn('church_id', $managedIds);
    } else {
        echo "Hit scope: abort\n";
    }
}

echo "Members found via scope: " . $query->count() . "\n";

