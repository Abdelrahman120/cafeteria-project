<?php

require 'utiles.php';


require 'credit.php';


        

$product_name = $_POST['product_name'];
$price = $_POST['price'];
$status = $_POST['status'];

$category = $_POST['category'];
$image = $_FILES['image'];


$errors = [];
$old_data = [];

if (empty($_FILES['image']['tmp_name'])) {
    $errors['image'] = "Image is required";
} else {
    $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
    if (!in_array($ext, ["jpg", "jpeg", "png"])) {
        $errors['image'] = " JPG, JPEG, PNG Only";
    }
}



foreach ($_POST as $key => $value) {
    if (empty($value)) {
        $errors[$key] = "{$key} is required";
    } else {

            $old_data[$key] = $value;
    }
} 






if ($errors) {
    $errors = json_encode($errors);
    if ($old_data) {
        $old_data = json_encode($old_data);
    }
    header("Location: add_product.php?errors={$errors}&old_data={$old_data}");
} else {

    $temp_name = $_FILES['image']['tmp_name'];
    $image_name = $_FILES['image']['name'];
    $image_path="images/".time()."."."{$ext}";
    $saved=move_uploaded_file($temp_name,$image_path);

try {
    $inser_q="insert into `Product`(`name`,`price`,`image`,`status`,`cat_id`)
    values (:productname,:productprice,:productimage,:status,:cat_id)";
        $inser_stat=$db->prepare($inser_q);
        $inser_stat->bindParam(':productname', $product_name);
        $inser_stat->bindParam(':productprice', $price);
        $inser_stat->bindParam(':productimage', $image_path);
        $inser_stat->bindParam(':status', $status);

        $inser_stat->bindParam(':cat_id', $category);
       
        $inser_stat->execute();
        if($db->lastInsertId()){
           header("Location: product.php");
        }
} 
    catch(PDOException $e){
        echo  "<h2> {$e->getMessage()} </h2>" ;
    }
   
        

}
