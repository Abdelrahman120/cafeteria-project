<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require 'credit.php';
// require 'db_controllers.php';

$db = connect_db($db_name,$db_host, $db_user, $db_pass);
$update_query = " UPDATE Orders SET status =:status_value WHERE id=:order_id ";
$update_stmt = $db->prepare($update_query);
$status = $_GET['status'];
$id = $_GET['id'];
$update_stmt->bindParam(':status_value', $status, PDO::PARAM_STR);
$update_stmt->bindParam(':order_id', $id, PDO::PARAM_INT);
$update_stmt->execute();

header('Location: http://localhost/Test%20(2)/order_admin_page.php');