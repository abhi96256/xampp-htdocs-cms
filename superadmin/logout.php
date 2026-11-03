<?php 
session_start();
unset($_SESSION['admin_b2b_id']);

session_destroy();
header('location:./');
exit;
?>