<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();
$role = \Spatie\Permission\Models\Role::findByName('pastor');
echo $role->permissions->pluck('name')->implode(', ');
