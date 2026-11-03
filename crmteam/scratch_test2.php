<?php 
require 'vendor/autoload.php'; 
$app = require_once 'bootstrap/app.php'; 
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap(); 

$teamMembers = \App\Models\TeamMember::with(['user', 'parent'])->get();
foreach($teamMembers as $tm) {
    echo "User: " . ($tm->user->name ?? 'null') . " -> Parent: " . ($tm->parent->name ?? 'null') . " (Team: {$tm->team_id})\n";
}
