<?php  
require 'credit.php';
session_start();
if (!isset($_SESSION['login']) || $_SESSION['login'] !== true) {
    header("Location: login_form.php");
    exit();
}

if ($_SESSION['user_type'] !== 'admin') {
    header("Location: fatoraOreder.php");
    exit();
}
    try {
      $selet_q= "  SELECT Product.*, Category.name AS category_name 
    FROM Product 
    JOIN Category ON Product.cat_id = Category.id ";
      $selet_stat= $db->prepare($selet_q);

      $selet_stat->execute();
      $products = $selet_stat->fetchAll(PDO::FETCH_ASSOC);
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
        <h1>All Product</h1>
        <a href="add_product.php" class="btn btn-primary px-5 pt-3">Add new Product</a>
     </div>

         <?php
         if ($products){
           echo'
          <div class="table-responsive w-100">
            <table class="table table-striped table-bordered table-hover text-center">
              <thead>
                <tr class="text-center">
                  <th>Product</th>
                  <th>Price</th>
                  <th>Image</th>
                  <th>Category</th>
                   <th>Status</th>
                  <th>Action</th>

                </tr>
              </thead>
              <tbody >
              ';
              foreach ($products as $product){
                echo"
                <tr>
                  <td class='align-middle'>{$product["name"]}</td>
                 <td class='align-middle'>{$product["price"]}</td>
                <td class='align-middle'><img src='{$product["image"]}' width='50' height='50' alt='' srcset=''></td>
                 <td class='align-middle'>{$product["category_name"]}</td>
                 <td class='align-middle'>{$product["status"]}</td>


                  <td class='align-middle'>
                 <a href='delete.php?id={$product['id']}&img={$product['image']}' class='btn btn-danger'>Delete </a> 
                  <a href='update.php?id={$product['id']}' class='btn btn-success'>Update </a> 
                  <a href='view_product.php?id={$product['id']}' class='btn btn-primary'>View</a>
                  </td>
                </tr>";

              }
              echo '
              </tbody>
            </table>
           
           
          </div>
          
          ';

          ;}?>
        </div>
  </main>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
    crossorigin="anonymous"></script>
</body>

</html>










