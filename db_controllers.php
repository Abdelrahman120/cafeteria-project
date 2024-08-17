<?php
require 'credit.php';
error_reporting(E_ALL);
ini_set('display_errors', 1);


function user_insert($db)
{
    $insert_query = "INSERT INTO `User`(`email`,`name`,`room`,`password`,`type`,`image`,`ext`) Values(?,?,?,?,?,?,?)";
    $stmt = $db->prepare($insert_query);
    $name = "Omar";
    $email = "omar@gmail.com";
    $room = "101";
    $password = "29711291400";
    $type = "admin";
    $image = "../images/cup.png";
    $ext = "First";
    $res = $stmt->execute([$email, $name, $room, $password, $type, $image, $ext]);
    var_dump($res);
}
;

//user_insert($database);

function update_status($status_update, $order_id)
{
    try {
        $update_query = " UPDATE `Orders` SET `status` =:status_value WHERE `id`=:order_id ";
        $update_stmt = $GLOBALS['db']->prepare($update_query);
        $status = $status_update;
        $id = $order_id;
        $update_stmt->bindParam(':status_value', $status, PDO::PARAM_STR);
        $update_stmt->bindParam(':order_id', $id, PDO::PARAM_INT);
        $update_stmt->execute();
    } catch (PDOException $e) {
        return $e->getMessage();
    }



}
//update_status($_GET['status'], $_GET['id']);