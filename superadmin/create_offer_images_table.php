<?php
include('./function/function.php');
$pdo = getPDOObject();

$sql = "CREATE TABLE IF NOT EXISTS `offer_images` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `offer_id` INT(11) NOT NULL,
    `photo` VARCHAR(255) NOT NULL,
    `title` VARCHAR(255) DEFAULT '',
    `caption` TEXT DEFAULT '',
    `fld_order` INT(11) DEFAULT 0,
    PRIMARY KEY (`id`),
    KEY `offer_id` (`offer_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";

try {
    $pdo->exec($sql);
    echo "<b style='color:green;'>✅ Table `offer_images` created successfully!</b>";
} catch (Exception $e) {
    echo "<b style='color:red;'>❌ Error: " . $e->getMessage() . "</b>";
}
?>
