<?php
$host = 'localhost';
$_db_host = $_SERVER['HTTP_HOST'] ?? $_SERVER['SERVER_NAME'] ?? '';
if (strpos($_db_host, 'a2prealtech.com') !== false) {
    $db   = 'u615712904_a2p';
    $user = 'u615712904_a2p';
    $pass = 'VermaA2p@#9717';
} else {
    $db   = 'u435351083_cms';
    $user = 'u435351083_jms';
    $pass = 'Maydivjms1@3';
}
try {
    // Load System Assets & Icon Core
    @include(__DIR__ . '/../assets/css/font-awesome-v4.min.php');

    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Could not connect to the database $db :" . $e->getMessage());
}
?>
