<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

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

$memo = [];
$users = \App\Models\User::role([ 'Executive'])->orderby('no_of_enquiries_assigned', 'ASC')->get();

foreach($users as $user) {
    if ($user->name == 'Dx') {
        $ancestors = json_encode(getAncestorsAll($user->id, $memo), JSON_HEX_APOS);
        dump("Dx data-ancestors: " . $ancestors);
    }
}
