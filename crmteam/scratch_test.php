<?php 
require 'vendor/autoload.php'; 
$app = require_once 'bootstrap/app.php'; 
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap(); 
$users = \App\Models\User::all();
foreach($users as $u) {
    if($u->name == 'Dx' || $u->name == 'Nisha') {
        echo json_encode(['name' => $u->name, 't1' => $u->tl1->name ?? '', 't2' => $u->tl2->name ?? '', 't3' => $u->tl3->name ?? '', 't4' => $u->tl4->name ?? '', 't5' => $u->tl5->name ?? '']) . "\n";
    }
}
