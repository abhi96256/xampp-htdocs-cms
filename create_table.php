<?php
include "function/function.php";
$pdo = getPDOObject();
$sql = "CREATE TABLE IF NOT EXISTS job_applications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    phone VARCHAR(20) NOT NULL,
    resume VARCHAR(255) NOT NULL,
    message TEXT,
    job_id INT,
    applied_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";
try {
    $pdo->exec($sql);
    echo "Table job_applications created successfully";
} catch (PDOException $e) {
    echo "Error creating table: " . $e->getMessage();
}
?>
