<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

echo "<h2>Admin Debug</h2>";

echo "<p>Checking vendor/autoload.php...</p>";
if(file_exists(__DIR__ . '/vendor/autoload.php')) {
    echo "<p style='color:green'>✓ vendor/autoload.php EXISTS</p>";
} else {
    echo "<p style='color:red'>✗ vendor/autoload.php NOT FOUND - This is why admin 500s!</p>";
}

echo "<p>Testing function.php include...</p>";
include('./function/function.php');
echo "<p style='color:green'>✓ function.php loaded. siteTitle=" . $siteTitle . "</p>";

echo "<p>Testing DB connection...</p>";
try {
    $pdo = getPDOObject();
    echo "<p style='color:green'>✓ DB connected OK</p>";
    
    $stmt2 = $pdo->query("SELECT * FROM admin LIMIT 1");
    $admins = $stmt2->fetchAll(PDO::FETCH_ASSOC);
    echo "<p>Admin records: " . count($admins) . "</p>";
    if(count($admins)) {
        echo "<p>Admin username: " . $admins[0]['username'] . "</p>";
        echo "<p>Admin password (first 5 chars): " . substr($admins[0]['password'], 0, 5) . "...</p>";
    }
} catch (Exception $e) {
    echo "<p style='color:red'>✗ DB Error: " . $e->getMessage() . "</p>";
}

echo "<p>Testing db.php include...</p>";
try {
    include('./db.php');
    echo "<p style='color:green'>✓ db.php loaded OK</p>";
} catch (Exception $e) {
    echo "<p style='color:red'>✗ db.php Error: " . $e->getMessage() . "</p>";
}
