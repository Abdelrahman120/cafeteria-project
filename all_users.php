<?php

require 'credit.php';
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

try {
    $selet_q= "select * from `User`";
    $selet_stat= $db->prepare($selet_q);
    $selet_stat->execute();
    $users = $selet_stat->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo  "<h2> {$e->getMessage()} </h2>" ;
           }

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>All Product</title>
  <link rel="stylesheet" href="style_nav.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  <link rel="stylesheet" href="./style/customer_order_view.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>

<body>


    <?php  
    require 'navbar.php';
    ?>


<main class="main-body">
      <div class="container mt-5">


      <div class="head d-flex justify-content-between mb-2">
        <h1>All User</h1>
        <a href="add_user.php" class="btn btn-primary px-5 pt-3">Add new User</a>
     </div>

         <?php
if ($users){
        echo'
       <div class="table-responsive w-100">
         <table class="table table-striped table-bordered table-hover text-center">
     <thead>
    <tr >
        <th>ID</th>
        <th>Name</th>
        <th>Email</th>
        <th>Role</th>
        <th>Room</th>
        <th>Ext.</th>
        <th>Image</th>
        <th >Action</th>
        

     </tr>
     </thead>
 <tbody >
              ';
foreach ($users as $user){
    echo "<tr >
        <td class='align-middle'>{$user['id']}</td>
        <td class='align-middle'>{$user['name']}</td>
        <td class='align-middle'>{$user['email']}</td>
         <td class='align-middle'>{$user['type']}</td>
        <td class='align-middle'>{$user['room']}</td>
        <td class='align-middle'>{$user['ext']}</td>
            
        <td class='align-middle'><img src='{$user['image']}' width='50' height='50'></td>

        <td class='align-middle'><a href='delete_user.php?id={$user['id']}&img={$user['image']}' class='btn btn-danger'>Delete </a> 
         <a href='update_user.php?id={$user['id']}' class='btn btn-success'>Update </a> </td>

    </tr>";
}
echo "</table>";
}
?>