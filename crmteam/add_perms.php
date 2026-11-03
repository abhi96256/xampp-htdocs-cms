<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$actions = ['access', 'create', 'edit', 'show', 'delete'];
foreach ($actions as $act) {
    \Spatie\Permission\Models\Permission::firstOrCreate(['name' => 'site_visits ' . $act, 'guard_name' => 'web']);
}
echo "Site Visit permissions created!\n";
