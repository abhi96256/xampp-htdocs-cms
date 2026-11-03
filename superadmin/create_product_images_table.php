<?php
include('./function/function.php');
$pdo = getPDOObject();

try {
    $sql = "CREATE TABLE IF NOT EXISTS `product_images` (
        `id` INT(11) NOT NULL AUTO_INCREMENT,
        `product_id` INT(11) NOT NULL DEFAULT '0',
        `photo` VARCHAR(255) NOT NULL DEFAULT '',
        `title` VARCHAR(255) DEFAULT '',
        `caption` TEXT,
        `fld_order` INT(11) DEFAULT '0',
        PRIMARY KEY (`id`),
        KEY `product_id` (`product_id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
    
    $pdo->exec($sql);
    echo "<h1>Table 'product_images' created successfully!</h1>";
} catch (PDOException $e) {
    echo "<h1>Error creating table:</h1><pre>" . $e->getMessage() . "</pre>";
}
?>
