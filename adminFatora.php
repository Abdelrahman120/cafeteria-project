<?php
session_start();
include "credit.php";
if (!isset($_SESSION['selected_user_id'])) {
    $_SESSION['error_messages'][] = 'No user selected.';
    header("Location: fatoraAdmin.php");
    exit();
}

if (!isset($_POST['itemname']) || !isset($_POST['itemQuantity']) || !isset($_POST['itemprice']) || empty($_POST['itemname']) || empty($_POST['itemQuantity']) || empty($_POST['itemprice'])) {
    $_SESSION['error_messages'][] = 'All fields are required.';
    header("Location: fatoraAdmin.php");
    exit();
}

    
$errors=[];
$total = $_POST['total'];
$notes = $_POST['notes'];
$product_names = $_POST['itemname'];
$quantities = $_POST['itemQuantity'];
$prices = $_POST['itemprice'];
 
try {
    $user_id = $_SESSION['selected_user_id']; 
    $sql = "INSERT INTO Orders(date, status, total, notes, user_id) VALUES (:date, :status, :total, :notes, :user_id)";
    $stmt = $db->prepare($sql);

    $date = date('Y-m-d H:i:s'); 
    $status = 'pending'; 
    $stmt->bindParam(':date', $date);
    $stmt->bindParam(':status', $status);
    $stmt->bindParam(':total', $total);
    $stmt->bindParam(':notes', $notes);
    $stmt->bindParam(':user_id', $user_id);

    $stmt->execute();

    $orderId = $db->lastInsertId();

    
    $sql = "INSERT INTO OrderDetails(order_id, product_name, quantity, price) VALUES (:order_id, :product_name, :quantity, :price)";
    $stmt = $db->prepare($sql);

    $sql_order_product = "INSERT INTO Order_product(order_id, product_id, quantity) VALUES (:order_id, :product_id, :quantity)";
    $stmt_order_product = $db->prepare($sql_order_product);

    foreach ($product_names as $index => $product_name) {
        $quantity = $quantities[$index];
        $price = $prices[$index];

        if (empty($product_name) || empty($quantity) || empty($price)) {
            throw new Exception('One of the fields is empty');
        }

       
        $stmt_product = $db->prepare("SELECT id FROM Product WHERE name = :product_name");
        $stmt_product->bindParam(':product_name', $product_name);
        $stmt_product->execute();
        $product_id = $stmt_product->fetchColumn();

        if(!$product_id) {
            throw new Exception('Product not found');
        }

        
        $stmt->bindParam(':order_id', $orderId);
        $stmt->bindParam(':product_name', $product_name);
        $stmt->bindParam(':quantity', $quantity);
        $stmt->bindParam(':price', $price);
        $stmt->execute();

        
        $stmt_order_product->bindParam(':order_id', $orderId);
        $stmt_order_product->bindParam(':product_id', $product_id);
        $stmt_order_product->bindParam(':quantity', $quantity);
        $stmt_order_product->execute();
        
    }

    header("Location: order_admin_page.php");

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
} 
?>