
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
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Add Category</title>

    <link rel="stylesheet" href="style_nav.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css"
        integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    <style>
    .custom-border:focus {
        border-color: #8B4513;
        box-shadow: 0 0 0 0.2rem rgba(139, 69, 19, 0.25);
    }
    </style>
</head>

<body>

    <?php

    require 'navbar.php';
    ?>
 <div class="container mt-5">
        <h1 class="mt-5">Add New Category </h1>

        <form  method="post" >
            <div class="mb-3   ">
                <label for="category_name" class="form-label ">Category</label>
                <input type="text" required name="category_name" class="form-control custom-border">
               
            </div>
            <div class="mb-3 d-flex justify-content-evenly ">
                <button type="submit" class="btn btn-primary">Add</button>
                <button type="reset" class="btn btn-warning">Reset</button>
            </div>
        </form>
 </div>








<?php

require 'credit.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
try {
    $inser_q="insert into `Category`(`name`)
    values (:catname)";
        $inser_stat=$db->prepare($inser_q);
        $inser_stat->bindParam(':catname', $_POST['category_name']);
        $inser_stat->execute();
        if($db->lastInsertId()){
           header("Location: add_product.php");
        }
} 
    catch(PDOException $e){
        echo  "<h2> {$e->getMessage()} </h2>" ;
    }}
   
?>