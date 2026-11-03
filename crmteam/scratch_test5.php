<?php
require 'vendor/autoload.php'; 
$app = require_once 'bootstrap/app.php'; 
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap(); 
$users = \App\Models\User::role(['Team Lead' , 'Executive'])->orderby('no_of_enquiries_assigned', 'ASC')->get();
$view = view('clients.form', ['users' => $users])->render();
file_put_contents('scratch_view.html', $view);
echo "Done";
