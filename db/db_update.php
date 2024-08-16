<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);
// $order_id = $_POST['id'];

require 'db_controllers.php';
global $database;
$database = connection_using_pdo($db_name, $db_user, $db_pass, $db_host, $db_port);

$update_query = " UPDATE `Orders` SET `status` =:status_value WHERE `id`=:order_id ";
$update_stmt = $GLOBALS['database']->prepare($update_query);
$status = $_GET['status'];
$id = $_GET['id'];
$update_stmt->bindParam(':status_value', $status, PDO::PARAM_STR);
$update_stmt->bindParam(':order_id', $id, PDO::PARAM_INT);
$update_stmt->execute();

header('Location: http://localhost/cafeteria-project/order_admin_page.php');