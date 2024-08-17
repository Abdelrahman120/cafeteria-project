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
        $selet_q= "select * from `Product` where id =:p_id";
        $selet_stat= $db->prepare($selet_q);
        $selet_stat->bindParam(":p_id", $_GET["id"], PDO::PARAM_INT);
        $selet_stat->execute();
        $product = $selet_stat->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
        echo  "<h2> {$e->getMessage()} </h2>" ;
               }


try {
    $selet_q = "select * from `Category`";
    $selet_stat = $db->prepare($selet_q);
    $selet_stat->execute();
    $categories = $selet_stat->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo  "<h2> {$e->getMessage()} </h2>";
}

if(isset($_GET['old_data'])){
      $old_data = json_decode($_GET['old_data'],true);
  }
  if(isset($_GET["errors"])){
      $errors = json_decode($_GET['errors'],true);

  }else{
    $old_data['product_name']=$product['name'];
    $old_data['price']=$product['price'];

    $old_data['image']=$product['image'];
    $old_data['category']=$product['cat_id'];

    $old_data['status']=$product['status'];
    

  }

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Update</title>

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
        <h1 class="mt-5">Update  Product </h1>

        <form action="edit.php" method="post" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?php echo $_GET['id']?>">

            <div class="mb-3   ">
                <label for="product_name" class="form-label ">Product</label>
                <input type="text" name="product_name" value="<?php $val = isset($old_data['product_name']) ? $old_data['product_name'] : "";
                            echo $val; ?>" class="form-control custom-border" aria-describedby="product_name">
                <span class="text-danger">
                    <?php $errorfn = isset($errors['product_name']) ? $errors['product_name'] : '';
                    echo $errorfn; ?>
                </span>
            </div>

            <div class="mb-3  ">
                <label for="price" class="form-label d-block ">Price</label>
                <input type="number" name="price" value="<?php $val = isset($old_data['price']) ? $old_data['price'] : '';
                            echo $val; ?>" class="form-control custom-border w-75 d-inline-block me-3"
                    aria-describedby="price"><span>EGP</span>
                <span class="text-danger ">


                    <?php $xerror = isset($errors['price']) ? $errors['price'] : '';
                    echo $xerror; ?></span>
            </div>

            <div class="mb-3  ">

                <label for="category" class="form-label"> Category</label>
                <select class="form-select custom-border" name="category" aria-label="Default select example">
                    <option value="<?php $val = isset($old_data['category']) ? $old_data['category'] : "";
                                    echo $val; ?>">
                        <?php $val = isset($old_data['category']) ? $old_data['category'] : "";
                        echo $val; ?></option>
                    <?php

                    foreach ($categories as $category) {
                        $selected = isset($old_data['category']) && $old_data['category'] == $category['id'] ? 'selected' : '';
                        echo "<option value=\"{$category['id']}\" $selected>{$category['name']}</option>";
                    }
                    ?>

                </select>
            </div>


            <span class="text-danger ">


                <?php $cerror = isset($errors['category']) ? $errors['category'] : '';
                echo $cerror; ?></span>



            <div class="mb-3  ">

                <label for="status" class="form-label"> Status</label>
                <select class="form-select " name="status" aria-label="Default select example">
                    <option value="<?php $val = isset($old_data['status']) ? $old_data['status'] : "";
                                echo $val; ?>"><?php $val = isset($old_data['status']) ? $old_data['status'] : "";
                                                                                                        echo $val; ?>
                    </option>
                    <option value="Available">Available</option>
                    <option value="Unavailable">Unavailable</option>
                </select>
            </div>


            <span class="text-danger ">


                <?php $cerror = isset($errors['status']) ? $errors['status'] : '';
                echo $cerror; ?></span>



<?php if (!empty($old_data['image'])){
                echo"
        <div>
            <img src='{$old_data["image"]}' style='max-width: 100px;'>
        </div> ";}
     ?>


            <div class="mb-3  ">
            <input type="hidden" name="current_image"  value="<?= htmlspecialchars($old_data['image']); ?>">

                <label for="image" class="form-label">Product Picture</label>
                <input type="file" name="image" class="form-control custom-border" aria-describedby="image">
                <span class="text-danger ">


                    <?php $cerror = isset($errors['image']) ? $errors['image'] : '';
                    echo $cerror; ?></span>

            </div>

            <div class="mb-3 d-flex justify-content-evenly ">
                <button type="submit" class="btn btn-primary">Update</button>
                <button type="reset" class="btn btn-warning">Reset</button>
            </div>

        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>

</body>

</html>