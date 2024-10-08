<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

require "../credit.php";

$order_id = $_GET['order_id'];
$cancelled = 'canceled';


try {
    $updateQuery = "UPDATE `Orders` SET `status` = :canceled WHERE `id` = :order_id";

    $update = $db->prepare($updateQuery);

    $update->bindParam(':order_id', $order_id, PDO::PARAM_INT);

    $update->bindParam(':canceled', $cancelled, PDO::PARAM_STR);

    $update->execute();

    if ($update->rowCount() > 0) {
        header("Location: ../order.view.php");
    } else {
        echo "No record found";
    }
    exit();
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}