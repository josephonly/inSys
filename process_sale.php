<?php
require_once('includes/load.php');

error_reporting(E_ALL);
ini_set('display_errors', 1);
header('Content-Type: application/json'); // Ensure JSON response

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $json = file_get_contents("php://input");
    $data = json_decode($json, true);

    if (!$data || !isset($data['cart'])) {
        echo json_encode(['success' => false, 'message' => 'Invalid JSON received']);
        exit;
    }

    foreach ($data['cart'] as $item) {
        $product_id = (int)$item['id'];
        $quantity = (int)$item['quantity'];

        // Check product
        $product = find_by_id('products', $product_id);
        if (!$product || $product['quantity'] < $quantity) {
            echo json_encode(['success' => false, 'message' => 'Insufficient stock']);
            exit;
        }

        // Deduct stock
        $sql = "UPDATE products SET quantity = quantity - {$quantity} WHERE id = {$product_id}";
        if (!$db->query($sql)) {
            echo json_encode(['success' => false, 'message' => 'Database error']);
            exit;
        }
    }

    echo json_encode(['success' => true, 'message' => 'Sale processed successfully']);
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}
?>
