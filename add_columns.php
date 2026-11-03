<?php
include "function/function.php";
$pdo = getPDOObject();

try {
    // Check if city exists
    $stmt = $pdo->query("SHOW COLUMNS FROM enquiry LIKE 'city'");
    if (!$stmt->fetch()) {
        echo "Adding city column...<br>";
        $pdo->exec("ALTER TABLE enquiry ADD COLUMN city VARCHAR(255) DEFAULT 'Not Shared' AFTER phone");
    } else {
        echo "City column already exists.<br>";
    }

    // Check if lat_long exists
    $stmt = $pdo->query("SHOW COLUMNS FROM enquiry LIKE 'lat_long'");
    if (!$stmt->fetch()) {
        echo "Adding lat_long column...<br>";
        $pdo->exec("ALTER TABLE enquiry ADD COLUMN lat_long VARCHAR(255) DEFAULT '' AFTER city");
    } else {
        echo "lat_long column already exists.<br>";
    }

    echo "DONE! Columns verified.";
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage();
}
?>
