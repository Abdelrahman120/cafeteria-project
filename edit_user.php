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


if ($_POST['password'] !== $_POST['conpassword']) {
    $errors['conpassword'] = "conpassword must be same password";
}

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
    header("Location: update_user.php?errors={$errors}&old_data={$old_data}");
} else { 
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

$password=$_POST['password'];
$hashed_password = password_hash($password, PASSWORD_DEFAULT);
try {
    $update_q="UPDATE `User` SET name = :username,
    email = :useremail,
    password = :userpassword,
    image = :userimage,
    room = :room,
    ext = :ex
WHERE 
    id = :userid";

        $update_stat=$db->prepare($update_q);
        $update_stat->bindParam(':username', $_POST['name']);
        $update_stat->bindParam(':useremail', $_POST['email']);
        $update_stat->bindParam(':userpassword', $hashed_password);
        $update_stat->bindParam(':userimage', $image_path);
        $update_stat->bindParam(':room', $_POST['room']);
        $update_stat->bindParam(':ex', $_POST['ex']);
        $update_stat->bindParam(':userid', $_POST['id']);

        $update_stat->execute();
           header("Location: all_users.php");
        
} 
    catch(PDOException $e){
        echo  "<h2> {$e->getMessage()} </h2>" ;
    }
   
        
}





