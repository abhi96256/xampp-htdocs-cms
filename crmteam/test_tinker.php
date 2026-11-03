<?php
$c = App\Models\Client::find(29);
if ($c) {
    echo "Client 29 Pass on: " . $c->pass_on_to_customer . "\n";
} else {
    echo "Client 29 not found\n";
}
$inc = DB::table('incentive')->where('client_id', 29)->get();
echo json_encode($inc);
