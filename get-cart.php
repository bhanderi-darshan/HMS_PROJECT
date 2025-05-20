<?php
session_start();
include 'includes/config.php';

header('Content-Type: application/json');

echo json_encode([
    'items' => $_SESSION['cart'],
    'total' => get_cart_total()
]);
?>