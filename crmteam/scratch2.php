<?php

// 1. Update Invoices.php
$modelFile = 'c:\xampp\htdocs\cms\crm\app\Models\Invoices.php';
$modelContent = file_get_contents($modelFile);

$addFields = array();
for($i=1; $i<=6; $i++){
    $addFields[] = "'commission_{$i}_percentage'";
}
$fieldsStr = implode(", ", $addFields);

$modelContent = str_replace("'commission_1_name',", "$fieldsStr, 'commission_1_name',", $modelContent);
file_put_contents($modelFile, $modelContent);
echo "Updated Invoices.php\n";

// 2. Update InvoicesController.php store and update arrays
$controllerFile = 'c:\xampp\htdocs\cms\crm\app\Http\Controllers\InvoicesController.php';
$controllerContent = file_get_contents($controllerFile);

for($i=1; $i<=6; $i++){
    $insert = "\n                'commission_{$i}_percentage' => \$request->input('commission_{$i}_percentage'),";
    $controllerContent = str_replace("'commission_{$i}_name' => \$request->input('commission_{$i}_name'),", "'commission_{$i}_name' => \$request->input('commission_{$i}_name')," . $insert, $controllerContent);
}

file_put_contents($controllerFile, $controllerContent);
echo "Updated InvoicesController.php\n";

// 3. Generate query
$queries = [];
for($i=1; $i<=6; $i++){
    $queries[] = "ADD commission_{$i}_percentage DECIMAL(8,2) NULL AFTER commission_{$i}_name";
}
$queryString = "ALTER TABLE invoice \n" . implode(",\n", $queries) . ";";
file_put_contents('c:\xampp\htdocs\cms\crm\scratch_query.txt', $queryString);
echo "Written query to scratch_query.txt\n";
