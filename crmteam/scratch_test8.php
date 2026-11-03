<?php
require 'vendor/autoload.php'; 
$app = require_once 'bootstrap/app.php'; 
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap(); 
$users = \App\Models\User::role(['Executive'])->get();
foreach($users as $u) {
    if($u->name == 'Dx') {
        echo "DX IS AN EXECUTIVE!\n";
    }
}
$users2 = \App\Models\User::role(['Team Lead'])->get();
foreach($users2 as $u) {
    if($u->name == 'Dx') {
        echo "DX IS A TEAM LEAD!\n";
    }
}
