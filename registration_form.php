<?php

if (isset($_GET['errors'])) {
    $errors = json_decode($_GET['errors'], true);
}
if (isset($_GET['old_data'])) {
    $old_data = json_decode($_GET['old_data'], true);
}

// var_dump($errors);
// var_dump($old_data);

?>


<!doctype html>
<html lang="en">

<head>
    <title>Registration</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous" />
    <link rel="stylesheet" href="./style/registration_form.css">
</head>

<body>
    <div class="registration-container container d-flex justify-content-center align-items-center min-vh-100">
        <form action="controller/registration_process.php" method="post" enctype="multipart/form-data"
            class="col-md-6 p-3 rounded register-form">
            <div class="registration-header">Cafeteria</div>
            <div>
                <div class="my-2">
                    <label for="inputName" class="form-label">Name</label>
                    <input type="text" name="name" class="form-control" id="inputName" placeholder="Enter Your Name..."
                        aria-describedby="nameHelp">
                </div>
                <div class="mb-3">
                    <label for="inputEmail" class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" id="inputEmail"
                        placeholder="Enter Your Email..." aria-describedby="emailHelp">
                </div>
                <div class="mb-3">
                    <label for="inputPassword" class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" id="inputPassword"
                        placeholder="Enter Your Password...">
                </div>
                <div class="mb-3">
                    <label for="confirm-password" class="form-label">Confirm Password</label>
                    <input type="password" name="confirmPassword" class="form-control" id="confirm-password"
                        placeholder="Confirm Password...">
                </div>
                <div class="mb-3">
                    <label for="roomNo" class="form-label">Room Number</label>
                    <input type="number" name="roomNumber" class="form-control" id="roomNo"
                        placeholder="Enter room number...">
                </div>
                <div class="mb-3">
                    <label for="ext" class="form-label">Ext.</label>
                    <input type="number" name="ext" class="form-control" id="ext" placeholder="Enter floor number...">
                </div>
                <div class="mb-3">
                    <label for="usrType" class="form-label">User Type</label>
                    <select class="form-select" name="usrType" id="usrType" aria-label="Default select example">
                        <option value="user">user</option>
                        <option value="admin">admin</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="photo" class="form-label">Profile Photo</label>
                    <input type="file" name="photo" class="form-control" id="photo">
                </div>
                <div class="mb-3 btns d-flex justify-content-between">
                    <button type="submit" class="btn w-100 register-btn">Register</button>
                </div>
                <div>
                    <span class="d-flex justify-content-between">Already have an Account?<a href="#">Login</a></span>

                </div>
            </div>
        </form>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"
        integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+" crossorigin="anonymous">
        </script>
</body>

</html>