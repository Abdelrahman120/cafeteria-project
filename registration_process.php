<?php

include "credit.php";

$name = $_POST['name'];
$email = $_POST['email'];
$password = $_POST['password'];
$hashed_password = password_hash($password, PASSWORD_DEFAULT);
$confirmPassword = $_POST['confirmPassword'];
$roomNo = $_POST['roomNumber'];
$ext = $_POST['ext'];
$photo = $_FILES['photo'];
$type='user';
$errors = [];
$old_data = [];

foreach ($_POST as $key => $val) {
    if (empty($val)) {
        $errors[$key] = "*{$key} is required";
    } else {
        $old_data[$key] = $val;
    }
}

if (empty($confirmPassword)) {
    $errors['cPassword'] = "*Please confirm password";
}
if ($password !== $confirmPassword) {
    $errors['cPassword'] = "*Password doesn't match";
    $errors['password'] = "*Password doesn't match";
}

if (empty($photo['tmp_name'])) {
    $errors['photo'] = "*Profile photo is required";
} else {
    $imageExtension = pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION);
    if (!in_array($imageExtension, ['jpeg', 'jpg', 'png'])) {
        $errors['photo'] = "*Invalid profile photo format";
    }
}

if ($errors) {
    $errors = json_encode($errors);
    $url = "Location: registration_form.php?errors={$errors}";
    if ($old_data) {
        $old_data = json_encode($old_data);
        $url .= "&old_data={$old_data}";
    }
    header($url);
} else {
    $currentTime = time();
    $tmpName = $_FILES['photo']['tmp_name'];
    $imgOriginalName = $_FILES['photo']['name'];
    $fileName = "images/{$currentTime}.{$imageExtension}";
    $stored = move_uploaded_file($tmpName, $fileName);

    if ($stored) {
        try {
            $insertionQuery = "INSERT INTO User (email, name, room, password, image, ext , type) VALUES (:userEmail, :userName, :userRoom, :userPassword, :userImage, :userExt , :type)";
            $insertion = $db->prepare($insertionQuery); // preparing statement for execution.
            //------ binding ------//
            $insertion->bindParam(':userEmail', $email);
            $insertion->bindParam(':userName', $name);
            $insertion->bindParam(':userRoom', $roomNo);
            $insertion->bindParam(':userPassword', $hashed_password);
            $insertion->bindParam(':userImage', $fileName);
            $insertion->bindParam(':userExt', $ext);
            $insertion->bindParam(':type', $type);
            $insertion->execute();
            //---------------------//

            //------ fetch last inserted id ------//
            if ($db->lastInsertId()) {
                header("Location: login_form.php");
            }
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    } else {
        echo "Error while storing image.";
    }
}
