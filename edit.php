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



$errors = [];
$old_data = [];

// if (empty($_FILES['image']['tmp_name'])) {
//     $errors['image'] = "Image is required";
// } else {
//     $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
//     if (!in_array($ext, ["jpg", "jpeg", "png"])) {
//         $errors['image'] = " JPG, JPEG, PNG Only";
//     }
// }
// $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);



foreach ($_POST as $key => $value) {
    if (empty($value)) {
        if($key !== 'image')
        $errors[$key] = "{$key} is required";
    } else {

        if ($key !== 'password' || $key !== 'conpassword')
            $old_data[$key] = $value;
    }
} 

if ($errors) {
    $errors = json_encode($errors);
    if ($old_data) {
        $old_data = json_encode($old_data);
    }
    header("Location: update.php?errors={$errors}&old_data={$old_data}");
} 

else { 
    $current_image = $_POST['current_image'];

if (isset($_FILES['image']) && $_FILES['image']['error'] == UPLOAD_ERR_OK) {
    $temp_name = $_FILES['image']['tmp_name'];
    $image_name = $_FILES['image']['name'];

    $ext = pathinfo($image_name, PATHINFO_EXTENSION);

    $image_path = "images/" . time() . "." . $ext;

    if (move_uploaded_file($temp_name, $image_path)) {
        if (file_exists($current_image)) {
            unlink($current_image);
        }
    }
} else {
    $image_path = $current_image;
}

try {
    $update_q="UPDATE `Product` SET name = :p_name,
    price = :p_price,
    cat_id = :cat_id,
    image = :p_image,
    status = :status
   
WHERE 
    id = :p_id";

        $update_stat=$db->prepare($update_q);
        $update_stat->bindParam(':p_name', $_POST['product_name']);
        $update_stat->bindParam(':p_price', $_POST['price']);
        $update_stat->bindParam(':cat_id', $_POST['category']);
        $update_stat->bindParam(':p_image', $image_path);
        $update_stat->bindParam(':status', $_POST['status']);
        $update_stat->bindParam(':p_id', $_POST['id']);

        $update_stat->execute();
           header("Location: product.php");
        
} 
    catch(PDOException $e){
        echo  "<h2> {$e->getMessage()} </h2>" ;
    }
   
        
}





