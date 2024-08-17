

<?php
session_start();
if (!isset($_SESSION['login']) || $_SESSION['login'] !== true) {
    // Redirect non-logged-in users to login page
    header("Location: login_form.php");
    exit();
}

if ($_SESSION['user_type'] !== 'admin') {
    // Redirect non-admin users to a different page, for example, user dashboard
    header("Location: fatoraOreder.php");
    exit();
}
require 'credit.php';

try {

    if(file_exists($_GET['img'])){
        unlink($_GET['img']);
    }

$delete_q="delete from `Product` where id = :p_id";
$delete_stat = $db->prepare($delete_q);
$delete_stat->bindParam(":p_id", $_GET["id"], PDO::PARAM_INT);
$delete_stat->execute();
header("Location: product.php");


} catch (PDOException $e) {
    echo  "<h2> {$e->getMessage()} </h2>" ;
               return false;
           }