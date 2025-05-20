<?php
session_start();
include 'includes/config.php';

header('Content-Type: application/json');

$input = json_decode(file_get_contents('php://input'), true);

if (isset($input['item_id'], $input['name'], $input['price'])) {
    add_to_cart($input['item_id'], $input['name'], $input['price']);
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'error' => 'Invalid input']);
}
?>