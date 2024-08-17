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
    $selet_q= "select * from `User` where id =:p_id";
    $selet_stat= $db->prepare($selet_q);
    $selet_stat->bindParam(":p_id", $_GET["id"], PDO::PARAM_INT);
    $selet_stat->execute();
    $user = $selet_stat->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
    echo  "<h2> {$e->getMessage()} </h2>" ;
           }


if(isset($_GET['old_data'])){
      $old_data = json_decode($_GET['old_data'],true);
  }
  if(isset($_GET["errors"])){
      $errors = json_decode($_GET['errors'],true);

  }else{
    $old_data['name']=$user['name'];
    $old_data['email']=$user['email'];

    $old_data['room']=$user['room'];
    $old_data['image']=$user['image'];

    $old_data['ex']=$user['ext'];
    

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
        <h1 class="mt-5">Update  User </h1>


        <form action="edit_user.php" method="post" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?php echo $_GET['id']?>">
            <div class="mb-3  ">
                <label for="name" class="form-label">Name</label>
                <input type="text" name="name"
                   value="<?php $val=isset($old_data['name'])?$old_data['name']:"";echo $val;?>"
                       class="form-control "  aria-describedby="name">
                       <span class="text-danger">
                     <?php $errorfn=isset($errors['name'])? $errors['name']: ''; echo $errorfn; ?>
               </span>
            </div>
            <div class="mb-3  ">
                <label for="email" class="form-label">E-mail</label>
                <input type="text" name="email"
                value="<?php $val=isset($old_data['email'])?$old_data['email']:"";echo $val;?>"

                       class="form-control "  aria-describedby="email">
               <span class="text-danger">
                  <?php $errorm=isset($errors['email'])? $errors['email']: ''; echo $errorm; ?>
               </span>
            </div>

            <div class="mb-3  ">
            <label for="exampleInputPassword1" class="form-label">Password</label>
                <input type="password" name="password"
                       class="form-control " id="exampleInputPassword1">
                       <span class="text-danger">
                  <?php $perror=isset($errors['password'])? $errors['password']: ''; echo $perror; ?>
               </span>
            </div>
            <div class="mb-3  ">
            <label for="exampleInputPassword1" class="form-label">Confirm Password</label>
                <input type="password" name="conpassword"
                       class="form-control " id="exampleInputconpassword1">
                       <span class="text-danger">
                  <?php $perror=isset($errors['conpassword'])? $errors['conpassword']: ''; echo $perror; ?>
               </span>
            </div>
           
            <div class="mb-3  ">

            <label for="room" class="form-label"> Room No</label>
            <select class="form-select " name="room" aria-label="Default select example">
              <option 
              value="<?php $val=isset($old_data['room'])?$old_data['room']:"";echo $val;?>"
  
              
              ><?php $val=isset($old_data['room'])?$old_data['room']:"";echo $val;?></option>
                <option  value= "app1">App1</option>
                <option value="app2">App2</option>
                <option value="cloud">cloud</option>
            </select>
        </div>


        <span class="text-danger ">


        <?php $cerror=isset($errors['room'])? $errors['room']: ''; echo $cerror; ?></span>


         
            <div class="mb-3  ">
                <label for="ext" class="form-label">Ext</label>
                <input type="text"  name="ex" 
                value="<?php $val=isset($old_data['ex'])?$old_data['ex']:'';echo $val;?>"
                       class="form-control "  aria-describedby="ext">
                       <span class="text-danger ">


<?php $xerror=isset($errors['ex'])? $errors['ex']: ''; echo $xerror; ?></span>
            </div>

            <?php if (!empty($old_data['image'])){
                echo"
        <div>
            <img src='{$old_data["image"]}' style='max-width: 100px;'>
        </div> ";}
     ?>
              
            <div class="mb-3  ">
            <input type="hidden" name="current_image"  value="<?= htmlspecialchars($old_data['image']); ?>">
                <label for="image" class="form-label">Profile Picture</label>
                <input type="file"  name="image" 
                       class="form-control "  aria-describedby="image">
                       <span class="text-danger ">


   <?php $cerror=isset($errors['image'])? $errors['image']: ''; echo $cerror; ?></span>

            </div>
              
            <div class="mb-3 d-flex justify-content-evenly ">
            <button type="submit" class="btn btn-primary">Update</button>
            <button type="reset" class="btn btn-warning">Reset</button>
            </div>

        </form>
    </div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

</body>
</html>