<?php 
require 'vendor/autoload.php'; 
$app = require_once 'bootstrap/app.php'; 
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap(); 

function getAncestors($user_id, &$memo = []) {
    if(isset($memo[$user_id])) return $memo[$user_id];
    
    $tm = \App\Models\TeamMember::with('parent')->where('user_id', $user_id)->first();
    if(!$tm || !$tm->parent) {
        $memo[$user_id] = [];
        return [];
    }

    $parent = $tm->parent;
    $ancestors = [$parent->name];
    
    $ancestors = array_merge($ancestors, getAncestors($parent->id, $memo));
    $memo[$user_id] = array_unique($ancestors);
    return $memo[$user_id];
}

$memo = [];
$u = \App\Models\User::where('name','Dx')->first();
echo "Ancestors of Dx: " . json_encode(getAncestors($u->id, $memo)) . "\n";
