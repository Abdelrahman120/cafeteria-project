<?php

session_start();
require "./db/db_conn.php";

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
    $url = "Location: ./login_form.php?errors={$errors}";
    if ($old_data) {
        $old_data = json_encode($old_data);
        $url .= "&old_data={$old_data}";
    }
    header($url);
} else {
    try {
        $query = "SELECT `email`, `password` FROM `User` WHERE email = :email";
        $stm = $database->prepare($query);
        $stm->bindParam(':email', $email, PDO::PARAM_STR);
        $stm->execute();

        $user = $stm->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            if ($password === $user['password']) {
                $_SESSION['email'] = $user['email'];
                $_SESSION['login'] = true;
                header("Location: ./welcome.php");
                exit();
            }
        } else {
            $_SESSION['errors'] = "Invalid Email Or Password";
            $errors = json_encode($errors);
            header("Location: ../login_form.php?errors=" . urlencode($errors));
            exit();
        }
    } catch (PDOException $e) {
        echo "Database error: " . $e->getMessage();
    }

    // try {
    //     $query = "SELECT * FROM User WHERE email = :email";
    //     $stmt = $DB->prepare($query);
    //     $stmt->bindParam(':email', $email);
    //     $stmt->execute();

    //     $user = $stmt->fetch();

    //     if ($user) {
    //         if ($password === $user['password']) {
    //             $_SESSION['email'] = $user['email'];
    //             $_SESSION['login'] = true;
    //             header("Location: ../Welcome.php");
    //         }
    //     } else {
    //         $error['login'] = "Invalid email or password!";
    //         $errors = json_encode($error);
    //         $url = "Location: ../login_form.php?errors={$errors}";
    //         header($url);
    //     }

    // } catch (PDOException $e) {
    //     echo "Error:" . $e->getMessage();
    // }

}