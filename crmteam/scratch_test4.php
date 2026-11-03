<?php 
require 'vendor/autoload.php'; 
$app = require_once 'bootstrap/app.php'; 
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap(); 

$users = \App\Models\User::all();
$memo = [];
function getAncestorsAll($user_id, &$memo = []) {
    if(isset($memo[$user_id])) return $memo[$user_id];
    $tms = \App\Models\TeamMember::with('parent')->where('user_id', $user_id)->get();
    if($tms->isEmpty()) {
        $memo[$user_id] = [];
        return [];
    }
    $ancestors = [];
    foreach($tms as $tm) {
        if($tm->parent) {
            $ancestors[] = $tm->parent->name;
            $ancestors = array_merge($ancestors, getAncestorsAll($tm->parent->id, $memo));
        }
    }
    $memo[$user_id] = array_values(array_unique($ancestors));
    return $memo[$user_id];
}

$u = \App\Models\User::where('name','Dx')->first();
echo "Ancestors of Dx: " . json_encode(getAncestorsAll($u->id, $memo)) . "\n";
$u = \App\Models\User::where('name','Test Incentive')->first();
echo "Ancestors of Test Incentive: " . json_encode(getAncestorsAll($u->id, $memo)) . "\n";
