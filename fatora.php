<?php 
require "credit.php";
session_start();

$total = $_POST['total'];
$notes = $_POST['notes'];
$product_names = $_POST['itemname'];
$quantities = $_POST['itemQuantity'];
$prices = $_POST['itemprice'];

if (empty($product_names) || empty($quantities) || empty($prices) || count($product_names) !== count($quantities) || count($product_names) !== count($prices)) {
    $_SESSION['error_messages'][] = 'One of the fields is empty .';
    header("Location: fatoraOreder.php");
    exit();
}
try {
    $sql = "INSERT INTO Orders(date, status, total, notes, user_id) VALUES (:date, :status, :total, :notes, :user_id)";
    $stmt = $db->prepare($sql);
    $user_id = $_SESSION['user_id'];
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

        if (!$product_id) {
            throw new Exception('Product not found');
        }

        $stmt->bindValue(':order_id', $orderId);
        $stmt->bindValue(':product_name', $product_name);
        $stmt->bindValue(':quantity', $quantity);
        $stmt->bindValue(':price', $price);
        $stmt->execute();

        $stmt_order_product->bindValue(':order_id', $orderId);
        $stmt_order_product->bindValue(':product_id', $product_id);
        $stmt_order_product->bindValue(':quantity', $quantity);
        $stmt_order_product->execute();
    }

    header("Location: order.view.php");

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>