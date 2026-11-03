<?php
require 'vendor/autoload.php'; 
$app = require_once 'bootstrap/app.php'; 
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap(); 
auth()->loginUsingId(1);
$users = \App\Models\User::role(['Team Lead' , 'Executive'])->orderby('no_of_enquiries_assigned', 'ASC')->get();
$view = view('clients.form', ['users' => $users])->with('errors', new \Illuminate\Support\MessageBag())->render();
$doc = new DOMDocument();
@$doc->loadHTML($view);
$xpath = new DOMXPath($doc);
$options = $xpath->query("//select[@id='closed_by_select']/option");
foreach($options as $opt) {
    if($opt->nodeValue == 'Dx' || strpos($opt->nodeValue, 'Dx') !== false) {
        echo "Dx Data Ancestors: " . $opt->getAttribute('data-ancestors') . "\n";
    }
}
