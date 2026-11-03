<?php
require 'vendor/autoload.php'; 
$app = require_once 'bootstrap/app.php'; 
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap(); 
auth()->loginUsingId(\App\Models\User::where('name','Dx')->first()->id);
$users = \App\Models\User::role(['Executive'])->orderby('no_of_enquiries_assigned', 'ASC')->get();
$view = view('clients.form', ['users' => $users])->with('errors', new \Illuminate\Support\MessageBag())->render();
$doc = new DOMDocument();
@$doc->loadHTML($view);
$xpath = new DOMXPath($doc);
$options = $xpath->query("//select[@id='closed_by_select']/option");
foreach($options as $opt) {
    if($opt->nodeValue != 'Select User') {
        echo "User Option: " . $opt->nodeValue . " | Selected: " . ($opt->hasAttribute('selected') ? 'YES' : 'NO') . " | Ancestors: " . $opt->getAttribute('data-ancestors') . "\n";
    }
}
