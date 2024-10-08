<?php

session_start();

error_reporting(E_ALL);
ini_set('display_errors', 1);

require "../credit.php";

$email = $_POST['email'];
$password = $_POST['password'];

$errors = [];
$old_data = [];

if (empty($email)) {
    $errors['email'] = "Please enter email.";
} else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors['email'] = "Please enter a valid email.";
} else {
    $old_data['email'] = $email;
}

if (empty($password)) {
    $errors['password'] = "Please enter password.";
}

if ($errors) {
    $errors = json_encode($errors);
    $url = "Location: ../login_form.php?errors={$errors}";
    if ($old_data) {
        $old_data = json_encode($old_data);
        $url .= "&old_data={$old_data}";
    }
    header($url);
} else {
    try {
        $query = "SELECT * FROM `User` WHERE `email` = :email";
        $stm = $db->prepare($query);
        $stm->bindParam(':email', $email, PDO::PARAM_STR);
        $stm->execute();

        $user = $stm->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            if (password_verify($password, $user['password'])) { 
                $_SESSION['email'] = $user['email'];
                $_SESSION['login'] = true;
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_type'] = $user['type']; 

                // check if user is admin :* 
                if ($user['type'] === "admin") {
                    header("Location: ../all_users.php");
                    # code...
                }elseif ($user['type'] === "user") {
                    header("Location: ../fatoraOreder.php");
                }
                
                exit();
            } else {
                $_SESSION['errors'] = "Invalid Email Or Password";
                header("Location: ../login_form.php?session_errors=" . urlencode($_SESSION['errors']));
                exit();
            }
        } else {
            $_SESSION['errors'] = "Invalid Email Or Password";
            header("Location: ../login_form.php?session_errors=" . urlencode($_SESSION['errors']));
            exit();
        }
    } catch (PDOException $e) {
        echo "Database error: " . $e->getMessage();
    }
}