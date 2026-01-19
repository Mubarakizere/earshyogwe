$permissions = Spatie\Permission\Models\Permission::where('name', 'like', '%activities%')->get();
foreach ($permissions as $p) {
    $newName = str_replace('activities', 'objectives', $p->name);
    if (Spatie\Permission\Models\Permission::where('name', $newName)->exists()) {
        $p->delete();
    } else {
        $p->update(['name' => $newName]);
    }
}
app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
echo "DONE\n";
